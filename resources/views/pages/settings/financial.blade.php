@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Financial Settings</h3>
        </div>

        <div class="card bg-white border border-white rounded-10 p-20 mb-4">
            <form action="{{ route('settings.financial.update') }}" method="POST">
                @csrf

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="reseller_service_charge" class="form-label">Default Reseller Service Charge</label>
                        <div class="input-group">
                            <span class="input-group-text">LKR</span>
                            <input
                                id="reseller_service_charge"
                                name="reseller_service_charge"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control"
                                value="{{ old('reseller_service_charge', $defaultServiceCharge) }}"
                            >
                        </div>
                        <small class="text-muted">Per order</small>
                    </div>

                    <div class="col-lg-6">
                        <label for="introducer_bonus" class="form-label">Default Introducer Bonus</label>
                        <div class="input-group">
                            <span class="input-group-text">LKR</span>
                            <input
                                id="introducer_bonus"
                                name="introducer_bonus"
                                type="number"
                                min="0"
                                step="0.01"
                                class="form-control"
                                value="{{ old('introducer_bonus', $defaultIntroducerBonus) }}"
                            >
                        </div>
                        <small class="text-muted">Per eligible sale</small>
                    </div>
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
