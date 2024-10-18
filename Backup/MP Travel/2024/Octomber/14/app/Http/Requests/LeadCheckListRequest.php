<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadCheckListRequest extends FormRequest
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
            'lead_type' => 'required',
            'name' => 'required|array',
            'name.*' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'lead_type.required' => 'The lead type field is required.',
            'name.required' => 'The name field is required.',
            'name.*.required' => 'The name field is required.',
        ];
    }   
}
