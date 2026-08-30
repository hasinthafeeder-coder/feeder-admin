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
use Feeder\Core\Support\CurrencyDisplay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class ProductMarketCurrencyTest extends TestCase
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

    public function test_admin_product_details_display_market_and_lkr_currency(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('LK Supplies', 'LK Product', $category->id, 'lk', 1500);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.show', $data['product']))
            ->assertOk()
            ->assertSee('Market: Sri Lanka')
            ->assertSee('LKR 1,500.00');
    }

    public function test_admin_product_details_display_myr_currency(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('MY Supplies', 'MY Product', $category->id, 'my', 25);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.show', $data['product']))
            ->assertOk()
            ->assertSee('Market: Malaysia')
            ->assertSee('MYR 25.00');
    }

    public function test_admin_product_list_includes_market_column(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $this->makeSupplierProduct('Alpha Supplies', 'Alpha Product', $category->id, 'lk', 1500);
        $this->makeSupplierProduct('Beta Supplies', 'Beta Product', $category->id, 'my', 25);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index'))
            ->assertOk()
            ->assertSee('Sri Lanka')
            ->assertSee('Malaysia');
    }

    public function test_admin_edit_form_displays_product_market_currency_labels(): void
    {
        $this->allowPermissions(['products.update']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Gamma Supplies', 'Gamma Product', $category->id, 'my', 25);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.edit', $data['product']))
            ->assertOk()
            ->assertSee('Product Market')
            ->assertSee('Malaysia')
            ->assertSee('Currency: MYR (RM)')
            ->assertSee('MYR');
    }

    public function test_legacy_product_without_market_shows_currency_unavailable(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Legacy Supplies', 'Legacy Product', $category->id, null, 1500);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.show', $data['product']))
            ->assertOk()
            ->assertSee('Market unavailable')
            ->assertSee(CurrencyDisplay::UNAVAILABLE_LABEL)
            ->assertDontSee('LKR 1,500.00');
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
            'email' => 'admin-market-'.Str::uuid().'@feeder.local',
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

    private function makeSupplier(string $companyName, string $phone, string $email, ?string $marketCode = 'lk'): User
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

        if ($marketCode !== null) {
            $this->configureSupplierCompany($company, $marketCode);
        }

        return $user;
    }

    /**
     * @return array{product: Product, supplier: User}
     */
    private function makeSupplierProduct(
        string $companyName,
        string $productName,
        string $categoryId,
        ?string $marketCode,
        float $sellingPrice
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
            'market_id' => $marketCode ? $this->marketByCode($marketCode)->id : null,
            'name' => $productName,
            'slug' => Str::slug($productName).'-'.Str::lower(Str::random(6)),
            'status' => ProductStatus::ACTIVE,
            'system_visible' => true,
            'web_visible' => true,
            'price_locked' => false,
            'published_at' => now(),
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
            'cost' => $sellingPrice - 5,
            'selling_price' => $sellingPrice,
            'suggested_price' => $sellingPrice + 5,
            'weight' => 0.500,
            'company_commission' => 150.00,
            'sort_order' => 0,
            'is_active' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        return [
            'product' => $product->fresh(['category', 'variants', 'descriptions', 'supplier.company', 'market.country', 'market.currency', 'images.file']),
            'supplier' => $supplier,
        ];
    }
}
