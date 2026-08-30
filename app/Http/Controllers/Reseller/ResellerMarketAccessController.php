<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reseller\UpdateResellerMarketAccessRequest;
use App\Services\Reseller\ResellerMarketManagementService;
use Feeder\Core\Models\User;
use Illuminate\Http\RedirectResponse;

class ResellerMarketAccessController extends Controller
{
    public function __construct(
        protected ResellerMarketManagementService $marketManagementService,
    ) {}

    public function update(UpdateResellerMarketAccessRequest $request, User $user): RedirectResponse
    {
        $this->marketManagementService->updateMarketSettings(
            $user,
            $request->validated('home_country_id'),
            $request->validated('allowed_market_ids'),
            $request->user()
        );

        return redirect()
            ->route('resellers.show', $user)
            ->with('success', 'Reseller market settings updated successfully.');
    }
}
