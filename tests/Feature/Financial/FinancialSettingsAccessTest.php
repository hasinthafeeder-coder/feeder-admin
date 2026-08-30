<?php

namespace Tests\Feature\Financial;

use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class FinancialSettingsAccessTest extends TestCase
{
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

    public function test_reseller_cannot_update_global_financial_settings(): void
    {
        $user = $this->createResellerUser('reseller@feeder.local', '0700000101');

        $this->actingAs($user)
            ->post(route('settings.financial.update'), [
                'market_company_commissions' => [],
                'market_introducer_bonuses' => [],
                'market_reseller_service_charges' => [],
            ])
            ->assertForbidden();
    }

    public function test_reseller_cannot_update_another_reseller_service_charge(): void
    {
        $actor = $this->createResellerUser('actor@feeder.local', '0700000102');
        $target = $this->createResellerUser('target@feeder.local', '0700000103');

        $this->actingAs($actor)
            ->post(route('resellers.financial.service-charge.update', $target->uuid), [
                'market_id' => (string) Str::uuid(),
                'reseller_service_charge' => '120.00',
            ])
            ->assertForbidden();
    }

    protected function createResellerUser(string $email, string $phone): User
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

        $company = Company::query()->firstOrCreate(
            ['phone' => $phone],
            [
                'uuid' => (string) Str::uuid(),
                'portal_id' => $portal->id,
                'name' => $email,
                'email' => $email,
                'phone' => $phone,
                'registration_number' => 'REG-' . $phone,
                'status' => CompanyStatus::ACTIVE->value,
            ]
        );

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
            'is_master_reseller' => false,
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        return $user;
    }
}
