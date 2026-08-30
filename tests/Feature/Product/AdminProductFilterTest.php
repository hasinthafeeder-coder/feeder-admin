<?php

namespace Tests\Feature\Product;

use App\Models\User;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\ProductStatus;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\Product;
use Feeder\Core\Models\ProductCategory;
use Feeder\Core\Models\ProductDescription;
use Feeder\Core\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class AdminProductFilterTest extends TestCase
{
    use SetsUpMarketData;

    /**
     * @var list<string>
     */
    private array $allowedPermissions = [];

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.url' => null,
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => '3306',
            'database.connections.mysql.database' => 'dropshipping',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => 'admin',
        ]);
        DB::purge('mysql');
        DB::reconnect('mysql');
        DB::beginTransaction();

        $this->seedMarketLookups();
    }

    protected function tearDown(): void
    {
        if (DB::transactionLevel() > 0) {
            DB::rollBack();
        }

        parent::tearDown();
    }

    public function test_filter_by_market(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $lkProduct = $this->makeSupplierProduct('LK Supplies', 'LK Mouse', $category->id, 'lk', ProductStatus::ACTIVE);
        $myProduct = $this->makeSupplierProduct('MY Supplies', 'MY Mouse', $category->id, 'my', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['market_id' => $lkProduct['product']->market_id]))
            ->assertOk()
            ->assertSee('LK Mouse')
            ->assertDontSee('MY Mouse');
    }

    public function test_filter_by_supplier(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $first = $this->makeSupplierProduct('Alpha Supplies', 'Alpha Item', $category->id, 'lk', ProductStatus::ACTIVE);
        $second = $this->makeSupplierProduct('Beta Supplies', 'Beta Item', $category->id, 'lk', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['supplier_id' => $first['supplier']->id]))
            ->assertOk()
            ->assertSee('Alpha Item')
            ->assertDontSee('Beta Item');
    }

    public function test_filter_by_status(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $this->makeSupplierProduct('Draft Co', 'Draft Item', $category->id, 'lk', ProductStatus::DRAFT);
        $this->makeSupplierProduct('Active Co', 'Active Item', $category->id, 'lk', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['status' => ProductStatus::DRAFT->value]))
            ->assertOk()
            ->assertSee('Draft Item')
            ->assertDontSee('Active Item');
    }

    public function test_search_by_product_name(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $this->makeSupplierProduct('Search Co', 'Unique Gadget', $category->id, 'lk', ProductStatus::ACTIVE);
        $this->makeSupplierProduct('Other Co', 'Regular Item', $category->id, 'lk', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['search' => 'Unique Gadget']))
            ->assertOk()
            ->assertSee('Unique Gadget')
            ->assertDontSee('Regular Item');
    }

    public function test_search_by_supplier_company_name(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $this->makeSupplierProduct('Orchid Traders', 'Orchid Product', $category->id, 'lk', ProductStatus::ACTIVE);
        $this->makeSupplierProduct('Maple Traders', 'Maple Product', $category->id, 'lk', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['search' => 'Orchid Traders']))
            ->assertOk()
            ->assertSee('Orchid Product')
            ->assertDontSee('Maple Product');
    }

    public function test_combined_filters(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $match = $this->makeSupplierProduct('Combo Co', 'Combo Match', $category->id, 'my', ProductStatus::ACTIVE);
        $this->makeSupplierProduct('Combo Co', 'Combo Draft', $category->id, 'my', ProductStatus::DRAFT);
        $this->makeSupplierProduct('Other Co', 'Other Match', $category->id, 'lk', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', [
                'market_id' => $match['product']->market_id,
                'supplier_id' => $match['supplier']->id,
                'status' => ProductStatus::ACTIVE->value,
                'search' => 'Combo',
            ]))
            ->assertOk()
            ->assertSee('Combo Match')
            ->assertDontSee('Combo Draft')
            ->assertDontSee('Other Match');
    }

    public function test_filter_values_preserved_in_pagination_links(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();

        for ($index = 1; $index <= 16; $index++) {
            $this->makeSupplierProduct(
                'Paged Co '.$index,
                'Paged Product '.$index,
                $category->id,
                'lk',
                ProductStatus::ACTIVE
            );
        }

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('products.index', [
                'search' => 'Paged',
                'status' => ProductStatus::ACTIVE->value,
            ]));

        $response->assertOk();
        $response->assertSee('search=Paged', false);
        $response->assertSee('status=ACTIVE', false);
        $response->assertSee('page=2', false);
    }

    public function test_market_and_currency_relationships_are_loaded_in_list(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Loaded Co', 'Loaded Product', $category->id, 'my', ProductStatus::ACTIVE);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['search' => 'Loaded Product']))
            ->assertOk()
            ->assertSee('Malaysia')
            ->assertSee('MYR');
    }

    /**
     * @param  list<string>  $permissions
     */
    private function allowPermissions(array $permissions): void
    {
        $this->allowedPermissions = $permissions;

        $permissionService = Mockery::mock(PermissionService::class);
        $permissionService->shouldReceive('hasPermission')
            ->andReturnUsing(function ($user, string $permission): bool {
                return in_array($permission, $this->allowedPermissions, true);
            });
        $permissionService->shouldReceive('hasAnyPermission')
            ->andReturnUsing(function ($user, array $permissions): bool {
                return collect($permissions)->intersect($this->allowedPermissions)->isNotEmpty();
            });
        $permissionService->shouldReceive('hasAllPermissions')
            ->andReturnUsing(function ($user, array $permissions): bool {
                return collect($permissions)->diff($this->allowedPermissions)->isEmpty();
            });

        $this->app->instance(PermissionService::class, $permissionService);
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-filter-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeCategory(): ProductCategory
    {
        return ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Office',
            'slug' => 'office-'.Str::lower(Str::random(6)),
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function makeSupplier(string $companyName, string $phone, string $email, string $marketCode = 'lk'): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::SUPPLIER->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Supplier Portal',
                'subdomain' => 'supplier-'.Str::lower(Str::random(4)),
                'description' => 'Supplier Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => $companyName,
            'email' => $email,
            'phone' => $phone,
            'registration_number' => 'REG-'.$phone,
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();
        $this->configureSupplierCompany($company, $marketCode);

        return $user;
    }

    /**
     * @return array{product: Product, supplier: User}
     */
    private function makeSupplierProduct(
        string $companyName,
        string $productName,
        string $categoryId,
        string $marketCode,
        ProductStatus $status
    ): array {
        $supplier = $this->makeSupplier(
            $companyName,
            '077'.random_int(100000, 999999),
            Str::slug($companyName).'-'.random_int(10, 99).'@feeder.local',
            $marketCode
        );

        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $categoryId,
            'market_id' => $this->marketByCode($marketCode)->id,
            'name' => $productName,
            'slug' => Str::slug($productName).'-'.Str::lower(Str::random(6)),
            'status' => $status,
            'system_visible' => true,
            'web_visible' => true,
            'price_locked' => false,
            'published_at' => $status === ProductStatus::ACTIVE ? now() : null,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        ProductDescription::query()->create([
            'product_id' => $product->id,
            'language_code' => 'en',
            'description' => $productName.' description',
        ]);

        ProductVariant::query()->create([
            'uuid' => (string) Str::uuid(),
            'product_id' => $product->id,
            'name' => 'Default',
            'barcode' => 'BC-'.strtoupper(Str::random(8)),
            'cost' => 1000,
            'selling_price' => 1500,
            'suggested_price' => 1800,
            'weight' => 0.500,
            'company_commission' => 150.00,
            'sort_order' => 0,
            'is_active' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        return [
            'product' => $product->fresh(['category', 'variants', 'supplier.company', 'market.country', 'market.currency']),
            'supplier' => $supplier,
        ];
    }
}
