@extends('layout_main.app')

@section('content')
    @php
        use Feeder\Core\Enums\UserStatus;
    @endphp

    <div class="main-content-container overflow-hidden">
        @php
            $profilePhoto = $reseller->profile?->profile_photo
                ? route('files.thumbnail', ['uuid' => $reseller->profile->profile_photo, 'size' => 'md'])
                : asset('assets/images/profile.jpg');

            $companyLogo = $reseller->company?->logo_uuid
                ? route('files.thumbnail', ['uuid' => $reseller->company->logo_uuid, 'size' => 'md'])
                : asset('assets/images/profile.jpg');
        @endphp

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Reseller Profile</h3>
            <a href="{{ route('resellers.index') }}" class="btn btn-outline-secondary">Back to List</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-xxl-3 col-xxxl-3">
                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="mb-20">Profile Information</h3>

                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ $profilePhoto }}" class="rounded-circle border border-3"
                                style="width: 75px; height: 75px;" alt="profile">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fs-18" style="margin-bottom: 2px;">
                                {{ $reseller->profile?->first_name ?? '' }} {{ $reseller->profile?->last_name ?? '' }}
                            </h3>
                            <span class="fs-16 me-2">{{ $reseller->phone ?? 'N/A' }}</span>
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
                        </div>
                    </div>
                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">{{ $reseller->id }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Email: <span
                                class="text-secondary text-end">{{ $reseller->email }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">NIC: <span
                                class="text-secondary text-end">{{ $reseller->profile?->nic ?? 'N/A' }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Address: <span
                                class="text-secondary text-end">{{ $reseller->profile?->address ?? 'N/A' }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Join Date: <span
                                class="text-secondary text-end">{{ $reseller->created_at->format('M d, Y') }}</span></li>
                    </ul>
                </div>

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="mb-20">Company Information</h3>

                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ $companyLogo }}" class="rounded-circle border border-3"
                                style="width: 75px; height: 75px;" alt="profile">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fs-18" style="margin-bottom: 2px;">{{ $reseller->company?->name ?? 'N/A' }}</h3>
                            <span class="fs-16 me-2">{{ $reseller->company?->phone ?? 'N/A' }}</span>
                            @php
                                $companyStatus = $reseller->company?->status?->value;

                                $statusClass2 = match ($companyStatus) {
                                    'ACTIVE' => 'text-success bg-success',
                                    'PENDING' => 'text-danger bg-danger',
                                    'REJECTED' => 'text-danger bg-danger',
                                    'SUSPENDED' => 'text-warning bg-warning',
                                    default => 'text-secondary bg-secondary',
                                };

                                $statusText2 = $companyStatus ? ucfirst(strtolower($companyStatus)) : 'N/A';
                            @endphp

                            <span
                                class="{{ $statusClass2 }} bg-opacity-10 mt-2 fs-15 fw-normal d-inline-block default-badge">
                                {{ $statusText2 }}
                            </span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">{{ $reseller->company?->id ?? 'N/A' }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Email: <span
                                class="text-secondary text-end">{{ $reseller->company?->email ?? 'N/A' }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Address: <span
                                class="text-secondary text-end">{{ $reseller->company?->address?->address ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">BR: <span
                                class="text-secondary text-end"><a
                                    href="#">{{ $reseller->company?->registration_number ?? 'N/A' }}</a></span></li>
                    </ul>
                </div>

                @php
                    $selectedHomeCountryUuid = $reseller->company?->homeCountry?->uuid;
                    $selectedMarketUuids = $reseller->company?->allowedMarkets?->pluck('uuid')->all() ?? [];
                @endphp

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <h3 class="mb-0">Market & Country</h3>
                    </div>

                    @can('resellers.markets.update')
                        @if ($reseller->status === UserStatus::ACTIVE && $reseller->company)
                            <form action="{{ route('resellers.markets.update', $reseller) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Home Country</label>
                                    <select name="home_country_id" class="form-select" required>
                                        <option value="" disabled {{ $selectedHomeCountryUuid ? '' : 'selected' }}>Select
                                            home country</option>
                                        @foreach ($activeCountries as $country)
                                            <option value="{{ $country->uuid }}"
                                                {{ $selectedHomeCountryUuid === $country->uuid ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small">Allowed Selling Markets</label>
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($activeMarkets as $market)
                                            <label class="d-flex align-items-center gap-2 mb-0">
                                                <input type="checkbox" name="allowed_market_ids[]" value="{{ $market->uuid }}"
                                                    {{ in_array($market->uuid, $selectedMarketUuids, true) ? 'checked' : '' }}>
                                                <span>{{ $market->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary text-white">Save Market Settings</button>
                                </div>
                            </form>
                        @else
                            <ul class="p-0 mb-0 list-unstyled last-child-none">
                                <li class="mb-10 fs-16 d-flex justify-content-between gap-2">
                                    <span>Home Country</span>
                                    <span
                                        class="text-secondary text-end">{{ $reseller->company?->homeCountry?->name ?? 'N/A' }}</span>
                                </li>
                                <li class="mb-0 fs-16 d-flex justify-content-between gap-2">
                                    <span>Allowed Markets</span>
                                    <span class="text-secondary text-end">
                                        {{ $reseller->company?->allowedMarkets?->pluck('name')->join(', ') ?: 'N/A' }}
                                    </span>
                                </li>
                            </ul>
                        @endif
                    @else
                        <ul class="p-0 mb-0 list-unstyled last-child-none">
                            <li class="mb-10 fs-16 d-flex justify-content-between gap-2">
                                <span>Home Country</span>
                                <span
                                    class="text-secondary text-end">{{ $reseller->company?->homeCountry?->name ?? 'N/A' }}</span>
                            </li>
                            <li class="mb-0 fs-16 d-flex justify-content-between gap-2">
                                <span>Allowed Markets</span>
                                <span class="text-secondary text-end">
                                    {{ $reseller->company?->allowedMarkets?->pluck('name')->join(', ') ?: 'N/A' }}
                                </span>
                            </li>
                        </ul>
                    @endcan
                </div>

                @php
                    $referralCode = $reseller->referralCode;
                    $referralLink = $referralCode ? url('/auth/register?ref=' . urlencode($referralCode->code)) : null;
                    $referralStatusClass =
                        $referralCode && $referralCode->is_active
                            ? 'text-success bg-success'
                            : 'text-warning bg-warning';
                    $referralStatusText = $referralCode
                        ? ($referralCode->is_active
                            ? 'Active'
                            : 'Inactive')
                        : 'Not Created';
                @endphp

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <h3 class="mb-20">Referral</h3>

                        @if ($referralCode)
                            @if ($referralCode->is_active)
                                @can('referrals.deactivate')
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#deactivateReferralModal">
                                        Deactivate
                                    </button>
                                @endcan
                            @else
                                @can('referrals.activate')
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#activateReferralModal">
                                        Activate
                                    </button>
                                @endcan
                            @endif
                        @endif
                    </div>

                    @if ($referralLink)
                        <div class="mb-3">
                            <label class="form-label text-muted small">Referral Link</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly value="{{ $referralLink }}">
                                <button type="button" class="btn btn-outline-secondary"
                                    onclick="navigator.clipboard.writeText('{{ addslashes($referralLink) }}')">Copy</button>
                            </div>
                        </div>
                    @endif



                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status</span>
                        <span
                            class="{{ $referralStatusClass }} bg-opacity-10 mt-1 fs-14 fw-normal d-inline-block default-badge">
                            {{ $referralStatusText }}
                        </span>
                    </div>
                </div>

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="">Introducer Information</h3>

                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">{{ $reseller->parentReseller?->parent?->id ?? 'N/A' }}</span>
                        </li>

                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Name: <span
                                class="text-secondary text-end">{{ $reseller->parentReseller?->parent?->profile?->first_name ?? '' }}
                                {{ $reseller->parentReseller?->parent?->profile?->last_name ?? '' }}</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Reseller Company: <span
                                class="text-secondary text-end">{{ $reseller->parentReseller?->parent?->company?->name ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Active Member: <span
                                class="text-secondary text-end">{{ $reseller->parentReseller?->parent?->status?->value ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-xxl-9 col-xxxl-9">
                <!-- Approval Card for Pending Users -->
                @if ($reseller->status === UserStatus::PENDING)
                    <div class="card bg-white rounded-10 border border-white mb-4">
                        <div class="p-20">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2>Seller waiting for approval</h2>
                                    <p class="text-muted">Please review the seller information and approve or reject their
                                        registration.</p>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <form action="{{ route('resellers.approve', $reseller->uuid) }}" method="POST"
                                        style="flex: 1;">
                                        @csrf
                                        <button type="button" class="btn btn-primary text-white w-100 h-100"
                                            data-bs-toggle="modal" data-bs-target="#approveModal">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('resellers.reject', $reseller->uuid) }}" method="POST"
                                        style="flex: 1;">
                                        @csrf
                                        <button type="button" class="btn btn-outline-secondary w-100 h-100"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($reseller->status === UserStatus::SUSPENDED)
                    <div class="card bg-white rounded-10 border border-white mb-4">
                        <div class="p-20">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2>Reactivate this seller</h2>
                                    <p class="text-muted">Please review the seller information and reactivate their
                                        account.</p>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <form action="{{ route('resellers.approve', $reseller->uuid) }}" method="POST"
                                        style="flex: 1;">
                                        @csrf
                                        <button type="button" class="btn btn-primary mt-2 text-white w-100 h-100"
                                            data-bs-toggle="modal" data-bs-target="#approveModal">
                                            Reactivate
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Bank Details Card -->
                <div class="card bg-white rounded-10 border border-white mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                        <h3>Bank Details</h3>
                        @if ($reseller->status === UserStatus::ACTIVE && $reseller->company)
                            <form class="mb-0"
                                action="{{ route('companies.bank-accounts.store', $reseller->company) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="floatingInput1"
                                                    name="account_name" placeholder="First Name">
                                                <label for="floatingInput1">Account Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="floatingInput2"
                                                    name="account_number" placeholder="Last Name">
                                                <label for="floatingInput2">Account Number</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <select class="form-select form-control" id="floatingSelect7"
                                                    name="bank_name" aria-label="Floating label select example">
                                                    <option selected disabled>Select</option>
                                                    <option value="Peoples'Bank">Peoples'Bank</option>
                                                    <option value="Bank of Ceylon">Bank of Ceylon</option>
                                                    <option value="Sampath Bank">Sampath Bank</option>
                                                    <option value="Commercial Bank">Commercial Bank</option>
                                                    <option value="Hatton National Bank">Hatton National Bank</option>
                                                </select>
                                                <label for="floatingSelect7">Bank Name</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="floatingInput4"
                                                    name="bank_code" placeholder="Phone">
                                                <label for="floatingInput4">Bank Code</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="floatingInput5"
                                                    name="branch_name" placeholder="Address">
                                                <label for="floatingInput5">Branch</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-20">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="floatingInput6"
                                                    name="branch_code" placeholder="Country">
                                                <label for="floatingInput6">Branch Code</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-center gap-2 mb-20">
                                    <button type="submit" class="btn btn-primary text-white">Add</button>
                                    <button type="reset" class="btn btn-outline-secondary">Clear</button>
                                </div>
                            </form>
                        @endif
                    </div>

                    <div class="default-table-area mx-minus-1 table-all-projects">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium">Account Name</th>
                                        <th scope="col" class="fw-medium">Account Number</th>
                                        <th scope="col" class="fw-medium">Bank</th>
                                        <th scope="col" class="fw-medium">Bank #</th>
                                        <th scope="col" class="fw-medium">Branch</th>
                                        <th scope="col" class="fw-medium">Branch #</th>
                                        <th scope="col" class="fw-medium text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reseller->company?->bankAccounts ?? [] as $account)
                                        <tr>
                                            <td>{{ $account->account_name }}</td>
                                            <td>{{ $account->account_number }}</td>
                                            <td>{{ $account->bank_name }}</td>
                                            <td>{{ $account->bank_code }}</td>
                                            <td>{{ $account->branch_name }}</td>
                                            <td>{{ $account->branch_code }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end" style="gap:12px;">
                                                    <button class="bg-transparent border-0" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i
                                                            class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                    </button>
                                                    <form
                                                        action="{{ route('companies.bank-accounts.destroy', $account) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="bg-transparent mt-3 p-0 border-0 hover-text-danger"
                                                            onclick="return confirm('Delete this bank account?')">
                                                            <i
                                                                class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                No bank accounts found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card bg-white rounded-10 border border-white mb-4">
                    <div class="ustify-content-between align-items-center flex-wrap gap-3 p-20">
                        <h3>Suppliers</h3>

                        @can('resellers.suppliers.assign')
                            <div class="row">
                                <form class="d-flex align-items-stretch justify-content-between mt-3 w-100"
                                    action="{{ route('resellers.suppliers.store', $reseller) }}" method="POST">
                                    @csrf
                                    <div class="col-lg-8">
                                        <div class="form-floating h-100">
                                            <select class="form-select form-control h-100" id="floatingSelectSupplier1"
                                                name="supplier" aria-label="Floating label select example">
                                                <option value="" selected>Select</option>
                                                <option value="all">Select All Suppliers</option>
                                                @foreach ($assignableSuppliers as $supplierOption)
                                                    <option value="{{ $supplierOption->uuid }}">
                                                        {{ $supplierOption->company?->name ?? $supplierOption->email }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="floatingSelectSupplier1">Supplier Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="submit" class="btn btn-primary text-white w-100 h-100">Add</button>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    </div>

                    <div class="default-table-area mx-minus-1 table-all-projects">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium">#</th>
                                        <th scope="col" class="fw-medium">Supplier Name</th>
                                        <th scope="col" class="fw-medium">Supplier #</th>
                                        <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($assignedSuppliers as $assignedSupplier)
                                        <tr>
                                            <td class="text-body">#{{ $loop->iteration }}</td>
                                            <td class="text-body">
                                                {{ $assignedSupplier->company?->name ?? $assignedSupplier->email }}</td>
                                            <td class="text-secondary">{{ $assignedSupplier->id }}</td>
                                            <td>
                                                <div class="d-flex justify-content-end" style="gap: 12px;">
                                                    @can('resellers.suppliers.assign')
                                                        <form
                                                            action="{{ route('resellers.suppliers.destroy', ['user' => $reseller, 'supplier' => $assignedSupplier]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="bg-transparent p-0 border-0 hover-text-danger"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-title="Delete"
                                                                onclick="return confirm('Remove this supplier assignment?')">
                                                                <i
                                                                    class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                No suppliers assigned.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                @php
                    use Feeder\Core\Support\CurrencyDisplay;
                @endphp

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-20">
                        <h3 class="mb-0">Reseller Service Charges</h3>
                    </div>

                    @if (($resellerServiceCharges ?? collect())->isEmpty())
                        <p class="text-muted mb-0">No accessible markets are assigned to this reseller.</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach ($resellerServiceCharges as $serviceChargeContext)
                                @php
                                    $market = $serviceChargeContext['market'];
                                    $currencyIso = CurrencyDisplay::inputLabel($market->currency);
                                @endphp
                                <div class="border rounded-10 p-3">
                                    <div class="fw-medium mb-2">
                                        {{ $market->name }}
                                        ({{ $currencyIso }})
                                    </div>

                                    <div class="fs-14 text-muted mb-2">
                                        Market Default:
                                        {{ CurrencyDisplay::formatAmount($market->currency, $serviceChargeContext['default_charge']) }}
                                    </div>

                                    <div class="fs-14 mb-3">
                                        Current:
                                        @if ($serviceChargeContext['uses_market_default'])
                                            <span class="text-success">Using market default</span>
                                        @else
                                            <span class="text-primary">
                                                Override:
                                                {{ CurrencyDisplay::formatAmount($market->currency, $serviceChargeContext['override']) }}
                                            </span>
                                        @endif
                                    </div>

                                    @can('resellers.financial.update')
                                        <form
                                            action="{{ route('resellers.financial.service-charge.update', $reseller->uuid) }}"
                                            method="POST" class="row g-2 align-items-end">
                                            @csrf
                                            <input type="hidden" name="market_id" value="{{ $market->uuid }}">
                                            <div class="col-md-6">
                                                <label class="form-label text-muted small mb-1">Override</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">{{ $currencyIso }}</span>
                                                    <input type="number" min="0" step="0.01" class="form-control"
                                                        name="reseller_service_charge"
                                                        value="{{ old('reseller_service_charge', $serviceChargeContext['override']) }}"
                                                        placeholder="{{ $serviceChargeContext['default_charge'] }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-flex flex-wrap gap-2">
                                                <button type="submit" class="btn btn-primary text-white btn-sm">Save</button>
                                                <button type="submit" name="use_market_default" value="1"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    Use Market Default
                                                </button>
                                            </div>
                                        </form>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Action Card for Active Users -->
                @if ($reseller->status === UserStatus::ACTIVE)
                    <div class="card bg-white rounded-10 border border-white mb-4">
                        <div class="p-20">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h2>Manage Active Seller</h2>
                                    <p class="text-muted">You can suspend or delete this seller account.</p>
                                </div>
                                <div class="col-lg-4 d-flex gap-2">
                                    <form style="flex: 1;">
                                        <button type="button" class="btn btn-outline-secondary w-100 h-100"
                                            data-bs-toggle="modal" data-bs-target="#suspendModal">
                                            Suspend
                                        </button>
                                    </form>
                                    <form style="flex: 1;">
                                        <button type="button" class="btn btn-primary text-white w-100 h-100"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="approveModalLabel">Confirm Approval</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this seller?</p>
                    <p class="text-muted mb-4">Assign the home country and allowed selling markets before approval.</p>

                    <form id="approveResellerForm" action="{{ route('resellers.approve', $reseller->uuid) }}"
                        method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted small">Home Country</label>
                            <select name="home_country_id" class="form-select" required>
                                <option value="" selected disabled>Select home country</option>
                                @foreach ($activeCountries as $country)
                                    <option value="{{ $country->uuid }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label text-muted small">Allowed Selling Markets</label>
                            <div class="d-flex flex-column gap-2">
                                @foreach ($activeMarkets as $market)
                                    <label class="d-flex align-items-center gap-2 mb-0">
                                        <input type="checkbox" name="allowed_market_ids[]" value="{{ $market->uuid }}">
                                        <span>{{ $market->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="approveResellerForm" class="btn btn-primary text-white">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rejectModalLabel">Confirm Rejection</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject this seller?</p>
                    <p class="text-muted">They will not be able to access the platform.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('resellers.reject', $reseller->uuid) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        <button type="submit" class="btn text-white btn-danger">Reject</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($referralCode)
        <div class="modal fade" id="activateReferralModal" tabindex="-1" aria-labelledby="activateReferralModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="activateReferralModalLabel">Activate referral link?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>New users will be able to register using this referral link.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('resellers.referral.activate', $reseller->uuid) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary text-white">Activate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deactivateReferralModal" tabindex="-1"
            aria-labelledby="deactivateReferralModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="deactivateReferralModalLabel">Deactivate referral link?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>New users will no longer be able to register using this referral link.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('resellers.referral.deactivate', $reseller->uuid) }}" method="POST"
                            style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">Deactivate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Suspend Modal -->
    <div class="modal fade" id="suspendModal" tabindex="-1" aria-labelledby="suspendModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="suspendModalLabel">Confirm Suspension</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to suspend this seller?</p>
                    <p class="text-muted">They will not be able to access the platform until they are activated again.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('resellers.suspend', $reseller->uuid) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        <button type="submit" class="btn text-white btn-danger">Suspend</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Confirm Deletion</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Warning:</strong> Are you sure you want to delete this seller?</p>
                    <p class="text-muted">This action cannot be undone. The seller will be permanently removed from the
                        system.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('resellers.delete', $reseller->uuid) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        <button type="submit" class="btn text-white btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1"></div>
@endsection


<style>
    .card {
        box-shadow: 0 0 0.125rem rgba(0, 0, 0, 0.05);
    }

    .modal-content {
        border: 1px solid #e5e7eb;
        border-radius: 0.625rem;
    }

    .btn-outline-secondary:hover {
        color: #6c757d;
    }
</style>
