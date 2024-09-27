@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">
        <div class="card-body">
            <form action="{{route('admin.add.agent.data')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="first_name" id="FirstName" value="{{old('first_name')}}" placeholder="" autofocus />
                    <label for="FirstName" class="form-label">First Name (required)</label>
                    @if ($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="last_name" id="LastName" value="{{old('last_name')}}" placeholder="" autofocus />
                    <label for="LastName" class="form-label">Last Name (required)</label>
                    @if ($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="tel" class="form-control" name="phone" id="Phone" value="{{old('phone')}}" placeholder="" />
                    <label for="Phone" class="form-label">Agent Mobile (required)</label>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <input type="email" class="form-control" name="email" id="email" value="{{old('email')}}" placeholder="" />
                    <label for="email" class="form-label">Agent Email (required)</label>
                    <p class="mb-0 fs-14">(*NOTE: This field use as Username and must be unique.)</p>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-6 mt-4">
                    <select name="team_lead" class="form-control" id="TeamLead">
                        <option value="0">Select Team Lead</option>
                        @if ($team_leads)
                            @foreach ($team_leads as $leads)
                                <option value="{{$leads->id}}">{{$leads->name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-12 mt-4 ms-2">
                    <div class="form-check d-flex align-items-center">
                        <input type="checkbox" class="form-check-input chk" id="customer" name="customer" placeholder=""  />
                        <label for="customer" class="ms-2">Customer</label>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <label for="Status" class="">
                        Status <span class="text-danger">*</span>
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" />
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                </div> --}}
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
