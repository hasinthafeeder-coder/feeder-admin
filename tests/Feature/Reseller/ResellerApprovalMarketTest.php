<?php

namespace Tests\Feature\Reseller;

use App\Models\User;
use App\Services\Reseller\ResellerApprovalService;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\ResellerMarketAccess;
use Feeder\Core\Models\User as CoreUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class ResellerApprovalMarketTest extends TestCase
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

    public function test_pending_reseller_approval_requires_home_country_and_markets(): void
    {
        $this->allowPermissions(['resellers.approve']);

        $reseller = $this->makePendingReseller();

        $this->actingAs($this->makeAdmin())
            ->post(route('resellers.approve', $reseller), [])
            ->assertSessionHasErrors(['home_country_id', 'allowed_market_ids']);
    }

    public function test_approved_reseller_receives_home_country_and_market_access_atomically(): void
    {
        $service = app(ResellerApprovalService::class);
        $reseller = $this->makePendingReseller();
        $homeCountry = $this->countryByIso('LK');
        $markets = [
            $this->marketByCode('lk')->uuid,
            $this->marketByCode('my')->uuid,
        ];

        $service->approve($reseller, $homeCountry->uuid, $markets);

        $reseller->refresh();
        $company = $reseller->company->fresh(['homeCountry', 'allowedMarkets']);

        $this->assertSame(UserStatus::ACTIVE, $reseller->status);
        $this->assertSame(CompanyStatus::ACTIVE, $company->status);
        $this->assertSame($homeCountry->id, $company->home_country_id);
        $this->assertCount(2, $company->allowedMarkets);
    }

    public function test_inactive_market_cannot_be_assigned_during_approval(): void
    {
        $service = app(ResellerApprovalService::class);
        $reseller = $this->makePendingReseller();

        $this->expectException(ValidationException::class);

        $service->approve(
            $reseller,
            $this->countryByIso('LK')->uuid,
            [$this->marketByCode('th')->uuid]
        );
    }

    public function test_approval_rolls_back_when_market_sync_fails(): void
    {
        $service = app(ResellerApprovalService::class);
        $reseller = $this->makePendingReseller();

        try {
            $service->approve($reseller, $this->countryByIso('LK')->uuid, []);
        } catch (ValidationException) {
            // expected
        }

        $reseller->refresh();

        $this->assertSame(UserStatus::PENDING, $reseller->status);
        $this->assertNull($reseller->company->fresh()->home_country_id);
        $this->assertSame(0, ResellerMarketAccess::query()->where('company_id', $reseller->company_id)->count());
    }

    private function makePendingReseller(): CoreUser
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::RESELLER->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Reseller Portal',
                'subdomain' => 'reseller-'.Str::lower(Str::random(4)),
                'description' => 'Reseller Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Pending Reseller Co',
            'email' => 'pending-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'status' => CompanyStatus::PENDING->value,
        ]);

        $user = CoreUser::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $company->email,
            'phone' => $company->phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::PENDING->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        return $user->fresh('company');
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
            'email' => 'admin-approval-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }
}
