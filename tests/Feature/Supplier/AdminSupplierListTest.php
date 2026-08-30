<?php

namespace Tests\Feature\Supplier;

use App\Services\Supplier\SupplierService;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class AdminSupplierListTest extends TestCase
{
    use SetsUpMarketData;
    use SetsUpPortalRoles;
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
        $this->allowPermissions(['suppliers.view']);
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_supplier_list_displays_operation_country_code(): void
    {
        $supplier = $this->makeActiveSupplier('lk');

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('suppliers.index'));

        $response->assertOk();
        $response->assertSee($supplier->company->name, false);
        $response->assertSee('LK', false);
    }

    public function test_supplier_without_operation_market_shows_fallback(): void
    {
        $supplier = $this->makeActiveSupplier(null);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('suppliers.index'));

        $response->assertOk();
        $response->assertSee($supplier->company->name, false);
        $response->assertSee('Market unavailable', false);
    }

    public function test_supplier_list_eager_loads_operation_market_country(): void
    {
        $this->makeActiveSupplier('lk');

        DB::enableQueryLog();

        $suppliers = app(SupplierService::class)->getList();
        $firstPage = $suppliers['ACTIVE']->items();

        $this->assertNotEmpty($firstPage);

        foreach ($firstPage as $supplier) {
            $this->assertTrue($supplier->relationLoaded('company'));
            $this->assertTrue($supplier->company->relationLoaded('operationMarket'));
            $this->assertTrue($supplier->company->operationMarket?->relationLoaded('country') ?? true);
        }

        DB::disableQueryLog();
    }

    private function makeActiveSupplier(?string $marketCode): User
    {
        $portal = $this->ensurePortal(PortalCode::SUPPLIER, 'Supplier Portal');

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Supplier '.Str::upper(Str::random(4)),
            'email' => 'supplier-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        if ($marketCode !== null) {
            $this->configureSupplierCompany($company, $marketCode);
        }

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

        return $user->fresh(['company.operationMarket.country']);
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-supplier-list-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * @param  list<string>  $permissions
     */
    private function allowPermissions(array $permissions): void
    {
        $this->allowedPermissions = $permissions;

        $permissionService = Mockery::mock(PermissionService::class);
        $permissionService->shouldReceive('hasPermission')
            ->andReturnUsing(fn ($user, string $permission): bool => in_array($permission, $this->allowedPermissions, true));
        $permissionService->shouldReceive('hasAnyPermission')
            ->andReturnUsing(fn ($user, array $permissions): bool => collect($permissions)->intersect($this->allowedPermissions)->isNotEmpty());
        $permissionService->shouldReceive('hasAllPermissions')
            ->andReturnUsing(fn ($user, array $permissions): bool => collect($permissions)->diff($this->allowedPermissions)->isEmpty());

        $this->app->instance(PermissionService::class, $permissionService);
    }
}
