@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <!--begin::Stats-->
                    <div class="row gx-6 gx-xl-9">

                        <div class="col-lg-6 col-xxl-6">
                            <div class=" h-100">
                                <div class="card-body p-9">
                                    <div class="fs-2hx fw-bold" id="total_employee_count">{{ count($employeeList) }}</div>
                                    <div class="symbol-group symbol-hover mb-9">
                                        @foreach ($employeeList as $key => $employee)
                                            @php $color = "success" @endphp
                                            @if ($key == 0)
                                                @php $color = "warning" @endphp
                                            @endif
                                            @if ($key == 1)
                                                @php $color = "primary" @endphp
                                            @endif
                                            @if ($key == 2)
                                                @php $color = "info" @endphp
                                            @endif
                                            @if ($key < 4)
                                                <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                                                    title="{{ $employee->name }}">
                                                    <span
                                                        class="symbol-label bg-{{ $color }} text-inverse-{{ $color }} fw-bold">{{ ucfirst(substr($employee->name, 0, 1)) }}</span>
                                                </div>
                                            @else
                                                <a href="#" class="symbol symbol-35px symbol-circle">
                                                    <span
                                                        class="symbol-label bg-dark text-gray-300 fs-8 fw-bold">+{{ count($employeeList) - 4 }}</span>
                                                </a>
                                                @php break; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                    {{-- <div class="d-flex">
                                        <a href="#" class="btn btn-primary btn-sm me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_view_users">All
                                            Clients</a>
                                        <a href="#" class="btn btn-light btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_users_search">Invite New</a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5 col-xl-3 col-xxl-2 mx-auto">
                            <canvas id="present_absent_chart" class="attendance-chart"></canvas>
                        </div>
                    </div>
                    <div id="kt_app_content" class="flex-column-fluid pt-15">
                        <div id="kt_app_content_container" class="container-fluid">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="employeeAjaxList(1)"
                                            placeholder="Search user" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <button type="button" class="btn btn-light-primary me-3"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-outline ki-filter fs-2"></i> Filter
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
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
                                                        <option value="">Select Role</option>
                                                        @foreach ($roleList as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}
                                                            </option>
                                                        @endforeach
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
                                                        data-kt-menu-dismiss="true" onclick="resetFormValue()"
                                                        data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                        onclick="employeeAjaxList(1)">Apply</button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>

                                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                                            <a href="{{ route('employees.create') }}"
                                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> Create Employee
                                            </a>
                                        </div>

                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Users</h2>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                    <form id="" class="form" action="#">
                                                        <div class="fv-row mb-10">
                                                            <label class="fs-6 fw-semibold form-label mb-2">Select
                                                                Roles:</label>
                                                            <select name="role" id="role_dropdown_export"
                                                                data-placeholder="Select a role" data-hide-search="true"
                                                                class="form-select form-select-solid fw-bold">
                                                                <option value="">Select Role</option>
                                                                @foreach ($roleList as $role)
                                                                    <option value="{{ $role->id }}">
                                                                        {{ $role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

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
                            <div class="card-body overflow-scroll py-4" id="employee_table_ajax">
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
    <div class="modal fade modal-xl" id="edit_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Offer Letter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('generate-offer-letter') }}" id="department_edit_form">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="" id="id">
                        <input type="hidden" name="offer_description" value="" id="offer_description">
                        <textarea name="offer_text" id="offer_text"></textarea>
                        <h4 class="mt-3">Dummy Text</h4>
                        <p>Congratulations for accepting the offer for the full-time Data Analyst at AgroJivan. We are pleased
                            to offer you the full-time employment as an Intern Data Analyst.</p>
                        <p>This offer has been issued on the basis of your core experience in Python & SQL. You will be working
                            with our team to gain knowledge on various projects.</p>
                        <p>You are entitled to work with us from Monday to Friday. Your duties and assignments will be referred
                            to you during your induction at the company.</p>
                        <p>This internship will be starting from 01st of January 2024 to 30th Jun 2024. You will be paid 12000/-
                            INR per month as an Internship remuneration.</p>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" onclick="closeOfferModel()">Generate Letter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('page')
    <script>
        var ajaxList = "{{ route('employee-ajax') }}"
        var exportFile = "{{ route('employee-export') }}";
        var deleteURL = "{{ route('employees.destroy', 'id') }}";
        var token = "{{ csrf_token() }}";
        var label = @json($data['labels']);
        var jsonData = @json($data['data']);
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.17.2/ckeditor.js"></script>
    <script src="{{ asset('public\assets\js\custom\admin\employee.js') }}?{{ time() }}"></script>
    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
    <script src="{{ asset('public/assets/plugins/global/chart/chart.js') }}"></script>
@endsection
