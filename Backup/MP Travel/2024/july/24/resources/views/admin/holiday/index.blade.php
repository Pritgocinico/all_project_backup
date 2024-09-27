@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Holiday Lists</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '10')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-primary" data-bs-target="#depositLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New Holiday</a>
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Holiday Name</th>
                            <th>Holiday Date</th>
                            <th>Status</th>
                            <th>Create At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($holidayList as $key=>$holiday)
                            <tr>
                                <td>{{ $holidayList->firstItem() + $key }}</td>
                                <td>{{ $holiday->holiday_name }}</td>
                                <td>{{ (new \DateTime($holiday->holiday_date))->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $text = 'Active';
                                        $color = 'success';
                                        if ($holiday->status == 0) {
                                            $color = 'danger';
                                            $text = 'Inactive';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                                </td>
                                <td>{{Utility::convertDmyAMPMFormat($holiday->created_at)}}</td>
                                <td class="text-end">
                                    @if (collect($accesses)->where('menu_id', '10')->first()->status == 2)
                                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                aria-expanded="false"><button type="button"
                                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                        class="bi bi-three-dots"></i></button></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="#"
                                                    onclick="editHoliday({{ $holiday->id }})"><i
                                                        class="bi bi-pencil me-3"></i>Edit Holiday</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="javascript:void(0)"
                                                    onclick="deleteHoliday({{ $holiday->id }})"><i
                                                        class="bi bi-trash me-3"></i>Delete Holiday </a>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $holidayList->links() }}
                </div>
            </div>
        </main>
        <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Holiday</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Holiday Date</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="date" name="holiday_date" class="form-control" id="holiday_date"
                                            placeholder="Enter Holiday Date">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="holiday_name" class="form-control" id="holiday_name"
                                            placeholder="Enter Holiday Name">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" class="form-control" placeholder="Enter Description" id="description"></textarea>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6" id="status_div">
                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var today = new Date().toISOString().split('T')[0];
            var holidayDateInput = document.getElementById('holiday_date');
            holidayDateInput.value = today;
            holidayDateInput.min = today;
        });

        function submitForm() {
            $.ajax({
                url: "{{ route('holiday.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
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

        function editHoliday(id) {
            $.ajax({
                url: "{{ route('holiday.edit', ['holiday' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#holiday_name').val(data.data.holiday_name);
                    $('#holiday_date').val(data.data.holiday_date);
                    $('#description').val(data.data.description);
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#depositLiquidityModalLabel').text('Edit Holiday');
                    $('#submitBtn').text('Update');
                    $('#submitBtn').attr('onclick', "updateHoliday(" + id + ")");
                    $('#depositLiquidityModal').modal('show');
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

        function deleteHoliday(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Holiday?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('holiday.destroy', '') }}" + "/" + id,
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

        function updateHoliday(id) {
            $.ajax({
                url: "{{ route('holiday.update', ['holiday' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
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
    </script>
@endsection
