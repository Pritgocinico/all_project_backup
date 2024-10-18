@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Document Check List</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '20')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#export_document_checklist">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addDepartmentModal"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus"></i>
                                Create Document Check List</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
                <div id="department_table_ajax" class="table-responsive custom-scrollbar"></div>
            </div>
        </main>
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4">Add Document Check List</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm" action="#" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Country Name<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="country_name" id="country_name" class="form-select">
                                            <option value="">Select Country</option>
                                            @foreach ($countryList as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error_span" id="country_name_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Visa Type<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="visa_type" id="visa_type" class="form-select">
                                            <option value="">Select Country</option>
                                            <option value="Tourist Visa">Tourist Visa</option>
                                            <option value="Business Visa">Business Visa</option>
                                            <option value="Student Visa">Student Visa</option>
                                            <option value="Work Visa">Work Visa</option>
                                            <option value="Family Reunion Visa">Family Reunion Visa</option>
                                            <option value="Transit Visa">Transit Visa</option>
                                            <option value="Refugee or Asylum Visa">Refugee or Asylum Visa</option>
                                            <option value="Investment Visa">Investment Visa</option>
                                            <option value="Retirement Visa">Retirement Visa</option>
                                            <option value="Cultural Exchange Visa">Cultural Exchange Visa</option>
                                            <option value="Diplomatic Visa">Diplomatic Visa</option>
                                            <option value="Special Visa">Special Visa</option>
                                        </select>
                                        <span class="error_span" id="visa_type_error"></span>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Document File<span
                                                    class="error_span">*</span></label></div>
                                        <div class="col-md-10 col-xl-10">
                                            <input type="file" name="document_file" class="form-control"
                                                id="document_file">
                                            <span class="error_span" id="document_file_error"></span>
                                        </div>
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
                        <h1 class="modal-title h4">Edit Document Check List</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="updateForm" action="#" enctype="multipart/form-data">
                        @method('PUT')
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="document_id" id="document_id">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Country Name<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="edit_country_name" id="edit_country_name" class="form-select">
                                            <option value="">Select Country</option>
                                            @foreach ($countryList as $country)
                                                <option value="{{ $country->name }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="error_span" id="edit_country_name_error"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Visa Type<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="edit_visa_type" id="edit_visa_type" class="form-select">
                                            <option value="">Select Country</option>
                                            <option value="Tourist Visa">Tourist Visa</option>
                                            <option value="Business Visa">Business Visa</option>
                                            <option value="Student Visa">Student Visa</option>
                                            <option value="Work Visa">Work Visa</option>
                                            <option value="Family Reunion Visa">Family Reunion Visa</option>
                                            <option value="Transit Visa">Transit Visa</option>
                                            <option value="Refugee or Asylum Visa">Refugee or Asylum Visa</option>
                                            <option value="Investment Visa">Investment Visa</option>
                                            <option value="Retirement Visa">Retirement Visa</option>
                                            <option value="Cultural Exchange Visa">Cultural Exchange Visa</option>
                                            <option value="Diplomatic Visa">Diplomatic Visa</option>
                                            <option value="Special Visa">Special Visa</option>
                                        </select>
                                        <span class="error_span" id="edit_visa_type_error"></span>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Document File</label></div>
                                        <div class="col-md-10 col-xl-10">
                                            <input type="file" name="document_file" class="form-control"
                                                id="document_file">
                                            <span class="error_span" id="document_file_error"></span>
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
        <div class="modal fade" id="export_document_checklist" tabindex="-1"
            aria-labelledby="depositLiquidityModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Document Check list</h1>
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
                                        <span id="format_error" class="error_span"></span>
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
                'url': "{{ route('document-checklist-ajax') }}",
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
            var exportFile = "{{ route('document-checklist-export') }}";
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
            var name = $('#country_name').val();
            var visa = $('#visa_type').val();
            var document = $('#document_file').val();
            var cnt = 0;
            $('#country_name_error').html("")
            $('#visa_type_error').html("")
            $('#document_file_error').html("")
            if (name == "") {
                $('#country_name_error').html("Select Country a Name");
                cnt = 1;
            }
            if (visa == "") {
                $('#visa_type_error').html("Select Visa Type");
                cnt = 1;
            }
            if (document == "") {
                $('#document_file_error').html("Select Document File");
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            $('#submit_department_add').html('<i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData($('#addForm')[0]);
            $.ajax({
                url: "{{ route('document-checklist.store') }}",
                type: 'POST',
                data: formData,
                contentType: false, // Important for file uploads
                processData: false,
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
                url: "{{ route('document-checklist.edit', ['document_checklist' => 'empid']) }}".replace('empid',
                    id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#edit_country_name').val(data.data.country_code);
                    $('#edit_visa_type').val(data.data.visa_type);
                    $('#document_id').val(id);
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
                text: "Are you sure the delete this Document Checklist?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('document-checklist.destroy', '') }}" + "/" + id,
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
            var id = $('#document_id').val()
            var name = $('#edit_country_name').val();
            var visa = $('#edit_visa_type').val();
            var cnt = 0;
            $('#edit_country_name_error').html("")
            $('#edit_visa_type_error').html("")
            if (name == "") {
                $('#edit_country_name_error').html("Select Country a Name");
                cnt = 1;
            }
            if (visa == "") {
                $('#edit_visa_type_error').html("Select Visa Type");
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            $('#update_button_department').html('<i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData($('#updateForm')[0]);
            $.ajax({
                url: "{{ route('document-checklist.update', ['document_checklist' => 'empid']) }}".replace('empid',
                    id),
                type: 'POST',
                data: formData,
                contentType: false, // Important for file uploads
                processData: false,
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
        document.querySelector("#updateForm").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });
        document.querySelector("#addForm").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });
    </script>
@endsection
