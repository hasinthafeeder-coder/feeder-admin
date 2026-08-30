<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reseller\BulkResellerMarketRequest;
use App\Http\Requests\Reseller\BulkRevokeResellerMarketRequest;
use Feeder\Core\Models\Market;
use Feeder\Core\Services\ResellerMarketAccessService;
use Illuminate\Http\RedirectResponse;

class ResellerBulkMarketController extends Controller
{
    public function __construct(
        protected ResellerMarketAccessService $marketAccessService,
    ) {}

    public function grant(BulkResellerMarketRequest $request): RedirectResponse
    {
        $market = Market::query()
            ->where('uuid', $request->validated('market_id'))
            ->where('is_active', true)
            ->firstOrFail();

        $result = $this->marketAccessService->bulkGrantMarketAccess(
            $request->validated('reseller_ids'),
            $market->id,
            $request->user()
        );

        return redirect()
            ->route('resellers.index', $request->only('search'))
            ->with('success', 'Market access granted. '.$result->summaryMessage());
    }

    public function revoke(BulkRevokeResellerMarketRequest $request): RedirectResponse
    {
        $market = Market::query()
            ->where('uuid', $request->validated('market_id'))
            ->firstOrFail();

        $result = $this->marketAccessService->bulkRevokeMarketAccess(
            $request->validated('reseller_ids'),
            $market->id
        );

        return redirect()
            ->route('resellers.index', $request->only('search'))
            ->with('success', 'Market access removed. '.$result->summaryMessage());
    }
}
