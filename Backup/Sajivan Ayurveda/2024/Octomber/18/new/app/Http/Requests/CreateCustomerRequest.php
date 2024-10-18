<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCustomerRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'name'          => 'required',
    //         'mobile_number' => [
    //             'required',
    //             'numeric',
    //             'digits:10',
    //             'regex:/^[0-9]+$/',
    //             function ($attribute, $value, $fail) {
    //                 // Check if mobile number exists in the 'customers' table
    //                 $existsInCustomers = \DB::table('customers')
    //                     ->where('mobile_number', $value)
    //                     ->whereNull('deleted_at') // Ensure soft deleted entries are ignored
    //                     ->exists();
                    
    //                 // Check if mobile number exists in the 'cust_alternate_numbers' table
    //                 $existsInAltNumbers = \DB::table('cust_alternate_numbers')
    //                     ->where('cust_alt_num', $value)
    //                     ->whereNull('deleted_at')
    //                     ->exists();

    //                 if ($existsInCustomers || $existsInAltNumbers) {
    //                     $fail('The phone number has already been taken.');
    //                 }
    //             },
    //         ],
    //         'cust_alt_num.*' => [
    //             'nullable', // If it's not required, make it nullable
    //             'digits:10',
    //             'regex:/^[0-9]+$/',
    //             function ($attribute, $value, $fail) {
    //                 // Check if alternate number exists in the 'customers' table
    //                 $existsInCustomers = \DB::table('customers')
    //                     ->where('mobile_number', $value)
    //                     ->whereNull('deleted_at')
    //                     ->exists();
                    
    //                 // Check if alternate number exists in the 'cust_alternate_numbers' table
    //                 $existsInAltNumbers = \DB::table('cust_alternate_numbers')
    //                     ->where('cust_alt_num', $value)
    //                     ->whereNull('deleted_at')
    //                     ->exists();

    //                 if ($existsInCustomers || $existsInAltNumbers) {
    //                     $fail('The alternate number has already been taken.');
    //                 }
    //             },
    //         ],
    //         'cust_disease' => 'required'
    //     ];
    // }
    public function rules(): array
    {
        // Assuming $this->id is the ID of the customer being updated
        $customerId = $this->id;

        return [
            'name' => 'required',
            
            'mobile_number' => [
                'required',
                'numeric',
                'digits:10',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) use ($customerId) {
                    // Check if mobile number exists in the 'customers' table
                    $existsInCustomers = \DB::table('customers')
                        ->where('mobile_number', $value)
                        ->where('id', '!=', $customerId) // Exclude current customer
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
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) use ($customerId) {
                    
                    // Check if alternate number exists in the 'customers' table
                    $existsInCustomers = \DB::table('customers')
                        ->where('mobile_number', $value)
                        ->where('id', '!=', $customerId) // Exclude current customer
                        ->whereNull('deleted_at')
                        ->exists();

                    // Check if alternate number exists in the 'cust_alternate_numbers' table
                    $existsInAltNumbers = \DB::table('cust_alternate_numbers')
                        ->where('cust_alt_num', $value)
                        ->where('customer_id', '!=', $customerId)
                        ->whereNull('deleted_at')
                        ->exists();
                    if ($existsInCustomers || $existsInAltNumbers) {
                        $fail('The alternate number has already been taken.');
                    }
                },
            ],
            
            'cust_disease' => 'required'
        ];
    }


    // public function messages()
    // {
    //     return [
    //         'add_type.*.required' => 'The address type field is required.',
    //         'address.*.required' => 'The address field is required.',
    //         'pin_code.*.required' => 'The pin code field is required.',
    //         'village.*.required' => 'The village field is required.',
    //         'office_name.*.required' => 'The post office field is required.',
    //         'dist_city.*.required' => 'The city field is required.',
    //         'dist_state.*.required' => 'The state field is required.',
    //     ];
    // }
}
