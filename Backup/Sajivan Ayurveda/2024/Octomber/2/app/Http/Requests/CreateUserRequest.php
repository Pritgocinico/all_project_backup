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
                'department' => 'required|string',
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
                'password' => 'nullable|min:5',
                'role' => 'required|numeric|exists:roles,id',
                'employee_salary' => [
                    'required_if:role,!=,1',
                    'numeric',
                    'min:0'
                ],
                'joining_date' => 'required|before_or_equal:today',
            ];
        }
        return [
            'name' => 'required|string|max:255',
            'department' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'password' => 'required|min:5',
            'phone_number' => [
                'required',
                'numeric',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'role' => 'required|numeric|exists:roles,id',
            'employee_salary' => [
                'required_if:role,!=,1',
                'numeric',
                'min:0'
            ],
            'joining_date' => 'required|before_or_equal:today',
        ];
    }
}
