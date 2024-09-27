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

        $category_data = Category::find($policy->category);
        $customer = Customer::find($policy->customer);
        $category_name = $category_data->name ?? "";
        $subCategory = Category::find($policy->sub_category);
        $managedDetail = User::find($policy->managed_by);
        $parameter = $policy->parameters->where('sub_category_id', 9)->where('parameter_id', 40)->last();
        $payment = $policy->payments->last();

        $pType = $this->getPaymentType($payment);
        $riskStartDate = $policy->risk_start_date ? date('d-m-Y', strtotime($policy->risk_start_date)) : "-";
        $riskEndDate = $policy->risk_end_date ? date('d-m-Y', strtotime($policy->risk_end_date)) : "-";
        $pDate = $payment ? date('d-m-Y', strtotime($payment->payment_date)) : "-";
        $company = Company::where('id',$policy->company)->latest()->first();

        return [
            $policy->policy_no,
            $company->name ?? '',
            $customer->name,
            $category_name,
            $subCategory->name??"",
            $policy->net_premium_amount,
            $riskStartDate,
            $riskEndDate,
            $policy->sourcingAgent? $policy->sourcingAgent->name:"-",
            $policy->vehicle_make,
            $policy->vehicle_model,
            $policy->vehicle_registration_no,
            $policy->vehicle_chassis_no,
            $policy->year_of_manufacture,
            $pDate,
            $pType,
            $payment->made_by ?? '',
            $managedDetail ? $managedDetail->name : '',
            $policy->teamLead ? $policy->teamLead->name : '',
            $policy->tp,
            $policy->od,
            $parameter->value ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'POLICY NO',
            'INSURANCE COMPANY',
            'CUSTOMER NAME',
            'CATEGORY',
            'SUB CATEGORY',
            'NET PREMIUM',
            'RISK START DATE',
            'RISK END DATE',
            'SOURCING AGENT',
            'VEHICLE MAKE',
            'VEHICLE MODEL',
            'REGISTRATION NO',
            'VEHICLE CHASSI NO',
            'YEAR OF MONTH',
            'PAYMENT DATE',
            'PAYMENT TYPE',
            'PAYMENT MADE BY',
            'MANAGED BY',
            'TEAM LEAD',
            'THIRD PARTY PREMIUM',
            'OWN DAMAGE PREMIUM',
            'GVW',
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