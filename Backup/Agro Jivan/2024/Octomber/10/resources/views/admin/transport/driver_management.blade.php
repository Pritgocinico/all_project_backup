@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid">
                        <div id="kt_app_content_container" class="app-container container-fluid">
                            <div class="card-header custom-responsive-div d-flex justify-content-between">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="driverAjaxList(1)"
                                            placeholder="Search user" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                                            <a href="{{ route('employees.create') }}"
                                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> Create Driver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body table-responsive" id="driver_table_ajax">
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
            driverAjaxList(1);
        })
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            driverAjaxList(page);
        });

        function driverAjaxList(page){
             $.ajax({
                method:'get',
                url:"{{route('driver-management-ajax')}}",
                data:{
                    page:page,
                    search:$('#search_data').val(),
                },
                success:function(res){
                    $('#driver_table_ajax').html('');
                    $('#driver_table_ajax').html(res);
                }
             })
        }
    </script>
@endsection
