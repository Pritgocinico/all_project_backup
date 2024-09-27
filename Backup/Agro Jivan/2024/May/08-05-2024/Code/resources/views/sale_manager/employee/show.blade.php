@extends('layouts.main_layout')
@section('section')
    <style>
        .fc .fc-toolbar {
            display: block !important;
        }

        .fc .fc-button-group .fc-button {
            height: 40px !important;
            background-color: #f4f4f4 !important;
        }

        .fc-today-button {
            height: 40px !important;
            background-color: #f4f4f4 !important;
        }
    </style>
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid ">
                <div id="kt_app_content_container" class="container-fluid ">
                    <div class="d-flex flex-column flex-lg-row">
                        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                            <div class="card mb-5 mb-xl-8">
                                <div class="card-body">
                                    <div class="d-flex flex-center flex-column py-5">
                                        <div class="symbol symbol-100px symbol-circle mb-7">
                                            @php
                                            $image = asset('public/assets/media/avatars/300-2.jpg'); @endphp

                                            @if (Auth()->user()->profile_image !== null)
                                                @php $image = asset('public/assets/upload/'.Auth()->user()->profile_image); @endphp
                                            @endif
                                            <img id="image" class="br-50" src="{{ $image }}" alt=""
                                                style="height: 100px; width:100px;">
                                        </div>
                                        <span class="fs-3 text-gray-800 text-hover-black fw-bold mb-3">
                                            {{ $employee->name }} </span>
                                        <div class="mb-9">
                                            <div class="badge badge-lg badge-light-primary d-inline">
                                                {{ $employee->phone_number }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-stack fs-4 py-3">
                                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                            href="#kt_user_view_details" role="button" aria-expanded="false"
                                            aria-controls="kt_user_view_details">
                                            Details
                                            <span class="ms-2 rotate-180">
                                                <i class="ki-outline ki-down fs-3"></i> </span>
                                        </div>

                                        <span data-bs-toggle="tooltip" data-bs-trigger="hover"
                                            data-bs-original-title="Edit Employee details" data-kt-initialized="1">
                                            <a href="{{ route('employees.edit', $employee->id) }}"
                                                class="btn btn-sm btn-light-primary">
                                                Edit
                                            </a>
                                        </span>
                                    </div>

                                    <div class="separator"></div>

                                    <div id="kt_user_view_details" class="collapse show">
                                        <div class="pb-5 fs-6">
                                            <div class="fw-bold mt-5">Salary</div>
                                            <div class="text-gray-600">&#x20B9;
                                                {{ number_format($employee->employee_salary, 2) }}
                                            </div>
                                            <div class="fw-bold mt-5">System Code</div>
                                            <div class="text-gray-600"><span
                                                    class="text-gray-600 text-hover-gray">{{ $employee->system_code }}</span>
                                            </div>
                                            <div class="fw-bold mt-5">Shift Type</div>
                                            <div class="text-gray-600">{{ ucfirst($employee->shift_type) }} - @if ($employee->shift_type == 'morning')
                                                    {{ '08:00 AM to 6:00 PM' }}
                                                @else
                                                    {{ '02:00 PM to 10:00 PM' }}
                                                @endif
                                            </div>
                                            <div class="fw-bold mt-5">Is Manager</div>
                                            <div class="text-gray-600">{{ $employee->is_manager == '1' ? 'Yes' : 'No' }}
                                            </div>
                                            <div class="fw-bold mt-5">Aadhar Card</div>
                                            @if ($employee->aadhar_card !== null && File::exists('public/assets/upload/'.$employee->aadhar_card))
                                            <div class="text-gray-600 mt-2">
                                                <a href="{{ asset('public/assets/upload/' . $employee->aadhar_card) }}"
                                                    target="_blank" class="btn btn-primary">View Aadhar Card</a>
                                            </div>
                                            @else
                                                {{'-'}}
                                            @endif
                                            <div class="fw-bold mt-5">Pan Card</div>
                                            @if ($employee->pan_card !== null && File::exists('public/assets/upload/'.$employee->pan_card))
                                            <div class="text-gray-600 mt-2">
                                                <a href="{{ asset('public/assets/upload/' . $employee->pan_card) }}"
                                                    target="_blank" class="btn btn-primary">View Pan Card</a>
                                            </div>
                                            @else
                                                {{'-'}}
                                            @endif
                                            <div class="fw-bold mt-5">Qualification</div>
                                            @if ($employee->qualification !== null && File::exists('public/assets/upload/'.$employee->qualification))
                                            <div class="text-gray-600 mt-2">
                                                <a href="{{ asset('public/assets/upload/' . $employee->qualification) }}"
                                                    target="_blank" class="btn btn-primary">View Qualification</a>
                                            </div>
                                            @else
                                                {{'-'}}
                                            @endif
                                            <div class="fw-bold mt-5">Agreegement</div>
                                            @if ($employee->join_agreement !== null && File::exists('public/assets/upload/'.$employee->join_agreement))
                                                <div class="text-gray-600 mt-2">
                                                    <a href="{{ asset('public/assets/upload/' . $employee->join_agreement) }}"
                                                        target="_blank" class="btn btn-primary">View Agreegement</a>
                                                </div>
                                            @else
                                                {{'-'}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--end::Sidebar-->

                        <!--begin::Content-->
                        <div class="flex-lg-row-fluid ms-lg-15">
                            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                                role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                        href="#kt_user_view_overview_tab" aria-selected="true"
                                        role="tab">Permissions</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true"
                                        data-bs-toggle="tab" href="#kt_user_view_overview_security" data-kt-initialized="1"
                                        aria-selected="false" tabindex="-1" role="tab">Attendance</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                                        href="#kt_user_view_overview_events_and_logs_tab" aria-selected="false"
                                        tabindex="-1" role="tab">Order</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                                    <div class="card card-flush mb-6 mb-xl-9">
                                        <div class="card p-3">
                                            <form action="{{ route('update-permission') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $employee->id }}">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Features</th>
                                                            <th>Capability</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($permissionList as $permission)
                                                            <tr>
                                                                <td>{{ $permission->feature }}</td>
                                                                <td>
                                                                    @foreach ($permission->permissionDetail as $detail)
                                                                        <input type="hidden"
                                                                            name="permission[{{ $permission->feature }}][{{ $detail->capability }}]"
                                                                            value="0" id="">
                                                                        <input type="checkbox"
                                                                            name="permission[{{ $permission->feature }}][{{ $detail->capability }}]"
                                                                            @if ($detail->value == 1) {{ 'checked' }} @endif>
                                                                        {{ $detail->capability }}<br />
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div>
                                                    <button type="submit"
                                                        class="btn btn-primary justify-content-end float-right mt-2">
                                                        Save
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">
                                    <div id="attendance_calendar"></div>
                                </div>
                                <div class="tab-pane fade" id="kt_user_view_overview_events_and_logs_tab"
                                    role="tabpanel">
                                    <!--begin::Card-->
                                    <div class="card pt-4 mb-6 mb-xl-9">
                                        <div class="">
                                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="pills-daily-tab"
                                                        data-bs-toggle="pill" data-bs-target="#pills-daily"
                                                        type="button" role="tab" aria-controls="pills-daily"
                                                        aria-selected="true">Today's Order</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-home" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">All</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-week-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-week" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">Weekly</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-Month-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-Month" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">Month</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="pills-Year-tab" data-bs-toggle="pill"
                                                        data-bs-target="#pills-Year" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">Year</button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show " id="pills-Year" role="tabpanel"
                                                    aria-labelledby="pills-Year-tab">
                                                    <div class="card mt-2">
                                                        <div class="card-body">
                                                            <h5>Year Orders : {{ count($yearOrder) }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="houmanity-card">
                                                        <div class="card-body table-responsive">
                                                            <table id="" class="table table-custom"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Order ID</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Expected Delivery Date</th>
                                                                        <th>Products</th>
                                                                        <th class="text-end">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="records">
                                                                    @forelse ($yearOrder as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="text-primary">{{ $order->order_id }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->customer_name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->phoneno }}
                                                                            </td>
                                                                            <td>
                                                                                @php $cnt = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $cnt = $cnt + 1;
                                                                                    @endphp
                                                                                @endforeach
                                                                                &#x20B9; {{ $order->amount }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($order->order_status == 1)
                                                                                    <span class="badge bg-warning">Pending
                                                                                        Order</span>
                                                                                @elseif($order->order_status == 2)
                                                                                    <span
                                                                                        class="badge bg-success">Confirmed</span>
                                                                                @elseif($order->order_status == 3)
                                                                                    <span class="badge bg-info">On
                                                                                        Delivery</span>
                                                                                @elseif($order->order_status == 4)
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @elseif($order->order_status == 5)
                                                                                    <span
                                                                                        class="badge bg-danger">Returned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ Utility::convertDmy($order->excepted_delievery_date) }}
                                                                            </td>
                                                                            <td>
                                                                                @php $i = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $i++;
                                                                                        $pr = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? $item->productDetail
                                                                                                ->product_name
                                                                                            : '';
                                                                                        $cate = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? (isset(
                                                                                                $item->productDetail
                                                                                                    ->categoryDetail,
                                                                                            )
                                                                                                ? $item->productDetail
                                                                                                    ->categoryDetail
                                                                                                    ->name
                                                                                                : '')
                                                                                            : '';
                                                                                    @endphp
                                                                                    @if ($i < $cnt)
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}],<br>
                                                                                    @else
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}]<br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>

                                                                            <td class="text-end">
                                                                                <div class="d-flex float-end">
                                                                                    <a href="{{ route('orders.edit', $order->id) }}"
                                                                                        class=""><img
                                                                                            src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                            width="20px"
                                                                                            class="me-2"></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr class="text-center">
                                                                            <td colspan="9">Orders Not Found.</td>
                                                                        </tr>
                                                                    @endforelse

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade show " id="pills-Month" role="tabpanel"
                                                    aria-labelledby="pills-Month-tab">
                                                    <div class="card mt-2">
                                                        <div class="card-body">
                                                            <h5>Month Orders : {{ count($monthOrder) }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="houmanity-card">
                                                        <div class="card-body table-responsive">
                                                            <table id="" class="table table-custom"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Order ID</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Expected Delivery Date</th>
                                                                        <th>Products</th>
                                                                        <th class="text-end">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="records">
                                                                    @forelse ($monthOrder as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="text-primary">{{ $order->order_id }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->customer_name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->phoneno }}
                                                                            </td>
                                                                            <td>
                                                                                @php $cnt = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $cnt = $cnt + 1;
                                                                                    @endphp
                                                                                @endforeach
                                                                                &#x20B9; {{ $order->amount }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($order->order_status == 1)
                                                                                    <span class="badge bg-warning">Pending
                                                                                        Order</span>
                                                                                @elseif($order->order_status == 2)
                                                                                    <span
                                                                                        class="badge bg-success">Confirmed</span>
                                                                                @elseif($order->order_status == 3)
                                                                                    <span class="badge bg-info">On
                                                                                        Delivery</span>
                                                                                @elseif($order->order_status == 4)
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @elseif($order->order_status == 5)
                                                                                    <span
                                                                                        class="badge bg-danger">Returned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ Utility::convertDmy($order->excepted_delievery_date) }}
                                                                            </td>
                                                                            <td>
                                                                                @php $i = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $i++;
                                                                                        $pr = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? $item->productDetail
                                                                                                ->product_name
                                                                                            : '';
                                                                                        $cate = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? (isset(
                                                                                                $item->productDetail
                                                                                                    ->categoryDetail,
                                                                                            )
                                                                                                ? $item->productDetail
                                                                                                    ->categoryDetail
                                                                                                    ->name
                                                                                                : '')
                                                                                            : '';
                                                                                    @endphp
                                                                                    @if ($i < $cnt)
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}],<br>
                                                                                    @else
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}]<br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>

                                                                            <td class="text-end">
                                                                                <div class="d-flex float-end">
                                                                                    <a href="{{ route('orders.edit', $order->id) }}"
                                                                                        class=""><img
                                                                                            src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                            width="20px"
                                                                                            class="me-2"></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr class="text-center">
                                                                            <td colspan="9">Orders Not Found.</td>
                                                                        </tr>
                                                                    @endforelse

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade show active" id="pills-daily" role="tabpanel"
                                                    aria-labelledby="pills-daily-tab">
                                                    <div class="card mt-2">
                                                        <div class="card-body">
                                                            <h5>Total Orders : {{ count($todayOrder) }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="houmanity-card">
                                                        <div class="card-body table-responsive">
                                                            <table id="" class="table table-custom"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Order ID</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Expected Delivery Date</th>
                                                                        <th>Products</th>
                                                                        <th class="text-end">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="records">
                                                                    @forelse ($todayOrder as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="text-primary">{{ $order->order_id }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->customer_name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->phoneno }}
                                                                            </td>
                                                                            <td>
                                                                                @php $cnt = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $cnt = $cnt + 1;
                                                                                    @endphp
                                                                                @endforeach
                                                                                &#x20B9; {{ $order->amount }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($order->order_status == 1)
                                                                                    <span class="badge bg-warning">Pending
                                                                                        Order</span>
                                                                                @elseif($order->order_status == 2)
                                                                                    <span
                                                                                        class="badge bg-success">Confirmed</span>
                                                                                @elseif($order->order_status == 3)
                                                                                    <span class="badge bg-info">On
                                                                                        Delivery</span>
                                                                                @elseif($order->order_status == 4)
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @elseif($order->order_status == 5)
                                                                                    <span
                                                                                        class="badge bg-danger">Returned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ Utility::convertDmy($order->excepted_delievery_date) }}
                                                                            </td>
                                                                            <td>
                                                                                @php $i = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $i++;
                                                                                        $pr = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? $item->productDetail
                                                                                                ->product_name
                                                                                            : '';
                                                                                        $cate = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? (isset(
                                                                                                $item->productDetail
                                                                                                    ->categoryDetail,
                                                                                            )
                                                                                                ? $item->productDetail
                                                                                                    ->categoryDetail
                                                                                                    ->name
                                                                                                : '')
                                                                                            : '';
                                                                                    @endphp
                                                                                    @if ($i < $cnt)
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}],<br>
                                                                                    @else
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}]<br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>

                                                                            <td class="text-end">
                                                                                <div class="d-flex float-end">
                                                                                    <a href="{{ route('orders.edit', $order->id) }}"
                                                                                        class=""><img
                                                                                            src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                            width="20px"
                                                                                            class="me-2"></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr class="text-center">
                                                                            <td colspan="9">Orders Not Found.</td>
                                                                        </tr>
                                                                    @endforelse

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade show" id="pills-week" role="tabpanel"
                                                    aria-labelledby="pills-week-tab">
                                                    <div class="card mt-2">
                                                        <div class="card-body">
                                                            <h5>Week Orders : {{ count($weekOrder) }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="houmanity-card">
                                                        <div class="card-body table-responsive">
                                                            <table id="" class="table table-custom"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Order ID</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Expected Delivery Date</th>
                                                                        <th>Products</th>
                                                                        <th class="text-end">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="records">
                                                                    @forelse ($weekOrder as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="text-primary">{{ $order->order_id }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->customer_name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->phoneno }}
                                                                            </td>
                                                                            <td>
                                                                                @php $cnt = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $cnt = $cnt + 1;
                                                                                    @endphp
                                                                                @endforeach
                                                                                &#x20B9; {{ $order->amount }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($order->order_status == 1)
                                                                                    <span class="badge bg-warning">Pending
                                                                                        Order</span>
                                                                                @elseif($order->order_status == 2)
                                                                                    <span
                                                                                        class="badge bg-success">Confirmed</span>
                                                                                @elseif($order->order_status == 3)
                                                                                    <span class="badge bg-info">On
                                                                                        Delivery</span>
                                                                                @elseif($order->order_status == 4)
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @elseif($order->order_status == 5)
                                                                                    <span
                                                                                        class="badge bg-danger">Returned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ Utility::convertDmy($order->excepted_delievery_date) }}
                                                                            </td>
                                                                            <td>
                                                                                @php $i = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $i++;
                                                                                        $pr = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? $item->productDetail
                                                                                                ->product_name
                                                                                            : '';
                                                                                        $cate = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? (isset(
                                                                                                $item->productDetail
                                                                                                    ->categoryDetail,
                                                                                            )
                                                                                                ? $item->productDetail
                                                                                                    ->categoryDetail
                                                                                                    ->name
                                                                                                : '')
                                                                                            : '';
                                                                                    @endphp
                                                                                    @if ($i < $cnt)
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}],<br>
                                                                                    @else
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}]<br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>

                                                                            <td class="text-end">
                                                                                <div class="d-flex float-end">
                                                                                    <a href="{{ route('orders.edit', $order->id) }}"
                                                                                        class=""><img
                                                                                            src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                            width="20px"
                                                                                            class="me-2"></a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr class="text-center">
                                                                            <td colspan="9">Orders Not Found.</td>
                                                                        </tr>
                                                                    @endforelse

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="pills-home" role="tabpanel"
                                                    aria-labelledby="pills-home-tab">

                                                    <div class="card mt-2">
                                                        <div class="card-body">
                                                            <h5>Total Orders : {{ count($allOrder) }}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="houmanity-card">
                                                        <div class="card-body table-responsive">
                                                            <table id="testTable" class="table table-custom"
                                                                style="width:100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Order ID</th>
                                                                        <th>Customer Name</th>
                                                                        <th>Phone Number</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Expected Delivery Date</th>
                                                                        <th>Products</th>
                                                                        <th class="text-end">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="records">
                                                                    @forelse ($allOrder as $order)
                                                                        <tr>
                                                                            <td><a href="{{ route('orders.show', $order->id) }}"
                                                                                    class="text-primary">{{ $order->order_id }}</a>
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->customer_name }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $order->phoneno }}
                                                                            </td>
                                                                            <td>
                                                                                &#x20B9; {{ $order->amount }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($order->order_status == 1)
                                                                                    <span class="badge bg-warning">Pending
                                                                                        Order</span>
                                                                                @elseif($order->order_status == 2)
                                                                                    <span
                                                                                        class="badge bg-success">Confirmed</span>
                                                                                @elseif($order->order_status == 3)
                                                                                    <span class="badge bg-info">On
                                                                                        Delivery</span>
                                                                                @elseif($order->order_status == 4)
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @elseif($order->order_status == 5)
                                                                                    <span
                                                                                        class="badge bg-danger">Returned</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                {{ Utility::convertDmy($order->excepted_delievery_date) }}
                                                                            </td>
                                                                            <td>
                                                                                @php $i = 0; @endphp
                                                                                @foreach ($order->orderItem as $item)
                                                                                    @php
                                                                                        $i++;
                                                                                        $pr = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? $item->productDetail
                                                                                                ->product_name
                                                                                            : '';
                                                                                        $cate = isset(
                                                                                            $item->productDetail,
                                                                                        )
                                                                                            ? (isset(
                                                                                                $item->productDetail
                                                                                                    ->categoryDetail,
                                                                                            )
                                                                                                ? $item->productDetail
                                                                                                    ->categoryDetail
                                                                                                    ->name
                                                                                                : '')
                                                                                            : '';
                                                                                    @endphp
                                                                                    @php $cnt = 0; @endphp
                                                                                    @foreach ($order->orderItem as $item)
                                                                                        @php
                                                                                            $cnt = $cnt + 1;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                    @if ($i < $cnt)
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}],<br>
                                                                                    @else
                                                                                        {{ $pr }}
                                                                                        [{{ $cate }}]<br>
                                                                                    @endif
                                                                                @endforeach
                                                                            </td>
                                                                            <td class="text-end">
                                                                                <div class="d-flex float-end">
                                                                                    @if ($order->order_status !== 2)
                                                                                        <a href="{{ route('orders.edit', $order->id) }}"
                                                                                            class=""><img
                                                                                                src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                                width="20px"
                                                                                                class="me-2"></a>
                                                                                    @endif
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr class="text-center">
                                                                            <td colspan="9">Orders Not Found.</td>
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
            </div>
        </div>

        <div class="modal fade" id="calendarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title houmanity-color" id="staticBackdropLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Login At : </h3>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="login_at"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Logout At :</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 pt-9 mb-7 text-gray-900 fw-normal letter-spacing fs-4" id="logout_at">
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4">Repeat Login</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 pt-9 mb-7 text-gray-900 fw-normal letter-spacing fs-4"
                                    id="repeate_login">
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Total Work Hours</h3>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="work_hours"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-10 mt-1">
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-bold letter-spacing fs-4">Break Time Count </h3>
                            </div>
                        </div>
                        <div class="col-md-6 mt-0">
                            <div data-kt-href="true"
                                class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden">
                                <h3 class="ps-12 text-gray-900 fw-normal letter-spacing fs-4" id="break_time_count"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <hr>
                        <h6 class="houmanity-color">Break Logs:</h6>
                        <div class="">
                            <div class="table-responsive" tabindex="1">
                                <table id="" class="table table-custom" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Break Start</th>
                                            <th>Break Complete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="break_logs">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('page')
        <script>
            var id = "{{ $employee->id }}";
            var calendarUrl = "{{ route('calendar-detail', 'id') }}";
            var attendanceData = "{{ route('attendance_by_date') }}";
            var breakCount = "{{ route('break_time_count') }}"
        </script>
        <script src="{{ asset('public\assets\js\custom\admin\employee.js') }}?{{ time() }}"></script>
    @endsection
