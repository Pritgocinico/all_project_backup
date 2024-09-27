@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div
                                class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                        <input type="text" data-kt-user-table-filter="search" id="search_data"
                                            class="form-control w-250px ps-13" placeholder="Search"
                                            onkeyup="stockAjaxList(1)" />
                                    </div>
                                </div>
                                <div class="card-toolbar">
                                    <div class="d-flex justify-content-end custom-responsive-div"
                                        data-kt-user-table-toolbar="base">
                                        <button type="button" class="btn btn-light-primary me-3" id="search_main_menu"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="ki-outline ki-filter fs-2"></i> Filter
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" id="search_sub_menu"
                                            data-kt-menu="true">
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                            </div>
                                            <div class="separator border-gray-200"></div>
                                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                                    <div class="mb-10">
                                                        <select name="category_id" id="category_id" class="form-select">
                                                            <option value="">Select Category</option>
                                                            @foreach ($categoryList as $category)
                                                                @if (count($category->childCategoryDetails) > 0)
                                                                    <optgroup label="{{ $category->name }}">
                                                                        @foreach ($category->childCategoryDetails as $child)
                                                                            <option
                                                                                value="{{ $child->id }}"@if ($child->id == old('category_id')) {{ 'selected' }} @endif>
                                                                                {{ $child->name }}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @else
                                                                    <option value="{{ $category->id }}"
                                                                        @if ($category->id == old('category_id')) {{ 'selected' }} @endif>
                                                                        {{ $category->name }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="reset"
                                                            class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" onclick="resetForm()"
                                                            data-kt-menu-dismiss="true"
                                                            data-kt-user-table-filter="reset">Reset</button>
                                                        <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                            data-kt-menu-dismiss="true" data-kt-user-table-filter="filter"
                                                            onclick="stockAjaxList(1)">Apply</button>
                                                    </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_export_users">
                                            <i class="ki-outline ki-exit-up fs-2"></i> Export
                                        </button>
                                    </div>

                                    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered mw-650px">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h2 class="fw-bold">Export Stock</h2>

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
                            <div class="card-body py-4 table-responsive" id="batch_data_table"></div>
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
            stockAjaxList(1)
        });

        function stockAjaxList(page) {
            $.ajax({
                method: 'get',
                url: "{{ route('warehouse-stock-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                    category_id: $('#category_id').val(),
                },
                success: function(res) {
                    $('#batch_data_table').html('')
                    $('#batch_data_table').html(res)
                },
            })
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            stockAjaxList(page);
        });
        function resetForm(){
            $('#search_data').val('')
            $('#category_id').val('')
            stockAjaxList(1)
        }
        function exportCSV(){
            var search = $('#search_data').val()
            var category_id =  $('#category_id').val();
            var format = $('#export_format').val();
            $('#export_format_error').html("");
            if(format == ""){
                $('#export_format_error').html('Please select format');
                return false;
            }
            window.open("{{route('warehouse-stock-export')}}" + '?format=' + format + '&search=' + search + '&category_id=' + category_id, '_blank');
        }
    </script>
@endsection
