<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Customer;
use App\Models\BusinessSource;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use \Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessSourceReport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct($data) {
        $this->source = $data;
       
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $i = 0;
        $records = [];
        foreach($this->source as $source){
            if($source->insurance_type == 1){
                $category = Category::where('id',$source->sub_category)->first();
            }else{
                $category = Category::where('id',$source->category)->first();
            }
            $type = '';
            if($source->business_type == 1){
                $type = 'New';
            }elseif($source->business_type == 2){
                $type = 'Renewal';
            }elseif($source->business_type == 3){
                $type = 'Rollover';
            }elseif($source->business_type == 4){
                $type = 'Used';
            }
            $i++;
            $records[] = array(
                $i,
                date('d-m-Y',strtotime($source->risk_start_date)),
                $category->name,
                $type,
                date('d-m-Y',strtotime($source->created_at)),
                '',
                $source->customers->name,
                '',
                $source->net_premium_amount,
            );
        }
        return collect($records);
    }
    public function headings(): array
    {
        return [
            '#',
            'Sourcing',
            'LOB',
            'Business',
            'Policy Start',
            'Policy No',
            'Customer Name',
            'Reg. No./Sr. No',
            'Net Amount'
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
