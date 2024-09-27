<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
            'name'=> 'required',
            'email' =>'nullable|email|unique:users,email,except,id,deleted_at,NULL',
            'phone_number' => 'required|numeric|unique:users,phone_number,except,id,deleted_at,NULL',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'status' => 'required',
            'role' => 'required|exists:role,id',
            'aadhar_card' => 'required',
            'pan_card' => 'required',
            'qualification' => 'required',
            'system_code' => 'required|unique:users,system_code,except,id,deleted_at,NULL',
            'employee_salary' => 'required|numeric',
            'join_date' => 'required',
            'department' => 'required',
            'join_agreement' => 'nullable|mimes:pdf',
        ];
    }
}
