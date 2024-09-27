@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Edit Permission</h3>
                        <a href="{{ route('permission.index') }}" class="btn btn-secondary">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('permission.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="first_name">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="role_name" name="role_name" value="{{$role->name}}" readonly>
                            <input type="hidden" name="role_id" value="{{$role->id}}">
                        </div>
                        <div class="row">
                            @foreach ($userPermissionList as $permission)
                                <div class="col-md-4">
                                    <input type="checkbox" name="permission_list[]" value="{{$permission->id}}" @if($permission->status == 1) checked @endif> {{$permission->permissionName->permission_name}}
                                    <input type="hidden" name="permission_id[]" value="{{$permission->id}}">
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
