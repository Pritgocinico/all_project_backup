@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="loadAttendanceDetail(1)"
                                                placeholder="Search User" />
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
        $(document).ready(function(e){
            loadAttendanceDetail(1)
        })
        function loadAttendanceDetail(page){
            var search =$('#search_data').val();
            var type ="{{$type}}";
            var status = "{{$status}}";
            $.ajax({
                method:'get',
                url:"{{route('daily-attendance-ajax')}}",
                data:{
                    page:page,
                    search:search,
                    type:type,
                    status:status,
                }, success: function(res){
                    $('#daily_attedance_data_table').html('')
                    $('#daily_attedance_data_table').html(res)
                }
            })
        }
        $(document).on('click', '.pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var page = $(this).attr('href').split('page=')[1];
    loadAttendanceDetail(page);
});
    </script>
@endsection