@extends('layout_main.app')

@php
    use Feeder\Core\Support\CurrencyDisplay;
@endphp

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Financial Settings</h3>
        </div>

        <div class="card bg-white border border-white rounded-10 p-20 mb-4">
            <form action="{{ route('settings.financial.update') }}" method="POST">
                @csrf

                <p class="text-muted fs-14 mb-4">
                    Configure independent market defaults for company commission, introducer bonus, and reseller service charge.
                    Amounts are stored in each market's native currency. Changing a market default does not update existing product variants or reseller overrides.
                </p>

                <h5 class="mb-3">1. Market Default Company Commission</h5>
                <p class="text-muted fs-14">
                    Applied to newly created product variants when no explicit commission is supplied.
                </p>

                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Market</th>
                                <th>Country</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th style="min-width: 220px;">Company Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($markets as $market)
                                @php
                                    $currencyIso = CurrencyDisplay::inputLabel($market->currency);
                                    $commissionValue = old(
                                        'market_company_commissions.' . $market->uuid,
                                        $marketCommissions[$market->uuid] ?? ''
                                    );
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $market->name }}</div>
                                        <small class="text-muted text-uppercase">{{ $market->code }}</small>
                                    </td>
                                    <td>{{ $market->country?->name ?? '—' }}</td>
                                    <td>{{ CurrencyDisplay::formatCurrencyDescriptor($market->currency) }}</td>
                                    <td>
                                        @if ($market->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $currencyIso }}</span>
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                class="form-control"
                                                name="market_company_commissions[{{ $market->uuid }}]"
                                                value="{{ $commissionValue }}"
                                                required
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">2. Market Default Introducer Bonus</h5>
                <p class="text-muted fs-14">
                    Paid to introducers for eligible sales in each market. Independent from company commission and service charge.
                </p>

                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Market</th>
                                <th>Country</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th style="min-width: 220px;">Introducer Bonus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($markets as $market)
                                @php
                                    $currencyIso = CurrencyDisplay::inputLabel($market->currency);
                                    $introducerBonusValue = old(
                                        'market_introducer_bonuses.' . $market->uuid,
                                        $marketIntroducerBonuses[$market->uuid] ?? ''
                                    );
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $market->name }}</div>
                                        <small class="text-muted text-uppercase">{{ $market->code }}</small>
                                    </td>
                                    <td>{{ $market->country?->name ?? '—' }}</td>
                                    <td>{{ CurrencyDisplay::formatCurrencyDescriptor($market->currency) }}</td>
                                    <td>
                                        @if ($market->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $currencyIso }}</span>
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                class="form-control"
                                                name="market_introducer_bonuses[{{ $market->uuid }}]"
                                                value="{{ $introducerBonusValue }}"
                                                required
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">3. Market Default Reseller Service Charge</h5>
                <p class="text-muted fs-14">
                    Default service charge for resellers without a market-specific override.
                </p>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Market</th>
                                <th>Country</th>
                                <th>Currency</th>
                                <th>Status</th>
                                <th style="min-width: 220px;">Service Charge</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($markets as $market)
                                @php
                                    $currencyIso = CurrencyDisplay::inputLabel($market->currency);
                                    $serviceChargeValue = old(
                                        'market_reseller_service_charges.' . $market->uuid,
                                        $marketServiceCharges[$market->uuid] ?? ''
                                    );
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $market->name }}</div>
                                        <small class="text-muted text-uppercase">{{ $market->code }}</small>
                                    </td>
                                    <td>{{ $market->country?->name ?? '—' }}</td>
                                    <td>{{ CurrencyDisplay::formatCurrencyDescriptor($market->currency) }}</td>
                                    <td>
                                        @if ($market->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <span class="input-group-text">{{ $currencyIso }}</span>
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                class="form-control"
                                                name="market_reseller_service_charges[{{ $market->uuid }}]"
                                                value="{{ $serviceChargeValue }}"
                                                required
                                            >
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    @can('settings.financial.update')
                        <button type="submit" class="btn btn-primary text-white">Save</button>
                    @endcan
                </div>
            </form>
        </div>
    </div>
@endsection
