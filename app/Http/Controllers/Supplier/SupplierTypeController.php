<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\UpdateSupplierTypeRequest;
use App\Services\Supplier\SupplierService;
use Feeder\Core\Enums\SupplierType;
use Feeder\Core\Models\User;
use Illuminate\Http\RedirectResponse;

class SupplierTypeController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}

    public function update(UpdateSupplierTypeRequest $request, User $user): RedirectResponse
    {
        $this->supplierService->updateSupplierType(
            $user,
            SupplierType::from($request->validated('supplier_type'))
        );

        return redirect()
            ->route('suppliers.show', $user)
            ->with('success', 'Supplier type updated successfully.');
    }
}
