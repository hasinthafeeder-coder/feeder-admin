<?php

namespace Tests\Feature\Product;

use App\Models\User;
use App\Services\FileServerService;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\FileCategory;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\ProductStatus;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\File;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\Product;
use Feeder\Core\Models\ProductCategory;
use Feeder\Core\Services\FileService;
use Feeder\Core\Services\UuidService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\TestCase;

class ProductGuidelineCategoryTest extends TestCase
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

    public function test_file_server_service_uploads_guideline_with_product_guideline_category(): void
    {
        $fileUuid = UuidService::generate();

        $fileService = Mockery::mock(FileService::class);
        $fileService->shouldReceive('upload')
            ->once()
            ->withArgs(function (
                UploadedFile $file,
                string $application,
                string $entityType,
                string $entityUuid,
                string $category,
            ): bool {
                return $category === FileCategory::PRODUCT_GUIDELINE->value
                    && $entityType === 'PRODUCT';
            })
            ->andReturn([
                'file' => ['uuid' => $fileUuid],
            ]);

        $this->app->instance(FileService::class, $fileService);

        File::query()->create([
            'uuid' => $fileUuid,
            'application' => 'ADMIN',
            'entity_type' => 'PRODUCT',
            'entity_uuid' => UuidService::generate(),
            'category' => FileCategory::PRODUCT_GUIDELINE->value,
            'disk' => 'feeder',
            'path' => 'product-guidelines/sample.pdf',
            'original_name' => 'sample.pdf',
            'extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
            'checksum' => str_repeat('a', 64),
            'visibility' => 'PRIVATE',
            'status' => 'ACTIVE',
        ]);

        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $uploaded = app(FileServerService::class)->uploadGuideline(
            UploadedFile::fake()->create('guideline.pdf', 100, 'application/pdf')
        );

        $this->assertSame($fileUuid, $uploaded['uuid']);
    }

    public function test_product_guideline_architecture_uses_guideline_file_id(): void
    {
        $this->allowPermissions(['products.view']);

        $category = $this->makeCategory();
        $supplier = $this->makeSupplier('Guideline Co', '0771234567', 'guideline@feeder.local');
        $file = File::query()->create([
            'uuid' => UuidService::generate(),
            'application' => 'SUPPLIER',
            'entity_type' => 'PRODUCT',
            'entity_uuid' => UuidService::generate(),
            'category' => FileCategory::BUSINESS_REGISTRATION->value,
            'disk' => 'feeder',
            'path' => 'business-registrations/legacy.pdf',
            'original_name' => 'legacy.pdf',
            'extension' => 'pdf',
            'mime_type' => 'application/pdf',
            'size' => 1024,
            'checksum' => str_repeat('b', 64),
            'visibility' => 'PRIVATE',
            'status' => 'ACTIVE',
        ]);

        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'market_id' => $this->marketByCode('lk')->id,
            'name' => 'Guideline Product',
            'slug' => 'guideline-product-'.Str::lower(Str::random(6)),
            'status' => ProductStatus::ACTIVE,
            'system_visible' => true,
            'web_visible' => true,
            'price_locked' => false,
            'guideline_file_id' => $file->id,
            'published_at' => now(),
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        $this->actingAs($this->makeAdmin())
            ->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('legacy.pdf');

        $this->assertFalse(Schema::hasTable('product_guidelines'));
        $this->assertSame($file->id, (int) $product->fresh()->guideline_file_id);
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
            'email' => 'admin-guideline-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    private function makeCategory(): ProductCategory
    {
        return ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'Office',
            'slug' => 'office-'.Str::lower(Str::random(6)),
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    private function makeSupplier(string $companyName, string $phone, string $email): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::SUPPLIER->value],
            [
                'uuid' => (string) Str::uuid(),
                'name' => 'Supplier Portal',
                'subdomain' => 'supplier-'.Str::lower(Str::random(4)),
                'description' => 'Supplier Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => (string) Str::uuid(),
            'portal_id' => $portal->id,
            'name' => $companyName,
            'email' => $email,
            'phone' => $phone,
            'registration_number' => 'REG-'.$phone,
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        $user = User::query()->create([
            'uuid' => (string) Str::uuid(),
            'company_id' => $company->id,
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();
        $this->configureSupplierCompany($company, 'lk');

        return $user;
    }
}
