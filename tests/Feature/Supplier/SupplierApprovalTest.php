<?php

namespace Tests\Feature\Supplier;

use App\Services\Supplier\SupplierApprovalService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\SupplierType;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Role;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\Support\SetsUpPortalRoles;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class SupplierApprovalTest extends TestCase
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

    public function test_pending_supplier_approval_assigns_supplier_owner_role_and_activates_records(): void
    {
        $ownerRole = $this->ensureOwnerRole(PortalCode::SUPPLIER);
        $supplier = $this->makePendingSupplier();

        app(SupplierApprovalService::class)->approve($supplier);

        $supplier->refresh();
        $company = $supplier->company->fresh();

        $this->assertSame($ownerRole->id, $supplier->role_id);
        $this->assertSame(UserStatus::ACTIVE, $supplier->status);
        $this->assertSame(CompanyStatus::ACTIVE, $company->status);
    }

    public function test_pending_supplier_approval_persists_selected_supplier_type(): void
    {
        $this->ensureOwnerRole(PortalCode::SUPPLIER);
        $supplier = $this->makePendingSupplier();

        app(SupplierApprovalService::class)->approve($supplier, SupplierType::PRO);

        $company = $supplier->company->fresh();

        $this->assertSame(SupplierType::PRO, $company->supplier_type);
        $this->assertTrue($supplier->fresh()->is_pro);
    }

    public function test_pending_supplier_approval_defaults_to_standard_supplier_type(): void
    {
        $this->ensureOwnerRole(PortalCode::SUPPLIER);
        $supplier = $this->makePendingSupplier();

        app(SupplierApprovalService::class)->approve($supplier, SupplierType::STANDARD);

        $company = $supplier->company->fresh();

        $this->assertSame(SupplierType::STANDARD, $company->supplier_type);
        $this->assertFalse($supplier->fresh()->is_pro);
    }

    public function test_supplier_approval_fails_safely_when_owner_role_cannot_be_resolved(): void
    {
        $supplier = $this->makePendingSupplier();

        Role::query()
            ->where('slug', 'owner')
            ->whereHas('portal', fn ($query) => $query->where('code', PortalCode::SUPPLIER->value))
            ->delete();

        try {
            app(SupplierApprovalService::class)->approve($supplier);
            $this->fail('Expected supplier approval to fail when owner role is missing.');
        } catch (ValidationException) {
            // expected
        }

        $supplier->refresh();
        $company = $supplier->company->fresh();

        $this->assertNull($supplier->role_id);
        $this->assertSame(UserStatus::PENDING, $supplier->status);
        $this->assertSame(CompanyStatus::PENDING, $company->status);
    }

    private function makePendingSupplier(): User
    {
        $portal = $this->ensurePortal(PortalCode::SUPPLIER, 'Supplier Portal');

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => 'Pending Supplier Co',
            'email' => 'pending-supplier-'.Str::lower(Str::random(6)).'@feeder.local',
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
}
