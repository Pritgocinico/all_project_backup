@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header justify-content-between d-flex card-no-border">
                    <h4>All Users</h4>
                    <a href="{{route('admin.add.user')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New User</a>
                  </div>
                  <div class="card-body mt-2">
                    <div class="table-responsive custom-scrollbar">
                      <table class="display border" id="basic-1">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>User Type</th>
                            <th>Created At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if (!blank($users))
                            @foreach ($users as $user)
                                <tr>
                                    <td data-header="Name" class="pt-2">
                                        <a href="{{route('admin.edit.user',$user->id)}}">
                                            <img class="img-30 me-2" src="{{url('/')}}/assets/images/forms/user.png" alt="profile">
                                        </a>
                                        <a href="{{route('admin.edit.user',$user->id)}}" class="table-url fw-bold">{{$user->name}}</a>
                                    </td>
                                    <td data-header="Email">{{$user->email}}</td>
                                    <td data-header="Phone Number">{{$user->phone}}</td>
                                    <td data-header="Status">
                                        @if ($user->status == 1)
                                            <h4 class="badge bg-success">Active</h4>
                                        @else
                                            <h4 class="badge bg-danger">Deactive</h4>
                                        @endif
                                    </td>
                                    <td data-header="User Type">
                                        @if ($user->role == 1)
                                            Admin
                                        @else
                                            Staff
                                        @endif
                                    </td>
                                    <td data-header="Created At">{{date('Y-m-d',strtotime($user['created_at']))}}</td>
                                    <td>
                                    <ul class="action">
                                        @if(Auth::user()->id == 1)
                                        <li class="edit"> <a href="{{route('user.permission',$user->id)}}" class=""><i class="fa fa-lock me-2" aria-hidden="true"></i></a>
                                        @endif 
                                        <li class="edit"> <a href="{{route('admin.edit.user',$user->id)}}"><i class="icon-pencil-alt"></i></a></li>
                                        <li class="delete"><a href="javascript:void(0);" data-id="{{ $user->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
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
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-btn',function(){
            var user_id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.user', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "User has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                top.location.href="{{ route('admin.users') }}";
                            }
                        });
                    }
                });
            }
            });
        });
    });
</script>
@endsection
