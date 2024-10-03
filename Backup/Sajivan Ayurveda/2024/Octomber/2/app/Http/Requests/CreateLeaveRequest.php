<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveRequest extends FormRequest
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
        if(Auth()->user()->role_id != 1){
            return [
                'leave_type' => 'required',
                'leave_from' => 'required|date|after_or_equal:'.date('Y-m-d'),
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'leave_feature' => 'required',
                'leave_reason' => 'required',
                'leave_on' => [
                        'required_if:leave_feature,==,0'
                    ],
                'other_reason' => [
                        'required_if:leave_reason,==,other'
                    ],
            ];
        }else{
            return [
                'employee' => 'required',
                'leave_type' => 'required',
                'leave_from' => 'required|date|after_or_equal:'.date('Y-m-d'),
                'leave_to' => 'required|date|after_or_equal:leave_from',
                'leave_feature' => 'required',
                'leave_reason' => 'required',
                'leave_on' => [
                        'required_if:leave_feature,==,0'
                    ],
                'other_reason' => [
                        'required_if:leave_reason,==,other'
                    ],
            ];
        }
        
    }
}
