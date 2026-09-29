<?php

namespace App\Services\Testing;

use Feeder\Core\Enums\PortalCode;
use Feeder\Core\Models\ProductVariant;
use Feeder\Core\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SupplierBarcodeTestSheetService
{
    /**
     * @return Collection<int, array{uuid: string, label: string}>
     */
    public function searchSuppliers(string $term): Collection
    {
        $term = trim($term);

        return $this->supplierQuery()
            ->when($term !== '', function (Builder $query) use ($term): void {
                $query->where(function (Builder $inner) use ($term): void {
                    $inner->where('email', 'like', '%'.$term.'%')
                        ->orWhereHas('company', function (Builder $company) use ($term): void {
                            $company->where('name', 'like', '%'.$term.'%');
                        })
                        ->orWhereHas('profile', function (Builder $profile) use ($term): void {
                            $profile->where('first_name', 'like', '%'.$term.'%')
                                ->orWhere('last_name', 'like', '%'.$term.'%');
                        });
                });
            })
            ->limit(20)
            ->get()
            ->sortBy(fn (User $user): string => mb_strtolower($this->labelFor($user)))
            ->values()
            ->map(fn (User $user): array => [
                'uuid' => (string) $user->uuid,
                'label' => $this->labelFor($user),
            ]);
    }

    /**
     * @return array{supplier: array{uuid: string, label: string}, barcodes: list<array{product_name: string, variant_name: string, barcode: string}>}
     */
    public function barcodesForSupplier(string $supplierUuid): array
    {
        $supplier = $this->supplierQuery()
            ->where('uuid', $supplierUuid)
            ->firstOrFail();

        $barcodes = ProductVariant::query()
            ->whereHas('product', function (Builder $product) use ($supplier): void {
                $product->where('supplier_id', $supplier->id);
            })
            ->whereNotNull('barcode')
            ->where('barcode', '!=', '')
            ->with('product:id,name')
            ->get()
            ->filter(fn (ProductVariant $variant): bool => trim((string) $variant->barcode) !== '')
            ->sort(function (ProductVariant $left, ProductVariant $right): int {
                return [
                    mb_strtolower((string) $left->product?->name),
                    (int) $left->sort_order,
                    mb_strtolower((string) $left->name),
                ] <=> [
                    mb_strtolower((string) $right->product?->name),
                    (int) $right->sort_order,
                    mb_strtolower((string) $right->name),
                ];
            })
            ->values()
            ->map(fn (ProductVariant $variant): array => [
                'product_name' => (string) $variant->product?->name,
                'variant_name' => trim((string) $variant->name),
                'barcode' => (string) $variant->barcode,
            ])
            ->all();

        return [
            'supplier' => [
                'uuid' => (string) $supplier->uuid,
                'label' => $this->labelFor($supplier),
            ],
            'barcodes' => $barcodes,
        ];
    }

    private function supplierQuery(): Builder
    {
        return User::query()
            ->with(['company', 'profile'])
            ->whereHas('company', function (Builder $company): void {
                $company->whereHas('portal', function (Builder $portal): void {
                    $portal->where('code', PortalCode::SUPPLIER->value);
                });
            })
            ->orderBy('id');
    }

    private function labelFor(User $user): string
    {
        $company = trim((string) $user->company?->name);
        $person = trim(implode(' ', array_filter([
            $user->profile?->first_name,
            $user->profile?->last_name,
        ])));

        if ($company !== '' && $person !== '') {
            return $company.' — '.$person;
        }

        if ($company !== '') {
            return $company;
        }

        if ($person !== '') {
            return $person;
        }

        return (string) $user->email;
    }
}
