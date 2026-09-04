@extends('layout_main.app')

@php
    use Feeder\Core\Support\CurrencyDisplay;

    $variants = $variants ?? null;
    $counts = $counts ?? ['all' => 0, 'in_stock' => 0, 'out_of_stock' => 0];
    $markets = $markets ?? collect();
    $suppliers = $suppliers ?? collect();
    $categories = $categories ?? collect();
    $filters = $filters ?? ['search' => '', 'market_id' => '', 'supplier_id' => '', 'category_id' => '', 'stock_status' => ''];
@endphp

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Stock</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span>Products</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Stock</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="p-20 border-bottom">
                <form method="GET" action="{{ route('stock.index') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="stockSearch" class="label fs-14 mb-2">Search</label>
                        <input type="text" class="form-control" id="stockSearch" name="search"
                            value="{{ $filters['search'] }}" placeholder="Product, variant, barcode, supplier...">
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="stockMarket" class="label fs-14 mb-2">Market</label>
                        <select class="form-select form-control" id="stockMarket" name="market_id">
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
                        <label for="stockSupplier" class="label fs-14 mb-2">Supplier</label>
                        <select class="form-select form-control" id="stockSupplier" name="supplier_id">
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
                        <label for="stockCategory" class="label fs-14 mb-2">Category</label>
                        <select class="form-select form-control" id="stockCategory" name="category_id">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected((string) $filters['category_id'] === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 col-lg-2">
                        <label for="stockStatus" class="label fs-14 mb-2">Stock Status</label>
                        <select class="form-select form-control" id="stockStatus" name="stock_status">
                            <option value="">All Statuses</option>
                            <option value="in_stock" @selected($filters['stock_status'] === 'in_stock')>In Stock</option>
                            <option value="out_of_stock" @selected($filters['stock_status'] === 'out_of_stock')>Out of Stock</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary text-white">Apply</button>
                        <a href="{{ route('stock.index') }}" class="btn btn-light border">Reset</a>
                    </div>
                </form>
            </div>

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <ul class="p-0 mb-0 list-unstyled d-flex align-items-center flex-wrap" style="gap: 20px;">
                    <li class="fs-16">
                        All Variants <span class="text-primary">({{ number_format($counts['all']) }})</span>
                    </li>
                    <li class="fs-16">
                        In Stock <span class="text-primary">({{ number_format($counts['in_stock']) }})</span>
                    </li>
                    <li class="fs-16">
                        Out of Stock <span class="text-primary">({{ number_format($counts['out_of_stock']) }})</span>
                    </li>
                </ul>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Product</th>
                                <th scope="col" class="fw-medium">Variant</th>
                                <th scope="col" class="fw-medium">Supplier</th>
                                <th scope="col" class="fw-medium">Market</th>
                                <th scope="col" class="fw-medium">Category</th>
                                <th scope="col" class="fw-medium">Barcode</th>
                                <th scope="col" class="fw-medium">Remaining Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($variants as $variant)
                                @php
                                    $product = $variant->product;
                                    $primaryImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
                                    $imageUuid = $primaryImage?->file_uuid;
                                    $remainingStock = (int) ($variant->remaining_stock ?? 0);
                                    $productCurrency = CurrencyDisplay::currencyFromMarket($product->market);
                                @endphp
                                <tr>
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
                                                    class="fs-16 text-secondary text-decoration-none hover-text fw-medium">
                                                    {{ $product->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-body">{{ $variant->name }}</td>
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
                                    <td class="text-body">{{ $variant->barcode ?: '—' }}</td>
                                    <td class="text-body">
                                        @if ($remainingStock > 0)
                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-10">
                                                {{ number_format($remainingStock) }} in stock
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger border-opacity-10">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">No product variants found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @include('partials.pagination', [
                    'paginator' => $variants,
                    'ariaLabel' => 'Stock list pagination',
                ])
            </div>
        </div>
    </div>
@endsection
