<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Validation\Rule;

class BulkRevokeResellerMarketRequest extends BulkResellerMarketRequest
{
    public function rules(): array
    {
        return [
            'reseller_ids' => ['required', 'array', 'min:1'],
            'reseller_ids.*' => ['required', 'integer', 'exists:companies,id'],
            'market_id' => [
                'required',
                'string',
                Rule::exists('markets', 'uuid'),
            ],
        ];
    }
}
