<?php

namespace Tests\Feature\Testing;

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
use Feeder\Core\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class SupplierBarcodeTestSheetTest extends TestCase
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

    public function test_guest_cannot_open_the_barcode_test_sheet(): void
    {
        $this->get(route('products.barcode-test-sheet'))
            ->assertRedirect(route('login', absolute: false));
    }

    public function test_page_exposes_the_existing_code128_renderer_and_print_layout(): void
    {
        $this->allowPermissions(['products.view']);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.barcode-test-sheet'))
            ->assertOk()
            ->assertSee('Product Barcode Test Sheet')
            ->assertSee('Generate / Load Barcodes')
            ->assertSee('Print Barcodes')
            ->assertSee('supplier-code128.js', false)
            ->assertSee('size: A4 portrait', false)
            ->assertSee('break-inside: avoid', false)
            ->assertSee('page-break-inside: avoid', false);
    }

    public function test_supplier_search_returns_only_matching_suppliers(): void
    {
        $this->allowPermissions(['products.view']);

        $match = $this->makeSupplier('Orchid Barcode Co');
        $this->makeSupplier('Maple Barcode Co');

        $this->actingAs($this->makeAdmin())
            ->getJson(route('products.barcode-test-sheet.suppliers', ['q' => 'Orchid Barcode']))
            ->assertOk()
            ->assertJsonFragment([
                'uuid' => $match->uuid,
                'label' => 'Orchid Barcode Co',
            ])
            ->assertJsonMissing(['label' => 'Maple Barcode Co']);
    }

    public function test_sheet_returns_existing_variant_barcodes_and_skips_blank_ones(): void
    {
        $this->allowPermissions(['products.view']);

        $supplier = $this->makeSupplier('Sheet Supplies');
        $supplier->forceFill(['uuid' => 'SHEET'.strtoupper(Str::random(5))])->save();
        $other = $this->makeSupplier('Other Supplies');
        $category = $this->makeCategory();

        $product = $this->makeProduct($supplier, $category, 'Scanner Tee With A Very Long Product Name That Must Wrap');
        $kept = $this->makeVariant($product, $supplier, 'Black / Large', '8901234567890', 1);
        $blank = $this->makeVariant($product, $supplier, 'White / Small', 'TEMPBLANK1', 2);
        $blank->forceFill(['barcode' => ''])->save();

        $otherProduct = $this->makeProduct($other, $category, 'Other Product');
        $this->makeVariant($otherProduct, $other, 'Default', '1112223334445', 0);

        $kept->refresh();
        $blank->refresh();

        $this->assertSame('8901234567890', $kept->barcode);
        $this->assertSame('', $blank->barcode);

        $this->actingAs($this->makeAdmin())
            ->getJson(route('products.barcode-test-sheet.barcodes', ['supplier' => $supplier->uuid]))
            ->assertOk()
            ->assertJsonPath('supplier.label', 'Sheet Supplies')
            ->assertJsonCount(1, 'barcodes')
            ->assertJsonPath('barcodes.0.product_name', 'Scanner Tee With A Very Long Product Name That Must Wrap')
            ->assertJsonPath('barcodes.0.variant_name', 'Black / Large')
            ->assertJsonPath('barcodes.0.barcode', '8901234567890');
    }

    public function test_supplier_with_no_barcodes_returns_an_empty_list(): void
    {
        $this->allowPermissions(['products.view']);

        $supplier = $this->makeSupplier('Empty Barcode Co');
        $category = $this->makeCategory();
        $product = $this->makeProduct($supplier, $category, 'Uncoded Product');
        $variant = $this->makeVariant($product, $supplier, 'Default', 'TEMPBLANK2', 0);
        $variant->forceFill(['barcode' => ''])->save();

        $this->actingAs($this->makeAdmin())
            ->getJson(route('products.barcode-test-sheet.barcodes', ['supplier' => $supplier->uuid]))
            ->assertOk()
            ->assertJsonPath('barcodes', []);
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
            'email' => 'admin-barcode-sheet-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeSupplier(string $companyName): User
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

        $phone = '077'.random_int(1000000, 9999999);
        $email = Str::slug($companyName).'-'.Str::lower(Str::random(6)).'@feeder.local';

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
        $this->configureSupplierCompany($company, 'lk');

        return $user;
    }

    private function makeCategory(): ProductCategory
    {
        return ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Apparel',
            'slug' => 'apparel-'.Str::lower(Str::random(6)),
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function makeProduct(User $supplier, ProductCategory $category, string $name): Product
    {
        return Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'market_id' => $this->marketByCode('lk')->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(6)),
            'status' => ProductStatus::ACTIVE,
            'system_visible' => true,
            'web_visible' => true,
            'price_locked' => false,
            'published_at' => now(),
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);
    }

    private function makeVariant(Product $product, User $supplier, string $name, string $barcode, int $sortOrder): ProductVariant
    {
        return ProductVariant::query()->create([
            'uuid' => (string) Str::uuid(),
            'product_id' => $product->id,
            'name' => $name,
            'barcode' => $barcode,
            'cost' => 1000,
            'selling_price' => 1500,
            'suggested_price' => 1800,
            'weight' => 0.250,
            'company_commission' => 100,
            'sort_order' => $sortOrder,
            'is_active' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);
    }
}
