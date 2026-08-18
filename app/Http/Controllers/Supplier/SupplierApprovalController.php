<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\ActivateSupplierRequest;
use App\Http\Requests\Supplier\ApproveSupplierRequest;
use App\Http\Requests\Supplier\RejectSupplierRequest;
use App\Http\Requests\Supplier\SuspendSupplierRequest;
use App\Services\Supplier\SupplierApprovalService;
use Feeder\Core\Models\User;
use Illuminate\Http\RedirectResponse;

class SupplierApprovalController extends Controller
{
    public function __construct(protected SupplierApprovalService $approvalService) {}

    public function approve(ApproveSupplierRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->approve($user);

        return redirect()->back()->with('success', 'Supplier approved successfully.');
    }

    public function reject(RejectSupplierRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->reject($user);

        return redirect()->back()->with('success', 'Supplier rejected successfully.');
    }

    public function suspend(SuspendSupplierRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->suspend($user);

        return redirect()->back()->with('success', 'Supplier suspended successfully.');
    }

    public function activate(ActivateSupplierRequest $request, User $user): RedirectResponse
    {
        $this->approvalService->activate($user);

        return redirect()->back()->with('success', 'Supplier activated successfully.');
    }

    public function delete(User $user): RedirectResponse
    {
        $this->approvalService->delete($user);

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
