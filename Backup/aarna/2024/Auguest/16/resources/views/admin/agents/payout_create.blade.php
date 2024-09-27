@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form action="{{route('admin.generate.payout')}}" method="post" class="row g-3">
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
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="start_date" id="StartDate" value="{{old('start_date')}}" placeholder="" />
                    <label for="StartDate" class="form-label">Start Date (required)</label>
                    @if ($errors->has('start_date'))
                        <span class="text-danger">{{ $errors->first('start_date') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="date" class="form-control" name="end_date" id="EndDate" value="{{old('end_date')}}" placeholder="" />
                    <label for="EndDate" class="form-label">End Date (required)</label>
                    @if ($errors->has('end_date'))
                        <span class="text-danger">{{ $errors->first('end_date') }}</span>
                    @endif
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary mt-3">
                        Create
                    </button>
                </div>
            </form>
            @if(isset($html))
                {!! $html !!}
            @endif
        </div>
    </div>
</div>
@endsection
