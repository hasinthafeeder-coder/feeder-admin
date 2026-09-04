<?php

namespace Tests\Feature\Supplier;

use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\SupplierType;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class SupplierTypeUpdateTest extends TestCase
{
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
        $this->allowPermissions(['suppliers.view', 'suppliers.approve']);
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_supplier_profile_displays_supplier_type_form_for_active_supplier(): void
    {
        $supplier = $this->makeActiveSupplier(SupplierType::STANDARD);

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('suppliers.show', $supplier));

        $response->assertOk();
        $response->assertSee('Supplier Type', false);
        $response->assertSee('Standard Supplier', false);
        $response->assertSee('PRO Supplier', false);
        $response->assertSee('Save Supplier Type', false);
    }

    public function test_admin_can_update_active_supplier_type_to_pro(): void
    {
        $supplier = $this->makeActiveSupplier(SupplierType::STANDARD);

        $response = $this->actingAs($this->makeAdmin())
            ->put(route('suppliers.supplier-type.update', $supplier), [
                'supplier_type' => SupplierType::PRO->value,
            ]);

        $response->assertRedirect(route('suppliers.show', $supplier));
        $this->assertSame(SupplierType::PRO, $supplier->company->fresh()->supplier_type);
        $this->assertTrue($supplier->fresh()->is_pro);
    }

    public function test_admin_can_update_active_supplier_type_to_standard(): void
    {
        $supplier = $this->makeActiveSupplier(SupplierType::PRO);

        $response = $this->actingAs($this->makeAdmin())
            ->put(route('suppliers.supplier-type.update', $supplier), [
                'supplier_type' => SupplierType::STANDARD->value,
            ]);

        $response->assertRedirect(route('suppliers.show', $supplier));
        $this->assertSame(SupplierType::STANDARD, $supplier->company->fresh()->supplier_type);
        $this->assertFalse($supplier->fresh()->is_pro);
    }

    public function test_supplier_type_update_requires_approve_permission(): void
    {
        $this->allowPermissions(['suppliers.view']);
        $supplier = $this->makeActiveSupplier(SupplierType::STANDARD);

        $response = $this->actingAs($this->makeAdmin())
            ->put(route('suppliers.supplier-type.update', $supplier), [
                'supplier_type' => SupplierType::PRO->value,
            ]);

        $response->assertForbidden();
        $this->assertSame(SupplierType::STANDARD, $supplier->company->fresh()->supplier_type);
    }

    private function makeActiveSupplier(SupplierType $supplierType): User
    {
        $portal = $this->ensurePortal(PortalCode::SUPPLIER, 'Supplier Portal');

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Supplier '.Str::upper(Str::random(4)),
            'email' => 'supplier-'.Str::lower(Str::random(6)).'@feeder.local',
            'phone' => '077'.random_int(1000000, 9999999),
            'status' => CompanyStatus::ACTIVE->value,
            'supplier_type' => $supplierType->value,
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

        return $user->fresh('company');
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => (string) Str::uuid(),
            'email' => 'admin-supplier-type-'.Str::uuid().'@feeder.local',
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
