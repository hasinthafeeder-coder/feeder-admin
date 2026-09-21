<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierCourierAccountRequest;
use App\Http\Requests\Supplier\TestSupplierCourierAccountRequest;
use App\Http\Requests\Supplier\UpdateSupplierCourierAccountRequest;
use Feeder\Core\Models\Courier;
use Feeder\Core\Models\CourierCity;
use Feeder\Core\Models\CourierState;
use Feeder\Core\Models\User;
use Feeder\Core\Services\Courier\CourierConnectionService;
use Feeder\Core\Services\Courier\CourierCredentialSchemaRegistry;
use Feeder\Core\Services\Courier\Curfox\RoyalCourierAccountSetupService;
use Feeder\Core\Services\Courier\SupplierCourierAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierCourierAccountController extends Controller
{
    public function __construct(
        private readonly SupplierCourierAccountService $accountService,
        private readonly CourierConnectionService $connectionService,
        private readonly CourierCredentialSchemaRegistry $schemaRegistry,
        private readonly RoyalCourierAccountSetupService $royalSetupService,
    ) {
    }

    public function create(User $user): View
    {
        $supplier = $this->requireSupplier($user);
        $availableCouriers = $this->accountService->availableCouriersForSupplier($supplier);

        return view('pages.supplier.courier-accounts.form', [
            'supplier' => $supplier,
            'account' => null,
            'presented' => null,
            'availableCouriers' => $availableCouriers,
            'schemaFieldsByCourier' => $this->schemaFieldsByCourier($availableCouriers),
            'royalLocations' => $this->royalLocationsPayload($availableCouriers),
            'mode' => 'create',
        ]);
    }

    public function store(StoreSupplierCourierAccountRequest $request, User $user): RedirectResponse
    {
        $supplier = $this->requireSupplier($user);
        $validated = $request->validated();
        $courier = Courier::query()->active()->where('uuid', $validated['courier_uuid'])->firstOrFail();

        if (strtoupper((string) $courier->code) === 'ROYAL') {
            $account = $this->royalSetupService->create(
                $supplier,
                [
                    'courier_uuid' => $validated['courier_uuid'],
                    'account_label' => $validated['account_label'],
                    'credentials' => $validated['credentials'] ?? [],
                    'meta' => $validated['meta'] ?? [],
                    'is_default' => (bool) ($validated['is_default'] ?? false),
                    'is_active' => true,
                ],
                (int) $request->user()->id,
            );

            return redirect()
                ->route('suppliers.show', $supplier)
                ->with('success', 'ROYAL courier account tested and saved successfully.')
                ->with('courier_account_uuid', $account->uuid);
        }

        $account = $this->accountService->create(
            $supplier,
            [
                'courier_uuid' => $validated['courier_uuid'],
                'account_label' => $validated['account_label'],
                'credentials' => $validated['credentials'] ?? [],
                'is_default' => (bool) ($validated['is_default'] ?? false),
                'is_active' => true,
            ],
            (int) $request->user()->id,
        );

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Courier account created successfully.')
            ->with('courier_account_uuid', $account->uuid);
    }

    public function edit(User $user, string $courierAccount): View
    {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $couriers = collect([$account->courier])->filter();

        return view('pages.supplier.courier-accounts.form', [
            'supplier' => $supplier,
            'account' => $account,
            'presented' => $this->accountService->presentAccount($account),
            'availableCouriers' => $couriers,
            'schemaFieldsByCourier' => $this->schemaFieldsByCourier($couriers),
            'royalLocations' => $this->royalLocationsPayload($couriers),
            'mode' => 'edit',
        ]);
    }

    public function update(
        UpdateSupplierCourierAccountRequest $request,
        User $user,
        string $courierAccount,
    ): RedirectResponse {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $validated = $request->validated();

        if (strtoupper((string) ($account->courier?->code ?? '')) === 'ROYAL') {
            $this->royalSetupService->update(
                $account,
                [
                    'account_label' => $validated['account_label'],
                    'credentials' => $validated['credentials'] ?? [],
                    'meta' => $validated['meta'] ?? [],
                    'is_default' => (bool) ($validated['is_default'] ?? false),
                ],
                (int) $request->user()->id,
            );

            return redirect()
                ->route('suppliers.show', $supplier)
                ->with('success', 'ROYAL courier account tested and saved successfully.');
        }

        $this->accountService->update(
            $account,
            [
                'account_label' => $validated['account_label'],
                'credentials' => $validated['credentials'] ?? [],
                'is_default' => (bool) ($validated['is_default'] ?? false),
            ],
            (int) $request->user()->id,
        );

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Courier account updated successfully.');
    }

    public function activate(Request $request, User $user, string $courierAccount): RedirectResponse
    {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $this->accountService->activate($account, (int) $request->user()->id);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Courier account activated.');
    }

    public function deactivate(Request $request, User $user, string $courierAccount): RedirectResponse
    {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $this->accountService->deactivate($account, (int) $request->user()->id);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Courier account deactivated.');
    }

    public function setDefault(Request $request, User $user, string $courierAccount): RedirectResponse
    {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $this->accountService->setDefault($account, (int) $request->user()->id);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Default courier updated.');
    }

    public function clearDefault(Request $request, User $user, string $courierAccount): RedirectResponse
    {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $this->accountService->clearDefault($account, (int) $request->user()->id);

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with('success', 'Default courier cleared.');
    }

    public function test(
        TestSupplierCourierAccountRequest $request,
        User $user,
        string $courierAccount,
    ): RedirectResponse {
        $supplier = $this->requireSupplier($user);
        $account = $this->accountService->requireForSupplier($supplier, $courierAccount);
        $result = $this->connectionService->testAccount($account);

        $flashKey = $result->successful ? 'success' : 'error';

        return redirect()
            ->route('suppliers.show', $supplier)
            ->with($flashKey, $result->message);
    }

    private function requireSupplier(User $user): User
    {
        $user->loadMissing('company.portal');

        abort_unless($user->isSupplier(), 404);

        return $user;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \Feeder\Core\Models\Courier|null>  $couriers
     * @return array<string, list<array<string, mixed>>>
     */
    private function schemaFieldsByCourier($couriers): array
    {
        $map = [];

        foreach ($couriers as $courier) {
            if ($courier === null) {
                continue;
            }

            $map[(string) $courier->uuid] = $this->schemaRegistry->fieldDefinitionsForCourier($courier);
        }

        return $map;
    }

    /**
     * @param  \Illuminate\Support\Collection<int, \Feeder\Core\Models\Courier|null>  $couriers
     * @return array{
     *     courier_uuids: list<string>,
     *     states: list<array{id: int, name: string}>,
     *     cities_by_state: array<string, list<array{id: int, name: string}>>
     * }
     */
    private function royalLocationsPayload($couriers): array
    {
        $royal = $couriers->first(
            static fn ($courier) => $courier !== null && strtoupper((string) $courier->code) === 'ROYAL'
        );

        if ($royal === null) {
            $royal = Courier::query()->active()->where('code', 'ROYAL')->first();
        }

        if ($royal === null) {
            return [
                'courier_uuids' => [],
                'states' => [],
                'cities_by_state' => [],
            ];
        }

        $states = CourierState::query()
            ->where('courier_id', $royal->id)
            ->active()
            ->orderBy('name')
            ->get(['id', 'name']);

        $citiesByState = [];

        foreach (
            CourierCity::query()
                ->where('courier_id', $royal->id)
                ->whereNotNull('courier_state_id')
                ->active()
                ->orderBy('city_name')
                ->get(['id', 'courier_state_id', 'city_name', 'name']) as $city
        ) {
            $stateKey = (string) $city->courier_state_id;
            $citiesByState[$stateKey] ??= [];
            $citiesByState[$stateKey][] = [
                'id' => (int) $city->id,
                'name' => (string) ($city->city_name ?: $city->name),
            ];
        }

        return [
            'courier_uuids' => [(string) $royal->uuid],
            'states' => $states
                ->map(static fn (CourierState $state) => [
                    'id' => (int) $state->id,
                    'name' => (string) $state->name,
                ])
                ->values()
                ->all(),
            'cities_by_state' => $citiesByState,
        ];
    }
}