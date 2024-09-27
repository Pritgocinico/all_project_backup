@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="container-fluid ">
                    <!--begin::Stats-->
                    
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container container-fluid">
                                  <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                     <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="salaryslipAjax(1)"
                                                placeholder="Search user" />
                                        </div>
                                    </div>

                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                            <button type="button" class="btn btn-light-primary me-3"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px"
                                                data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Role:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="role_dropdown_filter" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="role"
                                                            data-hide-search="true">
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Status:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="employee_status" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select Status</option>
                                                            <option value="1">Active</option>
                                                            <option value="0">Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="salaryslipAjax(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_export_users">
                                                <i class="ki-outline ki-exit-up fs-2"></i> Export
                                            </button>
                                            @if (Permission::checkPermission('salary-slip-create'))
                                            <div class="d-flex align-items-center gap-2 gap-lg-3">

                                                <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold" data-bs-toggle="modal"
                                                    data-bs-target="#generate_salaryslip">
                                                    Generate Salary Slip
                                                </a>
                                            </div>
                                            @endif

                                        </div>

                                        <div class="modal fade" id="kt_modal_export_users" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="fw-bold">Export Salary Slip</h2>

                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                        <form id="" class="form" action="#">
                                                            <div class="fv-row mb-10">
                                                                <label class="fs-6 fw-semibold form-label mb-2">Select
                                                                    Roles:</label>
                                                                <select name="role" id="role_dropdown_export"
                                                                    data-placeholder="Select a role"
                                                                    data-hide-search="true"
                                                                    class="form-select form-select-solid fw-bold">
                                                                    <option value="">Select Role</option>
                                                                    
                                                                </select>
                                                            </div>

                                                            <div class="fv-row mb-10">
                                                                <label
                                                                    class="fs-6 fw-semibold form-label mb-2">Select
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
                            <div class="card-body py-4 table-responsive" id="salaryslip_table_ajax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="generate_salaryslip" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Salary Slip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="Status" class="fs-6 fw-semibold mb-2">Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Select Month</option>
                            <option value="{{$monthNumber}}">{{$lastMonth}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addSalaryslip()">Submit</button>
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
    var storeURL = "{{ route('hr-salaryslip.store') }}";
    var getSalaryDetail = "{{route('hr-calculate-salary')}}";
    var ajaxList = "{{route('hr-salary-slip-ajax')}}"
    var token = "{{ csrf_token() }}";
    var salaryDetail = "{{route('generate-salary-pdf')}}"
</script>
<script src="{{ asset('public\assets\js\custom\hr\salaryslip.js') }}?{{ time() }}"></script>
@endsection
