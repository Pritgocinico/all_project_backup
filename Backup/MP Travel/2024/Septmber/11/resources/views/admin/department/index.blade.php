@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Department</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '2')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addDepartmentModal"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus"></i>
                                Create Department</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
                <div id="department_table_ajax" class="table-responsive custom-scrollbar">

                </div>
            </div>
        </main>
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Add Department</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Enter Department Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="submit_department_add"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Edit Department</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="updateForm">
                        @csrf
                        <input type="hidden" name="department_id" id="department_id">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <input type="text" name="name" class="form-control" id="edit_name"
                                            placeholder="Enter Department Name">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="update_button_department"
                                onclick="updateDepartment()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Department</h1>
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
                            <button type="button" class="btn btn-dark" id="submitBtn"
                                onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            departmentAjax(1);
        })

        function departmentAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('department-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#department_table_ajax').html('');
                    $('#department_table_ajax').html(res);
                    $("#department_table").DataTable({
                        initComplete: function() {
                            var searchInput = $('#department_table_filter input');
                            searchInput.attr('id', 'department_search');
                            searchInput.attr('placeholder', 'Search Department');
                        },
                        "pageLength": 30,
                        drawCallback: function() {
                            $('#department_table_paginate .paginate_button').addClass(
                                'datatable_paginate');
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
            departmentAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('department-export') }}";
            var format = $('#export_format').val();
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
            var search = $('#department_search').val();
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }

        function submitForm() {
            $('#submit_department_add').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('department.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#addDepartmentModal').modal('hide');
                    toastr.success(data.message);
                    departmentAjax(1);
                    $('#submit_department_add').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                    $('#submit_department_add').html('Submit');
                }
            });
        }

        function editDepartment(id) {
            $.ajax({
                url: "{{ route('department.edit', ['department' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_name').val(data.data.name);
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#department_id').val(id);
                    $('#editDepartmentModal').modal('show');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteDepartment(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Department?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('department.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            departmentAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        function updateDepartment() {
            $('#update_button_department').html('<i class="fa fa-spinner fa-spin"></i>');
            var id = $('#department_id').val()
            $.ajax({
                url: "{{ route('department.update', ['department' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#updateForm').serialize(),
                success: function(data) {
                    $('#editDepartmentModal').modal('hide');
                    toastr.success(data.message);
                    departmentAjax(1);
                    $('#update_button_department').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                    $('#update_button_department').html('Submit');
                }
            });
        }
    </script>
@endsection
