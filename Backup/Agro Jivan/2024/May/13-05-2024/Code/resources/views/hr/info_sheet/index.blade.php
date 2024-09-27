@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                              <div class="card-header d-flex justify-content-between">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="infoAjaxList(1)"
                                                placeholder="Search Info Sheet" />
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                        @if (Permission::checkPermission('info-sheet-add'))
                                            <a href="{{route('hr-info-sheet.create')}}"
                                                class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> New Info Sheet
                                            </a>
                                        @endif
                                    </div>
                                    
                                </div>
                            <div class="card-body py-4 table-responsive" id="hr_info_sheet_table">
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
        var ajaxList = "{{route('hr-info-sheet-ajax')}}";
        var deleteUrl = "{{route('hr-info-sheet-delete','id')}}";
        var token = "{{csrf_token()}}";
    </script>
    <script src="{{asset('public/assets/js/custom/hr/info_sheet.js')}}"></script>
@endsection
