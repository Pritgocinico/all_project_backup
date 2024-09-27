<?php

namespace App\Exports;

use App\Models\Claim;
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

class claimCSVExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct($data) {
        $this->claim = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Endorsement::all();
        $i = 0;
        $records = [];
        foreach($this->claim as $source){
            $company = Company::where('id',$source->policy->company)->first();
            $customer = Customer::where('id',$source->policy->customer)->first();
            if($source->claim_type == 1){
                $claim_type = 'OWN DAMAGE';
            }else{
                $claim_type = 'THIRD PARTY';
            }
            $i++;
            $records[] = array(
                $i,
                $source->claim_no,
                date('d-m-Y',strtotime($source->created_at)),
                $company->name,
                $claim_type,
                $source->policy->policy_no,
                $customer->name,
            );
        }
        return collect($records);
    }
    public function headings(): array
    {
        return [
            '#',
            'Claim No',
            'Claim Date',
            'Company Name',
            'Claim Type',
            'Policy No',
            'Customer Name'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
