<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductRequest extends FormRequest
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
        $id = $this->id;
        if ($id == null) {
            return [
                "name"      => "required",
                "sku"       => "unique:products|required",
                "category"  => "required",
                "image"     => "required",
                "price"     => "required",
                "quantity"  => "required",
                "stock"     => "required",
            ];
        } else {
            return [
                'name' => 'required',
                'sku' => [
                    'required',
                    Rule::unique('products')->ignore($this->id)->whereNull('deleted_at'),
                ],
                'category' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'stock' => 'required',
            ];
        }
    }
}
