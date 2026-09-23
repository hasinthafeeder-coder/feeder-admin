<?php

namespace Tests\Feature\Order;

use App\Models\User;
use App\Services\Order\AdminOrderPaymentReviewListService;
use Feeder\Core\Authorization\Services\PermissionService;
use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\OrderPaymentReviewStatus;
use Feeder\Core\Enums\OrderSource;
use Feeder\Core\Enums\OrderStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\ProductStatus;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\Courier;
use Feeder\Core\Models\CourierCity;
use Feeder\Core\Models\CourierMarketPricing;
use Feeder\Core\Models\CourierService;
use Feeder\Core\Models\Customer;
use Feeder\Core\Models\Order;
use Feeder\Core\Models\OrderAddress;
use Feeder\Core\Models\OrderItem;
use Feeder\Core\Models\OrderPaymentSubmission;
use Feeder\Core\Models\Portal;
use Feeder\Core\Models\Product;
use Feeder\Core\Models\ProductCategory;
use Feeder\Core\Models\ProductVariant;
use Feeder\Core\Models\ResellerSupplierAssignment;
use Feeder\Core\Models\SupplierCourierAccount;
use Feeder\Core\Services\Order\OrderPaymentReviewService;
use Feeder\Core\Services\UuidService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Mockery;
use Tests\Support\SetsUpMarketData;
use Tests\Support\UsesMysqlTestDatabase;
use Tests\TestCase;

class AdminBankTransferReviewTest extends TestCase
{
    use SetsUpMarketData;
    use UsesMysqlTestDatabase;

    /** @var list<string> */
    private array $allowedPermissions = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpMysqlTestDatabase();
        $this->seedMarketLookups();
        config([
            'cache.default' => 'array',
            'feeder.file_server.url' => 'http://files.test',
            'feeder.file_server.api_key' => 'test-file-key',
        ]);

        Http::fake(function () {
            return Http::response([
                'message' => 'File uploaded successfully.',
                'file' => [
                    'uuid' => 'ADMPROOF01',
                    'application' => 'RESELLER',
                    'entity_type' => 'ORDER_PAYMENT',
                    'entity_uuid' => 'ENTITY0001',
                    'category' => 'PAYMENT_PROOF',
                    'original_name' => 'slip.pdf',
                    'mime_type' => 'application/pdf',
                    'size' => 120,
                ],
            ], 201);
        });
    }

    protected function tearDown(): void
    {
        $this->tearDownMysqlTestDatabase();

        parent::tearDown();
    }

    public function test_admin_with_review_permission_can_open_review_bank_transfers(): void
    {
        $this->allowPermissions([OrderPaymentReviewService::PERMISSION_REVIEW]);

        $this->actingAs($this->makeAdmin())
            ->get(route('orders.payment-reviews.index'))
            ->assertOk()
            ->assertSee('Review Bank Transfers')
            ->assertSee('Pending Approval')
            ->assertSee('Approved');
    }

    public function test_unauthorized_user_cannot_access_review_page(): void
    {
        $this->allowPermissions([]);

        $this->actingAs($this->makeAdmin())
            ->get(route('orders.payment-reviews.index'))
            ->assertForbidden();
    }

    public function test_pending_and_approved_filters_return_correct_submissions(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_APPROVE,
        ]);

        $admin = $this->makeAdmin();
        [$reseller, $pendingOrder] = $this->makeReadyOrder();
        $pending = $this->submitBankTransfer($reseller, $pendingOrder, 'PEND-REF-1');

        [$reseller2, $approvedOrder] = $this->makeReadyOrder();
        $toApprove = $this->submitBankTransfer($reseller2, $approvedOrder, 'APPR-REF-1', 'ADMPROOF02');
        app(OrderPaymentReviewService::class)->approve($toApprove, $admin);

        $pendingPage = $this->actingAs($admin)
            ->get(route('orders.payment-reviews.index', [
                'filter' => AdminOrderPaymentReviewListService::FILTER_PENDING_APPROVAL,
            ]));
        $pendingPage->assertOk()
            ->assertSee($pendingOrder->order_number)
            ->assertSee('PEND-REF-1')
            ->assertDontSee($approvedOrder->order_number);

        $approvedPage = $this->actingAs($admin)
            ->get(route('orders.payment-reviews.index', [
                'filter' => AdminOrderPaymentReviewListService::FILTER_APPROVED,
            ]));
        $approvedPage->assertOk()
            ->assertSee($approvedOrder->order_number)
            ->assertSee('APPR-REF-1')
            ->assertDontSee($pendingOrder->order_number);

        $this->assertSame(OrderPaymentReviewStatus::PENDING_REVIEW, $pending->fresh()->review_status);
    }

    public function test_pending_submission_detail_shows_order_payment_and_proof(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_APPROVE,
            OrderPaymentReviewService::PERMISSION_REJECT,
        ]);

        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order, 'DETAIL-REF');

        $response = $this->actingAs($this->makeAdmin())
            ->get(route('orders.payment-reviews.show', $submission));

        $response->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('DETAIL-REF')
            ->assertSee('Pending Approval')
            ->assertSee('Approve Payment')
            ->assertSee('Reject Payment')
            ->assertSee(route('files.view', 'ADMPROOF01'), false)
            ->assertSee(route('files.download', 'ADMPROOF01'), false)
            ->assertSee('BT Customer')
            ->assertSeeInOrder([
                'Courier',
                'Payment Proof',
                'Bank Transfer Payment',
            ]);
    }

    public function test_arbitrary_uuid_returns_not_found(): void
    {
        $this->allowPermissions([OrderPaymentReviewService::PERMISSION_REVIEW]);

        $this->actingAs($this->makeAdmin())
            ->get(route('orders.payment-reviews.show', ['submission' => (string) Str::uuid()]))
            ->assertNotFound();
    }

    public function test_approve_payment_confirms_order_and_records_reviewer(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_APPROVE,
        ]);

        $admin = $this->makeAdmin();
        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order, 'APPROVE-REF');
        $previousStatus = $order->status;

        $this->actingAs($admin)
            ->post(route('orders.payment-reviews.approve', $submission))
            ->assertRedirect(route('orders.payment-reviews.show', $submission))
            ->assertSessionHas('success');

        $submission->refresh();
        $order->refresh();

        $this->assertSame(OrderPaymentReviewStatus::APPROVED, $submission->review_status);
        $this->assertSame(OrderStatus::CONFIRMED, $order->status);
        $this->assertSame((int) $admin->id, (int) $submission->reviewed_by_user_id);
        $this->assertNotNull($submission->reviewed_at);
        $this->assertNotNull($order->confirmed_at);
        $this->assertNotSame($previousStatus, $order->status);

        $detail = $this->actingAs($admin)->get(route('orders.payment-reviews.show', $submission));
        $detail->assertOk()
            ->assertSee('Approved')
            ->assertDontSee('id="approvePaymentModal"', false)
            ->assertDontSee('Approve Payment');
    }

    public function test_user_without_approve_permission_cannot_approve(): void
    {
        $this->allowPermissions([OrderPaymentReviewService::PERMISSION_REVIEW]);

        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);

        $this->actingAs($this->makeAdmin())
            ->post(route('orders.payment-reviews.approve', $submission))
            ->assertForbidden();

        $this->assertSame(OrderPaymentReviewStatus::PENDING_REVIEW, $submission->fresh()->review_status);
        $this->assertSame(OrderStatus::PENDING, $order->fresh()->status);
    }

    public function test_already_approved_submission_cannot_be_approved_again(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_APPROVE,
        ]);

        $admin = $this->makeAdmin();
        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);
        app(OrderPaymentReviewService::class)->approve($submission, $admin);

        $this->actingAs($admin)
            ->post(route('orders.payment-reviews.approve', $submission->fresh()))
            ->assertRedirect(route('orders.payment-reviews.show', $submission))
            ->assertSessionHasErrors('payment');
    }

    public function test_reject_requires_reason_and_keeps_order_status(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_REJECT,
        ]);

        $admin = $this->makeAdmin();
        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order, 'REJECT-REF');

        $this->actingAs($admin)
            ->post(route('orders.payment-reviews.reject', $submission), [
                'review_note' => '',
            ])
            ->assertSessionHasErrors('review_note');

        $this->assertSame(OrderPaymentReviewStatus::PENDING_REVIEW, $submission->fresh()->review_status);

        $this->actingAs($admin)
            ->post(route('orders.payment-reviews.reject', $submission), [
                'review_note' => 'Blurry slip image',
            ])
            ->assertRedirect(route('orders.payment-reviews.show', $submission))
            ->assertSessionHas('success');

        $submission->refresh();
        $order->refresh();

        $this->assertSame(OrderPaymentReviewStatus::REJECTED, $submission->review_status);
        $this->assertSame('Blurry slip image', $submission->review_note);
        $this->assertSame((int) $admin->id, (int) $submission->reviewed_by_user_id);
        $this->assertNotNull($submission->reviewed_at);
        $this->assertSame(OrderStatus::PENDING, $order->status);
        $this->assertNull($order->confirmed_at);

        $fileService = \Mockery::mock(\Feeder\Core\Services\FileService::class);
        $fileService->shouldReceive('upload')->once()->andReturn([
            'message' => 'File uploaded successfully.',
            'file' => [
                'uuid' => 'ADMPROOF99',
                'original_name' => 'slip2.pdf',
            ],
        ]);
        $this->app->instance(\Feeder\Core\Services\FileService::class, $fileService);

        $second = app(OrderPaymentReviewService::class)->submitBankTransfer(
            $order->fresh(),
            $reseller,
            UploadedFile::fake()->create('slip2.pdf', 120, 'application/pdf'),
            'REJECT-REF-2',
            350.50,
            'Second bank transfer after rejection',
            (int) $reseller->company_id,
        );

        $this->assertSame(2, OrderPaymentSubmission::query()->where('order_id', $order->id)->count());
        $this->assertSame(OrderPaymentReviewStatus::REJECTED, $submission->fresh()->review_status);
        $this->assertSame(OrderPaymentReviewStatus::PENDING_REVIEW, $second->review_status);
        $this->assertSame('ADMPROOF01', $submission->fresh()->slip_file_uuid);
        $this->assertSame('ADMPROOF99', $second->slip_file_uuid);
        $this->assertNotSame($submission->id, $second->id);
    }

    public function test_user_without_reject_permission_cannot_reject(): void
    {
        $this->allowPermissions([OrderPaymentReviewService::PERMISSION_REVIEW]);

        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);

        $this->actingAs($this->makeAdmin())
            ->post(route('orders.payment-reviews.reject', $submission), [
                'review_note' => 'Nope',
            ])
            ->assertForbidden();

        $this->assertSame(OrderPaymentReviewStatus::PENDING_REVIEW, $submission->fresh()->review_status);
    }

    public function test_already_rejected_submission_cannot_be_rejected_again(): void
    {
        $this->allowPermissions([
            OrderPaymentReviewService::PERMISSION_REVIEW,
            OrderPaymentReviewService::PERMISSION_REJECT,
        ]);

        $admin = $this->makeAdmin();
        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);
        app(OrderPaymentReviewService::class)->reject($submission, $admin, 'First reject');

        $this->actingAs($admin)
            ->post(route('orders.payment-reviews.reject', $submission->fresh()), [
                'review_note' => 'Second reject',
            ])
            ->assertRedirect(route('orders.payment-reviews.show', $submission))
            ->assertSessionHasErrors('payment');

        $this->assertSame('First reject', $submission->fresh()->review_note);
    }

    public function test_admin_with_review_permission_can_access_payment_proof_proxy_gate(): void
    {
        $this->allowPermissions([OrderPaymentReviewService::PERMISSION_REVIEW]);

        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);
        $admin = $this->makeAdmin();

        app(OrderPaymentReviewService::class)
            ->authorizeFileAccessIfPaymentProof($admin, (string) $submission->slip_file_uuid);

        $this->assertTrue(true);
    }

    public function test_unauthorized_actor_cannot_access_payment_proof(): void
    {
        [$reseller, $order] = $this->makeReadyOrder();
        $submission = $this->submitBankTransfer($reseller, $order);

        $outsider = User::query()->create([
            'uuid' => UuidService::generate(),
            'company_id' => null,
            'email' => 'outsider-'.Str::uuid().'@feeder.local',
            'phone' => '070'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $this->allowPermissions([]);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        app(OrderPaymentReviewService::class)
            ->authorizeFileAccessIfPaymentProof($outsider, (string) $submission->slip_file_uuid);
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

    private function makeAdmin(): User
    {
        return User::query()->create([
            'uuid' => UuidService::generate(),
            'company_id' => null,
            'email' => 'admin-pay-'.Str::uuid().'@feeder.local',
            'phone' => '071'.random_int(1000000, 9999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::SUPER_ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);
    }

    /**
     * @return array{0: User, 1: Order}
     */
    private function makeReadyOrder(): array
    {
        $reseller = $this->makeResellerUser();
        $supplier = $this->makeSupplierUser();
        ResellerSupplierAssignment::query()->create([
            'reseller_id' => $reseller->id,
            'supplier_id' => $supplier->id,
        ]);
        $variant = $this->makeVariant($supplier);
        $order = $this->makeOrder($reseller, $supplier, $variant);
        $setup = $this->makeCourierSetup($order);

        $order->forceFill([
            'draft_courier_id' => $setup['courier']->id,
            'draft_courier_service_id' => $setup['service']->id,
            'draft_courier_city_id' => $setup['city']->id,
        ])->save();

        return [$reseller, $order->fresh()];
    }

    private function submitBankTransfer(
        User $reseller,
        Order $order,
        string $reference = 'ADMIN-REF',
        string $fileUuid = 'ADMPROOF01',
    ): OrderPaymentSubmission {
        Http::fake(function () use ($fileUuid) {
            return Http::response([
                'message' => 'File uploaded successfully.',
                'file' => [
                    'uuid' => $fileUuid,
                    'application' => 'RESELLER',
                    'entity_type' => 'ORDER_PAYMENT',
                    'entity_uuid' => 'ENTITY0001',
                    'category' => 'PAYMENT_PROOF',
                    'original_name' => 'slip.pdf',
                    'mime_type' => 'application/pdf',
                    'size' => 120,
                ],
            ], 201);
        });

        return app(OrderPaymentReviewService::class)->submitBankTransfer(
            $order,
            $reseller,
            UploadedFile::fake()->create('slip.pdf', 120, 'application/pdf'),
            $reference,
            350.50,
            'Bank transfer payment for admin review',
            (int) $reseller->company_id,
        );
    }

    private function makeResellerUser(): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::RESELLER->value],
            [
                'uuid' => UuidService::generate(),
                'name' => 'Reseller Portal',
                'subdomain' => 'reseller-'.Str::lower(Str::random(4)),
                'description' => 'Reseller Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => UuidService::generate(),
            'portal_id' => $portal->id,
            'name' => 'Reseller Co '.Str::lower(Str::random(4)),
            'email' => 'reseller-'.Str::uuid().'@feeder.local',
            'phone' => '077'.random_int(100000, 999999),
            'registration_number' => 'REG-'.Str::random(6),
            'status' => CompanyStatus::ACTIVE->value,
        ]);

        $user = User::query()->create([
            'uuid' => UuidService::generate(),
            'company_id' => $company->id,
            'email' => 'user-'.Str::uuid().'@feeder.local',
            'phone' => '077'.random_int(100000, 999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();
        $this->configureResellerCompany($company, ['lk']);

        return $user->fresh(['company']);
    }

    private function makeSupplierUser(): User
    {
        $portal = Portal::query()->firstOrCreate(
            ['code' => PortalCode::SUPPLIER->value],
            [
                'uuid' => UuidService::generate(),
                'name' => 'Supplier Portal',
                'subdomain' => 'supplier-'.Str::lower(Str::random(4)),
                'description' => 'Supplier Portal',
                'is_active' => true,
            ]
        );

        $company = Company::query()->create([
            'uuid' => UuidService::generate(),
            'portal_id' => $portal->id,
            'name' => 'Supplier Co '.Str::lower(Str::random(4)),
            'email' => 'supplier-'.Str::uuid().'@feeder.local',
            'phone' => '076'.random_int(100000, 999999),
            'registration_number' => 'SUP-'.Str::random(6),
            'status' => CompanyStatus::ACTIVE->value,
            'operation_market_id' => $this->marketByCode('lk')->id,
        ]);

        $user = User::query()->create([
            'uuid' => UuidService::generate(),
            'company_id' => $company->id,
            'email' => 'supplier-user-'.Str::uuid().'@feeder.local',
            'phone' => '076'.random_int(100000, 999999),
            'password' => Hash::make('password'),
            'user_type' => UserType::OWNER->value,
            'status' => UserStatus::ACTIVE->value,
            'phone_verified_at' => now(),
        ]);

        $company->forceFill(['owner_user_id' => $user->id])->save();

        return $user;
    }

    private function makeVariant(User $supplier): ProductVariant
    {
        $category = ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'name' => 'General',
            'slug' => 'general-'.Str::lower(Str::random(6)),
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $product = Product::query()->create([
            'uuid' => (string) Str::uuid(),
            'supplier_id' => $supplier->id,
            'category_id' => $category->id,
            'market_id' => $this->marketByCode('lk')->id,
            'name' => 'Product '.Str::upper(Str::random(5)),
            'slug' => 'product-'.Str::lower(Str::random(8)),
            'status' => ProductStatus::ACTIVE->value,
            'system_visible' => true,
            'web_visible' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);

        return ProductVariant::query()->create([
            'uuid' => (string) Str::uuid(),
            'product_id' => $product->id,
            'name' => 'Default',
            'barcode' => 'BC'.Str::upper(Str::random(10)),
            'cost' => 100.00,
            'selling_price' => 250.00,
            'weight' => 0.500,
            'company_commission' => 150.00,
            'is_active' => true,
            'created_by' => $supplier->id,
            'updated_by' => $supplier->id,
        ]);
    }

    private function makeOrder(User $reseller, User $supplier, ProductVariant $variant): Order
    {
        $market = $this->marketByCode('lk');
        $customer = Customer::query()->create([
            'uuid' => UuidService::generate(),
            'display_name' => 'BT Customer',
            'primary_country_id' => $this->countryByIso('LK')->id,
            'is_banned' => false,
        ]);

        $order = Order::query()->create([
            'uuid' => (string) Str::uuid(),
            'order_number' => 'ORD-ADM-'.Str::upper(Str::random(5)),
            'source' => OrderSource::MANUAL,
            'status' => OrderStatus::PENDING,
            'market_id' => $market->id,
            'currency_id' => $market->currency_id,
            'market_code_snapshot' => $market->code,
            'currency_code_snapshot' => 'LKR',
            'reseller_id' => $reseller->id,
            'reseller_company_id' => $reseller->company_id,
            'supplier_id' => $supplier->id,
            'customer_id' => $customer->id,
            'customer_name_snapshot' => 'BT Customer',
            'primary_phone_snapshot' => '070'.random_int(1000000, 9999999),
            'primary_phone_country_id' => $this->countryByIso('LK')->id,
            'items_subtotal' => 250,
            'discount_amount' => 0,
            'courier_fee_amount' => 0,
            'customer_payable_amount' => 250,
            'total_weight' => 0.5,
            'created_by' => $reseller->id,
            'updated_by' => $reseller->id,
        ]);

        OrderItem::query()->create([
            'order_id' => $order->id,
            'product_id' => $variant->product_id,
            'product_variant_id' => $variant->id,
            'product_name_snapshot' => 'Admin Review Product',
            'variant_name_snapshot' => $variant->name,
            'barcode_snapshot' => $variant->barcode,
            'quantity' => 1,
            'unit_selling_price' => 250,
            'unit_cost_snapshot' => 100,
            'unit_company_commission_snapshot' => 150,
            'unit_weight_snapshot' => 0.5,
            'line_selling_total' => 250,
            'line_weight_total' => 0.5,
        ]);

        OrderAddress::query()->create([
            'order_id' => $order->id,
            'recipient_name' => 'BT Customer',
            'line1' => '1 Admin Review Road',
            'city_name' => 'Colombo',
            'country_id' => $this->countryByIso('LK')->id,
        ]);

        return $order->fresh();
    }

    /**
     * @return array{courier: Courier, service: CourierService, city: CourierCity}
     */
    private function makeCourierSetup(Order $order): array
    {
        $courier = Courier::query()->create([
            'code' => 'AD'.strtoupper(substr(uniqid(), -4)),
            'name' => 'Admin Review Courier',
            'is_active' => true,
        ]);

        $service = CourierService::query()->create([
            'courier_id' => $courier->id,
            'code' => 'STD',
            'name' => 'Standard',
            'external_service_id' => 'ext-std',
            'is_active' => true,
        ]);

        $city = CourierCity::query()->create([
            'courier_id' => $courier->id,
            'district_name' => 'Colombo',
            'city_name' => 'Colombo 03',
            'external_city_code' => 'CMB'.Str::upper(Str::random(4)),
            'external_district_code' => 'COL',
            'is_active' => true,
        ]);

        CourierMarketPricing::query()->create([
            'courier_id' => $courier->id,
            'market_id' => $order->market_id,
            'currency_id' => $order->currency_id,
            'first_kg_fee' => 600.00,
            'additional_kg_fee' => 100.00,
            'is_active' => true,
        ]);

        SupplierCourierAccount::query()->create([
            'supplier_id' => $order->supplier_id,
            'courier_id' => $courier->id,
            'account_label' => 'Primary',
            'credentials_encrypted' => Crypt::encryptString(json_encode(['api_key' => 'secret'], JSON_THROW_ON_ERROR)),
            'is_active' => true,
        ]);

        return compact('courier', 'service', 'city');
    }
}
