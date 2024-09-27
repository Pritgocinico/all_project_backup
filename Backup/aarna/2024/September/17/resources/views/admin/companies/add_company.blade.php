@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
      <div class="card">
        <div class="card-body">
            <form action="{{route('admin.add.company.data')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6 form-floating mt-4">
                    <input type="text" class="form-control" name="company_name" id="CompanyName" value="{{old('company_name')}}" placeholder="" autofocus />
                    <label for="CompanyName" class="form-label">Company Name (required)</label>
                    @if ($errors->has('company_name'))
                        <span class="text-danger">{{ $errors->first('company_name') }}</span>
                    @endif
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
