@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
        <div class="card">
            <div class="card-header justify-content-between d-flex card-no-border">
            <h4>Motor Insurance Covernote List</h4>
            @if(Auth::user()->role == 1)
                          <a href="{{route('admin.add.covernote')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Covernote</a>
                @else
                    @foreach($permissions as $permission)
                       @if(Auth::user()->role == 1 || $permission->capability == 'covernote-create' && $permission->value == 1)
                            <a href="{{route('admin.add.covernote')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Covernote</a>
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
                        <th>Insurance Type</th>
                        <th>Customer</th>
                        <th>Sub Category</th>
                        <th>Vehicle Make</th>
                        <th>Business Type</th>
                        <th>Risk Start Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($covernotes))
                        @foreach ($covernotes as $source)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    <a href="{{route('admin.view_covernote',$source->id)}}">
                                    @if ($source->insurance_type == 1)
                                        Motor Insurance
                                    @else
                                        Health Insurance
                                    @endif
                                    </a>
                                </td>
                                <td>
                                    @foreach ($customers as $customer)
                                        @if ($customer->id == $source->customer)
                                            {{$customer->name}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($sub_categories as $cats)
                                        @if($cats->id == $source->sub_category)
                                            {{$cats->name}}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    {{$source->vehicle_make}}
                                </td>
                                <td>
                                    @if ($source->business_type == 1)
                                        New
                                    @elseif ($source->business_type == 2)
                                        Renewal
                                    @elseif ($source->business_type == 3)
                                        Rollover
                                    @elseif ($source->business_type == 4)
                                        Used
                                    @endif
                                </td>
                                <td>
                                    {{date('d-m-Y',strtotime($source->risk_start_date))}}
                                </td>
                                <td>
                                    <ul class="action">
                                        @if(Auth::user()->role == 1)
                                            <li class="edit"><a href="{{route('admin.view_covernote',$source->id)}}"><i class="icon-eye"></i></a></li> 
                                            <li class="edit"><a href="javascript:void(0);" data-id="{{$source->id}}" class="ConvertCovernote"><i class="fa fa-repeat" aria-hidden="true"></i></a></li>
                                            <li class="edit"><a href="{{route('admin.edit_covernote',$source->id)}}"><i class="icon-pencil-alt"></i></a></li> 
                                            <li class="delete"><a href="javascript:void(0);" data-id="{{ $source->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
                                        @else
                                            @foreach($permissions as $permission)
                                                @if($permission->capability == 'covernote-convert' && $permission->value == 1)
                                                    <li class="edit"><a href="javascript:void(0);" data-id="{{$source->id}}" class="ConvertCovernote"><i class="fa fa-repeat" aria-hidden="true"></i></a></li>   
                                                @endif
                                                @if($permission->capability == 'covernote-edit' && $permission->value == 1)
                                                    <li class="edit"><a href="{{route('admin.edit_covernote',$source->id)}}"><i class="icon-pencil-alt"></i></a></li> 
                                                @endif
                                                @if($permission->capability == 'covernote-delete' && $permission->value == 1)
                                                    <li class="delete"><a href="javascript:void(0);" data-id="{{ $source->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
                                                @endif
                                            @endforeach
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
        $(document).on('click','.ConvertCovernote',function(){
            var covernote_id = $(this).data('id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You want to convert covernote into policy!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, convert it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('admin.convert.covernote')}}",
                    type : 'GET',
                    data : {'id':covernote_id},
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Converted!',
                            text: "Covernote has been converted to Policy.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                console.log(data);
                                const url = `{{ route('admin.edit_policy', ':data') }}`.replace(':data', data);
                                window.location.href = url;
                            }
                        });
                    }
                });
            }
            });
        });
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
                    url : "{{route('delete.covernote', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Covernote has been deleted.",
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
