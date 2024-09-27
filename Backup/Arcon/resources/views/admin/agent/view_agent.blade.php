@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">{{$user->name}}</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.users') }}" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <div class="card-body">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <a href="#pills-profile" class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Profile</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#pills-orders" class="nav-link" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab" aria-controls="pills-orders" aria-selected="false">Orders</a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="#pills-users" class="nav-link" id="pills-users-tab" data-bs-toggle="pill" data-bs-target="#pills-users" type="button" role="tab" aria-controls="pills-users" aria-selected="false">Dealers</a>
            </li>
          </ul>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #689efd; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Name
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->name}} </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #fe7070; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Phone Number
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->phone}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #d081de; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                        Email Address
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->email}}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="ps-3 mt-3" style="border-left:3px solid #365436; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                            Created At
                                        </span>
                                    </h6>
                                    <p class="mb-1">{{$user->created_at}}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="ps-3 mt-3" style="border-left:3px solid #bdc54e; border-spacing: 10px 0px;">
                                    <h6>
                                        <span class="font-weight-bold">
                                            Address
                                        </span>
                                    </h6>
                                    <p class="mb-1">
                                        @if (!blank($user->floor_no))
                                            {{$user->floor_no}},
                                        @endif
                                        @if (!blank($user->address))
                                            {{$user->address}},
                                        @endif
                                        <br>
                                        @if (!blank($user->locality))
                                            {{$user->locality}},
                                        @endif
                                        @if (!blank($user->city))
                                            {{$user->city}},
                                        @endif
                                        <br>
                                        @if (!blank($user->state))
                                            {{$user->state}},
                                        @endif
                                        @if (!blank($user->country))
                                            {{$user->country}},
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders-tab">
                <div class="houmanity-card">
                    <div class="table-responsive">
                        <table id="" class="table table-custom rwd-table" style="width:100%">
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
                            <tbody>
                                @if (!blank($orders))
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td data-th="Order ID"><a href="{{route('order.details',$order->id)}}" class="text-primary">{{$order->order_id}}</a></td>
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
                                                @if($order->status==0)
                                                    <span class="badge bg-warning">Pending Order</span>
                                                @elseif($order->status==1)
                                                    <span class="badge bg-success">Payment Done</span>
                                                @endif
                                            </td>
                                            <td data-th="Created At">{{date($setting->date_format,strtotime($order->created_at))}}</td>
                                            <td data-th="Action" class="text-md-end">
                                                <a href="{{route('order.details',$order->id)}}" class="btn btn-primary">View Order</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="8">Orders Not Found.</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
                <div class="houmanity-card">
                    <div class="table-responsive">
                        <table id="" class="table table-custom rwd-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone Number</th>
                                    <th>Agent</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($users))
                                    @foreach ($users as $user)
                                        <tr>
                                            <td data-th="Name"><a href="{{ route('admin.view_user',$user->id) }}" class="text-primary">{{$user->name}}</a></td>
                                            <td data-th="Email">
                                                {{$user->email}}
                                            </td>
                                            <td data-th="Phone Number">
                                                {{$user->phone}}
                                            </td>
                                            <td data-th="Agent">
                                                <?php $usr = DB::table('users')->where('id',$user->agent)->first(); ?>
                                                {{$usr->name}}
                                            </td>
                                            <td data-th="Status">
                                                <div class="btn-group ml-5">
                                                    @if($user->status==1)
                                                        <button class="btn btn-success text-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Verified
                                                        </button>
                                                    @else
                                                        <button class="btn btn-info text-white btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Not Verified
                                                        </button>
                                                    @endif
                                                    <ul class="dropdown-menu user-status">
                                                        <li><a class="dropdown-item status @if($user->status == 1) hide @endif" href="#" data-status="1" data-user="{{ $user->id }}">Verify</a></li>
                                                        <li><a class="dropdown-item status @if($user->status == 0) hide @endif" href="#" data-status="0" data-user="{{ $user->id }}">Not Verified</a></li>
                                                    </ul>
                                                </div>
                                                <p class="mb-0">
                                                    @if ($user->agent_status == 1)
                                                        <span class="text-success">Verified by agent</span>
                                                    @else
                                                        <span class="text-danger">Not Verified by agent</span>
                                                    @endif
                                                </p>
                                            </td>
                                            <td data-th="Created At">{{ date($setting->date_format,strtotime($user->created_at)) }}</td>
                                            <td data-th="Action" class="text-md-end">
                                                <div class="d-flex float-end">
                                                    <a href="{{ route('admin.edit_user',$user->id) }}" class=""><img src="{{url('/')}}/assets/admin/images/edit.png" width="20px" class="me-2"></a>
                                                    @if ($user->id != 1)
                                                        <a href="javascript:void(0);" data-id="{{ $user->id }}" class="ms-2 delete-btn"><img src="{{url('/')}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan=7>Users Not Found.</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        // $(document).on('click','.submit_options',function(){
        //     var period = $("input[name='period']:checked").val();
        //     var option = $("input[name='option']:checked").val();
        //     var user_id = {{$user->id}};
        //     $.ajax({
        //         url : "{{route('get_statement_data')}}",
        //         data: {"_token": "{{ csrf_token() }}",'period':period, 'option': option, 'user_id': user_id },
        //         type : 'POST',
        //         success : function(data) {

        //         }
        //     });

        // });
        function showPassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
