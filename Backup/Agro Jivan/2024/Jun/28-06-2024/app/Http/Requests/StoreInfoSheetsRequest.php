<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInfoSheetsRequest extends FormRequest
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
        if($this->id == null){    
            return [
                'title' =>'required',
                'info_sheet' =>'required',
                'description' =>'required',
            ];
        }
        return [
            'title' =>'required',
            'description' =>'required',
        ];
    }
}
