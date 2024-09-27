@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="loadAttendanceDetail(1)"
                                            placeholder="Search User" />
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
                                                    <a href="#" class="close-btn-filter"><i
                                                            class="fa fa-close"></i></a>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="fs-6 fw-semibold mb-2">Attendance Date</label>
                                                        <input type="text" placeholder="Select Date"
                                                            class="form-control attendance_date" id="attendance_date" name="attendance_date"
                                                            value="">&nbsp;
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetOrderForm()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="loadAttendanceDetail(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="daily_attedance_data_table">
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
            $('#attendance_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: false,
                maxDate: moment(),
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
            loadAttendanceDetail(1)
        })

        function loadAttendanceDetail(page) {
            var search = $('#search_data').val();
            var type = "{{ $type }}";
            var status = "{{ $status }}";
            var date = $('#attendance_date').val();
            $.ajax({
                method: 'get',
                url: "{{ route('daily-attendance-ajax') }}",
                data: {
                    page: page,
                    search: search,
                    type: type,
                    status: status,
                    date: date,
                },
                success: function(res) {
                    $('#daily_attedance_data_table').html('')
                    $('#daily_attedance_data_table').html(res)
                }
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            loadAttendanceDetail(page);
        });
    </script>
@endsection
