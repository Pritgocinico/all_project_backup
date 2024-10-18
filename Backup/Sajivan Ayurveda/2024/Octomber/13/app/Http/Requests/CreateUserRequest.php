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
                'date_of_birth' => 'required|date|before_or_equal:today',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($this->id)->whereNull('deleted_at')
                ],
                'personal_email' => [
                    'required',
                    'string',
                    'email',
                    'max:255'
                ],
                'phone_number' => [
                    'required',
                    'numeric',
                    Rule::unique('users')->ignore($this->id)->whereNull('deleted_at')
                ],
                'office_calling_number.*' => [
                    'nullable',
                    'digits:10',
                    function ($attribute, $value, $fail) {
                        // Ignore the current user's ID while checking the users table
                        $existsInUsers = \DB::table('users')
                            ->where('phone_number', $value)
                            ->whereNull('deleted_at')
                            ->where('id', '!=', $this->id) // Ignore the current user
                            ->exists();

                        // Ignore the current office calling number while checking the office_numbers table
                        $existsInOfficeNumbers = \DB::table('office_numbers')
                            ->where('office_calling_number', $value)
                            ->where('user_id', '!=', $this->id) // Adjust this based on your relation, e.g., 'user_id' should map to the user
                            ->exists();

                        // Fail validation if the number exists in either table
                        if ($existsInUsers || $existsInOfficeNumbers) {
                            $fail("The Office Calling Number has already been taken.");
                        }
                    },
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
            'date_of_birth' => 'required|date|before_or_equal:today',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'personal_email' => [
                    'required',
                    'string',
                    'email',
                    'max:255'
                ],
            'password' => 'required|min:5',
            'phone_number' => [
                'required',
                'numeric',
                Rule::unique('users')->where(function ($query) {
                    return $query->whereNull('deleted_at');
                }),
            ],
            'office_calling_number.*' => [
                'nullable',
                'digits:10',
                function ($attribute, $value, $fail) {
                    // Check in users table (ignore current user when updating)
                    $existsInUsers = \DB::table('users')
                        ->where('phone_number', $value)
                        ->whereNull('deleted_at')
                        ->where('id', '!=', $this->id)  // Ignore current user ID
                        ->exists();

                    // Check in office_numbers table (ignore current office number)
                    $existsInOfficeNumbers = \DB::table('office_numbers')
                        ->where('office_calling_number', $value)
                        ->where('user_id', '!=', $this->id) // Assuming 'user_id' relates to users, change if needed
                        ->exists();

                    // Fail if number exists in either table
                    if ($existsInUsers || $existsInOfficeNumbers) {
                        $fail("The Office Calling Number has already been taken.");
                    }
                },
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
