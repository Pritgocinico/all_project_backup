@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mt-4">Sourcing Agent :</div>
                    <div class="col-md-9 mt-4">
                        @if ($agents)
                            @foreach ($agents as $agent)
                                @if (isset($payouts))
                                    @if ($payouts->agent_id == $agent->id)
                                        {{ $agent->name }}
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">Start Date To End Date :</div>
                    <div class="col-md-9">
                        {{ date('d-m-Y', strtotime($payouts->start_date)) }} -
                        {{ date('d-m-Y', strtotime($payouts->end_date)) }}
                    </div>
                </div>
                <hr>
                @if ($payouts->disbursement_date != '')
                    <div class="row">
                        <div class="col-md-3">Disbursement Date :</div>
                        <div class="col-md-9">
                            {{ date('d-m-Y', strtotime($payouts->disbursement_date)) }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">Disbursement Amount :</div>
                        <div class="col-md-9">
                            ₹{{ $payouts->disbursement_amount }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-3">Payment Type :</div>
                        <div class="col-md-9">
                            {{ $payouts->payment_type }}
                        </div>
                    </div>
                    <hr>
                @endif
                <div class="col-12">
                    <div class="mt-3">
                        <div>
                            <h3>Policies Payout</h3>
                            <table class="table rwd-table mb-0 w-100 mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Policy No</th>
                                        <th>Insurance Type</th>
                                        <th>Customer</th>
                                        <th>Payout Restricted</th>
                                        <th>Policy Date</th>
                                        <th>Net Premium</th>
                                        <th>OD</th>
                                        <th>TP</th>
                                        <th>Payout (%)</th>
                                        <th>Payout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    foreach($payout_records as $record){
                                        $policy = DB::table('policies')->where('id',$record->policy_id)->first();
                                        $customer = DB::table('customers')->where('id',$policy->customer)->first();
                                        $i++;
                                        if($policy->insurance_type == 1){
                                            $pay = DB::table('sourcing_agent_payouts')->where('agent_id',$payouts->agent_id)->where('company',$policy->company)->where('category',$policy->sub_category)->first();
                                        }else{
                                            $pay = DB::table('sourcing_agent_payouts')->where('agent_id',$payouts->agent_id)->where('company',$policy->company)->where('category',$policy->health_plan)->first();
                                        }
                                        $percentageString = "Net Premium";
                                        if(!blank($pay)){
                                            if($pay->payout_on == "od"){
                                                $percentage = $pay->value;
                                                $payout = ($policy->od*$percentage)/100;
                                                $percentageString = "Own Damage";
                                            }else{
                                                $percentage = $pay->value;
                                                $payout = ($policy->net_premium_amount*$percentage)/100;
                                            }
                                        }else{
                                            $percentage = 10;
                                            $payout = ($policy->net_premium_amount*$percentage)/100;
                                        }
                                        $tp = 0;
                                        $tp_param = DB::table('policy_parameters')->where('policy_id',$policy->id)->get();
                                        foreach($tp_param as $param){
                                            $tp += (float)$param->value;
                                        }
                                        $od = $policy->net_premium_amount - $tp; ?>
                                    <tr>
                                        <td>#</td>
                                        <td><a
                                                href="{{ route('admin.view_policy', $policy->id) }}">{{ $policy->policy_no }}</a><input
                                                type="hidden" name="payout[{{ $record->id }}][policy_id]"
                                                value="{{ $policy->id }}"><input type="hidden"
                                                name="payout[{{ $record->id }}][policy_no]"
                                                value="{{ $policy->policy_no }}"></td>
                                        <td>
                                            @if ($policy->insurance_type == 1)
                                                Motor
                                            @else
                                                Health
                                            @endif
                                        </td>
                                        <td>{{ $customer->name }}<input type="hidden"
                                                name="payout[{{ $record->id }}][customer]"
                                                value="{{ $policy->customer }}"></td>
                                        <td class="text-capitalize">
                                            {{ isset($policy->payout_restricted) ? $policy->payout_restricted : 'No' }}
                                            {{ $policy->payout_restricted == 'Yes' ? $policy->payout_restricted_remark : '' }}
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($policy->risk_start_date)) }}<input type="hidden"
                                                name="payout[{{ $record->id }}][policy_date]"
                                                value="{{ $policy->risk_start_date }}"></td>
                                        <td>₹{{ $policy->net_premium_amount }}<input type="hidden"
                                                name="payout[{{ $record->id }}][net_premium]"
                                                value="{{ $policy->net_premium_amount }}"></td>
                                        <td>₹{{ $policy->od }}<input type="hidden"
                                                name="payout[{{ $record->id }}][od]" value="{{ $od }}"></td>
                                        <td>₹{{ $policy->tp }}<input type="hidden"
                                                name="payout[{{ $record->id }}][tp]" value="{{ $tp }}"></td>
                                        <td>{{ $percentage }}% On <br />
                                            {{$percentageString}}

                                            <input type="hidden" name="payout[{{ $record->id }}][percentage]"
                                                value="{{ $percentage }}">
                                        </td>
                                        <td>₹{{ $record->payout }}</td>
                                    </tr>
                                    <?php
                                        }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection