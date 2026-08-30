@if ($suppliers->count() > 0)
    <div class="default-table-area table-contact-list mb-4">
        <div class="table-responsive">
            <table class="table align-middle w-100">
                <thead>
                    <tr>
                        <th scope="col" class="fw-medium pe-0 rtl-pe">#</th>
                        <th scope="col" class="fw-medium">Company</th>
                        <th scope="col" class="fw-medium">Supplier</th>
                        <th scope="col" class="fw-medium">Email</th>
                        <th scope="col" class="fw-medium">Phone</th>
                        <th scope="col" class="fw-medium">Status</th>
                        <th scope="col" class="fw-medium text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($suppliers as $supplier)
                        @php
                            $profilePhoto = $supplier->profile?->profile_photo
                                ? route('files.thumbnail', [
                                    'uuid' => $supplier->profile->profile_photo,
                                    'size' => 'sm',
                                ])
                                : asset('assets/images/user15.jpg');
                        @endphp
                        <tr>
                            <td class="text-body pe-0 rtl-pe">#{{ $supplier->id }}</td>
                            <td class="text-body">
                                <div>{{ $supplier->company?->name ?? 'N/A' }}</div>
                                @php
                                    $operationCountryCode = $supplier->company?->operationMarket?->country?->iso_code;
                                @endphp
                                @if ($operationCountryCode)
                                    <small class="text-muted text-uppercase">{{ $operationCountryCode }}</small>
                                @else
                                    <small class="text-muted">Market unavailable</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $profilePhoto }}" class="rounded-circle"
                                            style="width: 35px; height: 35px;" alt="user">
                                    </div>
                                    <div class="flex-grow-1 ms-12">
                                        <h4 class="fw-medium fs-16 mb-0">
                                            {{ $supplier->profile?->first_name ?? '' }}
                                            {{ $supplier->profile?->last_name ?? '' }}
                                        </h4>
                                    </div>
                                </div>
                            </td>
                            <td class="text-body">{{ $supplier->email }}</td>
                            <td class="text-body">{{ $supplier->phone ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $status = $supplier->status->value;

                                    $statusClass = match ($status) {
                                        'ACTIVE' => 'text-success bg-success',
                                        'PENDING' => 'text-danger bg-danger',
                                        'REJECTED' => 'text-danger bg-danger',
                                        'SUSPENDED' => 'text-warning bg-warning',
                                        default => 'text-secondary bg-secondary',
                                    };

                                    $statusText = ucfirst(strtolower($status));
                                @endphp

                                <span
                                    class="{{ $statusClass }} bg-opacity-10 mt-2 fs-15 fw-normal d-inline-block default-badge">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-end" style="gap: 12px;">
                                    <a href="{{ route('suppliers.show', $supplier->uuid) }}"
                                        class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="View"
                                        style="cursor: pointer; text-decoration: none;">
                                        <i class="material-symbols-outlined fs-16 fw-normal"
                                            style="color: #EF4923;">visibility</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="text-muted fs-14">
            Showing {{ $suppliers->firstItem() }} to {{ $suppliers->lastItem() }}
            of {{ $suppliers->total() }} results
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                @if ($suppliers->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">← Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $suppliers->previousPageUrl() }}">← Previous</a>
                    </li>
                @endif

                @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                    @if ($page == $suppliers->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($suppliers->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $suppliers->nextPageUrl() }}">Next →</a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Next →</span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@else
    <div class="alert alert-info" role="alert">
        No suppliers found in this status.
    </div>
@endif



