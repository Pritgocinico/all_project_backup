@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>{{ $page }}</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-primary" data-bs-target="#depositLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New {{ $page }}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-6">
                    <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department Name</th>
                                <th>Created At</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($followUpList as $key=>$follow)
                                <tr>
                                    <td>{{ $followUpList->firstItem() + $key }}</td>
                                    <td>{{ $follow->name }}</td>
                                    <td>{{ Utility::convertDmyAMPMFormat($follow->created_at) }}</td>
                                    <td class="text-end">
                                        @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                                            <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                                    role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                                    aria-expanded="false"><button type="button"
                                                        class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                                            class="bi bi-three-dots"></i></button></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item" href="#"
                                                        onclick="editDepartment({{ $follow->id }})"><i
                                                            class="bi bi-pencil me-3"></i>Edit Department</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                        onclick="deleteDepartment({{ $follow->id }})"><i
                                                            class="bi bi-trash me-3"></i>Delete Department </a>
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No Data Available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end me-2 mt-2">
                        {{ $followUpList->links() }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="attendance_calendar"></div>
                </div>
            </div>
        </main>
        <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Department</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('follow-up.store') }}" method="POST" id="event-form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body row g-3">
                            <div class="col-12">
                                <label for="Type" class="form-label">Select <span class="text-danger">*</span></label>
                                <select name="type" id="type" class="form-control type read-only">
                                    <option value="1">Lead</option>
                                    <option value="2">Task</option>
                                </select>
                                <span class="text-danger subject-error"></span>
                            </div>
                            <div class="col-12 Leads">
                                <label for="Leads" class="form-label">Related To <span
                                        class="text-danger">*</span></label>
                                <select name="type_id" id="Leads" class="form-control type_id">
                                    @foreach ($leads as $lead)
                                        <option value="{{ $lead->id }}">{{ $lead->lead_id }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger subject-error"></span>
                            </div>

                            <div class="col-12">
                                <label for="FollowupType" class="form-label">Followup Type <span
                                        class="text-danger">*</span></label>
                                <select name="followup_type" id="" class="form-control followup_type">
                                    <option value="0" hidden>Select</option>
                                    <option value="1">New Followup</option>
                                    <option value="2">Existing Followup</option>
                                </select>
                                <span class="text-danger subject-error"></span>
                            </div>
                            <div class="new d-none">
                                <div class="col-12">
                                    <label for="Subject" class="form-label">Subject <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control subject" name="subject" id="Subject"
                                        placeholder="" value="{{ old('subject') }}">
                                    <span class="text-danger subject-error"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="Start" class="form-label">Start Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control start_date" name="start_date"
                                            id="start_date" placeholder="" value="{{ old('start_date') }}">
                                        <span class="text-danger start-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="End" class="form-label">End Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control end_date" name="end_date"
                                            id="end_date" placeholder="" value="{{ old('end_date') }}">
                                        <span class="text-danger end-error"></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="Subject" class="form-label">Remarks </label>
                                    <textarea name="remarks" id="Remarks" rows="3" class="form-control">{{ old('file_details') }}</textarea>
                                    <span class="text-danger details-error"></span>
                                </div>
                                <div class="col-12 members">
                                    <select name="emp[]" class="form-control select-type emp" multiple>
                                        <option value="">Select User</option>
                                        @foreach ($employees as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="existing d-none">
                                <div class="col-12">
                                    <label for="Followup" class="form-label">Select Existing Followup<span
                                            class="text-danger">*</span></label>
                                    <select name="followup" id="Followup" class="form-control followup">
                                        <option value="">Select</option>
                                        @foreach ($followUpList as $follow)
                                            <option value="{{$follow->id}}">{{$follow->event_name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger subject-error"></span>
                                </div>
                                <div class="col-12 my-3">
                                    <div class="card p-3 existing-data">
                                        <h6>Subject : <span class="followup_subject"></span></h6>
                                        <p>Remarks : <span class="followup_remarks"></span></p>
                                        <p>Start Date : <span class="followup_start_date"></span></p>
                                        <p>End Date : <span class="followup_end_date"></span></p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="">Followup Status</label>
                                    <select name="status" id="" class="status form-control">
                                        <option value="1">Completed</option>
                                        <option value="2">Extend</option>
                                    </select>
                                </div>
                                <div class="row dates d-none">
                                    <div class="col-md-6">
                                        <label for="Start" class="form-label">Start Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control start_date1" name="start_date"
                                            id="start_date" placeholder="" value="{{ old('start_date') }}">
                                        <span class="text-danger start-error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="End" class="form-label">End Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control end_date1" name="end_date"
                                            id="end_date" placeholder="" value="{{ old('end_date') }}">
                                        <span class="text-danger end-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="formFile" class="form-label">File upload for reference </label>
                                <input class="form-control file" type="file" id="reference_file"
                                    name="reference_file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="title" class="followup_title" value="">
                            <input type="hidden" name="start" class="followup_start" value="">
                            <input type="hidden" name="end" class="followup_end" value="">
                            <input type="hidden" name="select" class="followup_select" value="">
                            <input type="hidden" name="ttype_id" class="followup_ttype_id" value="">
                            <input type="hidden" name="type_id" class="followup_type_id" value="">
                            <input type="hidden" name="remark" class="followup_remark" value="">
                            <input type="hidden" name="followup" class="followup_followup" value="">
                            <input type="hidden" name="employee" class="followup_employee" value="">
                            <input type="hidden" name="type" class="followup_type" value="create">

                            <button type="button" class="btn btn-primary lead_followup">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function submitForm() {
            $.ajax({
                url: "{{ route('department.store') }}",
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

        function editDepartment(id) {
            $.ajax({
                url: "{{ route('department.edit', ['department' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.data.name);
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#status_div').removeClass('d-none');
                    $('#depositLiquidityModalLabel').text('Edit Department');
                    $('#submitBtn').text('Update');
                    $('#submitBtn').attr('onclick', "updateDepartment(" + id + ")");
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

        function updateDepartment(id) {
            $.ajax({
                url: "{{ route('department.update', ['department' => 'empid']) }}".replace('empid', id),
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
        var calendar = $('#attendance_calendar').fullCalendar({
            timeZone: 'UTC',
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: "{{ route('followup-ajax') }}",
            displayEventTime: false,
            eventRender: function(event, element, view) {
                if (event.allDay === 'true') {
                    event.allDay = true;
                } else {
                    event.allDay = false;
                }
            },
            selectable: true,
            selectHelper: true,
            select: function(event_start, event_end, allDay) {
                console.log(event_start);
                var start = $.fullCalendar.formatDate(event_start, "Y-MM-DD");
                var end = $.fullCalendar.formatDate(event_end, "Y-MM-DD");
            },
            eventDrop: function(event, delta) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var end = moment(event.end).format("Y-MM-DD");
                $.ajax({
                    url: SITEURL + '/calendar-crud-ajax',
                    data: {
                        title: event.title,
                        start: start,
                        end: end,
                        id: event.id,
                        type: 'edit'
                    },
                    type: "POST",
                    success: function(response) {
                        displayMessage("Event Updated Successfully");
                    }
                });
            },
            eventClick: function(event) {
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
                            type: "POST",
                            url: SITEURL + '/calendar-crud-ajax',
                            data: {
                                id: event.id,
                                type: 'delete'
                            },
                            success: function(response) {

                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Checklist item has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        calendar.fullCalendar('removeEvents', event
                                            .id);
                                        displayMessage("Event removed");
                                        location.reload();
                                    }
                                });
                            }
                        });

                    }
                });
            }
        });

        $(document).on('change', '.followup_type', function() {
            if ($(this).val() == 1) {
                $('.select-type').select2();
                $('.existing').addClass('d-none');
                $('.new').removeClass('d-none');
            } else if ($(this).val() == 2) {
                var type = $('.type').val();
                $('.new').addClass('d-none');
                $('.existing').removeClass('d-none');
            } else {
                $('.new').addClass('d-none');
                $('.existing').addClass('d-none');
            }
        });
    </script>
@endsection
