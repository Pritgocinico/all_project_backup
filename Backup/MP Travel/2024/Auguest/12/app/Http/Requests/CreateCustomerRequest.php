<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'name' => 'required',
            'insurance' => 'required',
            'customer_department' => 'required',
            'email' => 'required|email',
            'mobile_number' => ['required', 'numeric',],
            'birth_date' => 'required|date',
            'pan_card_number' => 'required|numeric',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pin_code' => 'required',
        ];
    }
}
