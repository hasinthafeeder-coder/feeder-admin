<?php

namespace Tests\Feature\Supplier;

use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Courier;
use Feeder\Core\Models\SupplierCourierAccount;
use Feeder\Core\Models\User;
use Feeder\Core\Services\Courier\CourierConnectionResult;
use Feeder\Core\Services\Courier\CourierConnectionTesterResolver;
use Feeder\Core\Contracts\Courier\CourierConnectionTester;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class SupplierCourierAccountAdminTest extends TestCase
{
    use SetsUpMarketData;
    use SetsUpPortalRoles;
    use UsesMysqlTestDatabase;

    /** @var list<string> */
    private array $allowedPermissions = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpMysqlTestDatabase();
        $this->seedMarketLookups();
        $this->allowPermissions([
            'suppliers.view',
            'suppliers.courier_accounts.view',
            'suppliers.courier_accounts.create',
            'suppliers.courier_accounts.update',
            'suppliers.courier_accounts.activate',
            'suppliers.courier_accounts.test',
        ]);
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_admin_can_view_courier_accounts_on_supplier_profile(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_VIEW');
        $this->makeAccount($supplier, $courier, 'secret-should-not-appear', 'REF-4321');

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('suppliers.show', $supplier));

        $response->assertOk();
        $response->assertSee('Courier Accounts', false);
        $response->assertSee($courier->name, false);
        $response->assertSee('4321', false);
        $response->assertDontSee('secret-should-not-appear');
    }

    public function test_admin_can_create_courier_account(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_CREATE');

        $response = $this->actingAs($this->makeAdmin())
            ->post(route('suppliers.courier-accounts.store', $supplier), [
                'courier_uuid' => $courier->uuid,
                'account_label' => 'New Account',
                'credentials' => [
                    'account_reference' => 'ACC-1001',
                    'api_key' => 'create-secret',
                ],
                'is_default' => '1',
            ]);

        $response->assertRedirect(route('suppliers.show', $supplier));

        $account = SupplierCourierAccount::query()
            ->where('supplier_id', $supplier->id)
            ->where('courier_id', $courier->id)
            ->first();

        $this->assertNotNull($account);
        $this->assertSame('New Account', $account->account_label);
        $this->assertTrue($account->is_default);
        $this->assertSame('create-secret', $account->getCredentials()['api_key']);

        $this->actingAs($this->makeAdmin())
            ->get(route('suppliers.show', $supplier))
            ->assertDontSee('create-secret');
    }

    public function test_admin_update_preserves_blank_secrets(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_UPDATE');
        $account = $this->makeAccount($supplier, $courier, 'original-secret', 'REF-1111');

        $this->actingAs($this->makeAdmin())
            ->put(route('suppliers.courier-accounts.update', [$supplier, $account]), [
                'account_label' => 'Updated Label',
                'credentials' => [
                    'account_reference' => 'REF-2222',
                    'api_key' => '',
                ],
                'is_default' => '0',
            ])
            ->assertRedirect(route('suppliers.show', $supplier));

        $account->refresh();
        $this->assertSame('Updated Label', $account->account_label);
        $this->assertSame('original-secret', $account->getCredentials()['api_key']);
        $this->assertSame('REF-2222', $account->getCredentials()['account_reference']);
    }

    public function test_admin_can_activate_deactivate_and_set_default(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_ACT');
        $account = $this->makeAccount($supplier, $courier, 'key', 'REF-1');

        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('suppliers.courier-accounts.set-default', [$supplier, $account]))
            ->assertRedirect();

        $this->assertTrue($account->fresh()->is_default);

        $this->actingAs($admin)
            ->post(route('suppliers.courier-accounts.deactivate', [$supplier, $account]))
            ->assertRedirect();

        $account->refresh();
        $this->assertFalse($account->is_active);
        $this->assertFalse($account->is_default);

        $this->actingAs($admin)
            ->post(route('suppliers.courier-accounts.activate', [$supplier, $account]))
            ->assertRedirect();

        $this->assertTrue($account->fresh()->is_active);
    }

    public function test_admin_test_connection_returns_safe_unavailable_message(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_TEST_NONE');
        $account = $this->makeAccount($supplier, $courier, 'secret-key', 'REF-9');

        $response = $this->actingAs($this->makeAdmin())
            ->from(route('suppliers.show', $supplier))
            ->post(route('suppliers.courier-accounts.test', [$supplier, $account]));

        $response->assertRedirect(route('suppliers.show', $supplier));
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            'not available',
            (string) session('error')
        );
        $this->assertStringNotContainsString('secret-key', (string) session('error'));
    }

    public function test_admin_test_connection_success_path(): void
    {
        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_TEST_OK');
        $account = $this->makeAccount($supplier, $courier, 'ok-secret', 'REF-8');

        app(CourierConnectionTesterResolver::class)->register(
            $courier->code,
            new class implements CourierConnectionTester
            {
                public function test(array $credentials): CourierConnectionResult
                {
                    return CourierConnectionResult::success('Connection successful.');
                }
            }
        );

        $this->actingAs($this->makeAdmin())
            ->from(route('suppliers.show', $supplier))
            ->post(route('suppliers.courier-accounts.test', [$supplier, $account]))
            ->assertRedirect(route('suppliers.show', $supplier))
            ->assertSessionHas('success', 'Connection successful.');
    }

    public function test_unauthorized_admin_cannot_create_courier_account(): void
    {
        $this->allowPermissions(['suppliers.view']);

        $supplier = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_DENY');

        $this->actingAs($this->makeAdmin())
            ->post(route('suppliers.courier-accounts.store', $supplier), [
                'courier_uuid' => $courier->uuid,
                'account_label' => 'Denied',
                'credentials' => ['api_key' => 'x'],
            ])
            ->assertForbidden();
    }

    public function test_cannot_access_another_suppliers_account_uuid(): void
    {
        $supplierA = $this->makeActiveSupplier();
        $supplierB = $this->makeActiveSupplier();
        $courier = $this->makeCourier('ADMIN_ISO');
        $account = $this->makeAccount($supplierA, $courier, 'iso-secret', 'REF-A');

        $this->actingAs($this->makeAdmin())
            ->put(route('suppliers.courier-accounts.update', [$supplierB, $account]), [
                'account_label' => 'Hijack',
                'credentials' => ['api_key' => ''],
            ])
            ->assertSessionHasErrors('account');

        $this->assertSame('iso-secret', $account->fresh()->getCredentials()['api_key']);
        $this->assertNotSame('Hijack', $account->fresh()->account_label);
    }

    private function makeActiveSupplier(): User
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

        return $user->fresh(['company']);
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-courier-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeCourier(string $code): Courier
    {
        return Courier::query()->create([
            'uuid' => (string) Str::uuid(),
            'code' => $code,
            'name' => 'Courier '.$code,
            'is_active' => true,
        ]);
    }

    private function makeAccount(User $supplier, Courier $courier, string $apiKey, string $reference): SupplierCourierAccount
    {
        return SupplierCourierAccount::query()->create([
            'supplier_id' => $supplier->id,
            'courier_id' => $courier->id,
            'account_label' => $courier->name.' Account',
            'credentials_encrypted' => Crypt::encryptString(json_encode([
                'account_reference' => $reference,
                'api_key' => $apiKey,
            ], JSON_THROW_ON_ERROR)),
            'is_active' => true,
            'is_default' => false,
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
