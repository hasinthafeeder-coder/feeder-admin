<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Services\Supplier\SupplierService;
use Feeder\Core\Models\User;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}

    public function index(): View
    {
        $suppliers = $this->supplierService->getList();

        return view('pages.supplier.list', [
            'pending' => $suppliers['PENDING'],
            'active' => $suppliers['ACTIVE'],
            'rejected' => $suppliers['REJECTED'],
            'suspended' => $suppliers['SUSPENDED'],
        ]);
    }

    public function show(User $user): View
    {
        return view('pages.supplier.profile', [
            'supplier' => $this->supplierService->getProfile($user),
        ]);
    }
}
