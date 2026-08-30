<?php

namespace App\Services\Reseller;

use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\User;
use Feeder\Core\Services\MarketService;
use Feeder\Core\Services\ResellerMarketAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResellerMarketManagementService
{
    public function __construct(
        private readonly MarketService $marketService,
        private readonly ResellerMarketAccessService $marketAccessService,
    ) {}

    public function updateMarketSettings(User $user, string $homeCountryUuid, array $allowedMarketUuids, ?User $updatedBy = null): void
    {
        $company = $this->resolveResellerCompany($user);
        $homeCountry = $this->marketService->findActiveCountryByUuid($homeCountryUuid);

        if (! $homeCountry) {
            throw ValidationException::withMessages([
                'home_country_id' => 'The selected home country is invalid or inactive.',
            ]);
        }

        $markets = $this->marketService->resolveActiveMarketsByUuids($allowedMarketUuids);

        DB::transaction(function () use ($company, $homeCountry, $markets, $updatedBy): void {
            $company->update([
                'home_country_id' => $homeCountry->id,
            ]);

            $this->marketAccessService->syncMarketAccess(
                $company,
                $markets->pluck('id')->map(fn ($id) => (int) $id)->all(),
                $updatedBy
            );
        });
    }

    protected function resolveResellerCompany(User $user): Company
    {
        $company = $user->company;

        if (! $company) {
            throw ValidationException::withMessages([
                'user' => 'Reseller company record was not found.',
            ]);
        }

        $company->loadMissing('portal');

        if ($company->portal?->code !== PortalCode::RESELLER->value) {
            throw ValidationException::withMessages([
                'user' => 'The selected user is not a reseller company owner.',
            ]);
        }

        return $company;
    }
}
