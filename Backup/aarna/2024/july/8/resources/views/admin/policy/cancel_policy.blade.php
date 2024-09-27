@extends('admin.layouts.app')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">    
            @if(Auth::user()->id == 1)
        <div class="card">    
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h4>Download Report</h4>
                    <div class="ms-auto">
                    <form action="{{route('admin.cancel.policy.report')}}">
                        <div class="d-flex position-relative">
                            <input type="date" name="start_date" id="startDate" class="form-control me-2 position-relative" value="@if(request()->get('start_date')){{request()->get('start_date')}} @else{{date('Y-m-d',strtotime('-1 month'))}}@endif">
                            <input type="date" name="end_date" id="endDate" class="form-control me-2" value="@if(request()->get('end_date')){{request()->get('end_date')}}@else{{date('Y-m-d')}}@endif">
                            <button type="submit" class="btn btn-primary">  Download </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Policy No</th>
                        <th>Insurance Type</th>
                        <th>Category</th>
                        <th>Business Type</th>
                        <th>Risk Start Date</th>
                        <th>Risk End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($policies))
                        @foreach ($policies as $source)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td><a href="{{route('admin.view_policy',$source->id)}}">{{$source->policy_no}}</a></td>
                                <td>
                                    @if ($source->insurance_type == 1)
                                        Motor Insurance
                                    @else
                                        Health Insurance
                                    @endif
                                </td>
                                <td>
                                    @if ($source->insurance_type == 1)
                                        @foreach($sub_categories as $cats)
                                            @if($cats->id == $source->sub_category)
                                                {{$cats->name}}
                                            @endif
                                        @endforeach
                                    @else
                                        @if ($source->category == 1)
                                            Base
                                        @elseif($source->category == 2)
                                            Personal Accident
                                        @elseif($source->category == 3)
                                            Super Topup
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if ($source->insurance_type == 1)
                                        @if ($source->business_type == 1)
                                            New
                                        @elseif ($source->business_type == 2)
                                            Renewal
                                        @elseif ($source->business_type == 3)
                                            Rollover
                                        @elseif ($source->business_type == 4)
                                            Used
                                        @endif
                                    @else
                                        @if ($source->business_type == 1)
                                            New
                                        @elseif ($source->business_type == 2)
                                            Renewal
                                        @elseif ($source->business_type == 3)
                                            Portability
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    {{date('d-m-Y',strtotime($source->risk_start_date))}}
                                </td>
                                <td>
                                    {{date('d-m-Y',strtotime($source->risk_end_date))}}
                                </td>
                                <td>
                                    <ul class="action">
                                        <li class="edit"><a href="{{route('admin.view_policy',$source->id)}}" title="View Policy"><i class="icon-eye"></i></a></li>
                                    </ul>

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
</div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
$(document).ready(function(){
    $(function () {
        // $('#startDate').datepicker({
        //     format: 'dd/mm/yyyy'
        // });
        // $('#example1').calendar();
    });
});
</script>
@endsection
