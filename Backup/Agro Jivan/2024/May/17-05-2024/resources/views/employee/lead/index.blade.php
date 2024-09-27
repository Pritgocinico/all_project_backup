@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class=" flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" placeholder="Search Lead" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <div class="parent-filter-menu">
                                            <button type="button" class="btn btn-light-primary me-3 order_filter_option">
                                                <i class="ki-outline ki-filter fs-2"></i> Filter
                                            </button>
                                            <div class="menu filter-menu w-300px w-md-325px" data-kt-menu="true">
                                                <div class="px-7 py-5">
                                                    <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                                </div>
                                                <div class="separator border-gray-200"></div>
                                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">District:</label>
                                                        <select class="form-select form-select-solid fw-bold"
                                                            id="lead_district" data-placeholder="Select option"
                                                            data-allow-clear="true" data-kt-user-table-filter="two-step"
                                                            data-hide-search="true">
                                                            <option value="">Select District</option>
                                                            @foreach ($leadDistricts as $lead)
                                                                <option value="{{ $lead->district }}">
                                                                    {{ $lead->districtDetail->district_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-10">
                                                        <label class="form-label fs-6 fw-semibold">Date:</label>
                                                    <input type="text" name="order_date" id="order_date"
                                                        class="form-control form-select-solid fw-bold search_lead_date"
                                                        max="{{ date('Y-m-d') }}" placeholder="Select Date">
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" onclick="resetSearch()"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit"
                                                            class="btn btn-primary fw-semibold px-6 order_filter_option"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="leadAjaxList(1)">Apply</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if (Permission::checkPermission('lead-add'))
                                            <a href="{{ route('employee-lead.create') }}"
                                                class="btn btn-primary h-40px fs-7 fw-bold">
                                                <i class="ki-outline ki-plus fs-2"></i> New Lead
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="lead_table_ajax">

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
        var disUrl = "{{ route('employee-get-subdistricts') }}";
        var villUrl = "{{ route('employee-get-villages') }}";
        var categoryUrl = "{{ route('employee-get-category') }}";
        var productUrlDetail = "{{ route('employee-get-product-list') }}";
        var productVariantUrlDetail = "{{ route('employee-get-product-variant-list') }}";
        var variantUrl = "{{ route('employee-get_variant') }}";
        var deleteImage = "{{ asset('public/assets/media/icons/delete.png') }}";
        var detailAjax = "{{ route('employee-add-lead-ajax') }}";
        var ajaxList = "{{ route('employee-lead-ajax') }}";
        var exportFile = "{{ route('employee-lead-export') }}";
        var convertUrl = "{{ route('convert-lead-order') }}"
    </script>
    <script src="{{ asset('public\assets\js\custom\employee\lead.js') }}?{{ time() }}"></script>
@endsection
