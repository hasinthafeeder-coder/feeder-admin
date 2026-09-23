<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\RejectOrderPaymentRequest;
use App\Services\Order\AdminOrderPaymentReviewListService;
use Feeder\Core\Enums\OrderPaymentMethod;
use Feeder\Core\Enums\OrderPaymentReviewStatus;
use Feeder\Core\Models\OrderPaymentSubmission;
use Feeder\Core\Services\Order\OrderPaymentReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderPaymentReviewController extends Controller
{
    public function __construct(
        private readonly AdminOrderPaymentReviewListService $listService,
        private readonly OrderPaymentReviewService $paymentReviewService,
    ) {}

    public function index(Request $request): View
    {
        $filter = $this->listService->normalizeFilter($request->query('filter'));

        return view('pages.orders.payment-reviews.index', [
            'filter' => $filter,
            'counts' => $this->listService->counts(),
            'submissions' => $this->listService->paginate($filter),
        ]);
    }

    public function show(OrderPaymentSubmission $submission): View
    {
        $this->assertBankTransferSubmission($submission);
        $this->paymentReviewService->assertActorMayAccessSubmission(
            $requestUser = request()->user(),
            $submission,
        );

        $submission = $this->listService->loadForReview($submission);
        $reviewStatus = $submission->review_status instanceof OrderPaymentReviewStatus
            ? $submission->review_status
            : OrderPaymentReviewStatus::tryFrom((string) $submission->review_status);

        return view('pages.orders.payment-reviews.show', [
            'submission' => $submission,
            'order' => $submission->order,
            'paymentHistory' => $submission->order?->paymentSubmissions ?? collect(),
            'isPendingReview' => $reviewStatus === OrderPaymentReviewStatus::PENDING_REVIEW,
            'canApprove' => $requestUser?->hasPermission(OrderPaymentReviewService::PERMISSION_APPROVE) ?? false,
            'canReject' => $requestUser?->hasPermission(OrderPaymentReviewService::PERMISSION_REJECT) ?? false,
        ]);
    }

    public function approve(OrderPaymentSubmission $submission): RedirectResponse
    {
        $this->assertBankTransferSubmission($submission);
        $this->paymentReviewService->assertActorMayAccessSubmission(request()->user(), $submission);

        try {
            $this->paymentReviewService->approve($submission, request()->user());
        } catch (ValidationException $e) {
            return redirect()
                ->route('orders.payment-reviews.show', $submission)
                ->withErrors($e->errors());
        }

        return redirect()
            ->route('orders.payment-reviews.show', $submission)
            ->with('success', 'Bank transfer payment approved. The order is now Confirmed.');
    }

    public function reject(RejectOrderPaymentRequest $request, OrderPaymentSubmission $submission): RedirectResponse
    {
        $this->assertBankTransferSubmission($submission);
        $this->paymentReviewService->assertActorMayAccessSubmission($request->user(), $submission);

        try {
            $this->paymentReviewService->reject(
                $submission,
                $request->user(),
                (string) $request->validated('review_note'),
            );
        } catch (ValidationException $e) {
            return redirect()
                ->route('orders.payment-reviews.show', $submission)
                ->withErrors($e->errors())
                ->withInput();
        }

        return redirect()
            ->route('orders.payment-reviews.show', $submission)
            ->with('success', 'Bank transfer payment rejected. The reseller can submit a new proof.');
    }

    private function assertBankTransferSubmission(OrderPaymentSubmission $submission): void
    {
        $method = $submission->method instanceof OrderPaymentMethod
            ? $submission->method
            : OrderPaymentMethod::tryFrom((string) $submission->method);

        if ($method !== OrderPaymentMethod::BANK_TRANSFER) {
            abort(404);
        }
    }
}
