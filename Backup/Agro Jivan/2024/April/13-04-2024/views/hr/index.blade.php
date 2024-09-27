@extends('layouts.main_layout')
@section('section')
<!--begin::Main-->
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">

            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                <!--begin::Toolbar wrapper-->
                <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">


                    <!--end::Page title-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('hr-employees-add') }}"
                            class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                            <i class="fa-solid fa-plus"></i>&nbsp; Add Employee
                        </a>

                        <!-- <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                            + Add Holiday
                        </a> -->
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar wrapper-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-fluid ">
                <!--begin::Row-->
                <div class="row gy-5 g-xl-10 agro-parent-main-dashboard">
                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                      <a href="{{ route('hr-employee') }}">
                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-grow-early">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="ki-outline ki-people fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$totalEmployee}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                            Total Employees </span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                      </a>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('hr-attendance') }}">
                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-arielle-smile">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                <i class="ki-outline ki-tablet-ok fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$presentCount}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                        Present  Today </span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                      </a>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                       <a href="{{ route('hr-attendance') }}">
                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-midnight-bloom">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="ki-outline ki-tablet-delete fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$absentCount}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                            Absent Today </span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                        </a> 

                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                       <a href="{{ route('hr-holiday') }}">
                        <!--begin::Card widget 2-->
                         <div class="card h-lg-100 bg-grow-ice">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="ki-outline ki-abstract-39 fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$holidayCount}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                            Total Holidays of {{ date('Y') }}</span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                       </a>
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                        <a href="{{ route('hr-ticket') }}">
                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-grow-stone">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="ki-outline ki-message-question fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$ticketCount}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                            Tickets </span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->                              
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                      </a>    

                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="agro-main-dashboard-child">
                      <a href="{{ route('hr-info-sheet.index') }}">
                        <!--begin::Card widget 2-->
                        <div class="card h-lg-100 bg-grow-ice">
                            <!--begin::Body-->
                            <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                <!--begin::Icon-->
                                <div class="m-0">
                                    <i class="ki-outline ki-abstract-26 fs-2hx text-white"></i>

                                </div>
                                <!--end::Icon-->

                                <!--begin::Section-->
                                <div class="d-flex flex-column my-7">
                                    <!--begin::Number-->
                                    <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$infoSheetCount}}</span>
                                    <!--end::Number-->

                                    <!--begin::Follower-->
                                    <div class="m-0">
                                        <span class="fw-semibold fs-2x text-white">
                                            Info Sheets </span>

                                    </div>
                                    <!--end::Follower-->
                                </div>
                                <!--end::Section-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Card widget 2-->
                      </a>  

                    </div>
                    <!--end::Col-->
                </div>

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->




    <!--end::Menu-->
</div>
<!--end::Footer container-->
</div>
<!--end::Footer-->
</div>

</div>
</div>
</div>
</div>

</body>
@endsection
@section('page')
<script>
var ajaxList = "{{route('admin-leave-ajax')}}";
var updateStatus = "{{route('admin-leave-status-update')}}";
</script>
<script src="{{asset('public/assets/js/custom/admin/leave.js')}}"></script>
@endsection