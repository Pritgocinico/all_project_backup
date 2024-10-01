@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>{{ $page }}</h1>
                    <div class="hstack gap-2 ms-auto">
                        <input type="text" name="follow_up_search" id="follow_up_search" class="form-control"
                            value="{{ request('lead_id') }}" placeholder="Search Follow Up" onkeyup="followupAjax()">
                        @if (collect($accesses)->where('menu_id', '19')->first()->status == 2)
                            <a href="{{ route('follow-up.create') }}" class="btn btn-sm btn-dark w-100"><i
                                    class="fa-solid fa-plus"></i>
                                New {{ $page }}</a>
                        @endif
                    </div>
                </div>
            </div>


            <div class="px-6 px-lg-7 pt-6">
                <div class="row g-4 mt-6">
                    @php $i =0; @endphp
                    <div id="sortable" class="list-group ui-sortable"></div>
                </div>
            </div>
        </main>
    </div>
    <!-- Sub Task Modal -->
    <div class="modal fade" id="SubTaskListModal" tabindex="-1" aria-labelledby="SubTaskListModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SubTaskListModalLabel">Add Follow Up Task <span
                            class="follow_up_code"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('followup_checklist_item') }}" method="POST" id="subTaskForm">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="Title" class="form-label">Title <span class="error_span">*</span></label>
                            <input type="text" class="form-control" name="note" id="add_note"
                                value="{{ old('note') }}" placeholder="Enter Title">
                            <span id="note_error" class="error_span"></span>
                            @if ($errors->has('note'))
                                <span class="text-danger">{{ $errors->first('note') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="follow_up_id" class="subtask-id" value="">
                        <input type="hidden" name="status" value="0">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="dueDateModal" tabindex="-1" aria-labelledby="dueDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dueDateModalLabel">Edit Due Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update_due_date') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="dueDate" class="form-label">Due Date <span class="error_span">*</span></label>
                            <input type="date" class="form-control duedate" name="due_date" id="inputDate">
                            @if ($errors->has('val'))
                                <span class="text-danger">{{ $errors->first('val') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="taskId" id="due_date_model_id" value="">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSubTaskListModal" tabindex="-1" aria-labelledby="editSubTaskListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubTaskListModalLabel">Edit Sub Task <span id="sub_Task_code"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST" id="editSubTaskForm">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="Title" class="form-label">Title <span class="error_span">*</span></label>
                            <input type="text" class="form-control sub-val" name="val" id="edit_note"
                                value="{{ old('title') }}" placeholder="Enter Title">
                            <span id="edit_note_error" class="error_span"></span>
                            @if ($errors->has('val'))
                                <span class="text-danger">{{ $errors->first('val') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" class="sub-id" value="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="add_sub_follow_up_button">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var number = 1;
        $('#subTaskForm').on('submit', function(e) {
            var note = $('#add_note').val();
            if (note.trim() == "") {
                e.preventDefault();
                $('#note_error').html('Please Enter Note');
                return false;
            }
            $('#add_follow_up_button').html('<i class="fa fa-spinner fa-spin"></i>');
        })
        var number = "{{ $i }}";
        $(document).ready(function(e) {
            followupAjax();
            $('[data-bs-toggle="tooltip"]').tooltip();
        })
        $('#add_follow_up_data').on('submit', function(e) {
            $('#add_follow_up_button').html('<i class="fa fa-spinner fa-spin"></i>');
        })

        function setSelectTwo(id) {
            $(`.select2${id}`).select2()
        }

        function openSubTaskModal(id, code) {
            $('.subtask-id').val(id);
            $('.follow_up_code').html(code);
            $('#SubTaskListModal').modal('show');
        }

        function openEditSubTaskModal(id) {
            $.ajax({
                'method': 'get',
                'url': "{{ route('edit-sub-task') }}",
                data: {
                    id: id,
                },
                success: function(res) {
                    $('#edit_note_error').html('');
                    $('#sub_Task_code').html(res.data.sub_follow_up_code);
                    $('#edit_note').val(res.data.note);
                    $('.sub-id').val(id);
                    $('#editSubTaskListModal').modal('show');
                },
                error: function(error) {
                    console.log(error);
                }
            })

        }

        function changeDueDate(id, date) {
            $('#due_date_model_id').val(id);
            $('#inputDate').val(date);
            $('#dueDateModal').modal('show');
        }

        function followupAjax() {
            var search = $('#follow_up_search').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('followup-ajax-list') }}",
                data: {
                    search: search,
                },
                success: function(res) {
                    $('#sortable').html('');
                    $('#sortable').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        };

        function exportCSV() {
            var exportFile = "{{ route('followup-export') }}";
            var format = $('#export_format').val();
            var search = $('#follow_up_search').val();
            $('#format_error').html('');
            if (format.trim() == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv', 'excel', 'pdf'];
            if (!allowValues.includes(format)) {
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }
        var calendar = $('#follow_up_calendar').fullCalendar({
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
                            type: "DELETE",
                            url: "{{ route('follow-up.destroy', '') }}" + "/" + event.id,
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
                                        followupAjax(1);
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
        });

        function deleteFollow(id) {
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
                        type: "DELETE",
                        url: "{{ route('follow-up.destroy', '') }}" + "/" + id,
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {

                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    followupAjax(1);
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
        $('.start_date').on('change', function(e) {
            var id = $(this).data('id');
            var date = $(this).val();
            var next_date = moment(date).add(1, 'days').format('YYYY-MM-DD');
            $(`#due_date_${id}`).val(next_date);
            $(`#due_date_${id}`).attr('min', date);
        });

        function changeSubTaskStatue(id) {
            var val = 0;
            if ($(`#checkbox-${id}`).prop('checked')) {
                val = 1
            }
            $.ajax({
                url: "{{ route('update_checklist_status') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'val': val
                },
                dataType: 'json',
                success: function(data) {
                    toastr.success("Sub Task Status updated");
                    $('#loader').addClass('hidden');
                    followupAjax();
                }
            })
        }
        $(document).on('click', '.delete-btn', function() {
            var task_id = $(this).data('id');
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
                        url: "{{ route('follow-up.destroy', '') }}" + "/" + task_id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            followupAjax();
                        }
                    });
                }
            });
        });
        $(document).on('click', '.remove-item', function() {
            var item_id = $(this).attr('data-id');
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
                        url: "{{ route('delete.checklist_item', '') }}" + "/" + item_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            toastr.success(data.message);
                            followupAjax();
                        }
                    });
                }
            });
        });

        function changeStatus(status, task_id) {
            $.ajax({
                url: "{{ route('change_task_status') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'status': status,
                    'id': task_id
                },
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message);
                    followupAjax();
                }
            });
        }

        function changePriority(priority, task_id) {
            $.ajax({
                url: "{{ route('change_priority') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'priority': priority,
                    'id': task_id
                },
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message);
                    followupAjax();
                },
            });
        }
        $('#add_sub_follow_up_button').on('click', function() {
            $('#add_sub_follow_up_button').html('<i class="fa fa-spinner fa-spin"></i>');
            var id = $('.sub-id').val();
            var note = $('#edit_note').val();
            $('#edit_note_error').html('');
            if (note.trim() == "") {
                $('#edit_note_error').html('Please Enter Note');
                openEditSubTaskModal(id)
                return false;
            }
            $.ajax({
                url: "{{ route('update_checklist_note') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'val': note,
                    'id': id,
                    'type': 'ajax'
                },
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message);
                    $('#add_sub_follow_up_button').html('Save Changes');
                    $('#editSubTaskListModal').modal('hide');
                    followupAjax();
                },
                error: function(error) {
                    openEditSubTaskModal(id)
                    $('#add_sub_follow_up_button').html('Save Changes');
                    toastr.error(error.responseJSON.message);
                }
            })
        })
    </script>
@endsection
