@extends('frontend.layouts.app')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <section class="banner-section prdct-parent about-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        My Account
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="inner-ac">
                <div class="tab-btn pe-xl-5 pe-lg-3">
                    <ul class="pro-tab">
                        <a href="{{ route('myaccount') }}">
                            <div class="d-flex align-items-center  justify-content-between active">
                                <p class="mb-0">Profile</p>
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                        </a>
                            <a href="{{ route('orders') }}" class="active">
                                <div class="d-flex align-items-center  justify-content-between ">
                                    <p class="mb-0">Orders</p>
                                    <i class="fas fa-shopping-basket"></i>
                                </div>
                            </a>
                            <a href="{{ route('card-detail') }}" class="active">
                                <div class="d-flex align-items-center  justify-content-between ">
                                    <p class="mb-0">Card Detail</p>
                                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                                </div>
                            </a>
                    </ul>
                </div>
                <div class="page-content tab-content">
                    <div>
                        <div class="container">
                            <table class="rwd-table mt-3" id="my_order_table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (!blank($orders))
                                        @foreach ($orders as $order)
                                            <tr class="bg-white text-dark">
                                                <td data-th="#">
                                                    {{ $loop->index + 1 }}
                                                </td>
                                                <td data-th="#">
                                                    {{ $order->order_id }}
                                                </td>
                                                <td data-th="Date">
                                                    {{ date('F j,Y', strtotime($order->created_at)) }}
                                                </td>
                                                <td data-th="Status">
                                                    @if ($order->status == 0)
                                                        <span class="badge bg-warning text-dark">Processing</span>
                                                    @elseif ($order->status == 1)
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif ($order->status == 3)
                                                        <span class="badge bg-danger text-dark">Canceled</span>
                                                    @endif
                                                </td>
                                                <td data-th="Total">
                                                    @php
                                                        $order_items = DB::table('order_items')
                                                            ->where('order_id', $order->id)
                                                            ->count();
                                                        if (!blank($order_items)) {
                                                            if ($order_items > 1) {
                                                                $cnt = $order_items . ' items';
                                                            } else {
                                                                $cnt = $order_items . ' item';
                                                            }
                                                        } else {
                                                            $cnt = 0;
                                                        }
                                                    @endphp
                                                    ${{ $order->total }} for {{ $cnt }}
                                                </td>
                                                <td data-th="Actions">
                                                    <div class="d-btn">
                                                        <a href="{{ route('view.order', $order->id) }}">View</a>
                                                        <a href="{{ route('doctor-generate-invoice', $order->id) }}"><i
                                                                class="fas fa-file-invoice"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#my_order_table').DataTable();
        });
    </script>
@endsection
