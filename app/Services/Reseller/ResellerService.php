<?php

namespace App\Services\Reseller;

use Feeder\Core\Models\User;
use Feeder\Core\Enums\UserType;
use Feeder\Core\Enums\UserStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResellerService
{
    public function getList(): array
    {
        $statuses = [
            UserStatus::PENDING,
            UserStatus::ACTIVE,
            UserStatus::REJECTED,
            UserStatus::SUSPENDED,
        ];

        $result = [];

        foreach ($statuses as $status) {
            $result[$status->value] = $this->getListByStatus($status);
        }

        return $result;
    }

    private function getListByStatus(UserStatus $status): LengthAwarePaginator
    {
        return User::query()
            ->with([
                'profile',
                'company',
            ])
            ->where('user_type', 'OWNER')
            ->where('status', $status->value)
            ->when(request('search'), function ($query) {
                $search = request('search');

                $query->where(function ($q) use ($search) {

                    $q->where('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")

                        ->orWhereHas('profile', function ($profile) use ($search) {
                            $profile->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('nic', 'like', "%{$search}%");
                        })

                        ->orWhereHas('company', function ($company) use ($search) {
                            $company->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
    }

    public function getProfile(User $user): User
    {
        return $user->load(['profile', 'company.address', 'company.bankAccount']);
    }
}
