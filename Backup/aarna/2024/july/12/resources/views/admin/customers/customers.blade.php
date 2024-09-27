@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header pb-0 card-no-border  justify-content-between d-flex">
            <h4>Customers List</h4>
                @if(Auth::user()->id == 1)
                          <a href="{{route('admin.add.customer')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer</a>
                @else
                    @foreach($permissions as $permission)
                        @if($permission->capability == 'customer-create' && $permission->value == 1)
                            <a href="{{route('admin.add.customer')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Customer</a>
                        @endif
                    @endforeach
                @endif
            </div>
            <div class="card-body">
            <div class="table-responsive custom-scrollbar">
                <table class="display border" id="customers" style="width:100%">
                <thead>
                    <tr>
                    <th>#</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th>#</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
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
$(function() {
    $('#customers').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('customers.data') }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'phone', name: 'phone'},
                {data: 'email', name: 'email'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false},
            ]
        });
    });

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
                    url : "{{route('delete.customer', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Customer has been deleted.",
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
