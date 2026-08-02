<?php

namespace App\Http\Requests\Reseller;

use Illuminate\Foundation\Http\FormRequest;

class ApproveResellerRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }
}
