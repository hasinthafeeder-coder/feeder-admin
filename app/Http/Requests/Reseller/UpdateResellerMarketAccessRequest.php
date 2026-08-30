<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResellerMarketAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'home_country_id' => [
                'required',
                'string',
                Rule::exists('countries', 'uuid')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'allowed_market_ids' => ['required', 'array', 'min:1'],
            'allowed_market_ids.*' => [
                'required',
                'string',
                Rule::exists('markets', 'uuid')->where(fn ($query) => $query->where('is_active', true)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'home_country_id.required' => 'Home country is required.',
            'home_country_id.exists' => 'The selected home country is invalid or inactive.',
            'allowed_market_ids.required' => 'At least one allowed market is required.',
            'allowed_market_ids.min' => 'At least one allowed market is required.',
            'allowed_market_ids.*.exists' => 'One or more selected markets are invalid or inactive.',
        ];
    }
}
