<?php

namespace App\Exports;

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

class CustomerPayoutCsvExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function __construct(String $start_date, String $end_date, String $agent) {

        $this->start_date   = $start_date;
        $this->end_date     = $end_date;
        $this->agent        = $agent;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $payouts = PayoutList::with('agents')->where('agent_id',$this->agent)->whereBetween('created_at', [$this->start_date, $this->end_date])->get();
        $records = [];
        foreach($payouts as $payout){
            $records[] = array(
                date('d/m/Y',strtotime($payout->created_at)),
                $payout->agents->name,
                PayoutListRecord::where('payout_list_id',$payout->id)->count(),
                PayoutListRecord::sum('payout')
            );
        }
        return collect($records);
    }
    // public function query()
    // {
    //     $payouts = PayoutList::with('agents')->get();
    //     return $payouts;
    // }
    // public function map($payouts): array
    // {
    //     return [
    //         date('d/m/Y',strtotime($payouts->created_at)),
    //         $payouts->agents->name,
    //         PayoutListRecord::where('payout_list_id',$payouts->id)->count(),
    //         PayoutListRecord::sum('payout'),
    //     ];
    // }
    // public function startCell(): string
    // {
    //     return 'A2';
    // }
    // public function registerEvents(): array {

    //     return [
    //         AfterSheet::class => function(AfterSheet $event) {
    //             /** @var Sheet $sheet */
    //             $sheet = $event->sheet;

    //             $sheet->mergeCells('A1:B1');
    //             $sheet->setCellValue('A1', "Account 1");

    //             $sheet->mergeCells('C1:D1');
    //             $sheet->setCellValue('C1', "Account 2");

    //             $styleArray = [
    //                 'alignment' => [
    //                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //                 ],
    //             ];

    //             $cellRange = 'A1:D1'; // All headers
    //             $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
    //         },
    //     ];
    // }
    public function headings(): array
    {

        return [
            'Payout Date',
            'DSA',
            'No of Policy',
            'Payout Amount'
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
