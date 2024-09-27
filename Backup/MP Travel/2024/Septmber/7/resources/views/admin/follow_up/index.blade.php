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
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="{{ route('follow-up.create') }}" class="btn btn-sm btn-dark"><i
                                    class="fa-solid fa-plus"></i>
                                New {{ $page }}</a>
                        @endif
                    </div>
                </div>
            </div>


            <div class="px-6 px-lg-7 pt-6">
                <div class="row g-4 mt-6">
                    <div id="sortable" class="list-group ui-sortable">
                        @php $i =0; @endphp
                        @foreach ($leads as $lead)
                            @php $i++; @endphp
                            <li class="list row1 ui-state-default list-unstyled ui-sortable-handle"
                                data-id="{{ $lead->id }}">
                                <div class="d-flex">
                                    <h5>{{ $lead->lead_id}}</h5>
                                    <div class="dropdown ms-auto">
                                        <a href="" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="fa-solid fa-ellipsis-v"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                            <li class="ui-sortable-handle">
                                                <a href="javascript:void(0);" class="dropdown-item edit_list"
                                                    data-id="{{ $lead->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#editListModal">
                                                    Edit List
                                                </a>
                                            </li>
                                            <li class="ui-sortable-handle">
                                                <a class="dropdown-item delete-list" data-id="{{ $lead->id }}"
                                                    href="javascript:void(0);">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="houmanity-card card">
                                    <div class="card-body">
                                        <ul id="task_sortable" class="mb-0 ps-0 ui-sortable" data-task="13">
                                            @foreach($lead->followUpDetail as $followUp)
                                                <li class="row1 ui-state-default task_list list-unstyled py-2 ui-sortable-handle"
                                                data-id="16" data-task="13">
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <i class="fa-solid fa-ellipsis-v"></i>
                                                    </div>
                                                    <div>
                                                        <a href="">
                                                            {{ $followUp->event_name }}
                                                        </a>
                                                    </div>
                                                    <div class="ms-2">
                                                        <div class="btn-group ml-5">
                                                            @if ($followUp->event_status == 1)
                                                                <button
                                                                    class="btn btn-light text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    Not Started
                                                                </button>
                                                            @elseif($followUp->event_status == 3)
                                                                <button
                                                                    class="btn btn-info text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    In Progress
                                                                </button>
                                                            @else
                                                                <button class="btn btn-success text-white btn-sm"
                                                                    type="button">
                                                                    Completed
                                                                </button>
                                                            @endif
                                                            <ul class="dropdown-menu task-status">
                                                                <li><a class="dropdown-item status @if ($followUp->event_status == 1) hide @endif"
                                                                        href="#" data-status="1"
                                                                        data-task="{{ $followUp->id }}">Not Started</a></li>
                                                                <li><a class="dropdown-item status @if ($followUp->event_status == 3) hide @endif"
                                                                        href="#" data-status="2"
                                                                        data-task="{{ $followUp->id }}">In Progress</a>
                                                                </li>

                                                                <li><a class="dropdown-item status @if ($followUp->event_status == 2) hide @endif"
                                                                        href="#" data-status="5"
                                                                        data-task="{{ $followUp->id }}">Complete</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="ms-2">
                                                        <h6><span class="badge bg-light text-dark">Due: <span
                                                                    class="text-danger">{{ Utility::convertFDYFormat($followUp->event_end) }}</span></span>
                                                        </h6>
                                                    </div>
                                                    <div class="ms-2">
                                                        @if (count($followUp->followUpMemberDetail) > 0)
                                                            @foreach ($followUp->followUpMemberDetail as $assignee)
                                                                @php $image = asset('assets/img/follow_up/profile.png') @endphp
                                                                @if (isset($assignee->userDetail) && $assignee->userDetail->profile_image)
                                                                    @php $image = asset('storage/'.$assignee->userDetail->profile_image) @endphp
                                                                @endif
                                                                <img src="{{ $image }}"
                                                                    title="{{ isset($assignee->userDetail) ? $assignee->userDetail->name : '-' }}"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    height="30px" width="30px" class="rounded-circle">
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="ms-2">
                                                        <div class="btn-group ml-5">
                                                            @if ($followUp->priority == 2)
                                                                <button
                                                                    class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    Medium
                                                                </button>
                                                            @elseif($followUp->priority == 3)
                                                                <button
                                                                    class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    High
                                                                </button>
                                                            @elseif($followUp->priority == 4)
                                                                <button
                                                                    class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    Urgent
                                                                </button>
                                                            @else
                                                                <button
                                                                    class="btn btn-dark text-white btn-sm dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    Low
                                                                </button>
                                                            @endif
                                                            <ul class="dropdown-menu priority">
                                                                <li><a class="dropdown-item status @if ($followUp->priority == 1) hide @endif"
                                                                        href="#" data-priority="1"
                                                                        data-task="{{ $followUp->id }}">Low</a></li>
                                                                <li><a class="dropdown-item status @if ($followUp->priority == 2) hide @endif"
                                                                        href="#" data-priority="2"
                                                                        data-task="{{ $followUp->id }}">Medium</a></li>
                                                                <li><a class="dropdown-item status @if ($followUp->priority == 3) hide @endif"
                                                                        href="#" data-priority="3"
                                                                        data-task="{{ $followUp->id }}">High</a></li>
                                                                <li><a cplass="dropdown-item status @if ($followUp->priority == 4) hide @endif"
                                                                        href="#" data-priority="4"
                                                                        data-task="{{ $followUp->id }}">Urgent</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="text-end ms-auto">
                                                        <div class="d-flex float-end">
                                                            <a href="javascript:void(0);" class="p-1 text-dark SubTask"
                                                                onclick="openSubTaskModal({{ $followUp->id }})"
                                                                data-bs-toggle="tooltip" title="Add SubTask">
                                                                <i class="fa fa-plus-square follow_up_list_icon me-2"></i>
                                                            </a>
                                                            <a href="#" class="p-1 text-dark">
                                                                <i
                                                                    class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                                            <input type="hidden" id="inputDate" class="hasDatepicker">
                                                            <a href="javascript:void(0);" class="p-1 text-dark due_date"
                                                                data-id="16" data-bs-toggle="modal"
                                                                data-bs-target="#dueDateModal">
                                                                <i
                                                                    class="fa-solid fa-calendar me-2 follow_up_list_icon"></i>
                                                            </a>

                                                            <a href="javascript:void(0);" data-id="16"
                                                                class="p-1 delete-btn "><i
                                                                    class="fa-solid fa-trash-can me-2 follow_up_list_icon"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul id="subtask-sortable" data-task="{{ $followUp->id }}"
                                                    class="ui-sortable">
                                                    @foreach ($followUp->subTaskData as $sub)
                                                        <li class="row1 sub_task ui-state-default list-unstyled py-1 ui-sortable-handle"
                                                            data-id="{{ $sub->id }}">
                                                            <div class="d-flex">
                                                                <div class="me-2">
                                                                    <i class="fa-solid fa-ellipsis-v"></i>
                                                                </div>
                                                                <div class="mr-25">
                                                                    <div class="round">
                                                                        <input type="checkbox"
                                                                            id="checkbox-{{ $sub->id }}"
                                                                            class="chk" data-id="{{ $sub->id }}"
                                                                            name="chk-item">
                                                                        <label for="checkbox-{{ $sub->id }}"></label>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-2">
                                                                    @if ($sub->task_status == 1)
                                                                        <del>{{ $sub->note }}</del> <span
                                                                            class="badge bg-light text-dark">completed
                                                                            {{ date('d F, Y', strtotime($sub->complete_date)) }}</span>
                                                                    @else
                                                                        {{ $sub->note }}
                                                                    @endif
                                                                </div>
                                                                <div class="text-end ms-auto">
                                                                    <div class="d-flex float-end">
                                                                        <a href="javascript:void(0);"
                                                                            class="p-1 text-dark editsubtask"
                                                                            data-id="{{ $sub->id }}"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editSubTaskListModal"><i
                                                                                class="fa-solid fa-pen-square me-2 follow_up_list_icon"></i></a>
                                                                        </a>
                                                                        <a href="javascript:void(0);"
                                                                            data-id="{{ $sub->id }}"
                                                                            class="p-1 remove-item "><i
                                                                                class="fa-solid fa-trash-can me-2 follow_up_list_icon"></i></a></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <a class="mt-2 select-id collapsed" data-bs-toggle="collapse"
                                    href="#collapseExample{{ $i }}" data-val="{{ $i }}"
                                    role="button" aria-expanded="false" aria-controls="collapseExample">
                                    <span class="lh-lg"> <i class="fa-solid fa-plus"></i>
                                        Add Follow Up</span>
                                </a>
                                <div class="mt-2 collapse" id="collapseExample{{ $i }}" style="">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <form action="#" method="POST" class="row g-3"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="col-12">
                                                            <label for="Subject" class="form-label">Subject <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" name="subject"
                                                                id="Subject" value="" placeholder=" ">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="StartDate" class="form-label">Start Date <span
                                                                    class="text-danger">*</span></label>
                                                            <input type="text" name="start_date" class="form-control"
                                                                value="">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="EndDate" class="form-label">Due Date</label>
                                                            <input type="text" name="due_date" class="form-control"
                                                                value="date('m/d/Y', strtotime('+1 week'))">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="TaskList" class="form-label">Task List <span
                                                                    class="text-danger">*</span></label>
                                                            <select id="TaskList" class="form-select" name="task_list">
                                                                @foreach ($leads as $lead)
                                                                    <option value="{{ $lead->id }}"
                                                                        {{ $followUp->lead_id == $lead->id ? 'selected' : '-' }}>
                                                                        {{ $lead->lead_id }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="Assignees" class="form-label">Assignees</label>
                                                            <select id="Assignees" placeholder="Select..."
                                                                class="form-control select2<?php echo $i; ?>"
                                                                name="assignees[]" multiple="multiple">
                                                                @if (count($employees) > 0)
                                                                    @foreach ($employees as $employee)
                                                                        <option value="{{ $employee->id }}">
                                                                            {{ $employee->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            @if ($errors->has('assignees'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('assignees') }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="Priority" class="form-label">Priority </label>
                                                            <select id="Priority" class="form-select" name="priority">
                                                                <option value="1">Low</option>
                                                                <option value="2" selected="">Medium</option>
                                                                <option value="3">High</option>
                                                                <option value="4">Urgent</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="exampleFormControlTextarea1"
                                                                class="form-label">Description</label>
                                                            <textarea class="form-control" id="exampleFormControlTextarea1" name="description" rows="5"></textarea>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="" class="form-label">Upload Files</label>
                                                            <div class="col-md-6">
                                                                <div
                                                                    class="input-group hdtuto control-group lst increment">
                                                                    <input type="file" name="filenames[]"
                                                                        class="myfrm form-control">
                                                                    <div class="input-group-btn">
                                                                        <button class="btn btn-success ms-2"
                                                                            type="button"><i
                                                                                class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                                                    </div>
                                                                </div>
                                                                <div class="clone d-none">
                                                                    <div class="hdtuto control-group lst input-group"
                                                                        style="margin-top:10px">
                                                                        <input type="file" name="filenames[]"
                                                                            class="myfrm form-control">
                                                                        <div class="input-group-btn">
                                                                            <button class="btn btn-danger ms-2"
                                                                                type="button"><i
                                                                                    class="fldemo glyphicon glyphicon-remove"></i>
                                                                                Remove</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 p-3">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <hr class="my-6" />
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
        {{-- export modal --}}
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Followup</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Sub Task Modal -->
    <div class="modal fade" id="SubTaskListModal" tabindex="-1" aria-labelledby="SubTaskListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="SubTaskListModalLabel">Add Sub Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('followup_checklist_item') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="col-12">
                            <label for="Title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="note" id="Title"
                                value="{{ old('title') }}" placeholder="Enter Title" required>
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
@endsection
@section('script')
    <script>
        var number = "{{ $i }}";
        $(document).ready(function(e) {
            followupAjax(1);
            $('[data-bs-toggle="tooltip"]').tooltip();
        })
        $('.select-id').on('click', function(e) {
            var id = $(this).data('val');
            $(`.select2${id}`).select2()
        })

        function openSubTaskModal(id) {
            $('.subtask-id').val(id);
            $('#SubTaskListModal').modal('show');
        }

        function followupAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('followup-ajax-list') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#followup_table_ajax').html('');
                    $('#followup_table_ajax').html(res);
                    $("#follow_up_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#follow_up_table_filter input');
                            $searchInput.attr('id', 'follow_up_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Follow Up');
                        },
                        lengthChange: false,
                        "order": [
                            [0, 'asc']
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        }]
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            followupAjax(page);
        });

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
    </script>
@endsection
