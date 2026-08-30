<?php

namespace Tests\Feature\Reseller;

use App\Models\User;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\ResellerMarketAccess;
use Feeder\Core\Models\ResellerSupplierAssignment;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class ResellerSupplierAssignmentTest extends TestCase
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

    public function test_guest_cannot_assign_suppliers(): void
    {
        $reseller = $this->makeReseller();

        $this->post(route('resellers.suppliers.store', $reseller), [
            'supplier' => 'all',
        ])->assertRedirect(route('login', absolute: false));
    }

    public function test_admin_without_permission_cannot_assign_or_remove_suppliers(): void
    {
        $this->allowPermissions(['resellers.view']);

        $admin = $this->makeAdmin();
        $reseller = $this->makeReseller();
        $supplier = $this->makeSupplier('Denied Co');

        $this->actingAs($admin)
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => $supplier->uuid,
            ])
            ->assertForbidden();

        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('resellers.suppliers.destroy', [
                'user' => $reseller,
                'supplier' => $supplier,
            ]))
            ->assertForbidden();

        $this->assertDatabaseHas('reseller_supplier_assignments', [
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
        ]);
    }

    public function test_profile_shows_assignable_suppliers_and_hides_already_assigned(): void
    {
        $this->allowPermissions(['resellers.view', 'resellers.suppliers.assign']);

        $reseller = $this->makeReseller();
        $available = $this->makeSupplier('Available Supplies');
        $assigned = $this->makeSupplier('Already Assigned Co');
        $inactive = $this->makeSupplier('Inactive Supplies', UserStatus::PENDING);

        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $assigned->id,
        ]);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('resellers.show', $reseller));

        $response->assertOk();
        $response->assertSee('Available Supplies');
        $response->assertSee('Already Assigned Co');
        $response->assertDontSee('Inactive Supplies');
        $this->assertStringNotContainsString(
            'value="'.$assigned->uuid.'"',
            $response->getContent()
        );
        $this->assertStringContainsString(
            'value="'.$available->uuid.'"',
            $response->getContent()
        );
    }

    public function test_admin_can_assign_a_single_supplier(): void
    {
        $this->allowPermissions(['resellers.view', 'resellers.suppliers.assign']);

        $admin = $this->makeAdmin();
        $reseller = $this->makeReseller();
        $supplier = $this->makeSupplier('Single Assign Co');

        $this->actingAs($admin)
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => $supplier->uuid,
            ])
            ->assertRedirect(route('resellers.show', $reseller))
            ->assertSessionHas('success', 'Supplier assigned successfully.');

        $this->assertDatabaseHas('reseller_supplier_assignments', [
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('resellers.show', $reseller))
            ->assertOk()
            ->assertSee('Single Assign Co')
            ->assertDontSee('value="'.$supplier->uuid.'"', false);
    }

    public function test_duplicate_assignment_is_rejected_at_application_and_database_level(): void
    {
        $this->allowPermissions(['resellers.suppliers.assign']);

        $admin = $this->makeAdmin();
        $reseller = $this->makeReseller();
        $supplier = $this->makeSupplier('Duplicate Co');

        $this->actingAs($admin)
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => $supplier->uuid,
            ])
            ->assertRedirect(route('resellers.show', $reseller));

        $this->actingAs($admin)
            ->from(route('resellers.show', $reseller))
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => $supplier->uuid,
            ])
            ->assertRedirect(route('resellers.show', $reseller))
            ->assertSessionHasErrors('supplier');

        $this->assertSame(1, ResellerSupplierAssignment::query()
            ->where('reseller_id', $reseller->id)
            ->where('supplier_id', $supplier->id)
            ->count());

        $this->expectException(UniqueConstraintViolationException::class);

        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
            'assigned_by' => $admin->id,
        ]);
    }

    public function test_admin_can_assign_all_remaining_eligible_suppliers_without_duplicates(): void
    {
        $this->allowPermissions(['resellers.view', 'resellers.suppliers.assign']);

        $admin = $this->makeAdmin();
        $reseller = $this->makeReseller();
        $first = $this->makeSupplier('All First Co');
        $second = $this->makeSupplier('All Second Co');
        $alreadyAssigned = $this->makeSupplier('All Already Co');
        $this->makeSupplier('All Pending Co', UserStatus::PENDING);

        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $alreadyAssigned->id,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => 'all',
            ])
            ->assertRedirect(route('resellers.show', $reseller))
            ->assertSessionHas('success');

        $assignedCount = ResellerSupplierAssignment::query()
            ->where('reseller_id', $reseller->id)
            ->count();

        $this->assertGreaterThan(1, $assignedCount);
        $this->assertDatabaseHas('reseller_supplier_assignments', [
            'reseller_id' => $reseller->id,
            'supplier_id' => $first->id,
        ]);
        $this->assertDatabaseHas('reseller_supplier_assignments', [
            'reseller_id' => $reseller->id,
            'supplier_id' => $second->id,
        ]);
        $this->assertSame(1, ResellerSupplierAssignment::query()
            ->where('reseller_id', $reseller->id)
            ->where('supplier_id', $alreadyAssigned->id)
            ->count());

        $this->actingAs($admin)
            ->post(route('resellers.suppliers.store', $reseller), [
                'supplier' => 'all',
            ])
            ->assertRedirect(route('resellers.show', $reseller));

        $this->assertSame($assignedCount, ResellerSupplierAssignment::query()
            ->where('reseller_id', $reseller->id)
            ->count());
    }

    public function test_removing_assignment_does_not_delete_supplier_or_reseller(): void
    {
        $this->allowPermissions(['resellers.view', 'resellers.suppliers.assign']);

        $admin = $this->makeAdmin();
        $reseller = $this->makeReseller();
        $supplier = $this->makeSupplier('Remove Co');

        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
            'assigned_by' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('resellers.suppliers.destroy', [
                'user' => $reseller,
                'supplier' => $supplier,
            ]))
            ->assertRedirect(route('resellers.show', $reseller))
            ->assertSessionHas('success', 'Supplier assignment removed successfully.');

        $this->assertDatabaseMissing('reseller_supplier_assignments', [
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $reseller->id,
            'email' => $reseller->email,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $supplier->id,
            'email' => $supplier->email,
        ]);
        $this->assertDatabaseHas('companies', [
            'id' => $supplier->company_id,
        ]);

        $this->actingAs($admin)
            ->get(route('resellers.show', $reseller))
            ->assertOk()
            ->assertSee('value="'.$supplier->uuid.'"', false)
            ->assertSee('No suppliers assigned.');
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
            'email' => 'admin-assign-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeReseller(): User
    {
        return $this->makePortalUser(
            PortalCode::RESELLER,
            'Reseller Portal',
            'reseller',
            'Reseller Co '.Str::lower(Str::random(6)),
            UserStatus::ACTIVE
        );
    }

    private function makeSupplier(string $companyName, UserStatus $status = UserStatus::ACTIVE): User
    {
        return $this->makePortalUser(
            PortalCode::SUPPLIER,
            'Supplier Portal',
            'supplier',
            $companyName,
            $status
        );
    }

    private function makePortalUser(
        PortalCode $portalCode,
        string $portalName,
        string $subdomain,
        string $companyName,
        UserStatus $status
    ): User {
        $portal = Portal::query()->firstOrCreate(
            ['code' => $portalCode->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => $portalName,
                'subdomain' => $subdomain.'-'.Str::lower(Str::random(4)),
                'description' => $portalName,
                'is_active' => true,
            ]
        );

        $phone = '07'.random_int(10000000, 99999999);
        $email = Str::slug($companyName).'-'.random_int(1000, 9999).'@feeder.local';

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => $companyName,
            'email' => $email,
            'phone' => $phone,
            'registration_number' => 'REG-'.$phone,
            'status' => $status === UserStatus::ACTIVE
                ? CompanyStatus::ACTIVE->value
                : CompanyStatus::PENDING->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => $status->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        if ($portalCode === PortalCode::SUPPLIER) {
            $this->configureSupplierCompany($company, 'lk');
        }

        if ($portalCode === PortalCode::RESELLER) {
            $this->configureResellerCompany($company, ['lk']);
        }

        return $user->fresh('company');
    }
}
