@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.download.customer.payoutcsv')}}" method="post" class="row customerDownload">
                @csrf
                <div class="col-md-12 mt-4">
                    <select name="agent" class="form-control" id="Agent">
                        <option value="0">Select Sourcing Agent</option>
                        @if ($agents)
                            @foreach ($agents as $agent)
                                <option value="{{$agent->id}}">{{$agent->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="text-danger agentError"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="start_date" id="StartDate" value="{{old('start_date')}}" placeholder="" />
                    <label for="StartDate" class="form-label">Payout From (required)</label>
                    <span class="text-danger startDateError"></span>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="end_date" id="EndDate" value="{{old('end_date')}}" placeholder="" />
                    <label for="EndDate" class="form-label">Payout To (required)</label>
                    <span class="text-danger endDateError"></span>
                </div>
                <div class="col-12 text-center">
                    <input type="hidden" name="type" class="type" value="">
                    <a href="javascript:void(0);" class="btn btn-info mt-3 customerPayoutReportCSV" data-type="csv">
                        Export To CSV
                    </a>
                    <a href="javascript:void(0);" class="btn btn-secondary mt-3 customerPayoutReportCSV" data-type="pdf">
                        Export To PDF
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
@section('script')
    <script>
        $(document).on('click','.customerPayoutReportCSV',function(){
            var agent = $('#Agent').val();
            var startDate = $('#StartDate').val();
            var endDate = $('#EndDate').val();
            $('.startDateError').html('');
            $('.endDateError').html('');
            if(agent == 0){
                $('.agentError').html('Please select valid agent.')
            }
            if(startDate == ''){
                $('.startDateError').html('Please select from date.');
            }
            if(endDate == ''){
                $('.endDateError').html('Please select to date.');
            }
            var type = $(this).data('type');
            $('.type').val(type);
            if(startDate != '' && endDate != '' && agent != 0){
                $('.customerDownload').submit();
            }
        });
    </script>
@endsection
