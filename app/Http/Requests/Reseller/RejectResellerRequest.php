<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Foundation\Http\FormRequest;

class RejectResellerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
