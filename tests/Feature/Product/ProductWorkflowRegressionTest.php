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
use Feeder\Core\Services\MarketDefaultCompanyCommissionService;
use Feeder\Core\Services\ProductService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class ProductWorkflowRegressionTest extends TestCase
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

    public function test_admin_cannot_move_product_market_through_update_request(): void
    {
        $this->allowPermissions(['products.update']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Immutable Co', 'Immutable Product', $category->id, 'lk');
        $variant = $data['product']->variants->first();
        $originalMarketId = $data['product']->market_id;
        $otherMarketId = $this->marketByCode('my')->id;

        $this->actingAs($this->makeAdmin())
            ->put(route('products.update', $data['product']), [
                'name' => 'Immutable Product',
                'category_id' => $category->id,
                'market_id' => $otherMarketId,
                'save_action' => 'save',
                'descriptions' => ['en' => 'Stay', 'si' => null, 'ta' => null],
                'variants' => [
                    [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'barcode' => $variant->barcode,
                        'cost' => $variant->cost,
                        'selling_price' => $variant->selling_price,
                        'weight' => $variant->weight,
                        'suggested_price' => $variant->suggested_price,
                        'company_commission' => $variant->company_commission,
                    ],
                ],
            ])
            ->assertSessionHasErrors(['market_id']);

        $this->assertSame($originalMarketId, $data['product']->fresh()->market_id);
    }

    public function test_existing_variant_commission_remains_when_omitted_after_market_default_changes(): void
    {
        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Commission Co', 'Commission Product', $category->id, 'lk');
        $product = $data['product'];
        $variant = $product->variants->first();

        app(MarketDefaultCompanyCommissionService::class)
            ->setDefaultCompanyCommission($this->marketByCode('lk'), '250.00');

        app(ProductService::class)->updateProduct(
            $product,
            ['name' => $product->name, 'updated_by' => $data['supplier']->id],
            [],
            [[
                'id' => $variant->id,
                'name' => $variant->name,
                'barcode' => $variant->barcode,
                'cost' => $variant->cost,
                'selling_price' => $variant->selling_price,
                'weight' => $variant->weight,
                'suggested_price' => $variant->suggested_price,
                'sort_order' => 0,
                'is_active' => true,
            ]],
        );

        $this->assertSame('150.00', (string) $variant->fresh()->company_commission);
    }

    public function test_new_variant_receives_latest_market_default_commission(): void
    {
        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('New Variant Co', 'New Variant Product', $category->id, 'lk');
        $product = $data['product'];
        $existingVariant = $product->variants->first();

        app(MarketDefaultCompanyCommissionService::class)
            ->setDefaultCompanyCommission($this->marketByCode('lk'), '275.00');

        app(ProductService::class)->updateProduct(
            $product,
            ['name' => $product->name, 'updated_by' => $data['supplier']->id],
            [],
            [
                [
                    'id' => $existingVariant->id,
                    'name' => $existingVariant->name,
                    'barcode' => $existingVariant->barcode,
                    'cost' => $existingVariant->cost,
                    'selling_price' => $existingVariant->selling_price,
                    'weight' => $existingVariant->weight,
                    'suggested_price' => $existingVariant->suggested_price,
                    'company_commission' => $existingVariant->company_commission,
                    'sort_order' => 0,
                    'is_active' => true,
                ],
                [
                    'name' => 'Large',
                    'barcode' => 'NEW-'.strtoupper(Str::random(8)),
                    'cost' => 1200,
                    'selling_price' => 1800,
                    'weight' => 0.750,
                    'suggested_price' => 2000,
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            ],
        );

        $newVariant = $product->variants()->where('name', 'Large')->first();
        $this->assertNotNull($newVariant);
        $this->assertSame('275.00', (string) $newVariant->company_commission);
    }

    public function test_soft_deleted_product_is_excluded_from_admin_list(): void
    {
        $this->allowPermissions(['products.view', 'products.delete']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Delete Co', 'Deleted Product', $category->id, 'lk');

        $this->actingAs($this->makeAdmin())
            ->delete(route('products.destroy', $data['product']))
            ->assertRedirect(route('products.index'));

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index', ['search' => 'Deleted Product']))
            ->assertOk()
            ->assertSee('No products found.');
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
            'email' => 'admin-workflow-'.Str::uuid().'@feeder.local',
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
        string $marketCode
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
            'product' => $product->fresh(['variants', 'market']),
            'supplier' => $supplier,
        ];
    }
}
