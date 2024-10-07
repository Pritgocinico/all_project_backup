<?php

namespace App\Exports;

use App\Models\Category;
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
            if($policy->insurance_type == 1){
                $pay = SourcingAgentPayout::where('agent_id',$request->agent)->where('company',$policy->company)->where('category',$policy->sub_category)->first();
                $subCategory = Category::where('id',$policy->sub_category)->first();
            }else{
                $pay = SourcingAgentPayout::where('agent_id',$request->agent)->where('company',$policy->company)->where('category',$policy->health_plan)->first();
                $subCategory = Category::where('id',$policy->health_sub_category)->first();
            }
            if($policy->tp == '0.00'){
                $tp = '0.00';
            }else{
                $tp = $policy->tp;
            }
            $perString = "Net Premium";
                if(!blank($pay)){
                    if($pay->payout_on == "od"){
                        $percentage = $pay->value;
                        $perString = "Own Damage";
                    }else{
                        $percentage = $pay->value;
                    }
                    
                }else{
                    $percentage = 10;
                }
            $records[] = array(
                $i,
                $policy->policy_no,
                $subCategory->name ?? "-",
                $policy->vehicle_registration_no,
                $policy->customers->name,
                date('d/m/Y',strtotime($policy->policy_date)),
                $policy->net_premium,
                $policy->od,
                $tp,
                $percentage.'% On<br />'.$perString,
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
            'Sub Category Name',
            'Vehicle Registration Number',
            'Customer',
            'Policy Date',
            'Net Premium',
            'OD',
            'TP',
            "Payout (%)",
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
