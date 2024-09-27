@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card my-3">
        <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
            <div class="pe-4 fs-5">Logs</div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive p-3">
            <table id="example" class="table rwd-table mb-0">
                <thead>
                    <tr>
                        <th>Created By</th>
                        <th>Module</th>
                        <th>Logs</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($logs))
                        @foreach ($logs as $log)
                            <tr>
                                <td data-header="Name" class="pt-2">{{$log->user_id}}</td>
                                <td data-header="Module">{{$log->module}}</td>
                                <td data-header="Log">{{$log->log}}</td>
                                <td data-header="Created At">{{date('Y-m-d',strtotime($log->created_at))}}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
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
                    url : "{{route('delete.client', '')}}"+"/"+user_id,
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
                                top.location.href="{{ route('admin.clients') }}";
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
