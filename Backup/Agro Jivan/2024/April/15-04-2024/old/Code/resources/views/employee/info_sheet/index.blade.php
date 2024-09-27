@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" placeholder="Search Info Sheet"
                                                onkeyup="infoAjaxList(1)" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
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
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                        <input type="text" placeholder="Select Date" class="form-control search_order_date"
                                                            id="order_date" name="order_date" value="">
                                                    </div>
                                
                                                        <div class="d-flex justify-content-end">
                                                            <button type="reset"
                                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                                data-kt-menu-dismiss="true" onclick="resetSearch()"
                                                                data-kt-user-table-filter="reset">Reset</button>
                                                            <button type="submit"
                                                                class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                                data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                                onclick="infoAjaxList(1)">Apply</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4" id="info_sheet_ajax_list_table"></div>
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
        var ajaxList = "{{ route('employee-info-ajax') }}";
        var exportFile = "{{route('employee-info-export')}}";
        var deleteUrl = "{{route('employee-info-sheet.destroy','id')}}";
        var token = "{{csrf_token()}}";
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\employee-info-sheet.js') }}?{{ time() }}"></script>
@endsection
