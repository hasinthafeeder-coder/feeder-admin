<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('stock.view') ?? false;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'market_id' => ['nullable', 'integer', 'exists:markets,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:users,id'],
            'category_id' => ['nullable', 'string', 'exists:product_categories,id'],
            'stock_status' => ['nullable', 'string', Rule::in(['', 'in_stock', 'out_of_stock'])],
        ];
    }
}
