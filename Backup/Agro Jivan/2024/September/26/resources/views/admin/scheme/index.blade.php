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
                                                class="form-control w-250px ps-13" onkeyup="schemeAjaxList(1)"
                                                placeholder="Search Scheme" />
                                        </div>
                                    </div>
                                    <div class="card-toolbar">
                                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                            <div class="d-flex align-items-center gap-2 gap-lg-3">
                                                <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold"
                                                    data-bs-toggle="modal" data-bs-target="#add_scheme_modal">
                                                    <i class="ki-outline ki-plus fs-2"></i>Create Scheme
                                                </a>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-4 table-responsive" id="scheme_table_ajax">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="add_scheme_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Scheme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body pt-6">
                        @forelse ($discountType as $discount)
                            <a href="{{route('discount-type-form',$discount->id)}}">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="d-flex flex-stack">
                                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                                <div class="flex-grow-1 me-2">
                                                    <span 
                                                        class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $discount->title }}</span>
                                                    <span
                                                        class="text-muted fw-semibold d-block fs-7">{{ $discount->description }}</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <span
                                            class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            {{ $discount->type }}</span>
                                        <span
                                            class="ms-3 btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                            <i class="ki-outline ki-arrow-right fs-2"></i> </span>
                                    </div>
                                </div>
                            </a>
                            <div class="separator separator-dashed my-4"></div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="storeScheme()">Submit</button>
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
        var storeURL = "{{ route('scheme.store') }}";
        var ajax = "{{ route('scheme-ajax') }}";
        var deleteUrl = "{{route('scheme.destroy','id')}}";
        var token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\scheme.js') }}?{{ time() }}"></script>
@endsection
