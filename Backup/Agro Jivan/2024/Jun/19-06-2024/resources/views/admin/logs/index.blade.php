@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">

                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header d-flex justify-content-between">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="logAjax(1)"
                                            placeholder="Search Log" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">

                                                    <div class="mb-10">
                                                        <label class="fs-6 fw-semibold mb-2">Date</label>
                                                        <input type="text" placeholder="Select Date"
                                                            class="form-control search_date" id="search_date"
                                                            name="search_date" value="{{ date('Y-m-01') }}/{{ date('Y-m-t') }}">&nbsp;
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="logAjax(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="log_table_ajax"></div>
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
        var ajaxList = "{{ route('ajax-logs') }}";
        var date = "{{ date('Y-m-01') }}/{{ date('Y-m-t') }}";
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\log.js') }}?{{ time() }}"></script>
@endsection
