<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Foundation\Http\FormRequest;

class AssignResellerSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('resellers.suppliers.assign') ?? false;
    }

    public function rules(): array
    {
        return [
            'supplier' => ['required', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier.required' => 'Please select a supplier.',
        ];
    }
}
