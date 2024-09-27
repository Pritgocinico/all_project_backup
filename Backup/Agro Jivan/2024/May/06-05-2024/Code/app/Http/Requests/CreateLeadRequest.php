<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name'            => 'required',
            'phoneno'                 => 'required',
            'address'                  => 'required',
            'state'                    => 'required|not_in:0',
            'district'                 => 'required|not_in:0',
            'sub_district'             => 'required|not_in:0',
            'village'                  => 'required|not_in:0',
            'product_data'             => 'required|string',
            'pincode'                  => 'required',
        ];
    }
}
