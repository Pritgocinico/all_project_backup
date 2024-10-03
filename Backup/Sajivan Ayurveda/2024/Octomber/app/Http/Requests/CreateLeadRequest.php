<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeadRequest extends FormRequest
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
        // dd($this->customer_type);
        if($this->lead_type == "New Lead"){
            return [
                'name'          => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    function ($attribute, $value, $fail) {
                        // Check if mobile number exists in the 'customers' table
                        $existsInCustomers = \DB::table('customers')
                            ->where('mobile_number', $value)
                            ->whereNull('deleted_at') // Ensure soft deleted entries are ignored
                            ->exists();
                        
                        // Check if mobile number exists in the 'cust_alternate_numbers' table
                        $existsInAltNumbers = \DB::table('cust_alternate_numbers')
                            ->where('cust_alt_num', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsInCustomers || $existsInAltNumbers) {
                            $fail('The phone number has already been taken.');
                        }
                    },
                ],
                'cust_alt_num.*' => [
                    'nullable', // If it's not required, make it nullable
                    'digits:10',
                    function ($attribute, $value, $fail) {
                        // Check if alternate number exists in the 'customers' table
                        $existsInCustomers = \DB::table('customers')
                            ->where('mobile_number', $value)
                            ->whereNull('deleted_at')
                            ->exists();
                        
                        // Check if alternate number exists in the 'cust_alternate_numbers' table
                        $existsInAltNumbers = \DB::table('cust_alternate_numbers')
                            ->where('cust_alt_num', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsInCustomers || $existsInAltNumbers) {
                            $fail('The alternate number has already been taken.');
                        }
                    },
                ],
                'lead_type' => 'required',
                'other_lead_source' => $this->lead_source == 'Other' ? 'required' : 'nullable',
                'cust_disease' => 'required',
                'assigned_to' => 'required',
            ];
        }else if($this->lead_type == "Referance Lead"){
            return [
                'name'          => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    function ($attribute, $value, $fail) {
                        // Check if mobile number exists in the 'customers' table
                        $existsInCustomers = \DB::table('customers')
                            ->where('mobile_number', $value)
                            ->whereNull('deleted_at') // Ensure soft deleted entries are ignored
                            ->exists();
                        
                        // Check if mobile number exists in the 'cust_alternate_numbers' table
                        $existsInAltNumbers = \DB::table('cust_alternate_numbers')
                            ->where('cust_alt_num', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsInCustomers || $existsInAltNumbers) {
                            $fail('The phone number has already been taken.');
                        }
                    },
                ],
                'cust_alt_num.*' => [
                    'nullable', // If it's not required, make it nullable
                    'digits:10',
                    function ($attribute, $value, $fail) {
                        // Check if alternate number exists in the 'customers' table
                        $existsInCustomers = \DB::table('customers')
                            ->where('mobile_number', $value)
                            ->whereNull('deleted_at')
                            ->exists();
                        
                        // Check if alternate number exists in the 'cust_alternate_numbers' table
                        $existsInAltNumbers = \DB::table('cust_alternate_numbers')
                            ->where('cust_alt_num', $value)
                            ->whereNull('deleted_at')
                            ->exists();

                        if ($existsInCustomers || $existsInAltNumbers) {
                            $fail('The alternate number has already been taken.');
                        }
                    },
                ],
                'lead_type' => 'required',
                'reference_name' => 'required',
                'other_lead_source' => $this->lead_source == 'Other' ? 'required' : 'nullable',
                'cust_disease' => 'required',
                'assigned_to' => 'required',
            ];
        }else if($this->lead_type == "Resale Lead"){
            return[
                'mobile_number' => 'required',
                'lead_type' => 'required',
                'other_lead_source' => $this->lead_source == 'Other' ? 'required' : 'nullable',
                'assigned_to' => 'required',
            ];
        }else{
            return[
                'mobile_number' => 'required',
                'lead_type' => 'required',
            ];
        }
    }
    public function messages()
    {
        return [
            'other_lead_source.required' => 'Please enter other lead source name.',
        ];
    }
}
