@extends('admin.partials.header', ['active' => 'user'])

@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

        <main class="container-fluid p-0">

            <div class="px-6 px-lg-7 pt-8 border-bottom">

                <div class="d-flex align-items-center mb-5">

                    <h1>Holiday</h1>

                    <div class="hstack gap-2 ms-auto">

                        @if (collect($accesses)->where('menu_id', '9')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">

                                Export</a>

                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addHoliday"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus"></i></i>

                                Create Holiday</a>

                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_bulk_import" data-bs-toggle="modal"><i
                                    class="fa-solid fa-file-import"></i>

                                Bulk Import</a>
                        @endif

                    </div>

                </div>

            </div>



            <div class="px-6 px-lg-7 pt-8">

                <div id="holiday_table_ajax" class="table-responsive custom-scrollbar">

                </div>

            </div>

        </main>

        <div class="modal fade" id="addHoliday" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
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
                                            value="{{ old('holiday_date', date('Y-m-d')) }}"
                                            placeholder="Enter Holiday Date">
                                        <span class="error_span" id="holiday_date_error"></span>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-3"><label class="form-label mb-0">Name</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <input type="text" name="holiday_name" class="form-control" id="holiday_name"
                                            placeholder="Enter Holiday Name">
                                        <span class="error_span" id="holiday_name_error"></span>
                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <textarea name="description" class="form-control" placeholder="Enter Description" id="description"></textarea>
                                        <span class="error_span" id="description_error"></span>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6" id="status_div">

                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <div class="form-check form-switch">

                                            <input class="form-check-input" type="checkbox" name="status" id="status"
                                                checked>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                            <button type="button" class="btn btn-primary" id="submitBtnCreate"
                                onclick="submitForm()">Submit</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="modal fade" id="editHoliday" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content overflow-hidden">

                    <div class="modal-header pb-0 border-0">

                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Edit Holiday</h1>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <form class="vstack" method="POST" id="editForm">

                        @csrf

                        <input type="hidden" name="holiday_id" id="holiday_id">

                        <div class="modal-body undefined">

                            <div class="vstack gap-1">

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-3"><label class="form-label mb-0">Holiday Date</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <input type="date" name="holiday_date" class="form-control"
                                            id="edit_holiday_date" value="{{ date('Y-m-d') }}"
                                            placeholder="Enter Holiday Date">
                                        <span class="error_span" id="edit_holiday_date_error"></span>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-3"><label class="form-label mb-0">Name</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <input type="text" name="holiday_name" class="form-control"
                                            id="edit_holiday_name" placeholder="Enter Holiday Name">
                                        <span class="error_span" id="edit_holiday_name_error"></span>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-3"><label class="form-label mb-0">Description</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <textarea name="description" class="form-control" placeholder="Enter Description" id="edit_description"></textarea>
                                        <span class="error_span" id="edit_holiday_description_error"></span>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6" id="status_div">

                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>

                                    <div class="col-md-9 col-xl-9">

                                        <div class="form-check form-switch">

                                            <input class="form-check-input" type="checkbox" name="status"
                                                id="status" checked>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                            <button type="button" class="btn btn-primary" id="submitBtnEdit"
                                onclick="updateHoliday()">Submit</button>

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

                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Holiday</h1>

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

                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="exportCSV()">Submit</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <div class="modal fade" id="kt_modal_bulk_import" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content overflow-hidden">

                    <div class="modal-header pb-0 border-0">

                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Import Holiday</h1>

                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>

                    <form class="form" action="{{ route('bulk-holiday-import') }}" enctype="multipart/form-data"
                        id="importFormSubmit" method="POST">

                        @csrf

                        <div class="modal-body undefined">

                            <div class="vstack gap-1">

                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Import Excel:</label></div>

                                    <div class="col-md-10 col-xl-10">

                                        <input type="file" name="import_file" class="form-control" id="import_file"
                                            required>

                                    </div>

                                </div>

                                <div class="row align-items-center g-3 mt-6">

                                    <a href="{{ asset('assets/excel/Holiday Dummy Excel.xlsx') }}" download>Dummy File</a>

                                </div>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                            <button type="submit" class="btn btn-primary" id="submitBtnImport">Submit</button>

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

            holidayAjax(1);

        })



        function holidayAjax(page) {

            var search = $('#search_data').val();

            $.ajax({

                'method': 'get',

                'url': "{{ route('holiday-ajax') }}",

                data: {

                    search: search,

                    page: page,

                },

                success: function(res) {

                    $('#holiday_table_ajax').html('');

                    $('#holiday_table_ajax').html(res);

                    $("#holiday_table").DataTable({

                        initComplete: function() {

                            var $searchInput = $('#holiday_table_filter input');

                            $searchInput.attr('id', 'holiday_search'); // Assign the ID

                            $searchInput.attr('placeholder', 'Search Holiday');

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



        function exportCSV() {

            var exportFile = "{{ route('holiday-export') }}";

            var format = $('#export_format').val();

            var search = $('#holiday_search').val();

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

        $(document).on('click', '.pagination a', function(event) {

            event.preventDefault();

            $('li').removeClass('active');

            $(this).parent('li').addClass('active');

            var page = $(this).attr('href').split('page=')[1];

            holidayAjax(page);

        });



        function submitForm() {
            var date = $('#holiday_date').val();
            var name = $('#holiday_name').val();
            var description = $('#description').val();
            var cnt = 0;
            $('#holiday_date_error').html('');
            $('#holiday_name_error').html('');
            $('#description_error').html('');
            if (date == "") {
                $('#holiday_date_error').html('Please Enter Date');
                cnt = 1;
            }
            if (name.trim() == "") {
                $('#holiday_name_error').html('Please Enter Holiday Name');
                cnt = 1;
            }
            if (description.trim() == "") {
                $('#description_error').html('Please Enter Holiday Description');
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            $('#submitBtnCreate').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({

                url: "{{ route('holiday.store') }}",

                type: 'POST',

                data: $('#addForm').serialize(),

                success: function(data) {

                    $('#addForm').trigger("reset");

                    $('#addHoliday').modal('hide');

                    toastr.success(data.message);

                    holidayAjax(1);

                    $('#submitBtnCreate').html('Submit');

                },

                error: function(error) {

                    toastr.error(error.responseJSON.message)

                    $('#submitBtnCreate').html('Submit');

                }

            });

        }



        function editHoliday(id) {

            $.ajax({

                url: "{{ route('holiday.edit', ['holiday' => 'empid']) }}".replace('empid', id),

                type: 'GET',

                dataType: 'json',

                success: function(data) {

                    $('#holiday_id').val(id)

                    $('#edit_holiday_name').val(data.data.holiday_name);

                    $('#edit_holiday_date').val(data.data.holiday_date);

                    $('#edit_description').val(data.data.description);

                    $('#status').prop('checked', data.data.status);

                    $('#editHoliday').modal('show');

                },

                error: function(error) {

                    toastr.error(error.responseJSON.message)

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

                            toastr.success(data.message);

                            holidayAjax(1);

                        },

                        error: function(error) {

                            toastr.error(error.responseJSON.message)

                        }

                    });

                }

            });

        }



        function updateHoliday() {

            var id = $('#holiday_id').val();
            var date = $('#edit_holiday_date').val();
            var name = $('#edit_holiday_name').val();
            var description = $('#edit_description').val();
            var cnt = 0;
            $('#edit_holiday_date_error').html('');
            $('#edit_holiday_name_error').html('');
            $('#edit_holiday_description_error').html('');
            if (date == "") {
                $('#edit_holiday_date_error').html('Please Enter Date');
                cnt = 1;
            }
            if (name.trim() == "") {
                $('#edit_holiday_name_error').html('Please Enter Holiday Name');
                cnt = 1;
            }
            if (description.trim() == "") {
                $('#edit_holiday_description_error').html('Please Enter Holiday Description');
                cnt = 1;
            }
            if (cnt == 1) {
                editHoliday(id)
                return false;
            }

            $('#submitBtnEdit').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({

                url: "{{ route('holiday.update', ['holiday' => 'empid']) }}".replace('empid', id),

                type: 'PUT',

                data: $('#editForm').serialize(),

                success: function(data) {

                    $('#editHoliday').modal('hide');

                    toastr.success(data.message);

                    holidayAjax(1);

                    $('#submitBtnEdit').html('Submit');

                },

                error: function(error) {

                    toastr.error(error.responseJSON.message)

                    $('#submitBtnEdit').html('Submit');
                    editHoliday(id);

                }

            });

        }

        $('#importFormSubmit').on('submit', function(e) {

            $('#submitBtnImport').html('<i class="fa fa-spinner fa-spin"></i>');

        });
    </script>
@endsection
