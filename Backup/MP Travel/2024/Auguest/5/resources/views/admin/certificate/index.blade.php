@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Certificate</h1>
                    <div class="hstack gap-2 ms-auto">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-user-table-filter="search" id="search_data" style="display: none;"
                                class="form-control w-250px ps-13" onkeyup="certificateAjax()"
                                placeholder="Search Certificate" />
                        </div>
                        <a href="#" class="btn btn-sm btn-primary" id="export_modal" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users"
                                data-bs-toggle="modal" style="display: none;"><i class="bi bi-plus-lg me-2"></i>
                                Export</a>
                    </div>
                </div>
                @if (collect($accesses)->where('menu_id', '14')->first()->status == 2)
                    <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" id="generate-certificate-tab">Generate Certificate</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="certificate-list-tab">Certificate List</a>
                        </li>
                    </ul>
                @elseif(collect($accesses)->where('menu_id', '14')->first()->status == 1)
                    <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4">
                        <li class="nav-item">
                            <a href="#" class="nav-link active" id="certificate-list-tab">Certificate List</a>
                        </li>
                    </ul>
                @endif
            </div>

            <div class="container">
                <div>
                    <!-- Form Section -->
                    <div id="generate-certificate-section"
                        @if (collect($accesses)->where('menu_id', '14')->first()->status == 1) style="display: none;" @else style="" @endif>
                        <main class="container-fluid px-6 pb-10">
                            <form id="certificateForm" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" value="{{ $certificateList->id ?? '' }}">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Title</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="title" class="form-control" placeholder="Enter Title"
                                            value="{{ old('title', $certificateList->name ?? '') }}">
                                        @error('title')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Employee</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="emp_id" class="form-control" id="employee">
                                            <option value="">Select Employee</option>
                                            @foreach ($employeeList as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('emp_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Director</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="director" class="form-control"
                                            placeholder="Enter Director"
                                            value="{{ old('director', $certificateList->name ?? '') }}">
                                        @error('director')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Manager</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="manager" class="form-control"
                                            placeholder="Enter Manager"
                                            value="{{ old('manager', $certificateList->name ?? '') }}">
                                        @error('manager')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Director Signature</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="file" name="director_signature_file" class="form-control"
                                            placeholder="Enter Director"
                                            value="{{ old('director_signature_file', $certificateList->director_signature_file ?? '') }}">
                                        @error('director')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Manager Signature</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="file" name="manager_signature_file" class="form-control"
                                            placeholder="Enter Director"
                                            value="{{ old('manager_signature_file', $certificateList->manager_signature_file ?? '') }}">
                                        @error('manager')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Description</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description">{{ old('description', $certificateList->description ?? '') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status"
                                                checked>
                                        </div>
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Month Name</label></div>
                                    <div class="col-md-4">
                                        <select name="month_name" class="form-control">
                                            <option value="">Select Option</option>
                                            @foreach ($monthList as $month)
                                                <option value="{{ $month }}">{{ $month }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <hr class="my-6">
                                <div class="d-flexjustify-content-end gap-2">
                                    <a href="{{ route('certificate.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </div>
                            </form>
                        </main>
                    </div>

                    <!-- Table Section -->
                    <div id="certificate-list-section"
                        @if (collect($accesses)->where('menu_id', '14')->first()->status == 1) style="" @else style="display: none;" @endif>
                        <div id="certificate_table_ajax">
                            
                        </div> 
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
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Certificate</h1>
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
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
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
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn" onclick="exportCSV()">Submit</button>
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

        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            certificateAjax(page);
        });

        function certificateAjax(page) {
            var search = $('#search_data').val();
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

                },
            });
        }

        function exportCSV() {
            var exportFile = "{{ route('certificate-export') }}";
            var format = $('#export_format').val();
            var search = $('#search_data').val();
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }

        document.getElementById('generate-certificate-tab').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('generate-certificate-section').style.display = 'block';
            document.getElementById('search_data').style.display = 'none';
            document.getElementById('export_modal').style.display = 'none';
            document.getElementById('certificate-list-section').style.display = 'none';
            document.getElementById('generate-certificate-tab').classList.add('active');
            document.getElementById('certificate-list-tab').classList.remove('active');
        });

        document.getElementById('certificate-list-tab').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('generate-certificate-section').style.display = 'none';
            document.getElementById('certificate-list-section').style.display = 'block';
            document.getElementById('search_data').style.display = 'block';
            document.getElementById('export_modal').style.display = 'block';
            document.getElementById('generate-certificate-tab').classList.remove('active');
            document.getElementById('certificate-list-tab').classList.add('active');
        });

        $(document).ready(function() {
            $('#certificateForm').on('submit', function(e) {
                e.preventDefault();

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

                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                certificateAjax(1);
                                form.reset();
                            }
                        });
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endsection
