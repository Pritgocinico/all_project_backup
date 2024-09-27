@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="row d-flex align-items-center mb-5">
                    <h1 class="col-md-4">Attendance List</h1>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label mb-0">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label mb-0">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                            <div class="col-md-2 mt-6">
                                <button type="button" class="btn btn-sm btn-dark filterDate"
                                    onclick="dateFilter()">Search</button>
                            </div>
                            @if (Auth()->user()->role_id !== 2)
                                <div class="col-md-2 mt-6">
                                    <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_export_users" data-bs-toggle="modal"><i
                                            class="bi bi-plus-lg me-2"></i>
                                        Export</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-6">
                <div class="row align-items-center g-3">
                    <div id="attendance-list" class="table-responsive custom-scrollbar">
                    </div>
                </div>
            </div>
        </main>
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            dateFilter();
        });
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            dateFilter(page);
        });

        function dateFilter(page) {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: '{{ route('attendance.ajax') }}',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    page: page,
                },
                success: function(response) {
                    $('#attendance-list').html("")
                    $('#attendance-list').html(response);
                    $("#attendance_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#attendance_table_filter input');
                            $searchInput.attr('id', 'attendance_search');
                            $searchInput.attr('placeholder', 'Search Attendance');
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
                error: function(xhr) {}
            });
        }

        function exportCSV() {
            var exportFile = "{{ route('attendance-export') }}";
            var format = $('#export_format').val();
            var search = $('#attendance_search').val();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
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
            window.open(exportFile + '?format=' + format + '&start_date=' + startDate + '&end_date=' + endDate, '_blank');
        }
    </script>
@endsection
