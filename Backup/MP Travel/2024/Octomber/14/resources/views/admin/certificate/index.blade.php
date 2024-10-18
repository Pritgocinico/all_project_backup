@extends('admin.partials.header', ['active' => 'user'])
@section('content')
<div
    class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
    <main class="container-fluid p-0">
        <div class="px-6 px-lg-7 pt-8 border-bottom">
            <div class="d-flex align-items-center mb-5">
                <h1>Certificate</h1>
                <div class="hstack gap-2 ms-auto">
                    @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                    @if(Auth()->user()->role_id == 1 && Auth()->user()->is_manager == "yes")
                    <a href="#" class="btn btn-sm btn-dark" id="export_modal" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_export_users" data-bs-toggle="modal" style="display: none;">
                        Export</a>
                    @endif
                    @endif
                </div>
            </div>
            @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
            <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="generate-certificate-tab" data-bs-toggle="tab"
                        href="#generate-certificate" role="tab" aria-controls="generate-certificate"
                        aria-selected="true">Generate Certificate</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="certificate-list-tab" data-bs-toggle="tab" href="#certificate-list"
                        role="tab" aria-controls="certificate-list" aria-selected="false">Certificate List</a>
                </li>
            </ul>
            @elseif(collect($accesses)->where('menu_id', '14')->first()->status == 1 ||
            collect($accesses)->where('menu_id', '14')->first()->status == 3)
            <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="certificate-list-tab" data-bs-toggle="tab"
                        href="#certificate-list" role="tab" aria-controls="certificate-list"
                        aria-selected="false">Certificate List</a>
                </li>
            </ul>
            @endif
        </div>
        <div class="px-6 px-lg-7 pt-6">
            <div class="tab-content clearfix">
                <div id="generate-certificate"
                    class="tab-pane fade @if (collect($accesses)->where('menu_id', '14')->first()->status == 2) show active @endif" role="tabpanel"
                    aria-labelledby="generate-certificate-tab">
                    <main class="container-fluid px-6 pb-10">
                        <form id="certificateForm" enctype="multipart/form-data">
                            @csrf
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Title<span class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                            value="{{ old('title') }}" id="title">
                                        <span class="error_span" id="title_error"></span>
                                        @error('title')
                                            <div class="text-danger  required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Employee<span class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6 ">
                                        <select name="emp_id" class="form-control" id="emp_id">
                                            <option value="">Select Employee</option>
                                            @foreach ($employeeList as $employee)
                                            <option value="{{ $employee->id }}"
                                                @if ($employee->id == old('emp_id')) {{ 'selected' }} @endif>
                                                {{ $employee->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <span class="error_span" id="emp_id_error"></span>
                                        @error('emp_id')
                                            <div class="text-danger required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Director<span class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="director" class="form-control" id="director"
                                            placeholder="Enter Director" value="{{ old('director') }}">
                                        <span class="error_span" id="director_error"></span>
                                        @error('director')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Director<span class="error_span">*</span></label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="director" class="form-control" id="director"
                                                placeholder="Enter Director" value="{{ old('director') }}">
                                            <span class="error_span" id="director_error"></span>
                                            @error('director')
                                                <div class="text-danger required-msg">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 row align-items-center g-3">

                                    <div class="col-md-4"><label class="form-label mb-0">Manager<span class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="manager" class="form-control" id="manager"
                                            placeholder="Enter Manager" value="{{ old('manager') }}">
                                        <span class="error_span" id="manager_error"></span>
                                        @error('manager')
                                            <div class="text-danger required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                </div>
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Director Signature<span class="error_span">*</span></label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="file" name="director_signature_file" class="form-control" id="director_signature_file"
                                                placeholder="Enter Director" value="{{ old('director_signature_file') }}">
                                                <span class="error_span" id="director_signature_file_error"></span>
                                            @error('director_signature_file')
                                                <div class="text-danger required-msg">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="file" name="director_signature_file" class="form-control" id="director_signature_file"
                                            placeholder="Enter Director" value="{{ old('director_signature_file') }}">
                                        <span class="error_span" id="director_signature_file_error"></span>
                                        @error('director_signature_file')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3">

                                    <div class="col-md-4"><label class="form-label mb-0">Manager Signature<span class="error_span">*</span></label>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="file" name="manager_signature_file" class="form-control" id="manager_signature_file"
                                            placeholder="Enter Director" value="{{ old('manager_signature_file') }}">
                                        <span class="error_span" id="manager_signature_file_error"></span>
                                        @error('manager_signature_file')
                                            <div class="text-danger required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Description<span class="error_span">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description') }}</textarea>
                                        <span class="error_span" id="description_error"></span>
                                        @error('description')
                                            <div class="text-danger required-msg">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3" id="">
                                    <div class="col-md-4"><label class="form-label mb-0">Month</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="month_name" class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach ($monthList as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>

                                <hr class="my-6">
                                <div class="d-flexjustify-content-end gap-2">
                                    <a href="{{ route('certificate.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-dark"
                                        id="certificate_create_button">Save</button>
                                </div>
                            </form>
                        </main>
                    </div>
                    <div id="certificate-list" class="tab-pane fade @if (collect($accesses)->where('menu_id', '14')->first()->status == 1 ||
                            collect($accesses)->where('menu_id', '14')->first()->status == 3) show active @endif"
                        role="tabpanel" aria-labelledby="certificate-list">
                        <div class="px-6 px-lg-7 pt-6">
                            <div id="certificate_table_ajax" class="table-responsive custom-scrollbar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Certificate</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger required-msg"></span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-6">
                            <div class="d-flexjustify-content-end gap-2">
                                <a href="{{ route('certificate.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-dark"
                                    id="certificate_create_button">Save</button>
                            </div>
                        </form>
                    </main>
                </div>
                <div id="certificate-list" class="tab-pane fade @if (collect($accesses)->where('menu_id', '14')->first()->status == 1 ||
                            collect($accesses)->where('menu_id', '14')->first()->status == 3) show active @endif"
                    role="tabpanel" aria-labelledby="certificate-list">
                    <div class="px-6 px-lg-7 pt-6">
                        <div id="certificate_table_ajax" class="table-responsive custom-scrollbar"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Certificate</h1>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="" class="form" action="#">
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center gap-3 mt-6">
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
                        <button type="button" class="btn btn-primary" id="submitBtn"
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
        certificateAjax(1);
    })

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var page = $(this).attr('href').split('page=')[1];
        certificateAjax(page);
    });

    function certificateAjax(page) {
        var search = $('#certificate_search').val();
        $.ajax({
            'method': 'get',
            'url': "{{ route('certificate-ajax') }}",
            data: {
                search: search,
                page: page,
            },
            success: function(res) {
                $('#certificate_table_ajax').html('');
                $('#certificate_table_ajax').html(res);
                $('#certificate_table_list').DataTable({
                    initComplete: function() {
                        var searchInput = $('#certificate_table_list_filter input');
                        searchInput.attr('id', 'certificate_search');
                        searchInput.attr('placeholder', 'Search Certificate');
                    },
                    "pageLength": 30,
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

    function exportCSV() {
        var exportFile = "{{ route('certificate-export') }}";
        var format = $('#export_format').val();
        var search = $('#certificate_search').val();
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

    $(document).ready(function() {
        $('#certificateForm').on('submit', function(e) {
            e.preventDefault();
            var title = $('#title').val();
            var empId = $('#emp_id').val();
            var director = $('#director').val();
            var manager = $('#manager').val();
            var directorSignature = $('#director_signature_file').val();
            var managerSignature = $('#manager_signature_file').val();
            var description = $('#description').val();

            var cnt = 1;
            $('#title_error').html("");
            $('#emp_id_error').html("");
            $('#director_error').html("");
            $('#manager_error').html("");
            $('#director_signature_file_error').html("");
            $('#manager_signature_file_error').html("");
            $('#description_error').html("");

            if (description.trim() == "") {
                $('#description_error').html('Please Enter Description.');
                cnt = 0;
            }
            if (managerSignature == "") {
                $('#manager_signature_file_error').html('Please Select Manager Signature.');
                cnt = 0;
            }
            if (directorSignature == "") {
                $('#director_signature_file_error').html('Please Select Director Signature.');
                cnt = 0;
            }
            if (manager.trim() == "") {
                $('#manager_error').html('Please Enter manager.');
                cnt = 0;
            }
            if (title.trim() == "") {
                $('#title_error').html('Please Enter Title.');
                cnt = 0;
            }
            if (director.trim() == "") {
                $('#director_error').html('Please Enter Director.');
                cnt = 0;
            }
            if (empId == "") {
                $('#emp_id_error').html('Please select Emp ID.');
                cnt = 0;
            }

            if (cnt == 0) {

                return false;
            }

            $('#certificate_create_button').html('<i class="fa fa-spinner fa-spin"></i>');

            var form = this;
            var formData = new FormData(form);
            $.ajax({
                url: '{{ route('certificate.store') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    const link = document.createElement('a');
                    link.href = response.download_url;
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    toastr.success(response.message);
                    certificateAjax(1);
                    form.reset();
                    $('#certificate_create_button').html('save');
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message);
                    $('#certificate_create_button').html('save');
                }
            });
        });
    });

    function deleteCertificate(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Are you sure the delete this Certificate?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('certificate.destroy', '') }}" + "/" + id,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        toastr.success(data.message);
                        certificateAjax(1)
                    },
                    error: function(error) {
                        toastr.error(error.responseJSON.message)
                    }
                });
            }
        });
    }
</script>
@endsection