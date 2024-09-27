@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container  container-fluid ">

                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <div class="d-flex custom-responsive-div gap-4">
                                                <?php
                                                $date = new DateTime('now');
                                                $from = $date->format('Y-m-d');
                                                ?>
                                                <input type="text" class="form-control" id="From" name="from"
                                                    value="{{ date('d-m-Y', strtotime($from)) }}">
                                                <?php
                                                $date = new DateTime('now');
                                                $date->modify('last day of this month');
                                                $last = $date->format('d-m-Y');
                                                ?>
                                                <input type="text" class="form-control" id="To" name="to"
                                                    value="{{ date('d-m-Y', strtotime($last)) }}"> 
                                                <input type="button" name="button" class="btn btn-primary" value="Search" onclick="attendanceAjaxList()">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="card-body py-4 overflow-scroll" id="attendance_test_table_ajax">
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
        var manager = "{{request('is_manager')}}";
        var ajaxAttendance = "{{ route('attendance-ajax') }}";
        var deleteURL = "{{ route('category.destroy', 'id') }}"
        var token = "{{ csrf_token() }}"
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\attendance.js') }}?{{ time() }}"></script>
@endsection
