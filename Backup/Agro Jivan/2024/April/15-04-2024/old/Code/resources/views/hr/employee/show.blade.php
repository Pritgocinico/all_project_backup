@extends('layouts.main_layout')
@section('section')
    <div class="content">
        <div class="card mb-2 p-3">
            <div class="card-body">
                <div class="d-md-flex gap-4 align-items-center">
                    <h4 class="m-0">{{ $employee->name }}</h4>
                    <div class="ms-auto">
                        <a onclick="history.back()" class="btn btn-primary justify-content-end float-right m-1">
                            Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card p-2">
                    <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" href="#v-pills-profile"
                            role="tab" aria-controls="v-pills-profile" aria-selected="true"><i class="fa fa-user"
                                aria-hidden="true"></i>
                            Profile</a>
                        <hr class="m-0">
                        <a class="nav-link" id="v-pills-attendance-tab" data-bs-toggle="pill" href="#v-pills-attendance"
                            role="tab" aria-controls="v-pills-attendance" aria-selected="false"><i
                                class="fas fa-clock"></i></i>
                            Attendance</a>
                        <hr class="m-0">
                        <a class="nav-link" id="v-pills-order-tab" data-bs-toggle="pill" href="#v-pills-order"
                            role="tab" aria-controls="v-pills-order" aria-selected="false"><i
                                class="fa-brands fa-first-order-alt"></i></i>
                            Order</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card p-2">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel"
                            aria-labelledby="v-pills-profile-tab">
                            <div class="row p-5">
                                <div class="col-lg-7 border-right">
                                    <div class="row">
                                        <div class="col-sm-4" style="align-self: center;">
                                            @php
                                            $image = asset('public/assets/media/avatars/300-2.jpg'); @endphp

                                            @if (Auth()->user()->profile_image !== null)
                                                @php $image = asset('public/assets/upload/'.Auth()->user()->profile_image); @endphp
                                            @endif
                                            <img id="image" class="br-50" src="{{ $image }}" alt=""
                                                style="height: 100px; width:100px;">
                                        </div>
                                        <div class="col-sm-8 mt-4 p-2">
                                            <h4>{{ $employee->name }}</h4>
                                            <h6><i class="bi bi-envelope"></i> Email :{{ $employee->email }} </a>
                                            </h6>
                                            <h6><i class="bi bi-phone"></i> Phone : {{ $employee->phone_number }} </h6>
                                            <a href="{{ route('hr-edit-profile', $employee->id) }}" target="_blank"
                                                class="btn btn-primary mt-2"><i class="bi bi-pencil-square"></i>
                                                Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-5 mt-2 text-right">
                                    <h6>Created At : {{ date($employee->created_at, strtotime($employee->created_at)) }}
                                    </h6>
                                    <h6 class="mt-5">Department :</h6>
                                    <ol>
                                        @foreach ($employee->departmentDetail as $department)
                                            <li>{{ isset($department->departmentNameDetail) ? $department->departmentNameDetail->department_name : '' }}
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-attendance" role="tabpanel"
                            aria-labelledby="v-pills-attendance-tab">

                            <div class="card p-3">
                                <div id="attendance_calendar"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="v-pills-order" role="tabpanel"
                            aria-labelledby="v-pills-order-tab">
                            <div class="">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-daily-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-daily" type="button" role="tab"
                                            aria-controls="pills-daily" aria-selected="true">Today's Order</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-home" type="button" role="tab"
                                            aria-controls="pills-home" aria-selected="true">All</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-daily" role="tabpanel"
                                        aria-labelledby="pills-daily-tab">
                                        <div class="card mt-2">
                                            <div class="card-body">
                                                <h5>Total Orders : {{ count($todayOrder) }}</h5>
                                            </div>
                                        </div>
                                        <div class="houmanity-card">
                                            <div class="card-body table-responsive">
                                                <table id="" class="table table-custom" style="width:100%">
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
                                                                    @php
                                                                        $amount = 0;
                                                                        $cnt = 0;
                                                                    @endphp
                                                                    @foreach ($order->orderItem as $item)
                                                                        @php
                                                                            $amount = $amount + $item->price * $item->quantity;
                                                                            $cnt = $cnt + 1;
                                                                        @endphp
                                                                    @endforeach
                                                                    &#x20B9; {{ $amount }}
                                                                </td>
                                                                <td>
                                                                    @if ($order->order_status == 1)
                                                                        <span class="badge bg-warning">Pending Order</span>
                                                                    @elseif($order->order_status == 2)
                                                                        <span class="badge bg-success">Confirmed</span>
                                                                    @elseif($order->order_status == 3)
                                                                        <span class="badge bg-info">On Delivery</span>
                                                                    @elseif($order->order_status == 4)
                                                                        <span class="badge bg-danger">Cancelled</span>
                                                                    @elseif($order->order_status == 5)
                                                                        <span class="badge bg-danger">Returned</span>
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
                                                                            $pr = isset($item->productDetail) ? $item->productDetail->product_name : '';
                                                                            $cate = isset($item->productDetail) ? (isset($item->productDetail->categoryDetail) ? $item->productDetail->categoryDetail->name : '') : '';
                                                                        @endphp
                                                                        @if ($i < $cnt)
                                                                            {{ $pr }} [{{ $cate }}],<br>
                                                                        @else
                                                                            {{ $pr }} [{{ $cate }}]<br>
                                                                        @endif
                                                                    @endforeach
                                                                </td>

                                                                <td class="text-end">
                                                                    <div class="d-flex float-end">
                                                                        <a href="{{ route('orders.edit', $order->id) }}"
                                                                            class=""><img
                                                                                src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                width="20px" class="me-2"></a>
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
                                                <table id="testTable" class="table table-custom" style="width:100%">
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
                                                                    @php
                                                                        $amount = 0;
                                                                        $cnt = 0;
                                                                    @endphp
                                                                    @foreach ($order->orderItem as $item)
                                                                        @php$amount = $amount + $item->price * $item->quantity;
                                                                                                                                                                                                                                    $cnt = $cnt + 1;
                                                                                                                                                                                                                        @endphp ?> ?>
                                                                    @endforeach
                                                                    &#x20B9; {{ $amount }}
                                                                </td>
                                                                <td>
                                                                    @if ($order->order_status == 1)
                                                                        <span class="badge bg-warning">Pending Order</span>
                                                                    @elseif($order->order_status == 2)
                                                                        <span class="badge bg-success">Confirmed</span>
                                                                    @elseif($order->order_status == 3)
                                                                        <span class="badge bg-info">On Delivery</span>
                                                                    @elseif($order->order_status == 4)
                                                                        <span class="badge bg-danger">Cancelled</span>
                                                                    @elseif($order->order_status == 5)
                                                                        <span class="badge bg-danger">Returned</span>
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
                                                                            $pr = isset($item->productDetail) ? $item->productDetail->product_name : '';
                                                                            $cate = isset($item->productDetail) ? (isset($item->productDetail->categoryDetail) ? $item->productDetail->categoryDetail->name : '') : '';
                                                                        @endphp
                                                                        @if ($i < $cnt)
                                                                            {{ $pr }} [{{ $cate }}],<br>
                                                                        @else
                                                                            {{ $pr }} [{{ $cate }}]<br>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                                <td class="text-end">
                                                                    <div class="d-flex float-end">
                                                                        <a href="{{ route('orders.edit', $order->id) }}"
                                                                            class=""><img
                                                                                src="{{ asset('public/assets/images/icons/edit.png') }}"
                                                                                width="20px" class="me-2"></a>
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
    </div>
    </div>

    </body>
    <div class="modal fade" id="calendarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title houmanity-color" id="staticBackdropLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Login At : <span class="login_at"></span></h6>
                    <h6>Logout At : <span class="logout_at"></span></h6>
                    <h6>Repeate Login: <span class="repeate_login"></span></h6>
                    <h6>Total Work Hours: <span class="work_hours"></span></h6>
                    <h6>Break Time Count: <span class="break_time_count"></span></h6>
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
        var attendanceData = "{{ route('attendance_by_date') }}"
    </script>
    <script src="{{ asset('public\assets\js\custom\admin\employee.js') }}?{{ time() }}"></script>
@endsection
