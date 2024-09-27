@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">
        <div class="card-body">
            <form action="{{route('admin.edit.customer')}}" method="post" class="row g-3">
                @csrf
                <div class="col-md-12 form-floating mt-4">
                    <input type="text" class="form-control" name="name" id="Name" value="{{$customer->name}}" placeholder="" />
                    <label for="Name" class="form-label">Name (required)</label>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <input type="email" class="form-control" name="email" id="Email" value="{{$customer->email}}" placeholder="" autofocus />
                    <label for="Email" class="form-label">Email</label>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-12 form-floating mt-4">
                    <input type="tel" class="form-control" name="phone" id="Phone" value="{{$customer->phone}}" placeholder="" />
                    <label for="Phone" class="form-label">Mobile (required)</label>
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-12 ms-2">
                    <label for="Status" class="">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" @if ($customer->status == 1)
                            checked
                        @endif>
                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                    </div>
                </div>
                <div class="col-12">
                    <input type="hidden" name="id" value="{{$customer->id}}">
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
