<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_name' => ['required', 'string', 'max:150'],
            'account_number' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:150'],
            'branch_name' => ['required', 'string', 'max:150'],
            'bank_code' => ['nullable', 'string', 'max:30'],
            'branch_code' => ['nullable', 'string', 'max:30'],
        ];
    }
}
