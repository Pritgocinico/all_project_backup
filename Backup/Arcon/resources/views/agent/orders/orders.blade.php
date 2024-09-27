@extends('agent.layouts.app')

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Orders</a>
            </li>
        </ol>
    </nav>
</div>
<div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="d-none d-md-flex">All Orders</div>
            <div class="d-md-flex gap-4 align-items-center">
                <form class="mb-3 mb-md-0">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <select class="form-select classic order-table">
                                <option hidden>Sort by</option>
                                <option value="desc">Desc</option>
                                <option value="asc">Asc</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
            <div class="ms-auto d-flex agent-order-form-date-outer">
                <form class="agent-order-form-inner" action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control src d-none" id="search-table" placeholder="Search">
                                <span class="search-btn mt-2 ms-2" type="button">
                                    <i class="bi bi-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="agent-order-date-inner d-flex gap-3 ps-3">
                    <input type="text" class="form-control" id="datefilterfrom" data-date-split-input="true">
                    <input type="text" class="form-control" id="datefilterto" data-date-split-input="true">
                </div>
                {{-- <a href="{{ route('admin.add_order') }}" class="ms-2 btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Add Order
                </a> --}}

            </div>
        </div>
    </div>
</div>
<div class="houmanity-card">
    <div class="card-body table-responsive">
        <table id="testTable" class="table table-custom rwd-table" style="width:100%">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Address</th>
                    <th>Order Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody class="records">
                @if (!blank($orders))
                    @foreach ($orders as $order)
                            <tr>
                                <td data-th="Order ID"><a href="{{route('agent.order.details',$order->id)}}" class="text-primary">{{$order->order_id}}</a></td>
                                <td data-th="Customer Name">{{$order->customer_name}}</td>
                                <td data-th="Phone Number">{{$order->phone}}</td>
                                <td data-th="Address">
                                    @if (!blank($order->floor_no))
                                        {{$order->floor_no}},
                                    @endif
                                    @if (!blank($order->address))
                                        {{$order->address}},
                                    @endif
                                    <br>
                                    @if (!blank($order->locality))
                                        {{$order->locality}},
                                    @endif
                                    @if (!blank($order->city))
                                        {{$order->city}},
                                    @endif
                                    <br>
                                    @if (!blank($order->state))
                                        {{$order->state}},
                                    @endif
                                    @if (!blank($order->country))
                                        {{$order->country}},
                                    @endif
                                </td>
                                <td data-th="Order Amount">{{$order->total}}</td>
                                <td data-th="Status">
                                    @if($order->status==1)
                                        <span class="badge bg-warning">Pending Order</span>
                                    @elseif($order->status==2)
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif($order->status==3)
                                        <span class="badge bg-info">Delivered</span>
                                    @endif
                                </td>
                                <td data-th="Created At">{{date($setting->date_format,strtotime($order->created_at))}}</td>
                                <td data-th="Action" class="text-md-end">
                                    <a href="{{route('agent.order.details',$order->id)}}" class="btn btn-primary">View Order</a>
                                </td>
                            </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">Orders Not Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class='pagination-container'>
    <nav class="mt-4" aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
        </ul>
    </nav>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){

    });
     function filterRows() {
          var from = $('#datefilterfrom').val();
          var to = $('#datefilterto').val();

          if (!from && !to) { // no value for from and to
            return;
          }

          from = from || '1970-01-01'; // default from to a old date if it is not set
          to = to || '2999-12-31';

          var dateFrom = moment(from, "DD/M/YYYY");
          var dateTo = moment(to, "DD/M/YYYY");

          $('#testTable tbody tr').each(function(i, tr) {
            var val = $(tr).find("td:nth-child(7)").text();
            var dateVal = moment(val, "DD/M/YYYY");
            console.log(dateVal);
            var visible = (dateVal.isBetween(dateFrom, dateTo, null, [])) ? "" : "none"; // [] for inclusive
            $(tr).css('display', visible);
          });
        }
        $(document).ready(function(){
            // filterRows();
            $('#datefilterfrom').on("change", filterRows);
            $('#datefilterto').on("change", filterRows);
        });
</script>
@endsection
