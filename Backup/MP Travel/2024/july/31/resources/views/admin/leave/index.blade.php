@extends('admin.partials.header', ['active' => 'customer'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Leave</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (Auth()->user()->role_id != 1)
                            @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                                <a href="{{ route('leave.create') }}" class="btn btn-sm btn-primary"><i
                                        class="bi bi-plus-lg me-2"></i>
                                    New Leave</a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Leave Type</th>
                            <th>Leave From</th>
                            <th>Leave To</th>
                            <th>Reason</th>
                            <th>Leave Statue</th>
                            <th>Leave Feature</th>
                            <th>Total Leave Day</th>
                            <th>Attachment</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leaveList as $key=>$leave)
                            <tr>
                                <td>{{ $leaveList->firstItem() + $key }}</td>
                                <td class="">
                                    @if (isset($leave->userDetail))
                                        <a class="text-capitalize"
                                            href="{{ route('user.show', $leave->userDetail->id) }}">{{ ucfirst($leave->userDetail->name) }}</a>
                                </td>
                            @else
                                -
                        @endif
                        <td>
                            {{ $leave->leave_type }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y') }}
                        </td>
                        <td>
                            {{ $leave->reason }}
                        </td>
                        <td>
                            @php
                                $status = 'pending';
                                $class = 'warning';
                                if ($leave->leave_status == 1) {
                                    $status = 'Approved';
                                    $class = 'success';
                                } elseif ($leave->leave_status == 2) {
                                    $status = 'Rejected';
                                    $class = 'danger';
                                }
                            @endphp
                            <span class="badge bg-{{ $class }}">{{ $status }}</span>
                        </td>
                        <td>
                            @php
                                $text = 'Full Day';
                                $class = 'warning';
                                if ($leave->leave_feature == 0) {
                                    $text = 'Half Day';
                                    $class = 'success';
                                }
                            @endphp
                            <span class="badge bg-{{ $class }}">{{ $text }}</span>
                        </td>
                        <td>{{ $leave->total_leave_day }}</td>
                        <td>
                            @if (isset($leave->attachment))
                                <a href="{{ Storage::url($leave->attachment) }}" download><img
                                        src="{{ url('assets/img/user/file.png') }}" width="60px"></a>
                            @endif
                        </td>
                        <td>{{ Utility::convertDmyAMPMFormat($leave->created_at) }}</td>
                        <td class="text-end">
                            @if (Auth()->user()->role_id == 1)
                                <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><button
                                            type="button" class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                class="bi bi-three-dots"></i></button></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item @if ($leave->leave_status == 1) d-none @endif"
                                            href="javascript:void(0)" onclick="approveLeave({{ $leave->id }})"><i
                                                class="bi bi-pencil me-3"></i>Approve Leave</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item @if ($leave->leave_status == 2) d-none @endif"
                                            href="javascript:void(0)" onclick="rejectLeave({{ $leave->id }})"><i
                                                class="bi bi-trash me-3"></i>Reject Leave</a>
                                    </div>
                                </div>
                            @else
                                @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                                    <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                            role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                            aria-expanded="false"><button type="button"
                                                class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                    class="bi bi-three-dots"></i></button></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('leave.edit', $leave->id) }}"><i
                                                    class="bi bi-pencil me-3"></i>Edit Leave</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void(0)"
                                                onclick="deleteLeave({{ $leave->id }})"><i
                                                    class="bi bi-trash me-3"></i>Delete Leave</a>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center">No Data Available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $leaveList->links() }}
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        function deleteLeave(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Leave?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leave.destroy', '') }}" + "/" + id,
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

        function approveLeave(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the approve this Leave?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leave.status') }}",
                        type: 'get',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: 1,
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

        function rejectLeave(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the reject this Leave?",
                text: "Enter Reason for Rejection",
                icon: 'warning',
                showCancelButton: true,
                input: 'text',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!',
                customClass: {
                    validationMessage: 'my-validation-message',
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Reason for cancellation is required')
                    }
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leave.status') }}",
                        type: 'get',
                        dataType: 'json',
                        data: {
                            id: id,
                            status: 2,
                            reject_reason: result.value,
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
