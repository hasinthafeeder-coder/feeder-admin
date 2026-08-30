<?php

namespace Tests\Feature\Reseller;

use App\Services\Reseller\ResellerService;
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

class AdminResellerListTest extends TestCase
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
        $this->allowPermissions(['resellers.view']);
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_reseller_list_view_icon_links_to_profile(): void
    {
        $reseller = $this->makeActiveReseller(['lk']);

        $this->actingAs($this->makeAdmin())
            ->get(route('resellers.index'))
            ->assertOk()
            ->assertSee(route('resellers.show', ['user' => $reseller->uuid]), false);
    }

    public function test_single_market_displays_one_country_code(): void
    {
        $reseller = $this->makeActiveReseller(['lk']);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('resellers.index'));

        $response->assertOk();
        $response->assertSee($reseller->company->name, false);
        $response->assertSee('LK', false);
        $response->assertDontSee('LK • MY', false);
    }

    public function test_multiple_markets_display_multiple_country_codes(): void
    {
        $reseller = $this->makeActiveReseller(['lk', 'my']);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('resellers.index'));

        $response->assertOk();
        $response->assertSee($reseller->company->name, false);
        $response->assertSee('LK • MY', false);
    }

    public function test_country_code_display_deduplicates_codes(): void
    {
        $codes = collect([
            ['country' => ['iso_code' => 'LK']],
            ['country' => ['iso_code' => 'LK']],
            ['country' => ['iso_code' => 'MY']],
        ])
            ->pluck('country.iso_code')
            ->filter()
            ->map(fn ($code) => strtoupper((string) $code))
            ->unique()
            ->values();

        $this->assertSame('LK • MY', $codes->implode(' • '));
    }

    public function test_reseller_without_market_access_shows_fallback(): void
    {
        $reseller = $this->makeActiveReseller([]);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('resellers.index'));

        $response->assertOk();
        $response->assertSee($reseller->company->name, false);
        $response->assertSee('Market unavailable', false);
    }

    public function test_reseller_list_eager_loads_allowed_markets_country(): void
    {
        $this->makeActiveReseller(['lk', 'my']);

        $resellers = app(ResellerService::class)->getList();
        $firstPage = $resellers['ACTIVE']->items();

        $this->assertNotEmpty($firstPage);

        foreach ($firstPage as $reseller) {
            $this->assertTrue($reseller->relationLoaded('company'));
            $this->assertTrue($reseller->company->relationLoaded('allowedMarkets'));

            foreach ($reseller->company->allowedMarkets as $market) {
                $this->assertTrue($market->relationLoaded('country'));
            }
        }
    }

    /**
     * @param  list<string>  $marketCodes
     */
    private function makeActiveReseller(array $marketCodes): User
    {
        $portal = $this->ensurePortal(PortalCode::RESELLER, 'Reseller Portal');

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Reseller '.Str::upper(Str::random(4)),
            'email' => 'reseller-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '078'.random_int(1000000, 9999999),
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        if ($marketCodes !== []) {
            $this->configureResellerCompany($company, $marketCodes);
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

        return $user->fresh(['company.allowedMarkets.country']);
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-reseller-list-'.Str::uuid().'@feeder.local',
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
