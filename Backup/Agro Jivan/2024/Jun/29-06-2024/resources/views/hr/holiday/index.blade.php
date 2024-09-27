@extends('layouts.main_layout')
@section('section')

    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="holidayAjaxList(1)"
                                                placeholder="Search Holidays" />
                                        </div>
                                    </div>

                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">                                         

                                            <button type="button" class="btn btn-light-primary me-3"
                                                data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                                <i class="ki-outline ki-exit-down fs-2"></i> Download
                                            </button>
                                            @if (Permission::checkPermission('holiday-list'))     
                                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#add_holiday_modal"
                                                                        class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                                        <i class="ki-outline ki-plus fs-2"></i> Add Holiday
                                                    </a>
                                              </div>
                                            @endif
                                        </div>
                                       
                                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Download Holiday List {{ date("Y") }}</h2>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <form id="" class="form" action="#">
                                                           

                                                            <div class="fv-row mb-10">
                                                                <label
                                                                    class="required fs-6 fw-semibold form-label mb-2">Select
                                                                    Export Format:</label>
                                                                <select name="format" data-placeholder="Select a format"
                                                                    id="export_format" data-hide-search="true"
                                                                    class="form-select form-select-solid fw-bold">
                                                                    <option value="">Select Format</option>
                                                                    <option value="excel">Excel</option>
                                                                    <option value="pdf">PDF</option>
                                                                    <option value="csv">CSV</option>
                                                                </select>
                                                            </div>
                                                            <div class="text-center">
                                                                <button type="reset" class="btn btn-light me-3"
                                                                    data-bs-dismiss="modal">
                                                                    Discard
                                                                </button>

                                                                <button type="button" class="btn btn-primary"
                                                                    onclick="exportCSV()">
                                                                    Download
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="hr_holiday_ajax">
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
    <div class="modal fade" id="add_holiday_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday Date</label>
                        <input class="form-control" type="date" name="holiday_date" id="holiday_date"
                            placeholder="Enter Holiday Date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                        <span id="holiday_date_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday name</label>
                        <input class="form-control" type="text" name="holiday_name" id="holiday_name"
                            placeholder="Enter Holiday Name">
                        <span id="holiday_name_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addHoliday()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
@section('page')
    <script>
         var ajaxList = "{{ route('hr-holiday-ajax') }}";
         var create = "{{route('hr-holiday-create')}}";
         var exportFile = "{{route('hr-holiday-export')}}"
         var token = "{{csrf_token()}}";
    </script>
    <script src="{{ asset('public\assets\js\custom\hr\holiday.js') }}?{{ time() }}"></script>
    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endsection
