@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Salary Slip Lists</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#depositLiquidityModal"
                                data-bs-toggle="modal"><i class="fa-solid fa-plus me-2"></i>
                                New Salary Slip</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7  pt-6">
                <div class="overflow-y-lg-auto">
                    <div id="salaryslip_table_ajax"></div>
                </div>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Generate Salary Slip</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="addForm" action="{{ route('salary-slip.store') }}">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2">
                                    <label class="form-label">Month</label>
                                </div>
                                <div class="col-md-10 col-xl-10">
                                    <select class="form-select" name="month" id="month" required>
                                        <option value="">Select Month</option>
                                        @foreach ($pastMonth as $month)
                                            <option value="{{ $month }}">{{ $month }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="createGenerateSlip()" class="btn btn-primary"
                            id="submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Salary Slip</h1>
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
                        <button type="button" class="btn btn-primary" id="submitExport"
                            onclick="exportCSV()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function createGenerateSlip() {
            $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i>')
            $('#addForm').submit();
        }
        $(document).ready(function(e) {
            salaryslipAjax(1);
        })
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            salaryslipAjax(page);
        });

        function salaryslipAjax(page) {
            var search = $('#salary_slip_search').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('salaryslip-ajax') }}",
                data: {
                    search: search,
                    page: page
                },
                success: function(res) {
                    $('#salaryslip_table_ajax').html('');
                    $('#salaryslip_table_ajax').html(res);
                    $('#salary_slip_table_list').DataTable({
                        initComplete: function() {
                            var searchInput = $('#salary_slip_table_list_filter input');
                            searchInput.attr('id', 'salary_slip_search');
                            searchInput.attr('placeholder', 'Search Salary Slip');
                        },
                        'pageLength' :30,
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
            var exportFile = "{{ route('salaryslip-export') }}";
            var format = $('#export_format').val();
            var search = $('#salary_slip_search').val();
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

        function generateSlip(id) {
            $.ajax({
                url: '{{ route('generate-salary-slip') }}',
                type: 'POST',
                data: {
                    'id': id,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    const link = document.createElement('a');
                    link.href = response.download_url;
                    link.download = '';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseText);
                }
            });
        }

        function deleteSalarySlip(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Salary Slip?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('salary-slip.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            salaryslipAjax(1);
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
