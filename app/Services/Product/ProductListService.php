<?php

namespace App\Services\Product;

use Feeder\Core\Enums\ProductStatus;
use Feeder\Core\Models\Market;
use Feeder\Core\Models\Product;
use Feeder\Core\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductListService
{
    private const PER_PAGE = 15;

    public function paginate(Request $request): LengthAwarePaginator
    {
        return $this->filteredQuery($request)
            ->with([
                'category',
                'variants',
                'images.file',
                'supplier.company',
                'supplier.profile',
                'market.country',
                'market.currency',
            ])
            ->latest()
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /**
     * @return array{all: int, active: int, draft: int, inactive: int}
     */
    public function counts(Request $request): array
    {
        $query = $this->filteredQuery($request, applyStatusFilter: false);

        return [
            'all' => (clone $query)->count(),
            'active' => (clone $query)->where('status', ProductStatus::ACTIVE)->count(),
            'draft' => (clone $query)->where('status', ProductStatus::DRAFT)->count(),
            'inactive' => (clone $query)->where('status', ProductStatus::INACTIVE)->count(),
        ];
    }

    /**
     * @return Collection<int, Market>
     */
    public function marketFilterOptions(): Collection
    {
        return Market::query()
            ->with(['country', 'currency'])
            ->whereIn('id', Product::query()->select('market_id')->whereNotNull('market_id')->distinct())
            ->orderBy('name')
            ->get();
    }

    /**
     * @return Collection<int, User>
     */
    public function supplierFilterOptions(): Collection
    {
        return User::query()
            ->with('company')
            ->whereIn('id', Product::query()->select('supplier_id')->distinct())
            ->get()
            ->sortBy(fn (User $user) => mb_strtolower($user->company?->name ?? ''))
            ->values();
    }

    private function filteredQuery(Request $request, bool $applyStatusFilter = true): Builder
    {
        $search = trim((string) $request->input('search', ''));
        $marketId = $request->input('market_id');
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');

        return Product::query()
            ->when($marketId, fn (Builder $query) => $query->where('market_id', (int) $marketId))
            ->when($supplierId, fn (Builder $query) => $query->where('supplier_id', (int) $supplierId))
            ->when(
                $applyStatusFilter && filled($status),
                fn (Builder $query) => $query->where('status', ProductStatus::from((string) $status))
            )
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $inner) use ($search): void {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function (Builder $supplier) use ($search): void {
                            $supplier->where(function (Builder $supplierInner) use ($search): void {
                                $supplierInner->whereHas('company', function (Builder $company) use ($search): void {
                                    $company->where('name', 'like', "%{$search}%");
                                })->orWhereHas('profile', function (Builder $profile) use ($search): void {
                                    $profile->where('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%");
                                });
                            });
                        });
                });
            });
    }
}
