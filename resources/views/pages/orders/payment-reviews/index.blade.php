@extends('layout_main.app')

@php
    use App\Services\Order\AdminOrderPaymentReviewListService;
    use Feeder\Core\Enums\OrderPaymentReviewStatus;
    use Feeder\Core\Enums\OrderStatus;
    use Feeder\Core\Support\CurrencyDisplay;

    $pendingCount = (int) ($counts[AdminOrderPaymentReviewListService::FILTER_PENDING_APPROVAL] ?? 0);
    $approvedCount = (int) ($counts[AdminOrderPaymentReviewListService::FILTER_APPROVED] ?? 0);
@endphp

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <div>
                <h3 class="mb-1">Review Bank Transfers</h3>
                <p class="fs-15 text-body mb-0">Review reseller bank transfer payment submissions.</p>
            </div>
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
                    <span class="text-body fs-14">Orders</span>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span class="text-secondary">Review Bank Transfers</span>
                </li>
            </ol>
        </nav>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="card bg-white border border-white rounded-10 mb-4">
            <div class="card-body p-20">
                <div class="payment-review-filter-row mb-4" aria-label="Payment review filters">
                    <a href="{{ route('orders.payment-reviews.index', ['filter' => AdminOrderPaymentReviewListService::FILTER_PENDING_APPROVAL]) }}"
                        class="filter-chip {{ $filter === AdminOrderPaymentReviewListService::FILTER_PENDING_APPROVAL ? 'is-active' : '' }}">
                        Pending Approval
                        <span class="tab-count">{{ $pendingCount }}</span>
                    </a>
                    <a href="{{ route('orders.payment-reviews.index', ['filter' => AdminOrderPaymentReviewListService::FILTER_APPROVED]) }}"
                        class="filter-chip {{ $filter === AdminOrderPaymentReviewListService::FILTER_APPROVED ? 'is-active' : '' }}">
                        Approved
                        <span class="tab-count">{{ $approvedCount }}</span>
                    </a>
                </div>

                @if ($submissions->count() > 0)
                    <div class="default-table-area mb-2">
                        <div class="table-responsive">
                            <table class="table align-middle w-100">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium">Order</th>
                                        <th scope="col" class="fw-medium">Customer</th>
                                        <th scope="col" class="fw-medium">Reseller</th>
                                        <th scope="col" class="fw-medium">Reference</th>
                                        <th scope="col" class="fw-medium">Amount</th>
                                        <th scope="col" class="fw-medium">Submitted</th>
                                        <th scope="col" class="fw-medium">Order status</th>
                                        <th scope="col" class="fw-medium">Review</th>
                                        @if ($filter === AdminOrderPaymentReviewListService::FILTER_APPROVED)
                                            <th scope="col" class="fw-medium">Reviewed</th>
                                        @endif
                                        <th scope="col" class="fw-medium text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($submissions as $row)
                                        @php
                                            $order = $row->order;
                                            $currency = $order?->currency;
                                            $reviewStatus = $row->review_status instanceof OrderPaymentReviewStatus
                                                ? $row->review_status
                                                : OrderPaymentReviewStatus::tryFrom((string) $row->review_status);
                                            $orderStatus = $order?->status instanceof OrderStatus
                                                ? $order->status->label()
                                                : (string) ($order?->status ?? '—');
                                            $customerName = $order?->customer_name_snapshot
                                                ?: ($order?->customer?->display_name ?? '—');
                                            $resellerName = $order?->resellerCompany?->name
                                                ?: (trim(($order?->reseller?->profile?->first_name ?? '').' '.($order?->reseller?->profile?->last_name ?? '')) ?: '—');
                                            $reviewerName = trim(($row->reviewedByUser?->profile?->first_name ?? '').' '.($row->reviewedByUser?->profile?->last_name ?? ''));
                                            if ($reviewerName === '') {
                                                $reviewerName = $row->reviewedByUser?->email ?? '—';
                                            }
                                            $reviewBadge = match ($reviewStatus) {
                                                OrderPaymentReviewStatus::PENDING_REVIEW => ['Pending Approval', 'text-warning bg-warning'],
                                                OrderPaymentReviewStatus::APPROVED => ['Approved', 'text-success bg-success'],
                                                OrderPaymentReviewStatus::REJECTED => ['Rejected', 'text-danger bg-danger'],
                                                default => ['—', 'text-secondary bg-secondary'],
                                            };
                                        @endphp
                                        <tr>
                                            <td class="text-body">
                                                <div class="fw-medium">{{ $order?->order_number ?? '—' }}</div>
                                                <small class="text-muted">Bank transfer</small>
                                            </td>
                                            <td class="text-body">{{ $customerName }}</td>
                                            <td class="text-body">{{ $resellerName }}</td>
                                            <td class="text-body">{{ $row->reference_number ?: '—' }}</td>
                                            <td class="text-body">{{ CurrencyDisplay::formatAmount($currency, $row->amount) }}</td>
                                            <td class="text-body">
                                                {{ optional($row->submitted_at)->format('M j, Y · g:i A') ?? '—' }}
                                            </td>
                                            <td class="text-body">{{ $orderStatus }}</td>
                                            <td>
                                                <span class="{{ $reviewBadge[1] }} bg-opacity-10 fs-14 fw-normal d-inline-block default-badge">
                                                    {{ $reviewBadge[0] }}
                                                </span>
                                            </td>
                                            @if ($filter === AdminOrderPaymentReviewListService::FILTER_APPROVED)
                                                <td class="text-body">
                                                    <div>{{ $reviewerName }}</div>
                                                    <small class="text-muted">
                                                        {{ optional($row->reviewed_at)->format('M j, Y · g:i A') ?? '—' }}
                                                    </small>
                                                </td>
                                            @endif
                                            <td class="text-end">
                                                <a href="{{ route('orders.payment-reviews.show', $row) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        {{ $submissions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-body mb-0">
                            @if ($filter === AdminOrderPaymentReviewListService::FILTER_APPROVED)
                                No approved bank transfer submissions found.
                            @else
                                No bank transfer submissions pending approval.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

<style>
    .payment-review-filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.45rem;
    }

    .payment-review-filter-row .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1px solid rgba(15, 23, 42, 0.1);
        background: #f8fafc;
        color: #334155;
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none;
    }

    .payment-review-filter-row .filter-chip .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 1.35rem;
        height: 1.35rem;
        padding: 0 0.35rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.08);
        font-size: 11px;
    }

    .payment-review-filter-row .filter-chip.is-active {
        background: #ef4923;
        border-color: #ef4923;
        color: #fff;
    }

    .payment-review-filter-row .filter-chip.is-active .tab-count {
        background: rgba(255, 255, 255, 0.22);
        color: #fff;
    }
</style>
