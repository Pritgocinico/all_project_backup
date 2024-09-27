@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
           
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                                <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="categoryAjaxList(1)"
                                                placeholder="Search user" />
                                        </div>
                                    </div>

                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">


                                            <div class="d-flex align-items-center gap-2 gap-lg-3">

                                                <a href="{{ route('category.create') }}"
                                                    class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                                    <i class="ki-outline ki-plus fs-2"></i> Create New Category
                                                </a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4" id="category_table_ajax">
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
        var ajaxList = "{{ route('category-ajax') }}";
        var deleteURL = "{{ route('category.destroy', 'id') }}"
        var token = "{{ csrf_token() }}"
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\category.js') }}?{{ time() }}"></script>
@endsection
