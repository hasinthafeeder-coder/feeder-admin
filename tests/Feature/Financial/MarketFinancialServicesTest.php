<?php

namespace Tests\Feature\Financial;

use Feeder\Core\Models\ResellerMarketServiceChargeOverride;
use Feeder\Core\Models\User;
use Feeder\Core\Services\IntroducerBonusService;
use Feeder\Core\Services\ResellerServiceChargeService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Support\SetsUpMarketData;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class MarketIntroducerBonusTest extends TestCase
{
    use SetsUpMarketData;
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

    public function test_lk_market_default_resolves_correctly(): void
    {
        $service = app(IntroducerBonusService::class);

        $this->assertSame('50.00', $service->resolveIntroducerBonus('lk'));
    }

    public function test_my_market_default_resolves_correctly(): void
    {
        $service = app(IntroducerBonusService::class);

        $this->assertSame('5.00', $service->resolveIntroducerBonus('my'));
    }

    public function test_changing_lk_does_not_affect_my(): void
    {
        $service = app(IntroducerBonusService::class);

        $service->setIntroducerBonus('lk', '55.00');

        $this->assertSame('55.00', $service->resolveIntroducerBonus('lk'));
        $this->assertSame('5.00', $service->resolveIntroducerBonus('my'));
    }

    public function test_introducer_bonus_is_independent_from_company_commission(): void
    {
        $introducerService = app(IntroducerBonusService::class);
        $commissionService = app(\Feeder\Core\Services\MarketDefaultCompanyCommissionService::class);

        $introducerService->setIntroducerBonus('lk', '60.00');
        $commissionService->setDefaultCompanyCommission('lk', '200.00');

        $this->assertSame('60.00', $introducerService->resolveIntroducerBonus('lk'));
        $this->assertSame('200.00', $commissionService->getDefaultCompanyCommission('lk'));
    }
}

class MarketResellerServiceChargeTest extends TestCase
{
    use SetsUpMarketData;
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

    public function test_market_global_default_resolves_correctly(): void
    {
        $service = app(ResellerServiceChargeService::class);

        $this->assertSame('75.00', $service->getDefaultCharge('lk'));
        $this->assertSame('15.00', $service->getDefaultCharge('my'));
    }

    public function test_reseller_market_override_takes_priority(): void
    {
        $service = app(ResellerServiceChargeService::class);
        $reseller = $this->makeReseller();

        $service->setResellerOverride($reseller, 'lk', '90.00');

        $this->assertSame('90.00', $service->resolveServiceCharge($reseller, 'lk'));
        $this->assertSame('15.00', $service->resolveServiceCharge($reseller, 'my'));
    }

    public function test_changing_global_default_does_not_overwrite_reseller_override(): void
    {
        $service = app(ResellerServiceChargeService::class);
        $reseller = $this->makeReseller();

        $service->setResellerOverride($reseller, 'lk', '90.00');
        $service->setDefaultCharge('lk', '80.00');

        $this->assertSame('90.00', $service->resolveServiceCharge($reseller, 'lk'));
    }

    public function test_reseller_without_override_receives_new_global_default(): void
    {
        $service = app(ResellerServiceChargeService::class);
        $reseller = $this->makeReseller();

        $service->setDefaultCharge('lk', '82.00');

        $this->assertSame('82.00', $service->resolveServiceCharge($reseller, 'lk'));
    }

    public function test_override_can_be_removed(): void
    {
        $service = app(ResellerServiceChargeService::class);
        $reseller = $this->makeReseller();

        $service->setResellerOverride($reseller, 'lk', '90.00');
        $service->clearResellerOverride($reseller, 'lk');

        $this->assertSame('75.00', $service->resolveServiceCharge($reseller, 'lk'));
        $this->assertFalse(
            ResellerMarketServiceChargeOverride::query()
                ->where('user_id', $reseller->id)
                ->where('market_id', $this->marketByCode('lk')->id)
                ->exists()
        );
    }

    public function test_reseller_cannot_create_override_for_inaccessible_market(): void
    {
        $service = app(ResellerServiceChargeService::class);
        $reseller = $this->makeReseller();

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $service->setResellerOverride($reseller, 'my', '20.00');
    }

    public function test_legacy_reseller_service_charge_is_migrated_to_lk_override(): void
    {
        $reseller = $this->makeReseller();
        $reseller->forceFill(['reseller_service_charge_override' => 95.00])->save();

        $override = ResellerMarketServiceChargeOverride::query()->firstOrCreate(
            [
                'user_id' => $reseller->id,
                'market_id' => $this->marketByCode('lk')->id,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'amount' => '95.00',
            ]
        );

        $service = app(ResellerServiceChargeService::class);

        $this->assertSame('95.00', $service->resolveServiceCharge($reseller->fresh(), 'lk'));
        $this->assertSame('95.00', (string) $override->amount);
    }

    private function makeReseller(): User
    {
        $company = \Feeder\Core\Models\Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => \Feeder\Core\Models\Portal::query()->firstOrCreate(
                ['code' => \Feeder\Core\Enums\PortalCode::RESELLER->value],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Reseller Portal',
                    'subdomain' => 'reseller',
                    'description' => 'Reseller Portal',
                    'is_active' => true,
                ]
            )->id,
            'name' => 'Reseller Co',
            'email' => 'reseller-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '079'.random_int(1000000, 9999999),
            'status' => \Feeder\Core\Enums\CompanyStatus::ACTIVE->value,
        ]);

        $this->configureResellerCompany($company, ['lk']);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $company->email,
            'phone' => $company->phone,
            'password' => Hash::make('password'),
            'user_type' => \Feeder\Core\Enums\UserType::OWNER->value,
            'status' => \Feeder\Core\Enums\UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        return $user->fresh(['company.allowedMarkets']);
    }
}
