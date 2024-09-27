@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">
        <div class="card-body">
            <form action="{{route('admin.add.plan.data')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="name" id="Name" value="{{old('name')}}" placeholder="" autofocus />
                    <label for="Name" class="form-label">Plan Name *</label>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-floating mt-4">
                    <select class="form-control" name="insurance_company" id="Company" value="{{old('insurance_company')}}" placeholder="">
                        @foreach ($companies as $company)
                            <option value="{{$company->id}}">{{$company->name}}</option>
                        @endforeach
                    </select>
                    <label for="Company" class="form-label">Insurance Company *</label>
                    @if ($errors->has('insurance_company'))
                        <span class="text-danger">{{ $errors->first('insurance_company') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <textarea class="form-control" name="description" id="Description" placeholder="" >{{old('description')}}</textarea>
                    <label for="Description" class="form-label">Plan Description</label>
                    @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
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
