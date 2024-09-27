@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
            <div class="row">
              <!-- Zero Configuration  Starts-->
              <div class="col-sm-12">
                <div class="card">
                  <div class="card-header justify-content-between d-flex card-no-border">
                    <h4>Insurance Company List</h4>
                    @if(Auth::user()->id == 1)
                         <a href="{{route('admin.add.company')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Company</a>
                    @else
                        @foreach($permissions as $permission)
                            @if($permission->capability == 'company-create' && $permission->value == 1)
                                 <a href="{{route('admin.add.company')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Company</a>
                            @endif
                        @endforeach
                    @endif
                  </div>
        <div class="card-body table-responsive">
            <table class="table rwd-table mb-0 example1 w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($companies))
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td data-header="Name" class="pt-2">
                                    <a class="fw-bold" href="{{route('admin.edit_company',$company->id)}}">{{$company->name}}</a>
                                </td>
                                <td data-header="Status">
                                    @if ($company->status == 1)
                                        <h4 class="badge bg-success">Active</h4>
                                    @else
                                        <h4 class="badge bg-danger">Deactive</h4>
                                    @endif
                                </td>
                                <td data-header="Created At">{{date('d-m-Y h:i:s',strtotime($company['created_at']))}}</td>
                                <td data-header="Action" class="gc_flex">
                                    <div class="d-flex align-items-center justify-content-start">
                                        @if(Auth::user()->id == 1)
                                            <a href="{{route('admin.edit_company',$company->id)}}"><i class="bi bi-pencil-square ed_btn me-2"></i></a>
                                        @else
                                            @foreach($permissions as $permission)
                                                @if($permission->capability == 'company-edit' && $permission->value == 1)
                                                    <a href="{{route('admin.edit_company',$company->id)}}"><i class="bi bi-pencil-square ed_btn me-2"></i></a>
                                                @endif
                                            @endforeach   
                                        @endif
                                        @if(Auth::user()->id == 1)
                                            <a href="javascript:void(0);" data-id="{{ $company->id }}" class="delete-btn"><i class="bi bi-trash-fill me-2"></i></a>
                                        @endif
                                    </div>

                                    <ul class="action">
                                        @if(Auth::user()->id == 1)
                                            <li class="edit"> <a href="{{route('admin.edit_company',$company->id)}}" class=""><i class="icon-pencil-alt" aria-hidden="true"></i></a>
                                        @else
                                            @foreach($permissions as $permission)
                                                @if($permission->capability == 'company-edit' && $permission->value == 1)
                                                    <li class="edit"> <a href="{{route('admin.edit_company',$company->id)}}" class=""><i class="icon-pencil-alt" aria-hidden="true"></i></a>
                                                @endif
                                            @endforeach   
                                        @endif
                                        @if(Auth::user()->id == 1)
                                            <li class="delete"><a href="javascript:void(0);" data-id="{{ $company->id }}" class="delete-btn"><i class="icon-trash"></i></a></li>
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
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-btn',function(){
            var company_id = $(this).attr('data-id');
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
                    url : "{{route('delete.company', '')}}"+"/"+company_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Company has been deleted.",
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
    $('.example1').DataTable();
</script>
@endsection
