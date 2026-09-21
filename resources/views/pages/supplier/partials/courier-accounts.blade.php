<?php
/**
 * @var \Feeder\Core\Models\User $supplier
 * @var list<array<string, mixed>> $courierAccounts
 */
$courierAccounts = $courierAccounts ?? ($supplier->courier_accounts_presented ?? []);
?>

<div class="card bg-white rounded-10 border border-white mb-4" id="courier-accounts">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
        <div>
            <h3 class="mb-1">Courier Accounts</h3>
            <p class="text-muted mb-0 fs-14">Supplier-specific courier credentials. Secrets are never displayed after saving.</p>
        </div>
        @can('suppliers.courier_accounts.create')
            @if ($supplier->status === \Feeder\Core\Enums\UserStatus::ACTIVE)
                <a href="{{ route('suppliers.courier-accounts.create', $supplier) }}" class="btn btn-primary text-white">
                    + Add Courier Account
                </a>
            @endif
        @endcan
    </div>

    <div class="default-table-area mx-minus-1 table-all-projects">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col" class="fw-medium">Courier</th>
                        <th scope="col" class="fw-medium">Account</th>
                        <th scope="col" class="fw-medium">Identifier</th>
                        <th scope="col" class="fw-medium">Status</th>
                        <th scope="col" class="fw-medium text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courierAccounts as $account)
                        <tr>
                            <td>
                                <div class="fw-medium">{{ $account['courier']['name'] ?? 'Unknown' }}</div>
                                <div class="text-muted fs-13">{{ $account['courier']['code'] ?? '' }}</div>
                            </td>
                            <td>{{ $account['account_label'] }}</td>
                            <td class="text-muted">
                                <div @class(['text-warning' => ! empty($account['credentials_unreadable'])])>
                                    {{ $account['mask_summary'] ?? '—' }}
                                </div>
                                @if (! empty($account['meta']['merchant_business_id']))
                                    <div class="fs-13">MBID: {{ $account['meta']['merchant_business_id'] }}</div>
                                @endif
                                @if (! empty($account['meta']['origin_city_name']))
                                    <div class="fs-13">
                                        Origin: {{ $account['meta']['origin_city_name'] }}
                                        @if (! empty($account['meta']['origin_state_name']))
                                            / {{ $account['meta']['origin_state_name'] }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if ($account['is_active'])
                                    <span class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                @else
                                    <span class="text-secondary bg-secondary bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center flex-nowrap gap-2">
                                    @can('suppliers.courier_accounts.update')
                                        <a href="{{ route('suppliers.courier-accounts.edit', [$supplier, $account['uuid']]) }}"
                                            class="btn btn-sm btn-outline-secondary text-nowrap">Edit</a>
                                    @endcan

                                    @can('suppliers.courier_accounts.activate')
                                        @if ($account['is_active'])
                                            <form action="{{ route('suppliers.courier-accounts.deactivate', [$supplier, $account['uuid']]) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning text-nowrap"
                                                    onclick="return confirm('Disable this courier account?')">Disable</button>
                                            </form>
                                        @else
                                            <form action="{{ route('suppliers.courier-accounts.activate', [$supplier, $account['uuid']]) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success text-nowrap">Enable</button>
                                            </form>
                                        @endif
                                    @endcan

                                    @can('suppliers.courier_accounts.test')
                                        @if ($account['is_active'])
                                            <form action="{{ route('suppliers.courier-accounts.test', [$supplier, $account['uuid']]) }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-info text-nowrap">Test Connection</button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No courier accounts configured for this supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
