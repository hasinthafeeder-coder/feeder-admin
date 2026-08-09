<?php

namespace App\Services\Company;

use Feeder\Core\Models\CompanyBankAccount;
use Feeder\Core\Models\Company;
use Feeder\Core\Services\UuidService;

class CompanyBankAccountService
{
    public function create(Company $company, array $data): CompanyBankAccount
    {
        return $company->bankAccounts()->create([
            'uuid' => UuidService::generate(),
            'account_name'   => $data['account_name'],
            'account_number' => $data['account_number'],
            'bank_name'      => $data['bank_name'],
            'branch_name'    => $data['branch_name'],
            'bank_code'      => $data['bank_code'],
            'branch_code'    => $data['branch_code'],
        ]);
    }

    public function update(CompanyBankAccount $bankAccount, array $data): bool
    {
        return $bankAccount->update($data);
    }

    public function delete(CompanyBankAccount $bankAccount): bool
    {
        return $bankAccount->delete();
    }
}
