<?php

namespace App\Services\Supplier;

use Feeder\Core\Enums\CompanyStatus;
use Feeder\Core\Enums\UserStatus;
use Feeder\Core\Models\User;
use Illuminate\Support\Facades\DB;

class SupplierApprovalService
{
    public function approve(User $user): bool
    {
        return $this->updateStatus(
            $user,
            UserStatus::ACTIVE,
            CompanyStatus::ACTIVE
        );
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
}
