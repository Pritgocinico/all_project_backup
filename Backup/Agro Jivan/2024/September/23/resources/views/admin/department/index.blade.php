@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">

                    <div id="kt_app_content" class=" flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-header border-0">
                                <div class="card-title row">
                                    <div class="col-md-9">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" onkeyup="departmentAjax(1)"
                                                placeholder="Search Department" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center gap-2 gap-lg-3 justify-content-end">

                                            <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold"
                                                data-bs-toggle="modal" data-bs-target="#add_department_modal">
                                                <i class="ki-outline ki-plus fs-2"></i> Create Department
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-body overflow-scroll py-4" id="department_table_ajax">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="add_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <label for="Status" class="required fs-6 fw-semibold mb-2">Department Name</label>
                        <input class="form-control" type="text" name="department_name" id="department_name"
                            placeholder="Enter Department Name">
                        <span id="department_name_error" class="text-danger"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="addDepartment()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit_department_modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="department_edit_form">
                        <div class="row">
                            <input type="hidden" name="id" value="" id="id">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Department Name</label>
                            <input class="form-control" type="text" name="edit_department_name"
                                id="edit_department_name" placeholder="Enter Category Name">
                            <span id="edit_department_name_error" class="text-danger"></span>
                        </div>
                        <div class="row">
                            <label for="Status" class="required fs-6 fw-semibold mb-2">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateDepartment()">Update</button>
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
        var storeURL = "{{ route('department.store') }}";
        var ajax = "{{ route('department-ajax') }}";
        var edit = "{{ route('department.edit', 'id') }}";
        var update = "{{ route('department.update', 'id') }}";
        var deleteURL = "{{ route('department.destroy', 'id') }}";
        var token = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\department.js') }}?{{ time() }}"></script>
@endsection
