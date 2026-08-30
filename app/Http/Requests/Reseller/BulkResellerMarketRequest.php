<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkResellerMarketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $resellerIds = $this->input('reseller_ids', []);

        if (is_string($resellerIds)) {
            $resellerIds = array_filter(array_map('trim', explode(',', $resellerIds)));
        }

        $this->merge([
            'reseller_ids' => array_values(array_unique((array) $resellerIds)),
        ]);
    }

    public function rules(): array
    {
        return [
            'reseller_ids' => ['required', 'array', 'min:1'],
            'reseller_ids.*' => ['required', 'integer', 'exists:companies,id'],
            'market_id' => [
                'required',
                'string',
                Rule::exists('markets', 'uuid')->where(fn ($query) => $query->where('is_active', true)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'reseller_ids.required' => 'Select at least one reseller.',
            'reseller_ids.min' => 'Select at least one reseller.',
            'market_id.required' => 'Select a market.',
            'market_id.exists' => 'The selected market is invalid or inactive.',
        ];
    }
}
