<?php

namespace App\Exports;

use App\Models\Policy;
use App\Models\PayoutList;
use App\Models\PayoutListRecord;
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

class PayoutPolicyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(String $payout_id) {
        $this->payout_id   = $payout_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $payouts = PayoutListRecord::with('customers')->where('payout_list_id',$this->payout_id)->get();
        $records = [];
        $i = 0;
        foreach($payouts as $policy){
            $i++;
            $policy_data = Policy::where('id',$policy->policy_id)->first();
            if($policy_data->insurance_type == 1){
                $type = 'Motor Insurance';
            }else{
                $type = 'Health Insurance';
            }
            if($policy->tp == '0.00'){
                $tp = '0.00';
            }else{
                $tp = $policy->tp;
            }
            $records[] = array(
                $i,
                $policy->policy_no,
                $type,
                $policy->customers->name,
                date('d/m/Y',strtotime($policy->policy_date)),
                $policy->net_premium,
                $policy->od,
                $tp,
                $policy->payout
            );
        }
        return collect($records);
    }
    public function headings(): array
    {
        return [
            '#',
            'Policy No',
            'Insurance Type',
            'Customer',
            'Policy Date',
            'Net Premium',
            'OD',
            'TP',
            'Payout'
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
