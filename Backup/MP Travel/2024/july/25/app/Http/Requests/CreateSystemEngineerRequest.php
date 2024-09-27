<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSystemEngineerRequest extends FormRequest
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
            'email' =>'nullable|email|unique:users,email,except,id',
            'phone_number' => 'required|numeric|unique:users,phone_number,except,id',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'status' => 'required',
            'role' => 'required|exists:role,id',
        ];
    }
}
