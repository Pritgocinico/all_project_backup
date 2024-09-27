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
                                Confirmed Order By Products Report
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="app-content  flex-column-fluid ">
                        <div id="kt_app_content_container" class="app-container  container-fluid ">
                            <div class="card">
                                <div class="card-header border-0 pt-6">
                                    <div class="card-title">
                                        <div class="d-flex align-items-center position-relative my-1">
                                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                                class="form-control w-250px ps-13" 
                                                placeholder="Search Order" />
                                        </div>
                                    </div>

                                    
                                </div>
                            </div>
                            <div class="card-body py-4">
                                <table id="example" class="table table-custom" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order ID</th>
                                            <th>Customer Name</th>
                                            <th>Phone Number</th>
                                            <th>Amount</th>
                                            <th>Created By</th>
                                            <th>Status</th>
                                            <th>Confirm Date</th>
                                            <th>Created At</th>
                                            <th>Products</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>AG -0001</td>
                                            <td>Test User</td>
                                            <td>1234567890</td>
                                            <td>500</td>
                                            <td>Test</td>
                                            <td>Done</td>
                                            <td>{{ Utility::convertMDY('') }}</td>
                                            <td>{{ Utility::convertDmyWith12HourFormat('') }}</td>
                                            <td>Testing Product</td>
                                        </tr>
                                    </tbody>
                                </table>
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
