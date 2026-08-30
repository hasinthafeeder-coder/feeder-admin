<?php

namespace Tests\Feature\Reseller;

use App\Services\Reseller\ResellerApprovalService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\ResellerMarketAccess;
use Feeder\Core\Models\Role;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\Support\SetsUpMarketData;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class ResellerApprovalRoleTest extends TestCase
{
    use SetsUpMarketData;
    use SetsUpPortalRoles;
    use UsesMysqlTestDatabase;

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

    public function test_first_reseller_approval_assigns_reseller_owner_role(): void
    {
        $ownerRole = $this->ensureOwnerRole(PortalCode::RESELLER);
        $reseller = $this->makePendingReseller();

        app(ResellerApprovalService::class)->approve(
            $reseller,
            $this->countryByIso('LK')->uuid,
            [$this->marketByCode('lk')->uuid]
        );

        $reseller->refresh();

        $this->assertSame($ownerRole->id, $reseller->role_id);
        $this->assertSame(UserStatus::ACTIVE, $reseller->status);
        $this->assertSame(CompanyStatus::ACTIVE, $reseller->company->fresh()->status);
    }

    public function test_suspended_reseller_reactivation_does_not_require_market_fields(): void
    {
        $ownerRole = $this->ensureOwnerRole(PortalCode::RESELLER);
        $reseller = $this->makeSuspendedReseller($ownerRole);

        app(ResellerApprovalService::class)->approve($reseller);

        $reseller->refresh();
        $company = $reseller->company->fresh(['allowedMarkets']);

        $this->assertSame($ownerRole->id, $reseller->role_id);
        $this->assertSame(UserStatus::ACTIVE, $reseller->status);
        $this->assertSame(CompanyStatus::ACTIVE, $company->status);
        $this->assertSame($this->countryByIso('LK')->id, $company->home_country_id);
        $this->assertCount(1, $company->allowedMarkets);
    }

    public function test_reseller_approval_fails_safely_when_owner_role_cannot_be_resolved(): void
    {
        $reseller = $this->makePendingReseller();

        Role::query()
            ->where('slug', 'owner')
            ->whereHas('portal', fn ($query) => $query->where('code', PortalCode::RESELLER->value))
            ->delete();

        try {
            app(ResellerApprovalService::class)->approve(
                $reseller,
                $this->countryByIso('LK')->uuid,
                [$this->marketByCode('lk')->uuid]
            );
            $this->fail('Expected reseller approval to fail when owner role is missing.');
        } catch (ValidationException) {
            // expected
        }

        $reseller->refresh();
        $company = $reseller->company->fresh();

        $this->assertNull($reseller->role_id);
        $this->assertSame(UserStatus::PENDING, $reseller->status);
        $this->assertSame(CompanyStatus::PENDING, $company->status);
        $this->assertNull($company->home_country_id);
        $this->assertSame(0, ResellerMarketAccess::query()->where('company_id', $company->id)->count());
    }

    private function makePendingReseller(): User
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

        $user = User::query()->create([
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

    private function makeSuspendedReseller(Role $ownerRole): User
    {
        $portal = $this->ensurePortal(PortalCode::RESELLER, 'Reseller Portal');
        $homeCountry = $this->countryByIso('LK');

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Suspended Reseller Co',
            'email' => 'suspended-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'home_country_id' => $homeCountry->id,
            'status' => CompanyStatus::SUSPENDED->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'role_id' => $ownerRole->id,
            'email' => $company->email,
            'phone' => $company->phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::SUSPENDED->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        ResellerMarketAccess::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'market_id' => $this->marketByCode('lk')->id,
            'granted_by' => $user->id,
        ]);

        return $user->fresh('company');
    }
}
