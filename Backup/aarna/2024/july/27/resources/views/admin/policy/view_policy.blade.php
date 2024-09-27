@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-no-border pb-0 justify-content-between d-flex">
                    <h4>View Policy Details</h4>
                    <a href="{{ route('admin.policy.history', $policy->id) }}" class="btn btn-primary me-2"><i
                            class="icon-time"></i> History</a>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs border-tab border-0 mb-0 nav-danger" id="topline-tab" role="tablist">
                        <li class="nav-item">
                            <a href="#pills-details" class="nav-link me-2 active" id="pills-details-tab"
                                data-bs-toggle="tab" role="tab" aria-controls="pills-details"
                                aria-selected="true">Policy Details</a>
                        </li>
                        @if ($policy->insurance_type == 1)
                            <li class="nav-item">
                                <a href="#pills-vehicleinfo" class="nav-link me-2" id="pills-vehicleinfo-tab"
                                    data-bs-toggle="tab" role="tab" aria-controls="pills-vehicleinfo"
                                    aria-selected="true">Vehicle Info</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a href="#pills-customer" class="nav-link me-2" id="pills-customer-tab" data-bs-toggle="tab"
                                role="tab" aria-controls="pills-customer" aria-selected="false">Customer Info</a>
                        </li>
                        <li class="nav-item">
                            <a href="#pills-attachments" class="nav-link me-2" id="pills-attachments-tab"
                                data-bs-toggle="tab" role="tab" aria-controls="pills-attachments"
                                aria-selected="false">Attachments</a>
                        </li>
                        <li class="nav-item">
                            <a href="#pills-payments" class="nav-link me-2" id="pills-payments-tab" data-bs-toggle="tab"
                                role="tab" aria-controls="pills-payments" aria-selected="false">Payments</a>
                        </li>
                        @if (Auth()->user() !== null && Auth()->user()->role == 1)
                            <li class="nav-item">
                                <a href="#pills-od_tp" class="nav-link me-2" id="pills-od_tp-tab" data-bs-toggle="tab"
                                    role="tab" aria-controls="pills-od_tp" aria-selected="false">OD & TP</a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="topline-tabContent">
                        <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                            aria-labelledby="pills-details-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <tbody>
                                                @if ($policy->insurance_type == 1)
                                                    <tr>
                                                        <td>Covernote No</td>
                                                        <td>{{ $policy->covernote_no }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>Policy No</td>
                                                    <td>{{ $policy->policy_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer</td>
                                                    <td>
                                                        {{ $customer ? $customer->name : '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Insurance Company</td>
                                                    <td>
                                                        @if (!blank($companies))
                                                            @foreach ($companies as $company)
                                                                @if ($company->id == $policy->company)
                                                                    {{ $company->name }}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if ($policy->insurance_type == 2)
                                                    <tr>
                                                        <td>Category</td>
                                                        <td>
                                                            @if ($policy->health_category == 1)
                                                                Base
                                                            @elseif ($policy->health_category == 2)
                                                                Personal Accident
                                                            @else
                                                                Super Topup
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Health Plan</td>
                                                        <td>
                                                            {{ $plans ? $plans->name : '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sum Insured Amount ( <i class="fa fa-inr"></i> )</td>
                                                        <td>
                                                            {{ $policy->sum_insured_amount }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if ($policy->insurance_type == 1)
                                                    <tr>
                                                        <td>Category</td>
                                                        <td>
                                                            @if (!blank($categories))
                                                                @foreach ($categories as $category)
                                                                    @if ($category->id == $policy->category)
                                                                        {{ $category->name }}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Sub Category</td>
                                                        <td>
                                                            @if (!blank($sub_categories))
                                                                @foreach ($sub_categories as $sub_cat)
                                                                    @if ($sub_cat->id == $policy->sub_category)
                                                                        {{ $sub_cat->name }}
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Is Individual?</td>
                                                        <td>
                                                            @if ($policy->policy_type == 1)
                                                                Yes
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @if ($policy->policy_type == 1)
                                                        <tr>
                                                            <td>Policy Individual Rate</td>
                                                            <td>
                                                                {{ $policy->policy_individual_rate }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>IDV Amount ( <i class="fa fa-inr"></i> )</td>
                                                        <td>{{ $policy->idv_amount }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>Gross Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                                    <td>{{ $policy->gross_premium_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <td>OD Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                                    <td>{{ $policy->od }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Net Premium Amount ( <i class="fa fa-inr"></i> )</td>
                                                    <td>{{ $policy->net_premium_amount }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Risk Start Date</td>
                                                    <td>{{ $policy->risk_start_date }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Risk End Date</td>
                                                    <td>{{ $policy->risk_end_date }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Business Type</td>
                                                    <td>
                                                        @if ($policy->insurance_type == 1)
                                                            @if ($policy->business_type == 1)
                                                                New
                                                            @elseif ($policy->business_type == 2)
                                                                Renewal
                                                            @elseif ($policy->business_type == 3)
                                                                Rollover
                                                            @elseif ($policy->business_type == 4)
                                                                Used
                                                            @endif
                                                        @else
                                                            @if ($policy->business_type == 1)
                                                                New
                                                            @elseif ($policy->business_type == 2)
                                                                Renewal
                                                            @elseif ($policy->business_type == 3)
                                                                Portability
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sourcing Agent</td>
                                                    <td>
                                                        @if (!blank($sourcing_agents))
                                                            @foreach ($sourcing_agents as $agent)
                                                                @if ($agent->id == $policy->agent)
                                                                    {{ $agent->name }}
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Type</td>
                                                    <td>
                                                        @if (!blank($payments))
                                                            @foreach ($payments as $payment)
                                                                @if ($payment->payment_type == 1)
                                                                    Cash
                                                                @elseif($payment->payment_type == 2)
                                                                    Cheque
                                                                @else
                                                                    Online
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        <!--@if ($policy->payment_type == 1)
    -->
                                                        <!--    Cash-->
                                                        <!--
@elseif($policy->payment_type == 2)
    -->
                                                        <!--    Cheque-->
                                                    <!--@else-->
                                                        <!--    Online-->
                                                        <!--
    @endif-->
                                                    </td>
                                                </tr>
                                                @if ($policy->payment_type == 3)
                                                    <tr>
                                                        <td>Transaction No</td>
                                                        <td>
                                                            {{ $policy->transaction_no }}
                                                        </td>
                                                    </tr>
                                                @elseif($policy->payment_type == 2)
                                                    <tr>
                                                        <td>Cheque No</td>
                                                        <td>
                                                            {{ $policy->cheque_no }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Cheque Date</td>
                                                        <td>
                                                            {{ $policy->cheque_date }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bank Name</td>
                                                        <td>
                                                            {{ $policy->bank_name }}
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td>Payout Restricted</td>
                                                    <td class="text-capitalize">
                                                        {{ $policy->payout_restricted ? $policy->payout_restricted : 'no' }}
                                                    </td>
                                                </tr>
                                                @if ($policy->payout_restricted == 'yes')
                                                    <tr>
                                                        <td>Payout Restricted Remark</td>
                                                        <td>{{ $policy->payout_restricted_remark }}</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-vehicleinfo" role="tabpanel"
                            aria-labelledby="pills-vehicleinfo-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <td>Vehicle Make</td>
                                                    <td>{{ $policy->vehicle_make }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vehicle Model</td>
                                                    <td>{{ $policy->vehicle_model }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vehicle Registration No</td>
                                                    <td>{{ $policy->vehicle_registration_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vehicle Chassis No</td>
                                                    <td>{{ $policy->vehicle_chassis_no }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Year Of Month</td>
                                                    <td>{{ $policy->year_of_manufacture }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Vehicle Registration No</td>
                                                    <td>{{ $policy->vehicle_registration_no }}</td>
                                                </tr>
                                                @if (!blank($parameters))
                                                    @foreach ($parameters as $item)
                                                        @if ($item->type == 1)
                                                            <tr>
                                                                <td>Public Carrier</td>
                                                                <td>
                                                                    <?php $para = DB::table('parameters')
                                                                        ->where('id', $item->value)
                                                                        ->first(); ?>
                                                                    {{ $para->carrier ?? '' }}
                                                                </td>
                                                            </tr>
                                                        @elseif ($item->type == 2)
                                                            <tr>
                                                                <td>Private Carrier</td>
                                                                <td>
                                                                    <?php $para = DB::table('parameters')
                                                                        ->where('id', $item->value)
                                                                        ->first(); ?>
                                                                    {{ $para->carrier }}
                                                                </td>
                                                            </tr>
                                                        @elseif ($item->type == 3)

                                                        @elseif ($item->type == 4)
                                                            <?php $param = DB::table('parameters')->where('type', 4)->get(); ?>
                                                            @foreach ($param as $para)
                                                                @if ($para->id == $item->parameter_id)
                                                                    <tr>
                                                                        <td>{{ $para->label }}
                                                                        </td>
                                                                        <td>{{ $item->value }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @elseif ($item->type == 5)
                                                            <?php $param = DB::table('parameters')->where('type', 5)->get(); ?>
                                                            @foreach ($param as $para)
                                                                @if ($para->id == $item->parameter_id)
                                                                    <tr>
                                                                        <td>CC
                                                                        </td>
                                                                        <td>{{ $para->label }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @elseif($item->type == 6)
                                                            <?php $param = DB::table('parameters')->where('type', 6)->get(); ?>
                                                            @foreach ($param as $para)
                                                                @if ($para->id == $item->parameter_id)
                                                                    <tr>
                                                                        <td>PA to Passanger
                                                                        </td>
                                                                        <td>{{ $para->label }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @elseif($item->type == 7)
                                                            <?php $param = DB::table('parameters')->where('type', 7)->get(); ?>
                                                            @foreach ($param as $para)
                                                                @if ($para->id == $item->parameter_id)
                                                                    <tr>
                                                                        <td>{{ $para->label }}
                                                                        </td>
                                                                        <td>{{ $item->value }}</td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @php
                                                    $tp = 0;
                                                    $tp_param = DB::table('policy_parameters')
                                                        ->where('policy_id', $policy->id)
                                                        ->get();
                                                    // echo count($tp_param);
                                                    if (!blank($tp_param)) {
                                                        foreach ($tp_param as $param) {
                                                            // echo '<pre>';
                                                            // print_r($param->type);
                                                            $para = DB::table('parameters')
                                                                ->where('id', $param->parameter_id)
                                                                ->first();
                                                            if (!blank($para)) {
                                                                if ($para->type != 3) {
                                                                    if (
                                                                        $param->type == 7 &&
                                                                        ($para->display_type == 'text' ||
                                                                            $para->display_type == 'dropdown')
                                                                    ) {
                                                                        if (
                                                                            property_exists($param, 'value') &&
                                                                            property_exists($para, 'carrier_value')
                                                                        ) {
                                                                            $tp +=
                                                                                (float) $param->value *
                                                                                (int) $para->carrier_value;
                                                                        }
                                                                    } else {
                                                                        if (property_exists($para, 'carrier_value')) {
                                                                            $tp += (float) $para->carrier_value;
                                                                        }
                                                                    }
                                                                } else {
                                                                    if (property_exists($para, 'value')) {
                                                                        $tp += (float) $para->value;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }

                                                    // echo $tp.'<br>';
                                                    if (
                                                        $policy->policy_type == 1 &&
                                                        $policy->policy_individual_rate != null
                                                    ) {
                                                        $rate = $policy->policy_individual_rate;
                                                    } else {
                                                        $rate = 0;
                                                    }
                                                    // echo $rate.'<br>';
                                                    $od = $policy->net_premium_amount - $tp - $rate;
                                                @endphp
                                                @if (count($tp_param) > 0)
                                                    <tr>
                                                        <td>TP</td>
                                                        <td>{{ $policy->tp }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>OD</td>
                                                        <td>{{ $policy->od }}</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-customer" role="tabpanel" aria-labelledby="pills-customer-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Customer Name</td>
                                                    <td>{{ $customer->name ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer Email</td>
                                                    <td>{{ $customer->email ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Customer Phone Number</td>
                                                    <td>{{ $customer->phone ?? '-' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-attachments" role="tabpanel"
                            aria-labelledby="pills-attachments-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Policy Attachments</td>
                                                    <td>
                                                        @if (!blank($attachments))
                                                            @foreach ($attachments as $attachment)
                                                                <a href="{{ url('/') }}/policy_attachment/{{ $attachment->file }}"
                                                                    target="_blank"><img
                                                                        src="{{ url('/') }}/assets/Images/docs.png"
                                                                        width="40px" alt="">
                                                                    {{ $attachment->file_name }}
                                                                </a>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Policy Document</td>
                                                    <td>
                                                        @if (!blank($documents))
                                                            @foreach ($documents as $doc)
                                                                <a href="{{ url('/') }}/policy_document/{{ $doc->file }}"
                                                                    target="_new" title="{{ $doc->file_name }}"><img
                                                                        src="{{ url('/') }}/assets/Images/docs.png"
                                                                        width="40px" alt=""></a>
                                                            @endforeach
                                                        @endif
                                                        {{-- @if (!blank($policy->policy_document))
                                            <a href="{{url('/')}}/policy_document/{{$policy->policy_document}}" target="_new"><img src="{{url('/')}}/assets/Images/docs.png" width="40px" alt=""></a>
                                        @endif --}}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-payments" role="tabpanel"
                            aria-labelledby="pills-payments-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <tbody>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Payment Date</th>
                                                        <th scope="col">Payment Type</th>
                                                        <th scope="col">Details</th>
                                                    </tr>
                                                </thead>
                                                @if (!blank($payments))
                                                    @foreach ($payments as $payment)
                                                        <tr>
                                                            <td>{{ date('d-m-Y', strtotime($payment->payment_date)) }}</td>
                                                            <td>
                                                                @if ($payment->payment_type == 1)
                                                                    Cash
                                                                @elseif($payment->payment_type == 2)
                                                                    Cheque
                                                                @else
                                                                    Online
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($payment->payment_type == 1)
                                                                    <p><strong>Payment Date : </strong>
                                                                        {{ $payment->payment_date }}</p>
                                                                    <p><strong>Payment Made By : </strong>
                                                                        {{ $payment->made_by }}</p>
                                                                @elseif($payment->payment_type == 2)
                                                                    <p><strong>Payment Date :
                                                                        </strong>{{ $payment->payment_date }}</p>
                                                                    <p><strong>Cheque No :
                                                                        </strong>{{ $payment->cheque_no }}</p>
                                                                    <p><strong>Cheque Date : </strong>
                                                                        {{ date('d-m-Y', strtotime($payment->cheque_date)) }}
                                                                    </p>
                                                                    <p><strong>Bank Name : </strong>
                                                                        {{ $payment->bank_name }}</p>
                                                                    <p><strong>Payment Made By : </strong>
                                                                        {{ $payment->made_by }}</p>
                                                                @else
                                                                    <p><strong>Payment Date : </strong>
                                                                        {{ $payment->payment_date }}</p>
                                                                    <p><strong>Transaction No : </strong>
                                                                        {{ $payment->transaction_no }}</p>
                                                                    <p><strong>Payment Made By : </strong>
                                                                        {{ $payment->made_by }}</p>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-od_tp" role="tabpanel" aria-labelledby="pills-od_tp-tab">
                            <div class="card-body px-0 pb-0">
                                <div class="user-header pb-2">
                                </div>
                                <div class="user-content">
                                    <div class="table-responsive custom-scrollbar">
                                        <table class="table mb-0">
                                            <tbody>
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Insurance Type</th>
                                                        <th scope="col">Customer</th>
                                                        <th scope="col">Payout Restricted</th>
                                                        <th scope="col">Policy Date</th>
                                                        <th scope="col">Net Premium</th>
                                                        <th scope="col">OD</th>
                                                        <th scope="col">TP</th>
                                                        <th scope="col">Payout (%)</th>
                                                        <th scope="col">Payout</th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    if ($policy->insurance_type == 1) {
                                                        $pay = DB::table('sourcing_agent_payouts')
                                                            ->where('agent_id', $policy->agent_id)
                                                            ->where('company', $policy->company)
                                                            ->where('category', $policy->sub_category)
                                                            ->first();
                                                    } else {
                                                        $pay = DB::table('sourcing_agent_payouts')
                                                            ->where('agent_id', $policy->agent_id)
                                                            ->where('company', $policy->company)
                                                            ->where('category', $policy->health_plan)
                                                            ->first();
                                                    }
                                                    $percentageString = 'Net Premium';
                                                    if (!blank($pay)) {
                                                        if ($pay->payout_on == 'od') {
                                                            $percentage = $pay->value;
                                                            $payout = ($policy->od * $percentage) / 100;
                                                            $percentageString = 'Own Damage';
                                                        } else {
                                                            $percentage = $pay->value;
                                                            $payout = ($policy->net_premium_amount * $percentage) / 100;
                                                        }
                                                    } else {
                                                        $percentage = 10;
                                                        $payout = ($policy->net_premium_amount * $percentage) / 100;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if ($policy->insurance_type == 1)
                                                            Motor
                                                        @else
                                                            Health
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $customer ? $customer->name : '' }}
                                                    </td>
                                                    <td class="text-capitalize">
                                                        {{ isset($policy->payout_restricted) ? $policy->payout_restricted : 'No' }}
                                                        {{ $policy->payout_restricted == 'Yes' ? $policy->payout_restricted_remark : '' }}
                                                    </td>
                                                    <td>{{ date('d-m-Y', strtotime($policy->risk_start_date)) }}</td>
                                                    <td>₹{{ $policy->net_premium_amount }}</td>
                                                    <td>₹{{ $policy->od }}</td>
                                                    <td>₹{{ $policy->tp }}</td>
                                                    <td>{{ $percentage }}% On <br />
                                                        {{ $percentageString }}
                                                    </td>
                                                    <td>₹{{ $payout }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>
@endsection
