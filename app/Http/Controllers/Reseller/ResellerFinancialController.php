<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use Feeder\Core\Models\Market;
use Feeder\Core\Models\User;
use Feeder\Core\Services\ResellerServiceChargeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResellerFinancialController extends Controller
{
    public function __construct(
        protected ResellerServiceChargeService $serviceChargeService,
    ) {}

    public function updateServiceCharge(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'market_id' => ['required', 'string'],
            'reseller_service_charge' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'use_market_default' => ['nullable', 'boolean'],
        ]);

        $market = Market::query()
            ->where('uuid', $request->input('market_id'))
            ->first();

        if ($market === null) {
            throw ValidationException::withMessages([
                'market_id' => 'The selected market is invalid.',
            ]);
        }

        $this->serviceChargeService->assertResellerHasMarketAccess($user, $market);

        if ($request->boolean('use_market_default')) {
            $this->serviceChargeService->clearResellerOverride($user, $market);

            return redirect()->back()->with('success', 'The reseller is now using the market default service charge.');
        }

        $amount = $request->input('reseller_service_charge');

        if ($amount === null || trim((string) $amount) === '') {
            $this->serviceChargeService->clearResellerOverride($user, $market);

            return redirect()->back()->with('success', 'The reseller is now using the market default service charge.');
        }

        $this->serviceChargeService->setResellerOverride($user, $market, $amount);

        return redirect()->back()->with('success', 'The reseller service charge override was saved.');
    }

    public function clearServiceCharge(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'market_id' => ['required', 'string'],
        ]);

        $market = Market::query()
            ->where('uuid', $request->input('market_id'))
            ->first();

        if ($market === null) {
            throw ValidationException::withMessages([
                'market_id' => 'The selected market is invalid.',
            ]);
        }

        $this->serviceChargeService->assertResellerHasMarketAccess($user, $market);
        $this->serviceChargeService->clearResellerOverride($user, $market);

        return redirect()->back()->with('success', 'The reseller service charge override was cleared.');
    }
}
