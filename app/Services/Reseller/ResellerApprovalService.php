<?php

namespace App\Services\Reseller;

use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Models\Company;
use Feeder\Core\Models\User;
use Feeder\Core\Services\MarketService;
use Feeder\Core\Services\PortalOwnerRoleService;
use Feeder\Core\Services\ResellerMarketAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResellerApprovalService
{
    public function __construct(
        private readonly MarketService $marketService,
        private readonly ResellerMarketAccessService $marketAccessService,
        private readonly PortalOwnerRoleService $portalOwnerRoleService,
    ) {}

    public function approve(User $user, ?string $homeCountryUuid = null, array $allowedMarketUuids = [], ?User $approvedBy = null): bool
    {
        if ($user->status === UserStatus::SUSPENDED) {
            return $this->activate($user);
        }

        if ($user->status !== UserStatus::PENDING) {
            throw ValidationException::withMessages([
                'user' => 'Only pending resellers can be approved with market settings.',
            ]);
        }

        $company = $this->resolveResellerCompany($user);
        $homeCountry = $this->marketService->findActiveCountryByUuid((string) $homeCountryUuid);

        if (! $homeCountry) {
            throw ValidationException::withMessages([
                'home_country_id' => 'The selected home country is invalid or inactive.',
            ]);
        }

        $markets = $this->marketService->resolveActiveMarketsByUuids($allowedMarketUuids);

        if ($markets->isEmpty()) {
            throw ValidationException::withMessages([
                'allowed_market_ids' => 'At least one allowed market is required.',
            ]);
        }

        return DB::transaction(function () use ($user, $company, $homeCountry, $markets, $approvedBy): bool {
            $this->portalOwnerRoleService->assignOwnerRole($user, PortalCode::RESELLER);

            $company->update([
                'home_country_id' => $homeCountry->id,
            ]);

            $this->marketAccessService->syncMarketAccess(
                $company,
                $markets->pluck('id')->map(fn ($id) => (int) $id)->all(),
                $approvedBy
            );

            $user->update(['status' => UserStatus::ACTIVE->value]);
            $company->update(['status' => CompanyStatus::ACTIVE->value]);

            return true;
        });
    }

    public function reject(User $user): bool
    {
        return $this->updateStatus(
            $user,
            UserStatus::REJECTED,
            CompanyStatus::REJECTED
        );
    }

    public function suspend(User $user): bool
    {
        return $this->updateStatus(
            $user,
            UserStatus::SUSPENDED,
            CompanyStatus::SUSPENDED
        );
    }

    public function activate(User $user): bool
    {
        return $this->updateStatus(
            $user,
            UserStatus::ACTIVE,
            CompanyStatus::ACTIVE
        );
    }

    public function delete(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->update(['status' => UserStatus::DELETED->value]);

            if ($user->company) {
                $user->company()->delete();
            }

            $user->delete();

            return true;
        });
    }

    protected function updateStatus(User $user, UserStatus $userStatus, CompanyStatus $companyStatus): bool
    {
        return DB::transaction(function () use ($user, $userStatus, $companyStatus) {
            $user->update(['status' => $userStatus->value]);

            if ($user->company) {
                $user->company->update(['status' => $companyStatus->value]);
            }

            return true;
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
