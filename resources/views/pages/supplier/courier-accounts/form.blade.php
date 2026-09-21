@extends('layout_main.app')

@section('content')
    @php
        $isEdit = $mode === 'edit';
        $schemaJson = json_encode($schemaFieldsByCourier, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
        $royalLocations = $royalLocations ?? ['courier_uuids' => [], 'states' => [], 'cities_by_state' => []];
        $selectedCourierUuid = old(
            'courier_uuid',
            $account?->courier?->uuid
        );
        $oldMeta = old('meta', $presented['meta'] ?? []);
    @endphp

    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <div>
                <h3 class="mb-1">{{ $isEdit ? 'Edit Courier Account' : 'Add Courier Account' }}</h3>
                <p class="text-muted mb-0">
                    Supplier: {{ $supplier->company?->name ?? $supplier->email }}
                </p>
            </div>
            <a href="{{ route('suppliers.show', $supplier) }}#courier-accounts" class="btn btn-outline-secondary">
                Back to Supplier
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white border border-white rounded-10 p-20 mb-4">
            <form method="POST"
                action="{{ $isEdit
                    ? route('suppliers.courier-accounts.update', [$supplier, $account])
                    : route('suppliers.courier-accounts.store', $supplier) }}">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-lg-6 mb-20">
                        <div class="form-floating">
                            @if ($isEdit)
                                <input type="text" class="form-control" id="courier_name"
                                    value="{{ $account->courier?->name }} ({{ $account->courier?->code }})" disabled>
                                <label for="courier_name">Courier Service</label>
                                <input type="hidden" name="courier_uuid" value="{{ $account->courier?->uuid }}">
                                <div class="form-text">Courier cannot be changed after creation. Create another account for a different courier.</div>
                            @elseif ($availableCouriers->isEmpty())
                                <input type="text" class="form-control" value="No available couriers" disabled>
                                <label>Courier Service</label>
                                <div class="form-text">All active couriers already have an account for this supplier, or no couriers are configured.</div>
                            @else
                                <select class="form-select form-control" id="courier_uuid" name="courier_uuid" required>
                                    <option value="" disabled @selected(! $selectedCourierUuid)>Select courier</option>
                                    @foreach ($availableCouriers as $courier)
                                        <option value="{{ $courier->uuid }}"
                                            data-code="{{ $courier->code }}"
                                            @selected($selectedCourierUuid === $courier->uuid)>
                                            {{ $courier->name }} ({{ $courier->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <label for="courier_uuid">Courier Service</label>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-6 mb-20">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="account_label" name="account_label"
                                value="{{ old('account_label', $account?->account_label) }}" required
                                placeholder="Account label">
                            <label for="account_label">Account Label</label>
                        </div>
                    </div>
                </div>

                @if ($isEdit && ! empty($presented['credential_display']))
                    <div class="mb-20">
                        <h5 class="mb-2">Saved Credentials</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach ($presented['credential_display'] as $row)
                                <li class="mb-1">
                                    <span class="text-muted">{{ $row['label'] }}:</span>
                                    <strong>{{ $row['display'] }}</strong>
                                </li>
                            @endforeach
                        </ul>
                        <div class="form-text">Leave secret fields blank to keep existing values.</div>
                    </div>
                @endif

                <div id="credential-fields" class="row"></div>

                <div id="royal-meta-fields" class="row d-none">
                    <div class="col-12 mb-10">
                        <h5 class="mb-1">ROYAL Account Configuration</h5>
                        <p class="text-muted fs-13 mb-0">
                            Merchant Business ID and origin are required. Global Curfox URL/tenant come from server configuration.
                        </p>
                    </div>
                    <div class="col-lg-6 mb-20">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="meta_merchant_business_id"
                                name="meta[merchant_business_id]"
                                value="{{ $oldMeta['merchant_business_id'] ?? '' }}"
                                placeholder="Merchant Business ID">
                            <label for="meta_merchant_business_id">Merchant Business ID</label>
                        </div>
                        <div class="form-text">Configured explicitly per supplier. Not taken from Curfox merchant_id.</div>
                    </div>
                    <div class="col-lg-6 mb-20">
                        <div class="form-floating">
                            <select class="form-select form-control" id="meta_origin_state_id" name="meta[origin_state_id]">
                                <option value="">Select origin state</option>
                            </select>
                            <label for="meta_origin_state_id">Origin State</label>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-20">
                        <div class="form-floating">
                            <select class="form-select form-control" id="meta_origin_city_id" name="meta[origin_city_id]">
                                <option value="">Select origin city</option>
                            </select>
                            <label for="meta_origin_city_id">Origin City</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('suppliers.show', $supplier) }}#courier-accounts" class="btn btn-outline-secondary">Cancel</a>
                    @if (! $isEdit && $availableCouriers->isEmpty())
                        <button type="submit" class="btn btn-primary text-white" disabled>Save Courier Account</button>
                    @else
                        <button type="submit" class="btn btn-primary text-white" id="courierAccountSubmitBtn">
                            {{ $isEdit ? 'Test & Save Changes' : 'Test & Save Account' }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const schemas = {!! $schemaJson !!};
            const royalLocations = @json($royalLocations);
            const oldCredentials = @json(old('credentials', []));
            const oldMeta = @json($oldMeta);
            const isEdit = @json($isEdit);
            const selected = @json($selectedCourierUuid);
            const editCourierCode = @json($account?->courier?->code);
            const container = document.getElementById('credential-fields');
            const royalMeta = document.getElementById('royal-meta-fields');
            const courierSelect = document.getElementById('courier_uuid');
            const submitBtn = document.getElementById('courierAccountSubmitBtn');
            const originStateSelect = document.getElementById('meta_origin_state_id');
            const originCitySelect = document.getElementById('meta_origin_city_id');
            const merchantInput = document.getElementById('meta_merchant_business_id');

            function isRoyalCourier(courierUuid) {
                if (isEdit && String(editCourierCode || '').toUpperCase() === 'ROYAL') {
                    return true;
                }

                const uuids = royalLocations.courier_uuids || [];
                return uuids.indexOf(courierUuid) !== -1;
            }

            function resetSelect(select, placeholder) {
                select.innerHTML = '';
                const option = document.createElement('option');
                option.value = '';
                option.textContent = placeholder;
                select.appendChild(option);
            }

            function populateOriginStates(selectedId) {
                resetSelect(originStateSelect, 'Select origin state');
                (royalLocations.states || []).forEach(function (state) {
                    const option = document.createElement('option');
                    option.value = String(state.id);
                    option.textContent = state.name;
                    if (selectedId && String(selectedId) === String(state.id)) {
                        option.selected = true;
                    }
                    originStateSelect.appendChild(option);
                });
            }

            function populateOriginCities(stateId, selectedCityId) {
                resetSelect(originCitySelect, 'Select origin city');
                if (!stateId) {
                    return;
                }

                const cities = (royalLocations.cities_by_state || {})[String(stateId)] || [];
                cities.forEach(function (city) {
                    const option = document.createElement('option');
                    option.value = String(city.id);
                    option.textContent = city.name;
                    if (selectedCityId && String(selectedCityId) === String(city.id)) {
                        option.selected = true;
                    }
                    originCitySelect.appendChild(option);
                });
            }

            function toggleRoyalMeta(courierUuid) {
                const show = isRoyalCourier(courierUuid);
                royalMeta.classList.toggle('d-none', !show);

                if (merchantInput) {
                    merchantInput.required = show;
                }
                if (originStateSelect) {
                    originStateSelect.required = show;
                }
                if (originCitySelect) {
                    originCitySelect.required = show;
                }

                if (submitBtn) {
                    submitBtn.textContent = show
                        ? (isEdit ? 'Test & Save Changes' : 'Test & Save Account')
                        : (isEdit ? 'Save Changes' : 'Save Courier Account');
                }

                if (show) {
                    populateOriginStates(oldMeta.origin_state_id || '');
                    populateOriginCities(oldMeta.origin_state_id || originStateSelect.value, oldMeta.origin_city_id || '');
                }
            }

            function renderFields(courierUuid) {
                container.innerHTML = '';
                const fields = schemas[courierUuid] || [];

                if (!courierUuid) {
                    container.innerHTML = '<div class="col-12"><p class="text-muted">Select a courier to enter credentials.</p></div>';
                    toggleRoyalMeta('');
                    return;
                }

                if (fields.length === 0) {
                    container.innerHTML = '<div class="col-12"><p class="text-muted">No credential fields defined for this courier.</p></div>';
                    toggleRoyalMeta(courierUuid);
                    return;
                }

                fields.forEach(function (field) {
                    const col = document.createElement('div');
                    col.className = 'col-lg-6 mb-20';

                    const wrap = document.createElement('div');
                    wrap.className = 'form-floating';

                    const input = document.createElement('input');
                    input.className = 'form-control';
                    input.id = 'credential_' + field.key;
                    input.name = 'credentials[' + field.key + ']';
                    if (field.input_type === 'password') {
                        input.type = 'password';
                    } else if (field.input_type === 'email') {
                        input.type = 'email';
                    } else {
                        input.type = 'text';
                    }
                    input.placeholder = field.label;
                    input.autocomplete = 'off';

                    if (!isEdit && field.required) {
                        input.required = true;
                    }

                    if (oldCredentials && Object.prototype.hasOwnProperty.call(oldCredentials, field.key)) {
                        input.value = oldCredentials[field.key] || '';
                    }

                    const label = document.createElement('label');
                    label.setAttribute('for', input.id);
                    label.textContent = field.label + (field.secret && isEdit ? ' (leave blank to keep)' : '');

                    wrap.appendChild(input);
                    wrap.appendChild(label);
                    col.appendChild(wrap);

                    if (field.help_text) {
                        const help = document.createElement('div');
                        help.className = 'form-text';
                        help.textContent = field.help_text;
                        col.appendChild(help);
                    }

                    container.appendChild(col);
                });

                toggleRoyalMeta(courierUuid);
            }

            if (originStateSelect) {
                originStateSelect.addEventListener('change', function () {
                    populateOriginCities(originStateSelect.value, '');
                });
            }

            if (courierSelect) {
                courierSelect.addEventListener('change', function () {
                    renderFields(courierSelect.value);
                });
            }

            renderFields(selected || (courierSelect ? courierSelect.value : ''));
        })();
    </script>
@endpush
