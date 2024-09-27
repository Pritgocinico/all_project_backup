<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
        if ($this->id) {
            return [
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($this->id)->whereNull('deleted_at')
                ],
                'phone_number' => [
                    'required',
                    'numeric',
                    Rule::unique('users')->ignore($this->id)->whereNull('deleted_at')
                ],
                'password' => 'nullable|min:8',
                'confirm_password' => 'nullable|same:password',
                'role' => 'required|numeric|exists:roles,id',
                'profile_image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            ];
        }
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,deleted_at',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'phone_number' => 'required|numeric|unique:users,phone_number,NULL,deleted_at',
            'role' => 'required|numeric|exists:roles,id',
            'profile_image' => 'required|image|max:2048',
        ];
    }
}
