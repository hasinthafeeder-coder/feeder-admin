<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Stock\IndexStockRequest;
use Feeder\Core\Services\StockListService;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct(
        private readonly StockListService $stockListService,
    ) {}

    public function index(IndexStockRequest $request): View
    {
        return view('pages.stock.list', [
            'variants' => $this->stockListService->paginateForAdmin($request),
            'counts' => $this->stockListService->adminCounts($request),
            'markets' => $this->stockListService->marketFilterOptions(),
            'suppliers' => $this->stockListService->supplierFilterOptions(),
            'categories' => $this->stockListService->categoryFilterOptions(),
            'filters' => [
                'search' => $request->input('search', ''),
                'market_id' => $request->input('market_id', ''),
                'supplier_id' => $request->input('supplier_id', ''),
                'category_id' => $request->input('category_id', ''),
                'stock_status' => $request->input('stock_status', ''),
            ],
        ]);
    }
}
