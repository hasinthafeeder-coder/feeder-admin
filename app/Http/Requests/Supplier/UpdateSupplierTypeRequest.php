<?php

namespace App\Http\Requests\Supplier;

use Feeder\Core\Enums\SupplierType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSupplierTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_type' => [
                'required',
                Rule::enum(SupplierType::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_type.required' => 'Supplier type is required.',
            'supplier_type.enum' => 'The selected supplier type is invalid.',
        ];
    }
}
