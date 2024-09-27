@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="d-flex align-items-end position-relative my-1">
                                            <div class="d-flex">
                                                <?php
                                                $date = new DateTime('now');
                                                $from = $date->format('Y-m-d');
                                                ?>
                                                <div class="me-5" >
                                                <label>From</label>
                                                <input type="text" class="form-control" id="From" name="from"
                                                    value="{{ date('d-m-Y', strtotime($from)) }}">
                                                </div>
                                                <div class="me-5">
                                                <label>To</label>
                                                <?php
                                                $date = new DateTime('now');
                                                $date->modify('last day of this month');
                                                $last = $date->format('Y-m-d');
                                                ?>
                                                <input type="text" class="form-control me-5" id="To" name="to"
                                                    value="{{ date('d-m-Y', strtotime($last)) }}">
                                                </div>
                                                </div>
                                                <div>
                                                <input type="button" name="button" class="btn btn-primary" value="Search" onclick="attendanceAjaxList()">
                                                </div>
                                            </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content pt-0 flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid  ">

                    <div id="kt_app_content" class="flex-column-fluid ">
                            <div class="card-body py-4" id="attendance_test_table_ajax">
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
        var ajaxList = "{{route('hr-attendance-ajax')}}";
    </script>
    <script src="{{asset('public/assets/js/custom/hr/attendance.js')}}"></script>
@endsection
