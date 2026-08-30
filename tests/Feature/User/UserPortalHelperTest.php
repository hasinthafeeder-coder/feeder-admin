<?php

namespace Tests\Feature\User;

use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class UserPortalHelperTest extends TestCase
{
    use SetsUpPortalRoles;
    use UsesMysqlTestDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpMysqlTestDatabase();
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_supplier_owner_is_not_identified_as_reseller(): void
    {
        $user = $this->makeOwnerForPortal(PortalCode::SUPPLIER);

        $this->assertFalse($user->isReseller());
        $this->assertTrue($user->isSupplier());
    }

    public function test_reseller_owner_is_identified_as_reseller(): void
    {
        $user = $this->makeOwnerForPortal(PortalCode::RESELLER);

        $this->assertTrue($user->isReseller());
        $this->assertFalse($user->isSupplier());
    }

    public function test_admin_user_is_not_identified_as_reseller_or_supplier(): void
    {
        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-helper-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $this->assertFalse($user->isReseller());
        $this->assertFalse($user->isSupplier());
    }

    public function test_user_without_company_is_not_identified_as_reseller_or_supplier(): void
    {
        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'orphan-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $this->assertFalse($user->isReseller());
        $this->assertFalse($user->isSupplier());
    }

    private function makeOwnerForPortal(PortalCode $portalCode): User
    {
        $portal = $this->ensurePortal(
            $portalCode,
            match ($portalCode) {
                PortalCode::SUPPLIER => 'Supplier Portal',
                PortalCode::RESELLER => 'Reseller Portal',
                PortalCode::ADMIN => 'Admin Portal',
            }
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => $portalCode->value.' Owner Co',
            'email' => Str::lower($portalCode->value).'-owner-'.Str::lower(Str::random(6)).'@feeder.local',
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

        return $user->fresh('company.portal');
    }
}
