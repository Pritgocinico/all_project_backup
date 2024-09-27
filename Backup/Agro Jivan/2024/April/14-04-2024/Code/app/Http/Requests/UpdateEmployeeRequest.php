<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
            'email' => [
                'required',
                Rule::unique('users')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'phone_number' => [
                'required',
                Rule::unique('users')->ignore($this->id)->whereNull('deleted_at'),
            ],
            'password' => ['nullable','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'role' => ['required','exists:role,id'],
            'system_code' => [
                'required',
                Rule::unique('users')->ignore($this->id)->whereNull('deleted_at'),
            ],
        ];
    }
}
