<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
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
            'logo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'fa_icon' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'profile_image' => 'nullable|mimes:png,jpg,jpeg|max:2048',
            'site_url' => 'nullable|url',
        ];
    }
}
