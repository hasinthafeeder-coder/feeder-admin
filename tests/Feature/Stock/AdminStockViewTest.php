<?php

namespace Tests\Feature\Stock;

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
use Feeder\Core\Services\GoodsReceivedNoteService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class AdminStockViewTest extends TestCase
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

    public function test_admin_with_permission_can_view_stock_across_suppliers(): void
    {
        $this->allowPermissions(['stock.view']);

        $supplierA = $this->makeSupplierUser('Alpha Supplies');
        $supplierB = $this->makeSupplierUser('Beta Supplies');
        $admin = $this->makeAdminUser();

        $variantA = $this->makeVariantForSupplier($supplierA, 'Alpha Widget', 'Large');
        $this->makeVariantForSupplier($supplierB, 'Beta Gadget', 'Small');

        app(GoodsReceivedNoteService::class)->createGrn(
            $supplierA->id,
            [
                'received_date' => now()->toDateString(),
                'created_by' => $supplierA->id,
                'updated_by' => $supplierA->id,
            ],
            [[
                'product_id' => $variantA->product_id,
                'product_variant_id' => $variantA->id,
                'received_quantity' => 50,
                'damaged_quantity' => 0,
                'unit_cost' => '100.00',
            ]]
        );

        $this->actingAs($admin)
            ->get(route('stock.index'))
            ->assertOk()
            ->assertSee('Alpha Widget')
            ->assertSee('Beta Gadget')
            ->assertSee('Alpha Supplies')
            ->assertSee('Beta Supplies')
            ->assertSee('50 in stock');
    }

    public function test_admin_can_filter_variants_by_market(): void
    {
        $this->allowPermissions(['stock.view']);

        $supplier = $this->makeSupplierUser('Market Filter Supplier Co');
        $admin = $this->makeAdminUser();

        $lkVariant = $this->makeVariantForSupplier($supplier, 'LK Stock Item', 'Default', 'lk');
        $this->makeVariantForSupplier($supplier, 'MY Stock Item', 'Default', 'my');

        $this->actingAs($admin)
            ->get(route('stock.index', ['market_id' => $lkVariant->product->market_id]))
            ->assertOk()
            ->assertSee('LK Stock Item')
            ->assertDontSee('MY Stock Item')
            ->assertSee('Sri Lanka')
            ->assertSee('LK');
    }

    public function test_admin_can_filter_out_of_stock_variants(): void
    {
        $this->allowPermissions(['stock.view']);

        $supplier = $this->makeSupplierUser('Filter Supplier Co');
        $admin = $this->makeAdminUser();

        $inVariant = $this->makeVariantForSupplier($supplier, 'In Stock Item', 'Default');
        $outVariant = $this->makeVariantForSupplier($supplier, 'Out Of Stock Item', 'Default');

        app(GoodsReceivedNoteService::class)->createGrn(
            $supplier->id,
            [
                'received_date' => now()->toDateString(),
                'created_by' => $supplier->id,
                'updated_by' => $supplier->id,
            ],
            [
                [
                    'product_id' => $inVariant->product_id,
                    'product_variant_id' => $inVariant->id,
                    'received_quantity' => 20,
                    'damaged_quantity' => 0,
                    'unit_cost' => '100.00',
                ],
                [
                    'product_id' => $outVariant->product_id,
                    'product_variant_id' => $outVariant->id,
                    'received_quantity' => 10,
                    'damaged_quantity' => 10,
                    'unit_cost' => '100.00',
                ],
            ]
        );

        $this->actingAs($admin)
            ->get(route('stock.index', ['stock_status' => 'out_of_stock', 'supplier_id' => $supplier->id]))
            ->assertOk()
            ->assertSee('Out Of Stock Item')
            ->assertDontSee('In Stock Item');
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

    private function makeAdminUser(): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::ADMIN->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Admin Portal',
                'subdomain' => 'admin-'.Str::lower(Str::random(4)),
                'description' => 'Admin Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Feeder Admin Co',
            'email' => 'admin-stock-'.Str::lower(Str::random(4)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $company->email,
            'phone' => $company->phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        return $user;
    }

    private function makeSupplierUser(string $companyName): User
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
            'email' => Str::lower(Str::slug($companyName)).'-'.Str::lower(Str::random(4)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $company->email,
            'phone' => $company->phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();
        $this->configureSupplierCompany($company, 'lk');

        return $user;
    }

    private function makeVariantForSupplier(
        User $supplier,
        string $productName,
        string $variantName,
        string $marketCode = 'lk',
    ): ProductVariant {
        $category = ProductCategory::query()->firstOrCreate(
            ['slug' => 'admin-stock-test'],
            [
                'id' => (string) Str::uuid(),
                'name' => 'General',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'market_id' => $this->marketByCode($marketCode)->id,
            'category_id' => $category->id,
            'name' => $productName,
            'slug' => Str::slug($productName).'-'.Str::lower(Str::random(4)),
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
            'description' => 'Sample description',
        ]);

        return ProductVariant::query()->create([
            'uuid' => (string) Str::uuid(),
            'product_id' => $product->id,
            'name' => $variantName,
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
    }
}
