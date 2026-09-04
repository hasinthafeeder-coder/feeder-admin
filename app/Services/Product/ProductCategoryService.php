<?php

namespace App\Services\Product;

use Feeder\Core\Models\Product;
use Feeder\Core\Models\ProductCategory;
use Feeder\Core\Support\ProductCategoryTree;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductCategoryService
{
    public function listForTree(): Collection
    {
        return ProductCategory::query()
            ->ordered()
            ->get();
    }

    public function buildTree(?Collection $categories = null): Collection
    {
        return ProductCategoryTree::build($categories ?? $this->listForTree());
    }

    public function create(array $data): ProductCategory
    {
        $name = $this->validateName($data['name'] ?? null);
        $parent = $this->resolveParent($data['parent_id'] ?? null);
        $slug = $this->generateUniqueSlug($data['slug'] ?? $name);

        $sortOrder = $this->resolveSortOrder((int) ($data['sort_order'] ?? 0), $parent?->id);

        $category = ProductCategory::query()->create([
            'id' => (string) Str::uuid(),
            'parent_id' => $parent?->id,
            'name' => $name,
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'sort_order' => $sortOrder,
            'is_active' => true,
            'created_by' => $this->currentUserId(),
            'updated_by' => $this->currentUserId(),
        ]);

        return $category->fresh();
    }

    public function update(ProductCategory $category, array $data): ProductCategory
    {
        $name = $this->validateName($data['name'] ?? null);
        $parent = $this->resolveParent($data['parent_id'] ?? null, $category->id);
        $slug = $this->generateUniqueSlug($data['slug'] ?? $name, $category->id);

        $category->fill([
            'parent_id' => $parent?->id,
            'name' => $name,
            'slug' => $slug,
            'description' => $data['description'] ?? $category->description,
            'sort_order' => $this->resolveSortOrder((int) ($data['sort_order'] ?? $category->sort_order), $parent?->id),
            'is_active' => array_key_exists('is_active', $data) ? (bool) $data['is_active'] : $category->is_active,
            'updated_by' => $this->currentUserId(),
        ]);

        $category->save();

        return $category->fresh();
    }

    public function delete(ProductCategory $category): bool
    {
        if ($category->children()->exists()) {
            throw ValidationException::withMessages([
                'category' => ['Cannot delete this category because it still has child categories. Move or delete the child categories first.'],
            ]);
        }

        if ($this->categoryHasProducts($category)) {
            throw ValidationException::withMessages([
                'category' => ['Cannot delete this category because it is assigned to one or more products. Reassign or remove those products first.'],
            ]);
        }

        return $category->delete();
    }

    public function activate(ProductCategory $category): ProductCategory
    {
        $category->update([
            'is_active' => true,
            'updated_by' => $this->currentUserId(),
        ]);

        return $category->fresh();
    }

    public function deactivate(ProductCategory $category): ProductCategory
    {
        $category->update([
            'is_active' => false,
            'updated_by' => $this->currentUserId(),
        ]);

        return $category->fresh();
    }

    protected function categoryHasProducts(ProductCategory $category): bool
    {
        return Product::query()
            ->withTrashed()
            ->where('category_id', $category->id)
            ->exists();
    }

    protected function validateName(mixed $name): string
    {
        $value = trim((string) ($name ?? ''));

        if ($value === '') {
            throw ValidationException::withMessages([
                'name' => ['The category name is required.'],
            ]);
        }

        if (mb_strlen($value) > 255) {
            throw ValidationException::withMessages([
                'name' => ['The category name may not exceed 255 characters.'],
            ]);
        }

        return $value;
    }

    protected function generateUniqueSlug(string $value, ?string $ignoreId = null): string
    {
        $base = trim((string) Str::slug($value ?: 'category'));

        if ($base === '') {
            $base = 'category';
        }

        $slug = $base;
        $count = 1;

        while (ProductCategory::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->exists()) {
            $slug = $base . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected function resolveParent(?string $parentId, ?string $ignoreId = null): ?ProductCategory
    {
        if (empty($parentId)) {
            return null;
        }

        $parent = ProductCategory::query()->find($parentId);

        if (! $parent) {
            throw ValidationException::withMessages([
                'parent_id' => ['The selected parent category is invalid.'],
            ]);
        }

        if ($ignoreId && $parent->id === $ignoreId) {
            throw ValidationException::withMessages([
                'parent_id' => ['A category cannot be its own parent.'],
            ]);
        }

        if ($ignoreId && $this->isDescendantOf($parent, $ignoreId)) {
            throw ValidationException::withMessages([
                'parent_id' => ['A category cannot be moved under one of its descendants.'],
            ]);
        }

        return $parent;
    }

    protected function isDescendantOf(ProductCategory $category, string $ancestorId): bool
    {
        $current = $category;

        while ($current && $current->parent_id) {
            if ($current->parent_id === $ancestorId) {
                return true;
            }

            $current = ProductCategory::query()->find($current->parent_id);
        }

        return false;
    }

    protected function resolveSortOrder(int $sortOrder, ?string $parentId): int
    {
        if ($sortOrder <= 0) {
            $query = ProductCategory::query();

            if ($parentId) {
                $query->where('parent_id', $parentId);
            } else {
                $query->whereNull('parent_id');
            }

            $maxSortOrder = (int) $query->max('sort_order');

            return $maxSortOrder + 1;
        }

        return $sortOrder;
    }

    protected function currentUserId(): int
    {
        $userId = Auth::id();

        if (! $userId) {
            throw ValidationException::withMessages([
                'auth' => ['You must be authenticated to manage product categories.'],
            ]);
        }

        return (int) $userId;
    }
}
