@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                {{ $team->team_id }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="container-fluid ">
                    <!--begin::Stats-->
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="employeeAjaxList(1)"
                                            placeholder="Search User" />
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="order_table_ajax">
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
            employeeAjaxList(1)
        });

        function employeeAjaxList(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('team-view-ajax') }}",
                data: {
                    page: page,
                    id: "{{ $id }}",
                    date:"{{$date}}",
                    search: $('#search_data').val(),
                },
                success: function(res) {
                    $('#order_table_ajax').html('')
                    $('#order_table_ajax').html(res)
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            employeeAjaxList(page);
        });

        function removeTeamMember(id, empID) {
            new swal({
                title: 'Are you sure Remove this employee From Team?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Remove it!'
            }).then(function(isConfirm) {
                if (isConfirm.isConfirmed) {
                    $.ajax({
                        method: 'get',
                        url: "{{ route('remove-team-member') }}",
                        data: {
                            team_id: id,
                            emp_id: empID,
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            employeeAjaxList(1)
                        },
                        error: function(error) {
                            toastr.error(res.responseJSON.message);
                        }
                    })
                }
            });

        }
    </script>
@endsection
