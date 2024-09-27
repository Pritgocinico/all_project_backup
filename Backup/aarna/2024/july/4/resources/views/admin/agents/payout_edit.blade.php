@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.edit.payout.data')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-12 mt-4">
                    <select name="agent" class="form-control" id="Agent" disabled>
                        <option value="0">Select Sourcing Agent</option>
                        @if ($agents)
                            @foreach ($agents as $agent)
                                <option value="{{$agent->id}}" @if(isset($payouts)) @if($payouts->agent_id == $agent->id) selected @endif @endif>{{$agent->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="start_date" id="StartDate" value="@if(isset($payouts)){{date('Y-m-d',strtotime($payouts->start_date))}}@else {{old('start_date')}} @endif" placeholder=""  disabled />
                    <label for="StartDate" class="form-label">Start Date (required)</label>
                    @if ($errors->has('start_date'))
                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="end_date" id="EndDate" value="@if(isset($payouts)){{date('Y-m-d',strtotime($payouts->end_date))}}@else {{old('end_date')}} @endif" placeholder="" disabled />
                    <label for="EndDate" class="form-label">End Date (required)</label>
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                </div>
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
                                        if(!blank($pay)){
                                            $percentage = $pay->value;
                                            $payout = ($policy->net_premium_amount*$percentage)/100;
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
                                            <td>{{$policy->policy_no}}<input type="hidden" name="payout[{{$record->id}}][policy_id]" value="{{$policy->id}}"><input type="hidden" name="payout[{{$record->id}}][policy_no]" value="{{$policy->policy_no}}"></td>
                                            <td>
                                                @if ($policy->insurance_type == 1)
                                                    Motor
                                                @else
                                                    Health
                                                @endif
                                            </td>
                                            <td>{{$customer->name}}<input type="hidden" name="payout[{{$record->id}}][customer]" value="{{$policy->customer}}"></td>
                                            <td>{{date('d-m-Y',strtotime($policy->risk_start_date))}}<input type="hidden" name="payout[{{$record->id}}][policy_date]" value="{{$policy->risk_start_date}}"></td>
                                            <td>{{$policy->net_premium_amount}}<input type="hidden" name="payout[{{$record->id}}][net_premium]" value="{{$policy->net_premium_amount}}"></td>
                                            <td>{{$od}}<input type="hidden" name="payout[{{$record->id}}][od]" value="{{$od}}"></td>
                                            <td>{{$tp}}<input type="hidden" name="payout[{{$record->id}}][tp]" value="{{$tp}}"></td>
                                            <td>{{$percentage}}%<input type="hidden" name="payout[{{$record->id}}][percentage]" value="{{$percentage}}"></td>
                                            <td><input type="number" min="0" value="{{$record->payout}}" class="form-control" name="payout[{{$record->id}}][payout]"></td>
                                        </tr>
                                    <?php
                                        }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn gc_btn mt-3">
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
