<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Disease;
use App\Models\Remark;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Validation\ValidationException;

class BulkImportData implements ToModel, WithHeadingRow
{
    protected $importedLeads = [];
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $requiredKeys = ['mobile_number', 'name', 'disease', 'language'];

        // Check if all required keys exist in the row
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $row)) {
                throw ValidationException::withMessages([
                    'missing_parameter' => "Missing required parameter: $key",
                ]);
            }
        }
        $disease = $row['disease'];
        $disease = Disease::where('name', $disease)->first();
        if ($disease) {
            $diseaseId = $disease->id;
        } else {
            $diseaseData = new Disease();
            $diseaseData->name = $disease;
            $diseaseData->save();
            $diseaseId = $diseaseData->id;
        }
        $lastId =  Lead::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $leadID = 'SA-LEAD-' . substr("00000{$lastId}", -6);
        $custLastId =  Customer::all()->last() ? Customer::all()->last()->id + 1 : 1;
        $customerId = 'SA-CUS-' . substr("00000{$custLastId}", -6);
        $number = str_replace(['-', ' ', '(', ')'], '', $row['mobile_number']);
        $customer = Customer::where('mobile_number', $number)->orWhereHas('getAlternativeNumber', function ($query) use ($number) {
            $query->where('cust_alt_num', $number);
        })->first();
        if ($customer) {
            $lead = Lead::where('customer_id', $customer->id)->first();
            if ($lead) {
                    $newLead = $lead;
            } else {
                $newLead = new Lead();
                $newLead->lead_id = $leadID;
                $newLead->customer_id = $customer->id;
                $newLead->created_by = Auth()->user()->id;
                $newLead->customer_language = $row['language'];
                $newLead->save();
                $remark = new Remark();
                $remark->lead_id = $newLead->id;
                $remark->title = 'New Lead';
                $remark->remark = "New Lead created.";
                $remark->created_by = Auth()->user()->id;
                $remark->remark_icon = 'fa-solid fa-database';
                $remark->save();
                $data[$newLead->id] = $newLead;
            }
        } else {
            $newCustomer = new Customer();
            $newCustomer->name = $row['name'];
            $newCustomer->mobile_number = (String) $number;
            $newCustomer->cust_disease = $diseaseId;
            $newCustomer->customer_id = $customerId;
            $newCustomer->role_id = 3;
            $newCustomer->save();
            if ($newCustomer) {
                $newLead = new Lead();
                $newLead->lead_id = $leadID;
                $newLead->customer_id = $newCustomer->id;
                $newLead->customer_language = $row['language'];
                $newLead->created_by = Auth()->user()->id;
                $newLead->save();
                $remark = new Remark();
                $remark->lead_id = $newLead->id;
                $remark->title = 'New Lead';
                $remark->remark = "New Lead created.";
                $remark->created_by = Auth()->user()->id;
                $remark->remark_icon = 'fa-solid fa-database';
                $remark->save();
                $data[$newLead->id] = $newLead;
            }
        }
        $this->importedLeads[] = $newLead; // Store lead in the array
        return $newLead;
    }

    public function getImportedLeads()
    {
        return $this->importedLeads;
    }
}
