@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content pt-0  flex-column-fluid ">


                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container  container-fluid ">
                    <div id="kt_app_content" class="flex-column-fluid ">
                        <div id="kt_app_content_container" class="container-fluid ">
                            <div class="card-body py-4 table-responsive" id="pending_ajax_list">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-125px">Employee Name</th>
                                            <th class="min-w-125px">Employee Email</th>
                                            <th class="min-w-125px">Phone Number</th>
                                            <th class="min-w-125px">Total Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600 fw-semibold">
                                        @forelse ($userList as $key=>$order)
                                            <tr>
                                                <td class="align-middle">
                                                    {{$order->name}}
                                                </td>
                                                <td>{{ $order->email }}</td>
                                                <td>{{ $order->phone_number }}</td>
                                                <td>{{ $order->confirm_order_count }}</td>
                                                
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No Data Available.</td>
                                            </tr>
                                        @endforelse
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
