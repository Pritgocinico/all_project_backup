@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">Payment Detail</h2>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Stripe Token</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!blank($paymentData))
                                @foreach ($paymentData as $payment)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        @php
                                            $orderId = isset($payment->orderDetail)?$payment->orderDetail->order_id:"";
                                            $id = isset($payment->orderDetail)?$payment->orderDetail->id:"";
                                        @endphp
                                        <td><a href="{{ route('admin.view.order',$id) }}">{{$orderId }}</a></td>
                                        <td>{{ $payment->stripe_token}}</td>
                                        <td>$ {{ $payment->amount }}</td>
                                        <td>{{ $payment->currency }}</td>
                                        <td>{{$payment->status}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable();
        });
    </script>
@endsection
