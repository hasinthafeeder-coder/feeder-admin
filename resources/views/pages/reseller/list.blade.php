@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white border border-white rounded-10 mb-4">
                    <div class="card-body p-20">
                        <h4 class="fs-18 fw-medium mb-20">Reseller Management</h4>

                        <!-- Search Bar -->
                        <div class="mb-4">
                            <form method="GET" action="{{ route('resellers.index') }}" class="d-flex gap-2">
                                <div class="flex-grow-1">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by name, email, phone or company"
                                           value="{{ request('search') }}" />
                                </div>
                                <button type="submit" class="btn btn-primary text-white">Search</button>
                                @if(request('search'))
                                    <a href="{{ route('resellers.index') }}" class="btn btn-outline-secondary">Clear</a>
                                @endif
                            </form>
                        </div>

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs mb-4" id="resellerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="active-tab" data-bs-toggle="tab"
                                    data-bs-target="#active-tab-pane" type="button" role="tab"
                                    aria-controls="active-tab-pane" aria-selected="true">
                                    Active
                                    <span class="badge ms-1 align-middle text-white" 
                                        style="background-color: #EF4923; border-radius:50%;">
                                        {{ $active->total() }}
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" 
                                    data-bs-target="#pending-tab-pane" type="button" role="tab" 
                                    aria-controls="pending-tab-pane" aria-selected="false">
                                    Pending
                                    <span class="badge ms-1 align-middle text-white" 
                                        style="background-color: #EF4923; border-radius:50%;">
                                        {{ $pending->total() }}
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" 
                                    data-bs-target="#rejected-tab-pane" type="button" role="tab" 
                                    aria-controls="rejected-tab-pane" aria-selected="false">
                                    Rejected
                                    <span class="badge ms-1 align-middle text-white" 
                                        style="background-color: #EF4923; border-radius:50%;">
                                        {{ $rejected->total() }}
                                    </span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="suspended-tab" data-bs-toggle="tab" 
                                    data-bs-target="#suspended-tab-pane" type="button" role="tab" 
                                    aria-controls="suspended-tab-pane" aria-selected="false">
                                    Suspended
                                    <span class="badge ms-1 align-middle text-white" 
                                        style="background-color: #EF4923; border-radius:50%;">
                                        {{ $suspended->total() }}
                                    </span>
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="resellerTabContent">
                            <!-- Active Tab -->
                            <div class="tab-pane fade show active" id="active-tab-pane" role="tabpanel"
                                aria-labelledby="active-tab" tabindex="0">
                                @include('pages.reseller.table', [
                                    'resellers' => $active,
                                    'status' => 'ACTIVE'
                                ])
                            </div>

                            <!-- Pending Tab -->
                            <div class="tab-pane fade" id="pending-tab-pane" role="tabpanel"
                                aria-labelledby="pending-tab" tabindex="0">
                                @include('pages.reseller.table', [
                                    'resellers' => $pending,
                                    'status' => 'PENDING'
                                ])
                            </div>

                            <!-- Rejected Tab -->
                            <div class="tab-pane fade" id="rejected-tab-pane" role="tabpanel"
                                aria-labelledby="rejected-tab" tabindex="0">
                                @include('pages.reseller.table', [
                                    'resellers' => $rejected,
                                    'status' => 'REJECTED'
                                ])
                            </div>

                            <!-- Suspended Tab -->
                            <div class="tab-pane fade" id="suspended-tab-pane" role="tabpanel"
                                aria-labelledby="suspended-tab" tabindex="0">
                                @include('pages.reseller.table', [
                                    'resellers' => $suspended,
                                    'status' => 'SUSPENDED'
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1"></div>
@endsection

<style>
    .default-table-area .d-flex .bg-transparent[data-bs-toggle="tooltip"] i[style*="color: #EF4923"]:hover {
        color: #D43D1F !important;
    }

    .default-table-area .d-flex .bg-transparent:hover i[style*="color: #EF4923"] {
        color: #D43D1F !important;
    }

    .search-input-group .form-control {
        border: 1px solid #e5e7eb;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
    }

    .search-input-group .form-control:focus {
        border-color: #EF4923;
        box-shadow: 0 0 0 0.2rem rgba(239, 73, 35, 0.25);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltip initialization
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
