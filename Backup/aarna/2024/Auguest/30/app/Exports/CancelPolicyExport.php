<?php

namespace App\Exports;

use App\Models\Policy;
use App\Models\Customer;
use App\Models\SourcingAgent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class CancelPolicyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(String $start_date, String $end_date) {

        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $policies = Policy::with('customers')->where('status',2)->whereBetween('cancel_date', [$this->start_date, $this->end_date])->get();
        $records = [];
        $i = 0;
        foreach($policies as $policy){
            $i++;
            if($policy->insurance_type == 1){
                if($policy->business_type == 1){
                    $type = 'New';
                }elseif($policy->business_type == 2){
                    $type = 'Renewal';
                }elseif($policy->business_type == 3){
                    $type = 'Rollover';
                }elseif ($policy->business_type == 4){
                    $type = 'Used';
                }
            }else{
                if($policy->business_type == 1){
                    $type = 'New';
                }elseif($policy->business_type == 2){
                    $type = 'Renewal';
                }elseif($policy->business_type == 3){
                    $type = 'Portability';
                }
            }
            $records[] = [
                $i,
                $type,
                $policy->risk_start_date,
                $policy->risk_end_date,
                $policy->policy_no,
                $policy->customers->name,
                $policy->net_premium_amount
            ];
        }
        return collect($records);
    }
    public function headings(): array
    {
        return [
            'Sr.',
            'LOB',
            'Start Date',
            'End Date',
            'Policy No',
            'Customer Name',
            'Net Premium'
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
