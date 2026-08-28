<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reseller\AssignResellerSupplierRequest;
use Feeder\Core\Models\User;
use Feeder\Core\Services\ResellerSupplierAssignmentService;
use Illuminate\Http\RedirectResponse;

class ResellerSupplierAssignmentController extends Controller
{
    public function __construct(
        protected ResellerSupplierAssignmentService $assignmentService,
    ) {}

    public function store(AssignResellerSupplierRequest $request, User $user): RedirectResponse
    {
        $supplierKey = $request->validated('supplier');

        $assignedCount = $this->assignmentService->assign(
            $user,
            $supplierKey,
            $request->user()
        );

        return redirect()
            ->route('resellers.show', $user)
            ->with('success', $this->successMessage($supplierKey, $assignedCount));
    }

    public function destroy(User $user, User $supplier): RedirectResponse
    {
        $this->assignmentService->unassign($user, $supplier);

        return redirect()
            ->route('resellers.show', $user)
            ->with('success', 'Supplier assignment removed successfully.');
    }

    protected function successMessage(string $supplierKey, int $assignedCount): string
    {
        if ($supplierKey !== ResellerSupplierAssignmentService::SELECT_ALL) {
            return 'Supplier assigned successfully.';
        }

        if ($assignedCount === 0) {
            return 'No additional suppliers were available to assign.';
        }

        if ($assignedCount === 1) {
            return '1 supplier assigned successfully.';
        }

        return $assignedCount.' suppliers assigned successfully.';
    }
}
