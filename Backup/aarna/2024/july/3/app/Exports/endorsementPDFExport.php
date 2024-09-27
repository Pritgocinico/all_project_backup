<?php

namespace App\Exports;

use App\Models\Endorsement;
use App\Models\Company;
use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class endorsementPDFExport implements FromCollection
{
    public function __construct($data) {
        $this->endorsement = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $i = 0;
        foreach($this->endorsement as $source){
            $company = Company::where('id',$source->policy->company)->first();
            $customer = Customer::where('id',$source->policy->customer)->first();
            $i++;
            $records[] = array(
                $i,
                date('d-m-Y',strtotime($source->created_at)),
                $company->name,
                $source->details,
                $source->policy->policy_no,
                $customer->name,
            );
        }
        return collect($records);
        // return Endorsement::all();
    }
    public function headings(): array
    {
        return [
            '#',
            'Endorsement',
            'Company Name',
            'Endorsement Details',
            'Policy No',
            'Customer Name'
        ];
    }
}
