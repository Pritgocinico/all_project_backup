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
                            <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" onkeyup="customerAjaxList(1)"
                                            placeholder="Search order" />
                                    </div>
                                </div>

                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>
                                    </div>

                                </div>

                                <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">Export Customer</h2>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <form id="" class="form" action="#">
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
                                                        <span id="export_format_error" class="text-danger"></span>
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
                        <div class="card-body py-4 table-responsive" id="vip_customer_table">
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
            customerAjaxList(1);
        });

        function customerAjaxList(page) {
            $.ajax({
                method: 'get',
                url:"{{route('vip-customer-ajax')}}",
                data:{
                    page:page,
                    search: $('#search_data').val(),
                },
                success: function(res){
                    $('#vip_customer_table').html('');
                    $('#vip_customer_table').html(res);
                },
            });
        }
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            customerAjaxList(page);
        });
        function exportCSV(){
            var search = $('#search_data').val()
            var format = $('#export_format').val()
            $('#export_format_error').html("");
            if(format == ""){
                $('#export_format_error').html('Please select format');
                return false;
            }
            window.open("{{route('vip-customer-export')}}" + '?format=' + format + '&search=' + search, '_blank');
        }
    </script>
@endsection
