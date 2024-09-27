@extends('admin.layouts.app')

@section('content')
 <div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex card-no-border">
            <h4>Health Plans List</h4>
            @if(Auth::user()->id == 1)
                          <a href="{{route('admin.add.plan')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Helth Plan</a>
                @else
                    @foreach($permissions as $permission)
                        @if($permission->capability == 'plan-create' && $permission->value == 1)
                            <a href="{{route('admin.add.plan')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Helth Plan</a>
                        @endif
                    @endforeach
                @endif
            </div>
        <div class="card-body">
          <div class="table-responsive custom-scrollbar">
            <table class="display border" id="basic-1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Plan Name</th>
                        <th>Insurance Company</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($plans))
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$plan->name}}</td>
                                <td>
                                    @foreach ($companies as $item)
                                        @if ($item->id == $plan->company)
                                            {{$item->name}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if ($plan->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Deactive</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="action">
                                        @if(Auth::user()->id == 1)
                                        <li class="edit"> <a href="{{route('admin.edit_plan',$plan->id)}}"><i class="icon-pencil-alt"></i></a></li>
                                        @else
                                            @foreach($permissions as $permission)
                                                @if($permission->capability == 'plan-edit' && $permission->value == 1)
                                                     <li class="edit"><a href="{{route('admin.edit_plan',$plan->id)}}"><i class="icon-pencil-alt"></i></a></li>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(Auth::user()->id == 1)
                                            <li class="delete"><a href="javascript:void(0);" data-id="{{ $plan->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
                                        @endif
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
                    url : "{{route('delete.plan', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Health Plan has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
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
