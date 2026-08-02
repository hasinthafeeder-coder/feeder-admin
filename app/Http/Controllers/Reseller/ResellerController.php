<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Services\Reseller\ResellerService;
use Feeder\Core\Models\User;
use Illuminate\View\View;

class ResellerController extends Controller
{
    public function __construct(protected ResellerService $resellerService) {}

    public function index(): View
    {
        $resellers = $this->resellerService->getList();

        return view('pages.reseller.list', [
            'pending' => $resellers['PENDING'],
            'active' => $resellers['ACTIVE'],
            'rejected' => $resellers['REJECTED'],
            'suspended' => $resellers['SUSPENDED'],
        ]);
    }

    public function show(User $user): View
    {
        return view('pages.reseller.profile', [
            'reseller' => $this->resellerService->getProfile($user),
        ]);
    }
}
