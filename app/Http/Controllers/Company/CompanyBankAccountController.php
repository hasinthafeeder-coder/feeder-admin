<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\CompanyBankAccountService;
use App\Http\Requests\Company\StoreCompanyBankAccountRequest;
use Feeder\Core\Models\Company;
use Illuminate\Http\Request;
use Feeder\Core\Models\CompanyBankAccount;

class CompanyBankAccountController extends Controller
{
    public function __construct(protected CompanyBankAccountService $bankAccountService) {}

    public function store(
        StoreCompanyBankAccountRequest $request,
        Company $company
    ) {
        $this->bankAccountService->create(
            $company,
            $request->validated()
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'Bank account added successfully.'
            );
    }

    public function update()
    {
        // Logic to update an existing company bank account
    }

    public function destroy(
        CompanyBankAccount $bankAccount
    ) {
        $this->bankAccountService->delete($bankAccount);

        return back()->with(
            'success',
            'Bank account deleted successfully.'
        );
    }
}
