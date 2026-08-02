<?php

namespace App\Services\Reseller;

use Feeder\Core\Models\User;
use Feeder\Core\Enums\UserStatus;

class ResellerApprovalService
{
    public function approve(User $user): bool
    {
        return $user->update(['status' => UserStatus::ACTIVE->value]);
    }

    public function reject(User $user): bool
    {
        return $user->update(['status' => UserStatus::REJECTED->value]);
    }

    public function suspend(User $user): bool
    {
        return $user->update(['status' => UserStatus::SUSPENDED->value]);
    }

    public function activate(User $user): bool
    {
        return $user->update(['status' => UserStatus::ACTIVE->value]);
    }

    public function delete(User $user): bool
    {
        return $user->update(['status' => UserStatus::DELETED->value]);
    }
}
