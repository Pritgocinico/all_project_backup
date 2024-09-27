@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Logs</h1>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logList as $key=>$log)
                            <tr>
                                <td>{{ $logList->firstItem() + $key }}</td>
                                <td>{{ $log->module }}</td>
                                <td>
                                    {{ $log->description }}
                                </td>
                                <td>
                                    {{ isset($log->userDetail)? ucfirst($log->userDetail->name) : 'Admin' }}
                                </td>
                                <td>{{ Utility::convertDmyAMPMFormat($log->created_at) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $logList->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        function deleteRole(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this role?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('role.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
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
                        },
                        error: function(error) {
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    });
                }
            });
        }
    </script>
@endsection
