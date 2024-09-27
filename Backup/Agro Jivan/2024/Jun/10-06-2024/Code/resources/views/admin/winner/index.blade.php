@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid ">
                <!--begin::Stats-->

                <div id="kt_app_content" class="flex-column-fluid pt-15">
                    <div id="kt_app_content_container" class="container-fluid">
                        <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                    <input type="text" data-kt-user-table-filter="search" id="search_data" class="form-control w-250px ps-13" onkeyup="winnerAjaxList(1)" placeholder="Search user" />
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end custom-responsive-div" data-kt-user-table-toolbar="base">
                                    <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-filter fs-2"></i> Filter
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                        </div>
                                        <div class="separator border-gray-200"></div>
                                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                                            <div class="mb-10">
                                                <label class="fs-6 fw-semibold mb-2">Date</label>
                                                <input type="text" placeholder="Select Date" class="form-control search_date" id="search_date" name="search_date" value="{{$date}}">&nbsp;
                                            </div>
                                        </div>
                                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                                            <div class="d-flex justify-content-end">
                                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" onclick="resetFormValue()" data-kt-user-table-filter="reset">Reset</button>
                                                <button type="submit" class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" onclick="winnerAjaxList(1)">Apply</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users">
                                        <i class="ki-outline ki-exit-up fs-2"></i> Export
                                    </button>

                                </div>

                                <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">Export Winner</h2>

                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                                <form id="" class="form" action="#">

                                                    <div class="fv-row mb-10">
                                                        <label class="required fs-6 fw-semibold form-label mb-2">Select
                                                            Export Format:</label>
                                                        <select name="format" data-placeholder="Select a format" id="export_format" data-hide-search="true" class="form-select form-select-solid fw-bold">
                                                            <option value="">Select Format</option>
                                                            <option value="excel">Excel</option>
                                                            <option value="pdf">PDF</option>
                                                            <option value="csv">CSV</option>
                                                        </select>
                                                        <span id="export_format_error" class="text-danger"></span>
                                                    </div>
                                                    <div class="text-center">
                                                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">
                                                            Discard
                                                        </button>
                                                        <button type="button" class="btn btn-primary" onclick="exportCSV()">
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
                        <div class="card-body overflow-scroll py-4" id="winner_table_ajax">
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
<input type="hidden" id="winner_role_id" value="{{Auth()->user() !== null ? Auth()->user()->role_id:''}}">
</body>
@endsection
@section('page')
<script>
    var role = $('#winner_role_id').val();
    var ajax = "{{route('show-winner-ajax')}}"
    var exportUrl = "{{route('show-winner-export')}}"
    if (role == 1) {
        ajax = "{{route('admin-show-winner-ajax')}}"
        exportUrl = "{{route('admin-show-winner-export')}}"
    }
    $(function() {
        $('.search_date').daterangepicker({
            autoUpdateInput: false,
            maxDate: moment(),
        }, function(start, end, label) {
            $('.search_date').val(start.format('Y-MM-DD') + '/' + end.format('Y-MM-DD'));
        });
    });
    $(document).ready(function(e) {
        winnerAjaxList();
    });

    function winnerAjaxList() {
        $.ajax({
            method: "get",
            url: ajax,
            data: {
                search: $('#search_data').val(),
                date: $('#search_date').val(),
                type:   "{{$type}}",
            },success:function(res){
                $('#winner_table_ajax').html('');
                $('#winner_table_ajax').html(res);
            }
        })
    }
    function resetFormValue(){
        $('#search_data').val('');
        $('#search_date').val("{{$date}}");
        winnerAjaxList()
    }
    function exportCSV(){
        var format = $('#export_format').val();
        $('#export_format_error').html('');

        if(format == ""){
            $('#export_format_error').html('Please Select Format.');
            return false;
        }
        var type = "{{$type}}";
        window.open(exportUrl+"?format="+format+"&date="+$('#search_date').val()+"&search="+$('#search_data')+"&type="+type)

    }
</script>
@endsection