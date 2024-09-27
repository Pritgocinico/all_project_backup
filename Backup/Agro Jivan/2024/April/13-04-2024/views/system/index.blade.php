@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div class="row gy-5 g-xl-10 agro-parent-main-dashboard">
                        <div class="agro-main-dashboard-child">
                            <a href="{{ route('engineer-ticket') }}">
                                <div class="card h-lg-100 bg-grow-early">
                                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                                        <div class="m-0">
                                            <i class="ki-outline ki-people fs-2hx text-white"></i>
                                        </div>
                                        <div class="d-flex flex-column my-7">
                                            <span class="fw-semibold fs-3x text-white lh-1 ls-n2">{{$ticketCount}}</span>
                                            <div class="m-0">
                                                <span class="fw-semibold fs-2x text-white">
                                                    Total Ticket </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div id="kt_app_content_container" class="app-container  container-fluid mt-sm-5 mt-2 pt-sm-5 pt-2">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="ticketAjaxList(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>
                        
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                        <button type="button" class="btn btn-light-primary me-3"  id="search_main_menu"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-outline ki-filter fs-2"></i> Filter
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" id="search_sub_menu"
                                            data-kt-menu="true">
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                            </div>
                                            <div class="separator border-gray-200"></div>
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Status:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                        id="order_status" data-placeholder="Select option"
                                                        data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                        data-hide-search="true">
                                                            <option value="">Select Status</option>
                                                            <option value="1">Pending Order</option>
                                                            <option value="2">Confirmed </option>
                                                            <option value="3">On Delivery </option>
                                                            <option value="4">Cancel Order </option>
                                                            <option value="5">Returned </option>
                                                        </select>
                                                    </div>
                                                <div class="mb-10">
                                                    <label class="form-label fs-6 fw-semibold">Date:</label>
                                                    <input type="text" name="order_date" id="order_date" class="form-control form-select-solid fw-bold search_order_date" max="{{date('Y-m-d')}}" placeholder="Select Date" onchange="employeeOrderAjaxList(1)">
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="reset"
                                                        class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                        data-kt-menu-dismiss="true"
                                                        data-kt-user-table-filter="reset">Reset</button>
                                                    <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                        data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                        onclick="ticketAjaxList(1)">Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="order_table_ajax"></div>
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
    $(document).ready(function(e) {
            ticketAjaxList(1)
        });

        function ticketAjaxList(page) {
            $.ajax({
                method:'get',
                url: "{{route('engineer-ticket-ajax')}}",
                data:{
                    page:page,
                    search:$('#search_data').val(),
                    date:$('#ticket_date').val(),
                    e_id:$('#emp_id').val(),
                },
                success: function(res){
                    $('#order_table_ajax').html('')
                    $('#order_table_ajax').html(res)
                },
            })
        }
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            ticketAjaxList(page);
        });
</script>
@endsection
