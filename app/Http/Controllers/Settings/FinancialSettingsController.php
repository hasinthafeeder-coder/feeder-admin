<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Feeder\Core\Services\IntroducerBonusService;
use Feeder\Core\Services\ResellerServiceChargeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialSettingsController extends Controller
{
    public function __construct(
        protected ResellerServiceChargeService $serviceChargeService,
        protected IntroducerBonusService $introducerBonusService,
    ) {}

    public function index(): View
    {
        return view('pages.settings.financial', [
            'defaultServiceCharge' => $this->serviceChargeService->getDefaultCharge(),
            'defaultIntroducerBonus' => $this->introducerBonusService->getDefaultBonus(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'reseller_service_charge' => ['required', 'numeric', 'min:0', 'max:100000000'],
            'introducer_bonus' => ['required', 'numeric', 'min:0', 'max:100000000'],
        ]);

        $this->serviceChargeService->setDefaultCharge($request->input('reseller_service_charge'));
        $this->introducerBonusService->setDefaultBonus($request->input('introducer_bonus'));

        return redirect()->route('settings.financial')->with('success', 'Financial settings updated successfully.');
    }
}
