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
            return [
                "medicine_type"      => "required",
                'powder_unit' => [
                    'required_if:medicine_type,==,powder'
                ],
                'tablet_unit' => [
                    'required_if:medicine_type,==,tablet'
                ],
                'capsule_unit' => [
                    'required_if:medicine_type,==,capsule'
                ],
                "name"      => "required",
                "hsm_sac"      => "required",
                "batch_number"      => "required",
                "price"     => "required",
                "mfg_lic_number"     => "required",
                "mfg_date"     => "required",
                "stock"     => "required",
            ];
    }
}
