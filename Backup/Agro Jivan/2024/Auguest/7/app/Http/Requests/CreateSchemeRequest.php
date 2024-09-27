<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSchemeRequest extends FormRequest
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
            'scheme_code' => 'required|unique:discount,discount_code,except,id,deleted_at,NULL',
            'product' => 'required|array',
            'product.*' => 'required|exists:product_variant,id',
            'free_product' => 'required|array',
            'free_product.*' => 'required|exists:product,id',
            'free_product.*' => 'required|exists:product_variant,id',
        ];
    }
}
