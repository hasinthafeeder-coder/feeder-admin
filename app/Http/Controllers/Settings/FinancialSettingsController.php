<?php



namespace App\Http\Controllers\Settings;



use App\Http\Controllers\Controller;

use Feeder\Core\Services\IntroducerBonusService;

use Feeder\Core\Services\MarketDefaultCompanyCommissionService;

use Feeder\Core\Services\MarketService;

use Feeder\Core\Services\ResellerServiceChargeService;

use Illuminate\Http\RedirectResponse;

use Illuminate\Http\Request;

use Illuminate\View\View;



class FinancialSettingsController extends Controller

{

    public function __construct(

        protected ResellerServiceChargeService $serviceChargeService,

        protected IntroducerBonusService $introducerBonusService,

        protected MarketDefaultCompanyCommissionService $marketCommissionService,

        protected MarketService $marketService,

    ) {}



    public function index(): View

    {

        $markets = $this->marketService->listMarketsForFinancialConfiguration();



        $marketCommissions = $markets->mapWithKeys(function ($market) {

            $value = $this->marketCommissionService->hasDefaultCompanyCommission($market)

                ? $this->marketCommissionService->getDefaultCompanyCommission($market)

                : '';



            return [$market->uuid => $value];

        });



        $marketIntroducerBonuses = $markets->mapWithKeys(function ($market) {

            $value = $this->introducerBonusService->hasIntroducerBonus($market)

                ? $this->introducerBonusService->getIntroducerBonus($market)

                : '';



            return [$market->uuid => $value];

        });



        $marketServiceCharges = $markets->mapWithKeys(function ($market) {

            $value = $this->serviceChargeService->hasDefaultCharge($market)

                ? $this->serviceChargeService->getDefaultCharge($market)

                : '';



            return [$market->uuid => $value];

        });



        return view('pages.settings.financial', [

            'markets' => $markets,

            'marketCommissions' => $marketCommissions,

            'marketIntroducerBonuses' => $marketIntroducerBonuses,

            'marketServiceCharges' => $marketServiceCharges,

        ]);

    }



    public function update(Request $request): RedirectResponse

    {

        $request->validate([

            'market_company_commissions' => ['required', 'array'],

            'market_company_commissions.*' => ['required', 'numeric', 'min:0', 'max:100000000'],

            'market_introducer_bonuses' => ['required', 'array'],

            'market_introducer_bonuses.*' => ['required', 'numeric', 'min:0', 'max:100000000'],

            'market_reseller_service_charges' => ['required', 'array'],

            'market_reseller_service_charges.*' => ['required', 'numeric', 'min:0', 'max:100000000'],

        ]);



        $markets = $this->marketService->listMarketsForFinancialConfiguration()->keyBy('uuid');



        foreach ((array) $request->input('market_company_commissions', []) as $marketUuid => $amount) {

            $market = $markets->get($marketUuid);



            if ($market === null) {

                continue;

            }



            $this->marketCommissionService->setDefaultCompanyCommission($market, $amount);

        }



        foreach ((array) $request->input('market_introducer_bonuses', []) as $marketUuid => $amount) {

            $market = $markets->get($marketUuid);



            if ($market === null) {

                continue;

            }



            $this->introducerBonusService->setIntroducerBonus($market, $amount);

        }



        foreach ((array) $request->input('market_reseller_service_charges', []) as $marketUuid => $amount) {

            $market = $markets->get($marketUuid);



            if ($market === null) {

                continue;

            }



            $this->serviceChargeService->setDefaultCharge($market, $amount);

        }



        return redirect()->route('settings.financial')->with('success', 'Financial settings updated successfully.');

    }

}


