@extends('admin.layouts.app')

@section('content')
    <div class="project">
        <div class="page-header d-md-flex justify-content-between align-items-center">
            <div class="">
                <h3 class="mb-0">Logs</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created By</th>
                            <th>Module</th>
                            <th>Logs</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!blank($logs))
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->module }}</td>
                                    <td>{{ $log->log }}</td>
                                    <td>{{ date('d/m/Y - H:i:s', strtotime($log->created_at)) }}</td>
                                </tr>
                            @endforeach
                        @endif     
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Created By</th>
                            <th>Module</th>
                            <th>Logs</th>
                            <th>Created At</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
<style>
    .warning-color {
    color: #ffc107; /* Yellow color for warning */
}

</style>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.feedback_delete_btn', function() {
                var feedback_id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this Feedback!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('deleteFeedback', '') }}" + "/" + feedback_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Project has been deleted.",
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
