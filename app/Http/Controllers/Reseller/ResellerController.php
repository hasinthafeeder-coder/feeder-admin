<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Services\Reseller\ResellerService;
use Feeder\Core\Models\User;
use Feeder\Core\Services\Referral\ReferralService;
use Feeder\Core\Services\ResellerSupplierAssignmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ResellerController extends Controller
{
    public function __construct(
        protected ResellerService $resellerService,
        protected ReferralService $referralService,
        protected ResellerSupplierAssignmentService $assignmentService,
    ) {}

    public function index(): View
    {
        $resellers = $this->resellerService->getList();

        return view('pages.reseller.list', [
            'pending' => $resellers['PENDING'],
            'active' => $resellers['ACTIVE'],
            'rejected' => $resellers['REJECTED'],
            'suspended' => $resellers['SUSPENDED'],
        ]);
    }

    public function show(User $user): View
    {
        $reseller = $this->resellerService->getProfile($user);

        return view('pages.reseller.profile', [
            'reseller' => $reseller,
            'assignedSuppliers' => $this->assignmentService->listAssignedSuppliers($reseller),
            'assignableSuppliers' => $this->assignmentService->listAssignableSuppliers($reseller),
        ]);
    }

    public function activateReferralLink(User $user): RedirectResponse
    {
        $this->referralService->toggleActivation($user, true, auth()->user());

        return redirect()->route('resellers.show', $user)->with('success', 'Referral link activated.');
    }

    public function deactivateReferralLink(User $user): RedirectResponse
    {
        $this->referralService->toggleActivation($user, false, auth()->user());

        return redirect()->route('resellers.show', $user)->with('success', 'Referral link deactivated.');
    }
}
