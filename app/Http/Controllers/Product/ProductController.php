<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\FileServerService;
use Feeder\Core\Enums\ProductStatus;
use Feeder\Core\Models\Product;
use Feeder\Core\Models\ProductCategory;
use Feeder\Core\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    private const MAX_PRODUCT_IMAGES = 4;

    public function __construct(
        private readonly ProductService $productService,
        private readonly FileServerService $fileServerService,
    ) {}

    public function index(): View
    {
        $products = Product::query()
            ->with(['category', 'variants', 'images.file', 'supplier.company'])
            ->latest()
            ->get();

        $counts = [
            'all' => $products->count(),
            'active' => $products->where('status', ProductStatus::ACTIVE)->count(),
            'draft' => $products->where('status', ProductStatus::DRAFT)->count(),
            'inactive' => $products->where('status', ProductStatus::INACTIVE)->count(),
        ];

        return view('pages.products.list', [
            'products' => $products,
            'counts' => $counts,
        ]);
    }

    public function show(Product $product): View
    {
        $product->load([
            'supplier.company',
            'supplier.profile',
            'category',
            'descriptions',
            'variants',
            'images.file',
            'guidelineFile',
        ]);

        return view('pages.products.details', [
            'product' => $product,
        ]);
    }

    public function edit(Product $product): View
    {
        $product->load([
            'supplier.company',
            'category',
            'descriptions',
            'variants',
            'images.file',
            'guidelineFile',
        ]);

        return view('pages.products.index', $this->formViewData($product));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->validateImageUploadLimit($request->file('images', []), $product->images()->count());

        $adminId = (int) Auth::id();
        $action = (string) $request->input('save_action', 'save');
        $status = $this->productService->resolveStatus($action, $product->status, false);

        $this->productService->updateProduct(
            $product,
            [
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'status' => $status,
                'system_visible' => $request->boolean('system_visible'),
                'web_visible' => $request->boolean('web_visible'),
                'price_locked' => $request->boolean('price_locked'),
                'updated_by' => $adminId,
            ],
            $this->extractDescriptions($request),
            $this->extractVariants($request, $adminId),
            $this->uploadImages($request),
            $request->hasFile('guideline') ? $this->uploadGuideline($request) : null,
        );

        $message = match ($action) {
            'publish' => 'Product published successfully.',
            'deactivate' => 'Product deactivated successfully.',
            'activate' => 'Product activated successfully.',
            default => 'Product updated successfully.',
        };

        return redirect()
            ->route('products.index')
            ->with('success', $message);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->productService->deleteProduct($product);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function deactivate(Product $product): RedirectResponse
    {
        $this->productService->deactivateProduct($product, (int) Auth::id());

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product deactivated successfully.');
    }

    public function activate(Product $product): RedirectResponse
    {
        $this->productService->activateProduct($product, (int) Auth::id());

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Product activated successfully.');
    }

    private function formViewData(Product $product): array
    {
        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return [
            'product' => $product,
            'categories' => $categories,
            'rootCategories' => $categories->filter(fn ($category) => empty($category->parent_id))->values(),
        ];
    }

    private function extractDescriptions(UpdateProductRequest $request): array
    {
        $descriptions = [];

        foreach (['en', 'si', 'ta'] as $locale) {
            $value = $request->input('descriptions.'.$locale);

            if ($value !== null && $value !== '') {
                $descriptions[] = [
                    'language_code' => $locale,
                    'description' => $value,
                ];
            }
        }

        return $descriptions;
    }

    private function extractVariants(UpdateProductRequest $request, int $adminId): array
    {
        $variants = [];
        $priceLocked = $request->boolean('price_locked');

        foreach ((array) $request->input('variants', []) as $index => $variant) {
            $sellingPrice = $variant['selling_price'] ?? 0;

            $variants[] = [
                'id' => $variant['id'] ?? null,
                'name' => $variant['name'] ?? null,
                'barcode' => $variant['barcode'] ?? null,
                'cost' => $variant['cost'] ?? 0,
                'selling_price' => $sellingPrice,
                'weight' => $variant['weight'] ?? null,
                'suggested_price' => $priceLocked
                    ? $sellingPrice
                    : ($variant['suggested_price'] ?? null),
                'company_commission' => $variant['company_commission'] ?? 150.00,
                'sort_order' => $index,
                'is_active' => true,
                'updated_by' => $adminId,
                'created_by' => $adminId,
            ];
        }

        return $variants;
    }

    private function uploadImages(UpdateProductRequest $request): array
    {
        $images = [];
        $files = $request->file('images', []);

        foreach ($files as $index => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            $uploaded = $this->fileServerService->uploadProductImage($file);

            $images[] = [
                'file_id' => $uploaded['id'],
                'sort_order' => $index,
                'is_primary' => $index === 0,
            ];
        }

        return $images;
    }

    private function uploadGuideline(UpdateProductRequest $request): ?array
    {
        if (! $request->hasFile('guideline')) {
            return null;
        }

        $uploaded = $this->fileServerService->uploadGuideline($request->file('guideline'));

        return [
            'file_id' => $uploaded['id'],
            'file_uuid' => $uploaded['uuid'],
        ];
    }

    private function validateImageUploadLimit(array $files, int $existingImageCount): void
    {
        $newUploads = collect($files)
            ->filter(fn ($file) => $file && $file->isValid())
            ->count();

        if ($existingImageCount + $newUploads > self::MAX_PRODUCT_IMAGES) {
            throw ValidationException::withMessages([
                'images' => ['You can upload up to 4 images for each product.'],
            ]);
        }
    }
}
