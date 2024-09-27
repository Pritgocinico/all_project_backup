@extends('admin.layouts.app')

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
            <div class="ms-auto d-md-flex arcon-user-inner-search-outer">
                <a href="{{ route('admin.add_order') }}" class="ms-2 btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Add Order
                </a>
            </div>
        </div>
    </div>
</div>
<div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="w-100 gap-4 align-items-center">
                <form action="{{route('admin.orders')}}" method="get" class="mb-3 mb-md-0">
                    <div class="row g-3">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="district-search">
                                        <select name="agent" id="Search" class="form-control select2" data-created ="{{Auth::user()->id}}">
                                            <option value="">Select Agent</option>
                                            @if(!blank($agents))
                                                @foreach ($agents as $item)
                                                    <option value="{{$item->id}}" @if (request()->get('agent'))
                                                        @if(request()->get('agent') == $item->id) selected @endif
                                                    @endif>{{$item->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="status-search">
                                        <select name="status" id="Search" class="form-control" data-created = "{{Auth::user()->id}}">
                                            <option value="" selected hidden>Select Status</option>
                                            <option value="1" @if (request()->get('status'))
                                                @if(request()->get('status') == 1) selected @endif
                                            @endif>Pending</option>
                                            <option value="2" @if (request()->get('status'))
                                                @if(request()->get('status') == 2) selected @endif
                                            @endif>Confirmed</option>
                                            <option value="4" @if (request()->get('status'))
                                                @if(request()->get('status') == 4) selected @endif
                                            @endif>Partially Delivered</option>
                                            <option value="3" @if (request()->get('status'))
                                                @if(request()->get('status') == 3) selected @endif
                                            @endif>Delivered</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="mt-2 mb-0">Date Filter</h6>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <div class="district-search">
                                        <select name="date_filter" id="Search" class="form-control select2 date_filter">
                                                {{-- <option value="">Select Date Filter Column</option> --}}
                                                <option value="1" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 1) selected @endif @endif>Created Date</option>
                                                <option value="2" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 2) selected @endif @endif>Confirm Date</option>
                                                <option value="3" @if (request()->get('date_filter'))  @if(request()->get('date_filter') == 3) selected @endif @endif>Delivered Date</option>
                                        </select>
                                        <label class="m-0">Select Date Type</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="text" name="start_date" class="form-control" id="datefilterfrom"  @if (request()->get('start_date')) value="{{request()->get('start_date')}}" @endif data-date-split-input="true">
                                    <label class="m-0">Select Start Date</label>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="text" name="end_date" class="form-control" id="datefilterto"  @if (request()->get('end_date')) value="{{request()->get('end_date')}}" @endif data-date-split-input="true">
                                    <label class="m-0">Select End Date</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex justify-content-between align-items-center">
                            <input type="submit" value="Search" class="btn btn-primary" name="submit">
                            <a href="{{route('admin.orders')}}" class="float-right"><i class="bi bi-bootstrap-reboot"></i> Refresh</a>
                        </div>
                    </div>
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
                    <th>Agent</th>
                    <th>Order Amount</th>
                    <th>Transport</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody class="records">
                @if (!blank($orders))
                    @foreach ($orders as $order)
                        <tr>
                            <td data-th="Order ID"><a href="{{route('order.details',$order->id)}}" class="text-primary">{{$order->order_id}}</a></td>
                            <td data-th="Customer Name">{{$order->customer_name}}</td>
                            <td data-th="Phone Number">{{$order->phone}}</td>
                            <td data-th="Agent">
                                <?php $agnt = DB::table('users')->where('id',$order->agent_id)->first(); ?>
                                @if(!blank($agnt))
                                    {{$agnt->name}}
                                @endif
                            </td>
                            <!--<td data-th="Address">-->
                            <!--    @if (!blank($order->floor_no))-->
                            <!--        {{$order->floor_no}},-->
                            <!--    @endif-->
                            <!--    @if (!blank($order->address))-->
                            <!--        {{$order->address}},-->
                            <!--    @endif-->
                            <!--    <br>-->
                            <!--    @if (!blank($order->locality))-->
                            <!--        {{$order->locality}},-->
                            <!--    @endif-->
                            <!--    @if (!blank($order->city))-->
                            <!--        {{$order->city}},-->
                            <!--    @endif-->
                            <!--    <br>-->
                            <!--    @if (!blank($order->state))-->
                            <!--        {{$order->state}},-->
                            <!--    @endif-->
                            <!--    @if (!blank($order->country))-->
                            <!--        {{$order->country}},-->
                            <!--    @endif-->
                            <!--</td>-->
                            <td data-th="Order Amount">{{$order->total}}</td>
                            <td data-th="Transport">{{$order->transport}}</td>
                            <td data-th="Status">
                                @if($order->status==1)
                                    <span class="badge bg-warning">Pending Order</span>
                                @elseif($order->status==2)
                                    <span class="badge bg-success">Confirmed</span>
                                @elseif($order->status==4)
                                    <span class="badge bg-secondary">Partially Delivered</span>
                                @elseif($order->status==3)
                                    <span class="badge bg-info">Delivered</span>
                                @endif
                            </td>
                            <td data-th="Created At">{{date($setting->date_format,strtotime($order->created_at))}}</td>
                            <td data-th="Action" class="text-md-end">
                                <div class="d-flex align-items-center">
                                    <a href="{{route('order.details',$order->id)}}" class="btn btn-primary">View Order</a>
                                    <a href="javascript:void(0);" class="btn btn-danger delete_btn" data-id="{{$order->id}}">Delete</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th colspan="9">Orders Not Found.</th>
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
    $(document).on('click', '.delete_btn', function() {
        var order_id = $(this).attr('data-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete.order', '') }}" + "/" + order_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Order has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    }
                });
            }
        });
    });
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
