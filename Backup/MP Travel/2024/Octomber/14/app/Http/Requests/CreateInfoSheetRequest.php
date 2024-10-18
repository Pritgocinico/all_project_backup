<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInfoSheetRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
        ];

        if ($this->isMethod('post')) {
            $rules['info_sheet'] = 'required|file|max:2048';
        } else if ($this->isMethod('put')) {
            $rules['info_sheet'] = 'nullable|file|max:2048';
        }

        return $rules;
    }
}
