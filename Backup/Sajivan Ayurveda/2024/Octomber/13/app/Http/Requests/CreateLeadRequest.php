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
            'lead_source' => 'required',
            'other_lead_source' => 'required_if:lead_source,Other',
            'other_cust_disease' => 'required_if:cust_disease,other',
            'assigned_to.*' => 'exists:users,id',
            'cust_disease' => 'required',
        ];
    }
}
