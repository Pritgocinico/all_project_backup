@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="teamAjaxList(1)"
                                            placeholder="Search Team" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">


                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>

                                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                                            <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold"
                                                onclick="openTeamModel()">
                                                <i class="ki-outline ki-plus fs-2"></i> Create Team
                                            </a>
                                        </div>

                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Team</h2>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="" class="form" action="#">


                                                        <div class="fv-row mb-10">
                                                            <label class="required fs-6 fw-semibold form-label mb-2">Select
                                                                Export Format:</label>
                                                            <select name="format" data-placeholder="Select a format"
                                                                id="export_format" data-hide-search="true"
                                                                class="form-select form-select-solid fw-bold">
                                                                <option value="">Select Format</option>
                                                                <option value="excel">Excel</option>
                                                                <option value="pdf">PDF</option>
                                                                <option value="csv">CSV</option>
                                                            </select>
                                                            <span id="export_format_error" class="text-danger"></span>
                                                        </div>
                                                        <div class="text-center">
                                                            <button type="reset" class="btn btn-light me-3"
                                                                data-bs-dismiss="modal">
                                                                Discard
                                                            </button>
                                                            <button type="button" class="btn btn-primary"
                                                                onclick="exportCSV()">
                                                                Submit
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="team_table_ajax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    </div>
    </div>
    </div>
    </div>

    </body>
    <div class="modal fade" id="add_team_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="team_create_form">
                        @csrf
                        <input type="hidden" name="team_id" id="team_id" />
                        <div class="row">
                            <label for="Team Name" class="required fs-6 fw-semibold mb-2">Team Name</label>
                            <input type="text" name="team_name" class="form-control" id="team_name">
                        </div>
                        @if (Auth()->user() !== null && Auth()->user()->role_id == 1)
                            <div class="row">
                                <label for="Status" class="required fs-6 fw-semibold mb-2">Select Manager</label>
                                <select name="manager" id="manager" class="form-control">
                                    <option value="">Select Manager</option>
                                    @foreach ($managerList as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="row" id="team_member_div">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Select Team Member</label>
                            <select name="team_member[]" id="team_member" class="form-control js-example-basic-multiple"
                                multiple>
                                <option value="">Select Team Member</option>
                                @foreach ($employeeList as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="team_model_submit_button"
                        onclick="createTeam()">Update</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_team_member_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="add_team_member_form">
                        @csrf
                        <input type="hidden" name="add_team_id" id="add_team_id" />
                        <input type="hidden" name="manager" id="manager" value="1" />
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Select Team Member</label>
                            <select name="team_member[]" id="add_team_member"
                                class="form-control js-example-basic-multiple" multiple>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateTeamMember()">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page')
    <script>
        $(document).ready(function(e) {
            $('#team_member').select2();
            $('#add_team_member').select2();
            teamAjaxList(1);
        });

        function teamAjaxList(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('team-list-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                },
                success: function(res) {
                    $('#team_table_ajax').html('')
                    $('#team_table_ajax').html(res)
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            teamAjaxList(page);
        });
        function exportCSV(){
            var search = $('#search_data').val()
            var format = $('#export_format').val()
            $('#export_format_error').html("");
            if(format == ""){
                $('#export_format_error').html('Please select format');
                return false;
            }
            window.open("{{route('team-export')}}" + '?format=' + format + '&search=' + search, '_blank');
        }

        function createTeam() {
            var id = $('#team_id').val();
            if (id == "") {
                $.ajax({
                    method: 'post',
                    url: '{{ route('team.store') }}',
                    data: $('#team_create_form').serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        $('#add_team_modal').modal('hide');
                        teamAjaxList(1)
                        $('#team_create_form')[0].reset();
                        $('#team_member').val('1').trigger('change');
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message);
                    },
                })
            }
            var url = "{{ route('team.update', 'id') }}";
            $.ajax({
                method: 'PUT',
                url: url.replace('id', id),
                data: $('#team_create_form').serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    $('#add_team_modal').modal('hide');
                    teamAjaxList(1)
                    $('#team_create_form')[0].reset();
                    $('#team_member').val('1').trigger('change');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                },
            })

        }

        function editManager(id) {
            var url = "{{ route('team.edit', 'id') }}";
            $.ajax({
                method: 'get',
                url: url.replace('id', id),
                success: function(res) {
                    $('#manager').val(res.manager_id)
                    $('#exampleModalLabel').html('Edit Manager');
                    $('#team_member_div').addClass('d-none');
                    $('#add_team_modal').modal('show');
                    $('#team_id').val(res.id);
                    $('#team_name').val(res.team_name);
                },
            })
        }

        function openTeamModel() {
            $('#team_member_div').removeClass('d-none');
            $('#exampleModalLabel').html('Create Team');
            $('#add_team_modal').modal('show');
            $('#team_create_form')[0].reset();
            $('#team_member').val('1').trigger('change');
            $('#team_id').val('');
        }

        function addMember(id) {
            $.ajax({
                method: 'get',
                url: "{{ route('team-employee-list') }}",
                data: {
                    id: id,
                },
                success: function(res) {
                    var html = '<option valu="">Select Team Member</option>';
                    $.each(res, function(i, v) {
                        html += "<option value=" + v.id + ">" + v.name + "</option>"
                    })
                    $('#add_team_id').val(id);
                    $('#add_team_member').html('')
                    $('#add_team_member').html(html);
                    $('#add_team_member_modal').modal('show');
                },
            })
        }

        function updateTeamMember() {
            $.ajax({
                method: 'post',
                url: "{{ route('update-team-employee') }}",
                data: $('#add_team_member_form').serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    $('#add_team_member_modal').modal('hide');
                    teamAjaxList(parseInt(document.querySelector('.pagination .page-item.active .page-link').innerText))
                    $('#add_team_member_form')[0].reset();
                    $('#team_member').val('1').trigger('change');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                },
            })
        }
    </script>
@endsection
