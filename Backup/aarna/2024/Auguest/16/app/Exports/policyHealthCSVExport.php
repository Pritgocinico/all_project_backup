<?php

namespace App\Exports;

use App\Models\Policy;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Plan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class policyHealthCSVExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct($data) {
        $this->policy = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Endorsement::all();
        $i = 0;
        $records = [];
        foreach($this->policy as $source){
            $i++;
            if($source->category == 1){
                $category = 'Base';
            }elseif($source->category == 2){
                $category = 'Personal Accident';  
            }else{
                $category = 'Super Popup'; 
            }
            $plan = Plan::where('id',$source->health_plan)->first();
            if(!blank($plan)){
                $plan_name = $plan->name;
            }else{
                $plan_name = "";
            }
            $records[] = array(
                $i,
                $category,
                $plan_name,
                date('d-m-Y',strtotime($source->risk_start_date)),
                date('d-m-Y',strtotime($source->risk_end_date)),
                $source->policy_no,
                $source->customers->name,
                $source->net_premium_amount
            );
        }
        return collect($records);
    }
    public function headings(): array
    {
        return [
            '#',
            'LOB',
            'Plan Name',
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
