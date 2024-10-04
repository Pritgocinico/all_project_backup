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
                            @include('employee._partials.search')
                            
                            <div class="card-body py-4 table-responsive" id="employee_order_table_ajax">
                                <div id="sale_report_loader" class="d-none">
                                    <i class="fa fa-spinner fa-spin"></i>
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
    </div>

    </body>
@endsection
@section('page')
    <script>
        var OrderajaxList = "{{route('employee-orders-ajax')}}";
        var exportFile = "{{route('employee-orders-export')}}";
        var subDistrictData = "{{route('get-sub-district-order')}}"
        var date = "{{date('Y-m-d')}}"
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\employeeorder.js') }}?{{ time() }}"></script>
    <script src="{{ asset('public/assets/js/custom/apps/user-management/users/list/export-users.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('public/assets/js/custom/utilities/modals/users-search.js') }}"></script>
@endsection
