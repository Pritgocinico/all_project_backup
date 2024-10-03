<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePushLeadRequest extends FormRequest
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
        if($this->customer_id){
            return [
                'customer_id' => 'required',
                'assigned_to' => 'required',
            ];
        }else{
            return [
                'customer_number' => 'required',
                'assigned_to' => 'required',
                'name' => 'required',
                'disease' => 'required'
            ];
        }
    }
}
