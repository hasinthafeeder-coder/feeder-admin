<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Feeder\Core\Models\User;
use Feeder\Core\Services\ResellerServiceChargeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResellerFinancialController extends Controller
{
    public function __construct(
        protected ResellerServiceChargeService $serviceChargeService,
    ) {}

    public function updateServiceCharge(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'reseller_service_charge' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'use_system_default' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('use_system_default')) {
            $this->serviceChargeService->clearResellerOverride($user);

            return redirect()->back()->with('success', 'The reseller has returned to the system default service charge.');
        }

        $amount = $request->input('reseller_service_charge');

        if ($amount === null || trim((string) $amount) === '') {
            $this->serviceChargeService->clearResellerOverride($user);

            return redirect()->back()->with('success', 'The reseller has returned to the system default service charge.');
        }

        $this->serviceChargeService->setResellerOverride($user, $amount);

        return redirect()->back()->with('success', 'The reseller service charge override was saved.');
    }

    public function clearServiceCharge(User $user): RedirectResponse
    {
        $this->serviceChargeService->clearResellerOverride($user);

        return redirect()->back()->with('success', 'The reseller service charge override was cleared.');
    }
}
