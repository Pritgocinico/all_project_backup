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
            'insurance_type' => 'required',
            'email' => 'required|email',
            'password'  => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'mobile_number' => [
                'required', 'numeric',
                function ($attribute, $value, $fail) {
                    if (
                        User::where('phone_number', $value)->whereNull('deleted_at')->exists() ||
                        Customer::where('mobile_number', $value)->whereNull('deleted_at')->exists()
                    ) {
                        $fail('The phone number has already been taken.');
                    }
                },
            ],
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'pan_card_number' => 'required|numeric',
            'aadhaar_number' => 'required|numeric',
            'address' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'pin_code' => 'required',
            'account_number' => 'nullable|numeric',
            'card_number' => 'nullable|numeric',
            'card_month' => 'nullable|numeric',
            'card_year' => 'nullable|numeric',
            'card_cvv' => 'nullable|numeric',
        ];
    }
}
