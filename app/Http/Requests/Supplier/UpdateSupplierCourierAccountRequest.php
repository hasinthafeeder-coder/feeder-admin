<?php



namespace App\Http\Requests\Supplier;



use Illuminate\Foundation\Http\FormRequest;



class UpdateSupplierCourierAccountRequest extends FormRequest

{

    public function authorize(): bool

    {

        return true;

    }



    /**

     * @return array<string, mixed>

     */

    public function rules(): array

    {

        return [

            'account_label' => ['required', 'string', 'max:255'],

            'credentials' => ['nullable', 'array'],

            'credentials.*' => ['nullable', 'string', 'max:2000'],

            'meta' => ['nullable', 'array'],

            'meta.merchant_business_id' => ['nullable', 'string', 'max:100'],

            'meta.origin_state_id' => ['nullable', 'integer', 'min:1'],

            'meta.origin_city_id' => ['nullable', 'integer', 'min:1'],

            'is_default' => ['sometimes', 'boolean'],

        ];

    }



    protected function prepareForValidation(): void

    {

        $this->merge([

            'is_default' => $this->boolean('is_default'),

        ]);

    }

}


