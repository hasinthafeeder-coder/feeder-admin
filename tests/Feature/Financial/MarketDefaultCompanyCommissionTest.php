<?php

namespace Tests\Feature\Financial;

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
use Feeder\Core\Models\User;
use Feeder\Core\Services\MarketDefaultCompanyCommissionService;
use Feeder\Core\Services\ProductService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class MarketDefaultCompanyCommissionTest extends TestCase
{
    use SetsUpMarketData;
    use UsesMysqlTestDatabase;

    /**
     * @var list<string>
     */
    private array $allowedPermissions = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpMysqlTestDatabase();
        $this->seedMarketLookups();
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_lk_market_resolves_configured_default(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);

        $this->assertSame('150.00', $service->getDefaultCompanyCommission('lk'));
    }

    public function test_my_market_resolves_its_own_independent_default(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);

        $this->assertSame('15.00', $service->getDefaultCompanyCommission('my'));
    }

    public function test_defaults_are_not_shared_between_markets(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);

        $this->assertNotSame(
            $service->getDefaultCompanyCommission('lk'),
            $service->getDefaultCompanyCommission('my')
        );
    }

    public function test_missing_market_default_fails_clearly(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);
        $market = $this->marketByCode('th');

        \Feeder\Core\Models\Setting::query()
            ->where('key', MarketDefaultCompanyCommissionService::KEY)
            ->where('market_id', $market->id)
            ->delete();

        $this->expectException(ValidationException::class);

        try {
            $service->getDefaultCompanyCommission($market);
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('default_company_commission', $exception->errors());

            throw $exception;
        }
    }

    public function test_changing_one_market_default_does_not_affect_another_market(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);

        $service->setDefaultCompanyCommission('my', '22.50');

        $this->assertSame('150.00', $service->getDefaultCompanyCommission('lk'));
        $this->assertSame('22.50', $service->getDefaultCompanyCommission('my'));
    }

    public function test_lk_supplier_product_variant_receives_lk_market_default_commission(): void
    {
        $supplier = $this->makeSupplierUser('lk');
        $category = $this->makeCategory();

        $product = app(ProductService::class)->createProduct(
            [
                'supplier_id' => $supplier->id,
                'category_id' => $category->id,
                'name' => 'LK Commission Product',
                'status' => ProductStatus::DRAFT,
            ],
            [],
            [[
                'name' => 'Default',
                'cost' => 100,
                'selling_price' => 200,
                'weight' => 0.5,
            ]]
        );

        $this->assertSame('150.00', (string) $product->variants->first()->company_commission);
    }

    public function test_my_supplier_product_variant_receives_my_market_default_commission(): void
    {
        $supplier = $this->makeSupplierUser('my');
        $category = $this->makeCategory();

        $product = app(ProductService::class)->createProduct(
            [
                'supplier_id' => $supplier->id,
                'category_id' => $category->id,
                'name' => 'MY Commission Product',
                'status' => ProductStatus::DRAFT,
            ],
            [],
            [[
                'name' => 'Default',
                'cost' => 10,
                'selling_price' => 20,
                'weight' => 0.5,
            ]]
        );

        $this->assertSame('15.00', (string) $product->variants->first()->company_commission);
    }

    public function test_existing_variant_commission_remains_unchanged_when_market_default_changes(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);
        $supplier = $this->makeSupplierUser('lk');
        $category = $this->makeCategory();
        $product = $this->makeProductWithVariant($supplier, $category, 'lk', '150.00');

        $service->setDefaultCompanyCommission('lk', '175.00');

        $product->refresh();
        $this->assertSame('150.00', (string) $product->variants->first()->company_commission);
    }

    public function test_new_variant_created_after_market_default_change_receives_new_default(): void
    {
        $service = app(MarketDefaultCompanyCommissionService::class);
        $supplier = $this->makeSupplierUser('lk');
        $category = $this->makeCategory();
        $product = $this->makeProductWithVariant($supplier, $category, 'lk', '150.00');

        $service->setDefaultCompanyCommission('lk', '175.00');

        app(ProductService::class)->updateProduct(
            $product,
            ['updated_by' => $supplier->id],
            [],
            [
                [
                    'id' => $product->variants->first()->id,
                    'name' => 'Default',
                    'cost' => 100,
                    'selling_price' => 200,
                    'weight' => 0.5,
                    'company_commission' => '150.00',
                ],
                [
                    'name' => 'Large',
                    'cost' => 120,
                    'selling_price' => 220,
                    'weight' => 0.6,
                ],
            ]
        );

        $product->refresh()->load('variants');

        $newVariant = $product->variants->firstWhere('name', 'Large');
        $this->assertNotNull($newVariant);
        $this->assertSame('175.00', (string) $newVariant->company_commission);
        $this->assertSame('150.00', (string) $product->variants->firstWhere('name', 'Default')->company_commission);
    }

    public function test_authorized_admin_can_update_market_default(): void
    {
        $this->allowPermissions(['settings.view', 'settings.financial.update']);

        $admin = $this->makeAdmin();
        $market = $this->marketByCode('my');

        $this->actingAs($admin)
            ->post(route('settings.financial.update'), $this->marketFinancialUpdatePayload([
                'market_company_commissions' => [
                    $this->marketByCode('lk')->uuid => '150.00',
                    $market->uuid => '18.00',
                    $this->marketByCode('th')->uuid => '50.00',
                ],
            ]))
            ->assertRedirect(route('settings.financial'));

        $this->assertSame('18.00', app(MarketDefaultCompanyCommissionService::class)->getDefaultCompanyCommission('my'));
    }

    public function test_invalid_market_commission_is_rejected(): void
    {
        $this->allowPermissions(['settings.view', 'settings.financial.update']);

        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('settings.financial.update'), $this->marketFinancialUpdatePayload([
                'market_company_commissions' => [
                    $this->marketByCode('lk')->uuid => 'not-a-number',
                    $this->marketByCode('my')->uuid => '15.00',
                    $this->marketByCode('th')->uuid => '50.00',
                ],
            ]))
            ->assertSessionHasErrors('market_company_commissions.'.$this->marketByCode('lk')->uuid);
    }

    public function test_negative_market_commission_is_rejected(): void
    {
        $this->allowPermissions(['settings.view', 'settings.financial.update']);

        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('settings.financial.update'), $this->marketFinancialUpdatePayload([
                'market_company_commissions' => [
                    $this->marketByCode('lk')->uuid => '-5.00',
                    $this->marketByCode('my')->uuid => '15.00',
                    $this->marketByCode('th')->uuid => '50.00',
                ],
            ]))
            ->assertSessionHasErrors('market_company_commissions.'.$this->marketByCode('lk')->uuid);
    }

    public function test_unauthorized_user_cannot_update_market_financial_defaults(): void
    {
        $reseller = $this->makeResellerUser();

        $this->actingAs($reseller)
            ->post(route('settings.financial.update'), $this->marketFinancialUpdatePayload([
                'market_company_commissions' => [
                    $this->marketByCode('lk')->uuid => '999.00',
                    $this->marketByCode('my')->uuid => '15.00',
                    $this->marketByCode('th')->uuid => '50.00',
                ],
            ]))
            ->assertForbidden();
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-commission-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeResellerUser(): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::RESELLER->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Reseller Portal',
                'subdomain' => 'reseller',
                'description' => 'Reseller Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Reseller Co',
            'email' => 'reseller-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '071'.random_int(1000000, 9999999),
            'registration_number' => 'REG-'.Str::random(8),
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

    private function makeSupplierUser(string $marketCode): User
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
            'name' => 'Supplier Co '.Str::lower(Str::random(4)),
            'email' => 'supplier-'.Str::lower(Str::random(6)).'@feeder.local',
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
        $this->configureSupplierCompany($company, $marketCode);

        return $user;
    }

    private function makeCategory(): ProductCategory
    {
        return ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'General',
            'slug' => 'general-'.Str::lower(Str::random(6)),
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function makeProductWithVariant(User $supplier, ProductCategory $category, string $marketCode, string $commission): Product
    {
        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'market_id' => $this->marketByCode($marketCode)->id,
            'name' => 'Historical Product',
            'slug' => 'historical-product-'.Str::lower(Str::random(6)),
            'status' => ProductStatus::DRAFT,
            'system_visible' => true,
            'web_visible' => true,
            'price_locked' => false,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        ProductVariant::query()->create([
            'uuid' => (string) Str::uuid(),
            'product_id' => $product->id,
            'name' => 'Default',
            'barcode' => 'BC-'.strtoupper(Str::random(8)),
            'cost' => 100,
            'selling_price' => 200,
            'suggested_price' => 220,
            'weight' => 0.500,
            'company_commission' => $commission,
            'sort_order' => 0,
            'is_active' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        return $product->fresh('variants');
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function marketFinancialUpdatePayload(array $overrides = []): array
    {
        return array_merge([
            'market_company_commissions' => [
                $this->marketByCode('lk')->uuid => '150.00',
                $this->marketByCode('my')->uuid => '15.00',
                $this->marketByCode('th')->uuid => '50.00',
            ],
            'market_introducer_bonuses' => [
                $this->marketByCode('lk')->uuid => '50.00',
                $this->marketByCode('my')->uuid => '5.00',
                $this->marketByCode('th')->uuid => '20.00',
            ],
            'market_reseller_service_charges' => [
                $this->marketByCode('lk')->uuid => '75.00',
                $this->marketByCode('my')->uuid => '15.00',
                $this->marketByCode('th')->uuid => '30.00',
            ],
        ], $overrides);
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
}
