<?php

namespace App\Services\Order;

use Feeder\Core\Enums\OrderPaymentMethod;
use Feeder\Core\Enums\OrderPaymentReviewStatus;
use Feeder\Core\Models\OrderPaymentSubmission;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Admin list/detail loading for bank-transfer payment reviews.
 *
 * Approve/reject business rules remain in OrderPaymentReviewService.
 */
class AdminOrderPaymentReviewListService
{
    public const FILTER_PENDING_APPROVAL = 'pending_approval';

    public const FILTER_APPROVED = 'approved';

    public function normalizeFilter(?string $filter): string
    {
        return match ($filter) {
            self::FILTER_APPROVED => self::FILTER_APPROVED,
            default => self::FILTER_PENDING_APPROVAL,
        };
    }

    /**
     * @return array{pending_approval: int, approved: int}
     */
    public function counts(): array
    {
        $rows = OrderPaymentSubmission::query()
            ->where('method', OrderPaymentMethod::BANK_TRANSFER)
            ->whereIn('review_status', [
                OrderPaymentReviewStatus::PENDING_REVIEW,
                OrderPaymentReviewStatus::APPROVED,
            ])
            ->select('review_status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('review_status')
            ->get();

        $counts = [
            self::FILTER_PENDING_APPROVAL => 0,
            self::FILTER_APPROVED => 0,
        ];

        foreach ($rows as $row) {
            $status = $row->review_status instanceof OrderPaymentReviewStatus
                ? $row->review_status
                : OrderPaymentReviewStatus::tryFrom((string) $row->review_status);

            if ($status === OrderPaymentReviewStatus::PENDING_REVIEW) {
                $counts[self::FILTER_PENDING_APPROVAL] = (int) $row->aggregate;
            } elseif ($status === OrderPaymentReviewStatus::APPROVED) {
                $counts[self::FILTER_APPROVED] = (int) $row->aggregate;
            }
        }

        return $counts;
    }

    public function paginate(string $filter, int $perPage = 20): LengthAwarePaginator
    {
        $filter = $this->normalizeFilter($filter);

        return $this->baseQuery($filter)
            ->with([
                'order.customer',
                'order.resellerCompany',
                'order.reseller.profile',
                'order.currency',
                'submittedByUser.profile',
                'reviewedByUser.profile',
            ])
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function loadForReview(OrderPaymentSubmission $submission): OrderPaymentSubmission
    {
        $submission->load([
            'submittedByUser.profile',
            'submittedByCompany',
            'reviewedByUser.profile',
            'order.customer',
            'order.address',
            'order.items.variant.product',
            'order.reseller.profile',
            'order.resellerCompany',
            'order.supplier.company',
            'order.market',
            'order.currency',
            'order.draftCourier',
            'order.draftCourierService',
            'order.draftCourierCity',
            'order.paymentSubmissions.submittedByUser.profile',
            'order.paymentSubmissions.reviewedByUser.profile',
        ]);

        return $submission;
    }

    private function baseQuery(string $filter): Builder
    {
        $reviewStatus = $filter === self::FILTER_APPROVED
            ? OrderPaymentReviewStatus::APPROVED
            : OrderPaymentReviewStatus::PENDING_REVIEW;

        return OrderPaymentSubmission::query()
            ->where('method', OrderPaymentMethod::BANK_TRANSFER)
            ->where('review_status', $reviewStatus);
    }
}
