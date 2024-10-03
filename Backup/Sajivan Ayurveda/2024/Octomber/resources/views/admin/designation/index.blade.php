@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Designation</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '3')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addDesignationModel"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus"></i>
                                Create Designation</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
                <div id="designation_table_ajax" class=" custom-scrollbar">
                </div>
            </div>
        </main>
        <div class="modal fade" id="addDesignationModel" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" data-bs-backdrop="static"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Designation</h1>
                        <button type="button" class="btn-close close-designation" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Department <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="department" class="form-control" id="department_name">
                                            <option value="">Select Department</option>
                                            @foreach ($departmentList as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red;" id="department_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Designation <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="designation" class="form-control" id="designation_name"
                                            placeholder="Enter Designation Name">
                                            <span style="color: red;" id="designation_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" class="form-control" placeholder="Enter Description" id="description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral close-designation" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="submitBtnCreate"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDesignationModel" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" data-bs-backdrop="static"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Edit Designation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="updateForm">
                        @csrf
                        <input type="hidden" name="designation_id" id="designation_id">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Department <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <select name="department" class="form-control" id="edit_department">
                                            <option value="">Select Department</option>
                                            @foreach ($departmentList as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                        <span style="color: red;" id="edit_department_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Designation <span class="text_danger_require">*</span></label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="designation" class="form-control" id="edit_name"
                                            placeholder="Enter Designation Name">
                                            <span style="color: red;" id="edit_designation_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <textarea name="description" class="form-control" placeholder="Enter Description" id="edit_description"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-dark" id="submitBtnUpdate"
                                onclick="updateDesignation()">Submit</button>
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
            designationAjax(1);
        })

        function designationAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('designation-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#designation_table_ajax').html('');
                    $('#designation_table_ajax').html(res);
                    $("#designation_table").DataTable({
                        initComplete: function() {
                            var searchInput = $('#designation_table_filter input');
                            searchInput.attr('id', 'designation_search');
                            searchInput.attr('placeholder', 'Search Designation');
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
            designationAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('designation-export') }}";
            var format = $('#export_format').val();
            var search = $('#designation_search').val();
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

        document.getElementById('addForm').addEventListener('submit', function(event) {
            event.preventDefault();

            submitForm();
        });

        function submitForm() {
            var department = $('#department_name').val();
            var designation = $('#designation_name').val();
            
            if (department == '' && designation == '') {
                $('#department_error').html('Please Select Department.')
                $('#designation_error').html('Please Enter Designation.')
                    return false;
            }else if(department == ''){
                $('#department_error').html('Please Select Department.')
                return false;
            }else if(designation == ''){
                $('#designation_error').html('Please Enter Designation.')
                return false;
            }else{
                $('#designation_error').html('')
                $('#department_error').html('')
            }


            $('#submitBtnCreate').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('designation.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#addDesignationModel').modal('hide');
                    toastr.success(data.message);
                    designationAjax(1);
                    $('#submitBtnCreate').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#submitBtnCreate').html('Submit');
                }
            });
        }

        $('#department_name').on('change', function() {
            if ($(this).val() != '') {
                $('#department_error').html('');
            }
        });

        $('#designation_name').on('keyup', function() {
            if ($(this).val() != '') {
                $('#designation_error').html('');
            }
        });

        $('.close-designation').on('click', function(){
            $('#addForm').trigger("reset");
        });

        document.getElementById('addForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                submitForm();
            }
        });


        function editDesignation(id) {
            $.ajax({
                url: "{{ route('designation.edit', ['designation' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_name').val(data.data.name);
                    $('#edit_description').val(data.data.description);
                    $('#edit_department').val(data.data.department_id);
                    $('#designation_id').val(id);
                    $('#editDesignationModel').modal('show');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteDesignation(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Designation?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('designation.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            designationAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault();

            updateDesignation();
        });

        function updateDesignation() {
            var departmentName = $('#edit_department').val();
            var designationName = $('#edit_name').val();
            var id = $('#designation_id').val();
            
            if (departmentName == '' && designationName == '') {
                $('#edit_department_error').html('Please Select Department.')
                $('#edit_designation_error').html('Please Enter Designation.')
                deleteDesignation(id);
                    return false;
            }else if(designationName == ''){
                $('#edit_designation_error').html('Please Enter Designation.')
                deleteDesignation(id);
                    return false;
            }else if(departmentName == ''){
                $('#edit_department_error').html('Please Select Department.')
                deleteDesignation(id);
                    return false;
            }else{
                $('#edit_designation_error').html('')
                $('#edit_department_error').html('')
            }

            $('#submitBtnUpdate').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('designation.update', ['designation' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#updateForm').serialize(),
                success: function(data) {
                    $('#editDesignationModel').modal('hide');
                    toastr.success(data.message);
                    designationAjax(1);
                    $('#submitBtnUpdate').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#submitBtnUpdate').html('Submit');
                }
            });
        }

        $('#edit_department').on('change', function() {
            if ($(this).val() != '') {
                $('#edit_department_error').html('');
            }
        });

        $('#edit_name').on('keyup', function() {
            if ($(this).val() != '') {
                $('#edit_designation_error').html('');
            }
        });

        document.getElementById('updateForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                updateDesignation();
            }
        });
    </script>
@endsection
