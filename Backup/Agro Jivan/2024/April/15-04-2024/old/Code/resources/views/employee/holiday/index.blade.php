@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                                 <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="holidayAjaxList(1)"
                                                placeholder="Search Log" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                            <button type="button" class="btn btn-light-primary me-3"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                                <i class="ki-outline ki-exit-up fs-2"></i> Download
                                            </button>
                                        </div>
                            
                                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Download Holiday</h2>
                            
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <form id="" class="form" action="#">
                                                           
                            
                                                            <div class="fv-row mb-10">
                                                                <label
                                                                    class="required fs-6 fw-semibold form-label mb-2">Select
                                                                    Download Format:</label>
                                                                <select name="format" data-placeholder="Select a format"
                                                                    id="export_format" data-hide-search="true"
                                                                    class="form-select form-select-solid fw-bold">
                                                                    <option value="">Select Format</option>
                                                                    <option value="excel">Excel</option>
                                                                    <option value="pdf">PDF</option>
                                                                    <option value="csv">CSV</option>
                                                                </select>
                                                                <span id="export_format_error" class="text-danger"></span>
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
                            <div class="card-body py-4" id="holiday_table_ajax">
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
    <input type="hidden" name="holiday_role_id" value="{{Auth()->user() !== null? Auth()->user()->role_id:''}}" id="holiday_role_id">
    </body>
@endsection
@section('page')
    <script>
        var role_id = $('#holiday_role_id').val();
        var ajaxList = "{{ route('holiday-ajax') }}";
        var exportFile = "{{route('holiday-export')}}"
        if(role_id == 4){
            ajaxList = "{{ route('confirm-holiday-ajax') }}";
            exportFile = "{{route('confirm-holiday-export')}}"
        }
        if(role_id == 5){
            ajaxList = "{{ route('driver-holiday-ajax') }}";
            exportFile = "{{route('driver-holiday-export')}}"
        }
        if(role_id == 7){
            ajaxList = "{{ route('transport-holiday-ajax') }}";
            exportFile = "{{route('transport-holiday-export')}}"
        }
        if(role_id == 8){
            ajaxList = "{{ route('warehouse-holiday-ajax') }}";
            exportFile = "{{route('warehouse-holiday-export')}}"
        }
        if(role_id == 9){
            ajaxList = "{{ route('sales-holiday-ajax') }}";
            exportFile = "{{route('sales-holiday-export')}}"
        }
        if(role_id == 10){
            ajaxList = "{{ route('sale-service-holiday-ajax') }}";
            exportFile = "{{route('sale-service-holiday-export')}}"
        }
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\holiday.js') }}?{{ time() }}"></script>
@endsection
