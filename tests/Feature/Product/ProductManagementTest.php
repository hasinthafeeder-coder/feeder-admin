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

class ProductManagementTest extends TestCase
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

    public function test_guest_cannot_access_product_list(): void
    {
        $this->get(route('products.index'))
            ->assertRedirect(route('login', absolute: false));
    }

    public function test_user_without_permission_cannot_view_products(): void
    {
        $this->allowPermissions([]);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.index'))
            ->assertForbidden();
    }

    public function test_admin_product_list_shows_all_suppliers_and_company_names(): void
    {
        $this->allowPermissions(['products.view', 'products.update', 'products.delete']);

        $category = $this->makeCategory();
        $first = $this->makeSupplierProduct('Alpha Supplies', 'Wireless Mouse', $category->id);
        $second = $this->makeSupplierProduct('Beta Traders', 'USB Keyboard', $category->id);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Wireless Mouse');
        $response->assertSee('USB Keyboard');
        $response->assertSee('Alpha Supplies');
        $response->assertSee('Beta Traders');
        $response->assertSee($first['product']->category->name);
        $response->assertSee($second['product']->category->name);
    }

    public function test_admin_can_view_supplier_product_details(): void
    {
        $this->allowPermissions(['products.view', 'products.update']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Gamma Goods', 'Desk Lamp', $category->id, [
            'status' => ProductStatus::ACTIVE,
            'description' => 'Bright LED desk lamp',
            'commission' => 150.00,
        ]);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('products.show', $data['product']));

        $response->assertOk();
        $response->assertSee('Desk Lamp');
        $response->assertSee('Gamma Goods');
        $response->assertSee('Bright LED desk lamp');
        $response->assertSee('LKR 150.00');
        $response->assertSee('System Visible');
        $response->assertSee('Web Visible');
    }

    public function test_admin_can_edit_any_supplier_product_and_update_commission_without_changing_ownership(): void
    {
        $this->allowPermissions(['products.view', 'products.update']);

        $admin = $this->makeAdmin();
        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Delta Corp', 'Office Chair', $category->id, [
            'status' => ProductStatus::ACTIVE,
            'commission' => 150.00,
        ]);

        $product = $data['product'];
        $variant = $product->variants->first();
        $originalSupplierId = $product->supplier_id;
        $originalCreatedBy = $product->created_by;

        $response = $this->actingAs($admin)
            ->put(route('products.update', $product), [
                'name' => 'Ergonomic Office Chair',
                'category_id' => $category->id,
                'save_action' => 'save',
                'system_visible' => 1,
                'web_visible' => 1,
                'descriptions' => [
                    'en' => 'Updated English description',
                    'si' => 'Updated Sinhala description',
                    'ta' => 'Updated Tamil description',
                ],
                'variants' => [
                    [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'barcode' => $variant->barcode,
                        'cost' => $variant->cost,
                        'selling_price' => $variant->selling_price,
                        'weight' => $variant->weight,
                        'suggested_price' => $variant->suggested_price,
                        'company_commission' => '175.00',
                    ],
                ],
            ]);

        $response->assertRedirect(route('products.index'));

        $product->refresh()->load(['descriptions', 'variants']);
        $variant->refresh();

        $this->assertSame('Ergonomic Office Chair', $product->name);
        $this->assertSame($originalSupplierId, (int) $product->supplier_id);
        $this->assertSame($originalCreatedBy, (int) $product->created_by);
        $this->assertSame($admin->id, (int) $product->updated_by);
        $this->assertSame(ProductStatus::ACTIVE, $product->status);
        $this->assertSame('175.00', (string) $variant->company_commission);
        $this->assertSame($admin->id, (int) $variant->updated_by);
        $this->assertSame('Updated English description', $product->descriptionFor('en'));
    }

    public function test_admin_cannot_change_supplier_id_through_the_update_request(): void
    {
        $this->allowPermissions(['products.update']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Echo Ltd', 'Notebook', $category->id);
        $otherSupplier = $this->makeSupplier('Foxtrot Ltd', '0770000099', 'foxtrot@feeder.local');
        $variant = $data['product']->variants->first();

        $this->actingAs($this->makeAdmin())
            ->put(route('products.update', $data['product']), [
                'name' => 'Notebook',
                'category_id' => $category->id,
                'supplier_id' => $otherSupplier->id,
                'created_by' => $otherSupplier->id,
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
            ->assertSessionHasErrors(['supplier_id', 'created_by']);

        $this->assertSame($data['supplier']->id, (int) $data['product']->fresh()->supplier_id);
    }

    public function test_admin_can_deactivate_and_reactivate_a_product(): void
    {
        $this->allowPermissions(['products.view', 'products.update']);

        $admin = $this->makeAdmin();
        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Hotel Supplies', 'Power Bank', $category->id, [
            'status' => ProductStatus::ACTIVE,
        ]);

        $this->actingAs($admin)
            ->post(route('products.deactivate', $data['product']))
            ->assertRedirect(route('products.show', $data['product']));

        $data['product']->refresh();
        $this->assertSame(ProductStatus::INACTIVE, $data['product']->status);
        $this->assertSame($admin->id, (int) $data['product']->updated_by);
        $this->assertSame($data['supplier']->id, (int) $data['product']->supplier_id);

        $this->actingAs($admin)
            ->post(route('products.activate', $data['product']))
            ->assertRedirect(route('products.show', $data['product']));

        $data['product']->refresh();
        $this->assertSame(ProductStatus::ACTIVE, $data['product']->status);
        $this->assertSame($data['supplier']->id, (int) $data['product']->supplier_id);
    }

    public function test_admin_can_delete_any_supplier_product(): void
    {
        $this->allowPermissions(['products.delete']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('India Goods', 'Travel Mug', $category->id);

        $this->actingAs($this->makeAdmin())
            ->delete(route('products.destroy', $data['product']))
            ->assertRedirect(route('products.index'));

        $this->assertSoftDeleted('products', ['id' => $data['product']->id]);
    }

    public function test_edit_form_loads_existing_product_and_commission(): void
    {
        $this->allowPermissions(['products.update']);

        $category = $this->makeCategory();
        $data = $this->makeSupplierProduct('Juliet Co', 'Desk Organizer', $category->id, [
            'commission' => 160.50,
        ]);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.edit', $data['product']))
            ->assertOk()
            ->assertSee('Desk Organizer')
            ->assertSee('Juliet Co')
            ->assertSee('Company Commission');
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
            'email' => 'admin-product-'.Str::uuid().'@feeder.local',
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

    private function makeSupplier(string $companyName, string $phone, string $email): User
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
        $this->configureSupplierCompany($company, 'lk');

        return $user;
    }

    /**
     * @param  array{status?: ProductStatus, description?: string, commission?: float}  $options
     * @return array{product: Product, supplier: User}
     */
    private function makeSupplierProduct(string $companyName, string $productName, string $categoryId, array $options = []): array
    {
        $supplier = $this->makeSupplier(
            $companyName,
            '077'.random_int(100000, 999999),
            Str::slug($companyName).'-'.random_int(10, 99).'@feeder.local'
        );

        $status = $options['status'] ?? ProductStatus::ACTIVE;

        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $categoryId,
            'market_id' => $this->marketByCode('lk')->id,
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
            'description' => $options['description'] ?? $productName.' description',
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
            'company_commission' => $options['commission'] ?? 150.00,
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
