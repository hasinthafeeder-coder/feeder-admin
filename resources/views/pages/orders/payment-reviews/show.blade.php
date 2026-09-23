@extends('layout_main.app')

@php
    use Feeder\Core\Enums\OrderPaymentReviewStatus;
    use Feeder\Core\Enums\OrderSource;
    use Feeder\Core\Enums\OrderStatus;
    use Feeder\Core\Support\CurrencyDisplay;

    $order = $order;
    $submission = $submission;
    $currency = $order?->currency ?? $order?->market?->currency;
    $statusLabel = $order?->status instanceof OrderStatus
        ? $order->status->label()
        : (string) ($order?->status ?? '—');
    $sourceEnum = $order?->source instanceof OrderSource
        ? $order->source
        : OrderSource::tryFrom((string) ($order?->source ?? ''));
    $sourceLabel = $sourceEnum?->label() ?? (string) ($order?->source ?? '—');
    $reviewStatus = $submission->review_status instanceof OrderPaymentReviewStatus
        ? $submission->review_status
        : OrderPaymentReviewStatus::tryFrom((string) $submission->review_status);
    $reviewLabel = match ($reviewStatus) {
        OrderPaymentReviewStatus::PENDING_REVIEW => 'Pending Approval',
        OrderPaymentReviewStatus::APPROVED => 'Approved',
        OrderPaymentReviewStatus::REJECTED => 'Rejected',
        default => '—',
    };
    $displayActor = static function ($user): string {
        if ($user === null) {
            return '—';
        }
        $name = trim(($user->profile?->first_name ?? '').' '.($user->profile?->last_name ?? ''));

        return $name !== '' ? $name : ($user->phone ?? $user->email ?? 'User #'.$user->id);
    };
    $addressParts = collect([
        $order?->address?->line1,
        $order?->address?->line2,
        $order?->address?->city_name,
        $order?->address?->district_name,
    ])->filter()->implode(', ');
    if ($addressParts === '' && $order?->address?->full_address_text) {
        $addressParts = $order->address->full_address_text;
    }
    $customerName = $order?->customer_name_snapshot
        ?: ($order?->customer?->display_name ?? '—');
    $resellerCompany = $order?->resellerCompany?->name ?? '—';
    $supplierName = $order?->supplier?->company?->name ?? '—';
    $courierName = $order?->draftCourier?->name ?? '—';
    $courierService = $order?->draftCourierService?->name ?? '—';
    $courierCity = $order?->draftCourierCity?->city_name ?? '—';
    $reviewBadgeClass = match ($reviewStatus) {
        OrderPaymentReviewStatus::PENDING_REVIEW => 'bg-warning-subtle text-warning border border-warning',
        OrderPaymentReviewStatus::APPROVED => 'bg-success-subtle text-success border border-success',
        OrderPaymentReviewStatus::REJECTED => 'bg-danger-subtle text-danger border border-danger',
        default => 'bg-light text-body border',
    };
    $history = ($paymentHistory ?? collect())->values();
@endphp

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-2 mt-1">
            <div>
                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                    <h3 class="mb-0">Order {{ $order?->order_number ?? '—' }}</h3>
                    <span class="badge {{ $reviewBadgeClass }} border-opacity-10 fs-13">
                        {{ $reviewLabel }}
                    </span>
                    <span class="badge bg-light text-body border fs-13">
                        Status: {{ $statusLabel }}
                    </span>
                </div>
                <p class="fs-15 text-body mb-0">Bank transfer payment review — order details are read-only.</p>
            </div>
            <a href="{{ route('orders.payment-reviews.index', ['filter' => $isPendingReview ? 'pending_approval' : 'approved']) }}"
                class="btn btn-light border">Back to Review List</a>
        </div>

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('orders.payment-reviews.index') }}" class="text-decoration-none">
                        <span class="text-body fs-14 hover">Review Bank Transfers</span>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="text-secondary">{{ $order?->order_number ?? 'Detail' }}</span>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="p-20 border-bottom">
                <h4 class="fs-18 mb-0">Order Information</h4>
            </div>
            <div class="p-20">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Customer</div>
                        <div class="fw-medium">{{ $customerName }}</div>
                        <div class="fs-14 text-body">{{ $order?->primary_phone_snapshot ?? $order?->customer?->primary_phone ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Reseller / Company</div>
                        <div class="fw-medium">{{ $resellerCompany }}</div>
                        <div class="fs-14 text-body">{{ $displayActor($order?->reseller) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Supplier</div>
                        <div class="fw-medium">{{ $supplierName }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Market</div>
                        <div class="fw-medium">{{ $order?->market?->name ?? ($order?->market_code_snapshot ?: '—') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Source</div>
                        <div class="fw-medium">{{ $sourceLabel }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Order type</div>
                        <div class="fw-medium">{{ $order?->order_type?->label() ?? (string) ($order?->order_type ?? '—') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Address</div>
                        <div class="fw-medium">{{ $order?->address?->recipient_name ?? $customerName }}</div>
                        <div class="fs-14 text-body">{{ $addressParts !== '' ? $addressParts : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="p-20 border-bottom">
                <h4 class="fs-18 mb-0">Products</h4>
            </div>
            <div class="p-20">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="fw-medium">Product</th>
                                <th class="fw-medium">Qty</th>
                                <th class="fw-medium">Unit price</th>
                                <th class="fw-medium text-end">Line total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (($order?->items ?? collect()) as $item)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $item->product_name_snapshot ?? $item->variant?->product?->name ?? '—' }}</div>
                                        <small class="text-muted">{{ $item->variant_name_snapshot ?? $item->variant?->name ?? '' }}</small>
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ CurrencyDisplay::formatAmount($currency, $item->unit_selling_price) }}</td>
                                    <td class="text-end">{{ CurrencyDisplay::formatAmount($currency, $item->line_selling_total ?? ($item->quantity * $item->unit_selling_price)) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-body">No products on this order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="row g-3 mt-2">
                    <div class="col-md-3">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Subtotal</div>
                        <div class="fw-medium">{{ CurrencyDisplay::formatAmount($currency, $order?->items_subtotal) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Discount</div>
                        <div class="fw-medium">{{ CurrencyDisplay::formatAmount($currency, $order?->discount_amount) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Courier fee</div>
                        <div class="fw-medium">{{ CurrencyDisplay::formatAmount($currency, $order?->courier_fee_amount) }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Customer payable</div>
                        <div class="fw-medium">{{ CurrencyDisplay::formatAmount($currency, $order?->customer_payable_amount) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="p-20 border-bottom">
                <h4 class="fs-18 mb-0">Courier</h4>
            </div>
            <div class="p-20">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Courier</div>
                        <div class="fw-medium">{{ $courierName }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Service / Account</div>
                        <div class="fw-medium">{{ $courierService }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Courier city</div>
                        <div class="fw-medium">{{ $courierCity }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="p-20 border-bottom">
                <h4 class="fs-18 mb-0">Payment Proof</h4>
            </div>
            <div class="p-20">
                @if ($submission->slip_file_uuid)
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('files.view', $submission->slip_file_uuid) }}"
                            class="btn btn-light border" target="_blank" rel="noopener">
                            View PDF/Image
                        </a>
                        <a href="{{ route('files.download', $submission->slip_file_uuid) }}"
                            class="btn btn-light border">
                            Download
                        </a>
                    </div>
                    <div class="fs-13 text-body mt-2">
                        {{ $submission->slip_original_name ?: $submission->slip_file_uuid }}
                    </div>
                @else
                    <p class="text-body mb-0">No payment proof file is attached to this submission.</p>
                @endif
            </div>
        </div>

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="p-20 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="fs-18 mb-0">Bank Transfer Payment</h4>
                <span class="badge {{ $reviewBadgeClass }} border-opacity-10">
                    {{ $reviewLabel }}
                </span>
            </div>
            <div class="p-20">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Payment method</div>
                        <div class="fw-medium">Bank transfer</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Reference number</div>
                        <div class="fw-medium">{{ $submission->reference_number ?: '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Amount</div>
                        <div class="fw-medium">{{ CurrencyDisplay::formatAmount($currency, $submission->amount) }}</div>
                    </div>
                    <div class="col-12">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Description</div>
                        <div class="fw-medium">{{ $submission->description ?: '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Submitted by</div>
                        <div class="fw-medium">{{ $displayActor($submission->submittedByUser) }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Submitted at</div>
                        <div class="fw-medium">{{ optional($submission->submitted_at)->format('M j, Y · g:i A') ?? '—' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="fs-12 text-uppercase text-secondary mb-1">Review status</div>
                        <div class="fw-medium">{{ $reviewLabel }}</div>
                    </div>
                    @if ($submission->reviewed_at)
                        <div class="col-md-4">
                            <div class="fs-12 text-uppercase text-secondary mb-1">Reviewed by</div>
                            <div class="fw-medium">{{ $displayActor($submission->reviewedByUser) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fs-12 text-uppercase text-secondary mb-1">Reviewed at</div>
                            <div class="fw-medium">{{ optional($submission->reviewed_at)->format('M j, Y · g:i A') }}</div>
                        </div>
                    @endif
                    @if ($submission->review_note)
                        <div class="col-12">
                            <div class="fs-12 text-uppercase text-secondary mb-1">Review note</div>
                            <div class="fw-medium">{{ $submission->review_note }}</div>
                        </div>
                    @endif
                </div>

                @if ($isPendingReview)
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        @if ($canApprove)
                            <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal"
                                data-bs-target="#approvePaymentModal">
                                Approve Payment
                            </button>
                        @endif
                        @if ($canReject)
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectPaymentModal">
                                Reject Payment
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if ($history->count() > 1)
            <div class="card bg-white border border-white rounded-10 mb-4">
                <div class="p-20 border-bottom">
                    <h4 class="fs-18 mb-0">Payment Submission History</h4>
                </div>
                <div class="p-20">
                    <ul class="list-unstyled mb-0">
                        @foreach ($history->sortBy('id')->values() as $index => $historyRow)
                            @php
                                $historyStatus = $historyRow->review_status instanceof OrderPaymentReviewStatus
                                    ? $historyRow->review_status
                                    : OrderPaymentReviewStatus::tryFrom((string) $historyRow->review_status);
                                $historyLabel = match ($historyStatus) {
                                    OrderPaymentReviewStatus::PENDING_REVIEW => 'Pending Approval',
                                    OrderPaymentReviewStatus::APPROVED => 'Approved',
                                    OrderPaymentReviewStatus::REJECTED => 'Rejected',
                                    default => (string) ($historyRow->review_status ?? '—'),
                                };
                                $isCurrent = (int) $historyRow->id === (int) $submission->id;
                            @endphp
                            <li class="d-flex justify-content-between align-items-center flex-wrap gap-2 py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <span class="fw-medium">#{{ $index + 1 }} — {{ $historyLabel }}</span>
                                    @if ($isCurrent)
                                        <span class="badge bg-primary-subtle text-primary border border-primary border-opacity-10 ms-1">Current</span>
                                    @endif
                                    <div class="fs-13 text-body">
                                        Ref: {{ $historyRow->reference_number ?: '—' }}
                                        · {{ optional($historyRow->submitted_at)->format('M j, Y · g:i A') ?? '—' }}
                                    </div>
                                </div>
                                @if (! $isCurrent)
                                    <a href="{{ route('orders.payment-reviews.show', $historyRow) }}" class="btn btn-sm btn-light border">Open</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    @if ($isPendingReview && $canApprove)
        <div class="modal fade" id="approvePaymentModal" tabindex="-1" aria-labelledby="approvePaymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="approvePaymentModalLabel">Approve Payment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-2">Approving this bank transfer will:</p>
                        <ul class="mb-0 ps-3">
                            <li>Approve the payment submission</li>
                            <li>Automatically move the order to <strong>Confirmed</strong></li>
                            <li>Lock the order according to the existing confirmation workflow</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form method="POST" action="{{ route('orders.payment-reviews.approve', $submission) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary text-white">Approve Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($isPendingReview && $canReject)
        <div class="modal fade" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('orders.payment-reviews.reject', $submission) }}">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="rejectPaymentModalLabel">Reject Payment</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="mb-3">Provide a rejection reason. The reseller will be able to submit a new bank transfer proof.</p>
                            <label class="form-label" for="review_note">Rejection reason</label>
                            <textarea id="review_note" name="review_note" rows="4"
                                class="form-control @error('review_note') is-invalid @enderror"
                                required maxlength="2000">{{ old('review_note') }}</textarea>
                            @error('review_note')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger text-white">Reject Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
