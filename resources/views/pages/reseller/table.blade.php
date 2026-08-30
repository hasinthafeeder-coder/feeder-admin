@if ($resellers->count() > 0)
    <div class="default-table-area table-contact-list mb-4">
        <div class="table-responsive">
            <table class="table align-middle w-100">
                <thead>
                    <tr>
                        @can('resellers.markets.update')
                            <th scope="col" class="fw-medium pe-0">
                                <input type="checkbox" class="form-check-input" id="selectAllResellers-{{ $status }}">
                            </th>
                        @endcan
                        <th scope="col" class="fw-medium pe-0 rtl-pe">#</th>
                        <th scope="col" class="fw-medium">Company</th>
                        <th scope="col" class="fw-medium">Reseller</th>
                        <th scope="col" class="fw-medium">Email</th>
                        <th scope="col" class="fw-medium">Phone</th>
                        <th scope="col" class="fw-medium">Status</th>
                        <th scope="col" class="fw-medium text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($resellers as $reseller)
                        @php
                            $profilePhoto = $reseller->profile?->profile_photo
                                ? route('files.thumbnail', [
                                    'uuid' => $reseller->profile->profile_photo,
                                    'size' => 'sm',
                                ])
                                : asset('assets/images/user15.jpg');
                        @endphp
                        <tr>
                            @can('resellers.markets.update')
                                <td class="pe-0">
                                    @if ($reseller->company)
                                        <input type="checkbox" class="form-check-input reseller-bulk-checkbox"
                                            value="{{ $reseller->company->id }}">
                                    @endif
                                </td>
                            @endcan
                            <td class="text-body pe-0 rtl-pe">#{{ $reseller->id }}</td>
                            <td class="text-body">
                                <div>{{ $reseller->company?->name ?? 'N/A' }}</div>
                                @php
                                    $marketCountryCodes = $reseller->company?->allowedMarkets
                                        ?->pluck('country.iso_code')
                                        ->filter()
                                        ->map(fn ($code) => strtoupper((string) $code))
                                        ->unique()
                                        ->values();
                                @endphp
                                @if ($marketCountryCodes && $marketCountryCodes->isNotEmpty())
                                    <small class="text-muted text-uppercase">{{ $marketCountryCodes->implode(' • ') }}</small>
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
                                            {{ $reseller->profile?->first_name ?? '' }}
                                            {{ $reseller->profile?->last_name ?? '' }}
                                        </h4>
                                    </div>
                                </div>
                            </td>
                            <td class="text-body">{{ $reseller->email }}</td>
                            <td class="text-body">{{ $reseller->phone ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $status = $reseller->status->value;

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
                                    <a href="{{ route('resellers.show', ['user' => $reseller->uuid]) }}"
                                        class="bg-transparent p-0 border-0 d-inline-flex align-items-center"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-title="View profile"
                                        aria-label="View reseller profile"
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
            Showing {{ $resellers->firstItem() }} to {{ $resellers->lastItem() }}
            of {{ $resellers->total() }} results
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                @if ($resellers->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">← Previous</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $resellers->previousPageUrl() }}">← Previous</a>
                    </li>
                @endif

                @foreach ($resellers->getUrlRange(1, $resellers->lastPage()) as $page => $url)
                    @if ($page == $resellers->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                @if ($resellers->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $resellers->nextPageUrl() }}">Next →</a>
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
        No resellers found in this status.
    </div>
@endif

@can('resellers.markets.update')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const master = document.getElementById('selectAllResellers-{{ $status }}');

            if (!master) {
                return;
            }

            master.addEventListener('change', function() {
                const pane = master.closest('.tab-pane');

                if (!pane) {
                    return;
                }

                pane.querySelectorAll('.reseller-bulk-checkbox').forEach((checkbox) => {
                    checkbox.checked = master.checked;
                });
            });
        });
    </script>
@endcan
