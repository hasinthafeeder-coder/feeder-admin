<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;
use App\Services\Testing\SupplierBarcodeTestSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupplierBarcodeTestSheetController extends Controller
{
    public function __construct(
        private readonly SupplierBarcodeTestSheetService $barcodeTestSheetService,
    ) {}

    public function index(): View
    {
        return view('pages.testing.barcode-test-sheet');
    }

    public function suppliers(Request $request): JsonResponse
    {
        $term = (string) $request->query('q', '');

        return response()->json([
            'suppliers' => $this->barcodeTestSheetService->searchSuppliers($term)->all(),
        ]);
    }

    public function barcodes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'supplier' => ['required', 'string', 'max:36', Rule::exists('users', 'uuid')],
        ]);

        return response()->json(
            $this->barcodeTestSheetService->barcodesForSupplier($validated['supplier'])
        );
    }
}
