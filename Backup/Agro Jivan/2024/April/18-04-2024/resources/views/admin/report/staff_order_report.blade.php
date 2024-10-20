@extends('layouts.main_layout')
@section('section')
<style>
    #loader {
    position: fixed;
    z-index: 9999;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    /* background-color: rgba(255, 255, 255, 0.7); */
}

#loader i {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="orderReportAjax(1)"
                                            placeholder="Search Order" />
                                    </div>
                                </div>
                                
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu custom-close w-300px w-md-325px"
                                            data-kt-menu="true">
                                            <div class="px-7 py-5 d-flex align-items-center justify-content-between">
                                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                <a href="#" class="close-btn-filter"><i class="fa fa-close"></i></a>
                                            </div>
                                            <div class="separator border-gray-200"></div>
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                <div class="mb-10">
                                                    <label class="fs-6 fw-semibold mb-2">Date</label>
                                                    <input type="text" placeholder="Select Date" class="form-control search_date"
                                                    id="search_date" name="search_date" value="{{ date('Y-m-01') }}/{{ date('Y-m-t') }}">&nbsp;
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="reset"
                                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                        data-kt-menu-dismiss="true" onclick="resetForm()"
                                                        data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" 
                                                        onclick="orderReportAjax(1)">Apply</button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>
                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Staff Order</h2>

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
                                                            <span id="format_error" class="text-danger"></span>
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
                            <div id="loader" class="d-none">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                            <div class="card-body py-4 table-responsive" id="product_table_ajax">
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
@endsection
@section('page')
    <script>
        $(document).ready(function(e) {
            orderReportAjax(1);
        });
        function resetForm(){
            $('#search_data').val('')
            $('#search_date').val('')
            orderReportAjax(1);
        }
        function orderReportAjax(page) {
            $('#loader').removeClass('d-none')
            var search = $('#search_data').val();
            var date = $('#search_date').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('staff-order-report-ajax') }}",
                data: {
                    page: page,
                    search: search,
                    search_date: date,
                },
                success: function(res) {
                    $('#product_table_ajax').html('');
                    $('#product_table_ajax').html(res);
                    $('#loader').addClass('d-none')
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            orderReportAjax(page);
        });
        
        function exportCSV(){
            var format = $('#export_format').val();
            var cnt = 0;
            $('#format_error').html('');

            if(format == ""){
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var search = $('#search_data').val();
            var search_date = $('#search_date').val();
            window.open("{{route('staff-order-report-export')}}" + '?format=' + format + '&search=' + search +'&search_date='+search_date, '_blank');
        }
        $(function() {
                $('.search_date').daterangepicker({
                    autoUpdateInput: false,
                    maxDate: moment(),
                    startDate: moment().startOf('month'),
                    endDate: moment().endOf('month'),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1,
                                'month').endOf(
                                'month')
                        ]
                    }

                }, function(start, end, label) {
                    $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
                });
            });
    </script>
@endsection
