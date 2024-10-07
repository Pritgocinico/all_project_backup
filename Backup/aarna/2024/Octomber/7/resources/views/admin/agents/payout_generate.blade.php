@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.generate.payout.data')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-10 mt-4">
                    <select name="agent" class="form-control pe-none" id="Agent">
                        <option value="0">Select Sourcing Agent</option>
                        @if ($agents)
                            @foreach ($agents as $agent)
                                <option value="{{$agent->id}}" @if(isset($data)) @if($data['agent'] == $agent->id) selected @endif @endif>{{$agent->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{route('admin.create.payout')}}" class="btn btn-secondary mt-3">
                        <i class="fa fa-refresh"></i> Refresh
                    </a>
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control pe-none" name="start_date" id="StartDate" value="@if(isset($data)){{$data['start_date']}}@else {{old('start_date')}} @endif" placeholder="" />
                    <label for="StartDate" class="form-label">Start Date (required)</label>
                    @if ($errors->has('start_date'))
                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control pe-none" name="end_date" id="EndDate" value="@if(isset($data)){{$data['end_date']}}@else {{old('end_date')}} @endif" placeholder="" />
                    <label for="EndDate" class="form-label">End Date (required)</label>
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                </div>
                <div class="col-12">
                    <div class="mt-3">
                        @if(isset($html))
                            {!! $html !!}
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
