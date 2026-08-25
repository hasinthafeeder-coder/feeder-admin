<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\ProductCategory\UpdateProductCategoryRequest;
use App\Services\Product\ProductCategoryService;
use Feeder\Core\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function __construct(
        private readonly ProductCategoryService $productCategoryService,
    ) {}

    public function index(): View
    {
        $rootCategories = $this->productCategoryService->buildTree();

        return view('pages.product-categories.index', [
            'rootCategories' => $rootCategories,
        ]);
    }

    public function store(StoreProductCategoryRequest $request): RedirectResponse
    {
        $this->productCategoryService->create($request->validated());

        return redirect()->route('product-categories.index')->with('success', 'Category created successfully.');
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $this->productCategoryService->update($productCategory, $request->validated());

        return redirect()->route('product-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        try {
            $this->productCategoryService->delete($productCategory);

            return redirect()->route('product-categories.index')->with('success', 'Category deleted successfully.');
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors())->withInput();
        }
    }

    public function activate(ProductCategory $productCategory): RedirectResponse
    {
        $this->productCategoryService->activate($productCategory);

        return redirect()->route('product-categories.index')->with('success', 'Category activated successfully.');
    }

    public function deactivate(ProductCategory $productCategory): RedirectResponse
    {
        $this->productCategoryService->deactivate($productCategory);

        return redirect()->route('product-categories.index')->with('success', 'Category deactivated successfully.');
    }
}
