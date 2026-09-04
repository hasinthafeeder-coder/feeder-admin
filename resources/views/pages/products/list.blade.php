@extends('layout_main.app')

@php
    use Feeder\Core\Support\CurrencyDisplay;

    $counts = $counts ?? ['all' => 0, 'active' => 0, 'draft' => 0, 'inactive' => 0];
    $products = $products ?? null;
    $markets = $markets ?? collect();
    $suppliers = $suppliers ?? collect();
    $filters = $filters ?? ['search' => '', 'market_id' => '', 'supplier_id' => '', 'status' => ''];
@endphp

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Products List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span>E-Commerce</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Products List</span>
                    </li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="p-20 border-bottom">
                <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="productSearch" class="label fs-14 mb-2">Search</label>
                        <input type="text" class="form-control" id="productSearch" name="search"
                            value="{{ $filters['search'] }}" placeholder="Name, slug, supplier...">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="productMarket" class="label fs-14 mb-2">Market</label>
                        <select class="form-select form-control" id="productMarket" name="market_id">
                            <option value="">All Markets</option>
                            @foreach ($markets as $market)
                                <option value="{{ $market->id }}"
                                    @selected((string) $filters['market_id'] === (string) $market->id)>
                                    {{ $market->country?->name ?? $market->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="productSupplier" class="label fs-14 mb-2">Supplier</label>
                        <select class="form-select form-control" id="productSupplier" name="supplier_id">
                            <option value="">All Suppliers</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    @selected((string) $filters['supplier_id'] === (string) $supplier->id)>
                                    {{ $supplier->company?->name ?? 'Supplier #'.$supplier->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="productStatus" class="label fs-14 mb-2">Status</label>
                        <select class="form-select form-control" id="productStatus" name="status">
                            <option value="">All Statuses</option>
                            <option value="DRAFT" @selected($filters['status'] === 'DRAFT')>Draft</option>
                            <option value="ACTIVE" @selected($filters['status'] === 'ACTIVE')>Active</option>
                            <option value="INACTIVE" @selected($filters['status'] === 'INACTIVE')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary text-white">Apply</button>
                        <a href="{{ route('products.index') }}" class="btn btn-light border">Reset</a>
                    </div>
                </form>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <ul class="p-0 mb-0 list-unstyled d-flex align-items-center flex-wrap" style="gap: 20px;">
                    <li class="fs-16">
                        All Products <span class="text-primary">({{ number_format($counts['all']) }})</span>
                    </li>
                    <li class="fs-16">
                        Published Products <span class="text-primary">({{ number_format($counts['active']) }})</span>
                    </li>
                    <li class="fs-16">
                        Drafts Products <span class="text-primary">({{ number_format($counts['draft']) }})</span>
                    </li>
                    <li class="fs-16">
                        Inactive Products <span class="text-primary">({{ number_format($counts['inactive']) }})</span>
                    </li>
                </ul>
            </div>

            <div class="default-table-area mx-minus-1 table-product-list">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">
                                    <div class="form-check position-relative" style="top: -3px;">
                                        <input class="form-check-input" type="checkbox" id="flexCheckDefault1">
                                    </div>
                                </th>
                                <th scope="col" class="fw-medium ps-0">Product ID</th>
                                <th scope="col" class="fw-medium">Product</th>
                                <th scope="col" class="fw-medium">Supplier</th>
                                <th scope="col" class="fw-medium">Market</th>
                                <th scope="col" class="fw-medium">Category</th>
                                <th scope="col" class="fw-medium">Price</th>
                                <th scope="col" class="fw-medium">Variants</th>
                                <th scope="col" class="fw-medium">Created</th>
                                <th scope="col" class="fw-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                @php
                                    $primaryVariant = $product->variants->first();
                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                    $imageUuid = $primaryImage?->file_uuid;
                                    $productCurrency = CurrencyDisplay::currencyFromMarket($product->market);
                                    $statusValue = $product->status instanceof \Feeder\Core\Enums\ProductStatus
                                        ? $product->status->value
                                        : (string) $product->status;
                                    $statusBadgeClass = match ($statusValue) {
                                        'ACTIVE' => 'bg-success-subtle text-success border border-success border-opacity-10',
                                        'INACTIVE' => 'bg-danger-subtle text-danger border border-danger border-opacity-10',
                                        default => 'bg-primary-subtle text-primary border border-primary border-opacity-10',
                                    };
                                @endphp
                                <tr>
                                    <td class="text-body" style="width: 62px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $product->uuid }}">
                                        </div>
                                    </td>
                                    <td class="text-body ps-0">#{{ $product->id }}</td>
                                    <td class="text-body">
                                        <div class="d-flex align-items-center">
                                            <a href="{{ route('products.show', $product) }}" class="flex-shrink-0">
                                                @if ($imageUuid)
                                                    <img src="{{ route('files.thumbnail', ['uuid' => $imageUuid, 'size' => 'md']) }}"
                                                        style="width: 50px; height: 50px;" class="rounded-circle"
                                                        alt="{{ $product->name }}">
                                                @else
                                                    <img src="{{ asset('assets/images/product6.png') }}"
                                                        style="width: 50px; height: 50px;" class="rounded-circle"
                                                        alt="{{ $product->name }}">
                                                @endif
                                            </a>
                                            <div class="flex-grow-1 ms-12">
                                                <a href="{{ route('products.show', $product) }}"
                                                    class="fs-16 text-secondary text-decoration-none hover-text fw-medium">{{ $product->name }}</a>
                                                <div class="mt-1">
                                                    <span class="badge {{ $statusBadgeClass }}">{{ $statusValue }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-body">{{ $product->supplierCompanyName() }}</td>
                                    <td class="text-body">
                                        @if ($product->market?->country?->iso_code && $productCurrency)
                                            <div>{{ $product->market->country->name }}</div>
                                            <span class="badge bg-light text-body border mt-1">
                                                {{ $product->market->country->iso_code }} &bull;
                                                {{ CurrencyDisplay::inputLabel($productCurrency) }}
                                            </span>
                                        @else
                                            <span class="text-warning">Market unavailable</span>
                                        @endif
                                    </td>
                                    <td class="text-body">{{ $product->category?->name ?? '—' }}</td>
                                    <td class="text-body">
                                        {{ $primaryVariant
                                            ? CurrencyDisplay::formatProductVariantListPrice(
                                                $productCurrency,
                                                (bool) $product->price_locked,
                                                $primaryVariant->selling_price,
                                                $primaryVariant->suggested_price,
                                                $primaryVariant->suggested_price_min,
                                                $primaryVariant->suggested_price_max,
                                            )
                                            : '—' }}
                                    </td>
                                    <td class="text-body">{{ $product->variants->count() }}</td>
                                    <td class="text-body">{{ optional($product->created_at)->format('M d, Y') ?? '—' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end" style="gap: 12px;">
                                            <a href="{{ route('products.show', $product) }}"
                                                class="bg-transparent p-0 border-0 text-decoration-none"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                data-bs-title="view Product">
                                                <i class="material-symbols-outlined fs-16 fw-normal text-primary">visibility</i>
                                            </a>
                                            @can('products.update')
                                                <a href="{{ route('products.edit', $product) }}"
                                                    class="bg-transparent p-0 border-0 text-decoration-none hover-text-success"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Edit Product">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </a>
                                            @endcan
                                            @can('products.delete')
                                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                                    onsubmit="return confirm('Delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-transparent p-0 border-0 hover-text-danger"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Delete">
                                                        <i class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @include('partials.pagination', [
                    'paginator' => $products,
                    'ariaLabel' => 'Product list pagination',
                ])
            </div>
        </div>
    </div>
@endsection
