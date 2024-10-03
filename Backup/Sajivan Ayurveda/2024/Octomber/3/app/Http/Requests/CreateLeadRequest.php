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
            'mobile_number' => 'required',
            'name' => 'required',
            'assigned_to' => 'required',
            'assigned_to.*' => 'exists:users,id',
            'cust_disease' => 'required|exists:diseases,id',
        ];
    }
    public function messages()
    {
        return [
            'cust_disease.required' => 'Please select disease.',
            'cust_disease.exists' => 'Please select valid disease.',
        ];
    }
}
