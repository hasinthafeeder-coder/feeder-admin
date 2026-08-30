<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reseller\ApproveResellerRequest;
use App\Http\Requests\Reseller\RejectResellerRequest;
use App\Http\Requests\Reseller\SuspendResellerRequest;
use App\Http\Requests\Reseller\ActivateResellerRequest;
use App\Services\Reseller\ResellerApprovalService;
use Feeder\Core\Models\User;
use Illuminate\Http\RedirectResponse;

class ResellerApprovalController extends Controller
{
    public function __construct(protected ResellerApprovalService $approvalService) {}

    public function approve(ApproveResellerRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->approve(
            $user,
            $request->validated('home_country_id'),
            $request->validated('allowed_market_ids', []),
            $request->user()
        );

        return redirect()->back()->with('success', 'Reseller approved successfully.');
    }

    public function reject(RejectResellerRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->reject($user);

        return redirect()->back()->with('success', 'Reseller rejected successfully.');
    }

    public function suspend(SuspendResellerRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->suspend($user);

        return redirect()->back()->with('success', 'Reseller suspended successfully.');
    }

    public function activate(ActivateResellerRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->activate($user);

        return redirect()->back()->with('success', 'Reseller activated successfully.');
    }

    public function delete(User $user): RedirectResponse
    {
        $this->approvalService->delete($user);

        return redirect()->route('resellers.index')->with('success', 'Reseller deleted successfully.');
    }
}
