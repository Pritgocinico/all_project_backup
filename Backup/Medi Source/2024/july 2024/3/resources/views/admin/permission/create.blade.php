@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Add Permission</h3>
                        <a href="{{ route('permission.index') }}" class="btn btn-secondary">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('permission.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="first_name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="permission_name" name="permission_name" value="{{old('permission_name')}}">
                            <span id="firstNameError" class="text-danger"></span>
                            @if ($errors->has('permission_name'))
                                <span class="text-danger">{{ $errors->first('permission_name') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
