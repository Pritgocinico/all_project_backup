<?php

namespace App\Exports;

use App\Models\Policy;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use App\Models\SourcingAgent;
use App\Models\PolicyPayment;
use App\Models\Category;
use App\Models\PolicyParameter;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMapping;

class policyCSVExport implements FromQuery, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    protected $query;

    public function __construct($query) {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query->with([
            'company', 
            'agent', 
            'teamLead', 
            'sourcingAgent', 
            'payments', 
            'parameters'
        ]);
    }

    public function map($policy): array
    {
        static $i = 0;
        $i++;

        $category_data = Category::find($policy->sub_category);
        $category_name = $category_data->name ?? "";

        $business_type = $this->getBusinessType($policy->business_type);

        $payment = $policy->payments->last();
        $parameter = $policy->parameters->where('sub_category_id', 9)->where('parameter_id', 40)->last();

        $ptype = $this->getPaymentType($payment);
        $pdate = $payment ? date('d-m-Y', strtotime($payment->payment_date)) : "-";
        $company = Company::where('id',$policy->company)->latest()->first();

        return [
            $i,
            $policy->policy_no,
            $company->name ?? '',
            $policy->vehicle_make,
            $policy->vehicle_model,
            $policy->idv_amount,
            $policy->year_of_manufacture,
            $policy->gross_premium_amount,
            $policy->net_premium_amount,
            $policy->tp,
            $policy->od,
            $parameter->value ?? '',
            $business_type,
            $policy->ncb,
            $policy->rto_name,
            $policy->teamLead->name ?? '',
            $policy->sourcingAgent->name ?? '',
            $ptype,
            $pdate,
            $payment->made_by ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            '#',
            'POLICY NO',
            'INSURANCE COMPANY',
            'MAKE',
            'MODEL',
            'IDV',
            'YEAR OF MANUFACTURE',
            'GROSS PREMIUM',
            'NET PREMIUM',
            'THIRD PARTY PREMIUM',
            'OWN DAMAGE PREMIUM',
            'GVW',
            'BUSINESS TYPE',
            'NCB',
            'RTO NAME',
            'TEAM LEAD',
            'SOURCING AGENT',
            'PAYMENT TYPE',
            'PAYMENT DATE',
            'PAYMENT BY',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }

    private function getBusinessType($type)
    {
        switch ($type) {
            case 1: return 'New';
            case 2: return 'Renew';
            case 3: return 'Rollover';
            case 4: return 'Used';
            default: return '';
        }
    }

    private function getPaymentType($payment)
    {
        if (!$payment) return "-";
        switch ($payment->payment_type) {
            case 2: return "CHEQUE";
            case 3: return "ONLINE";
            default: return "CASH";
        }
    }
}