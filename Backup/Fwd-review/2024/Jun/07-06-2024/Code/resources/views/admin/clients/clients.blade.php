@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card my-3">
        <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
            <div class="pe-4 fs-5">All Clients</div>
            <div class="ms-auto">
                <div class="d-flex align-items-center">
                    <a href="{{route('admin.add.client')}}" class="btn gc_btn align-items-center d-none d-md-flex"><span class="fs-4 me-2">+</span>Add Customer</a>
                </div>
            </div>
        </div>
    </div>
    <div class="gc_bottom_btn fixed-bottom customer"> <a href="{{route('admin.add.client')}}" class="btn gc_btn d-flex align-items-center d-block d-md-none"><span class="fs-4 me-2">+</span>Add Customer</a></div>
    <div class="card">
        <div class="table-responsive p-3">
            <table id="example" class="table rwd-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Total Business</th>
                        <th>Total Amount</th>
                        <th>Created At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($clients))
                    @foreach ($clients as $client)
                    @php
                            $subscription_endDate = date('Y-m-d',strtotime($client->sub_end_date));   
                            $amount = 0;
                        @endphp
                            <tr>
                                <td data-header="Name" class="pt-2">
                                    <a href="{{route('admin.view.client',$client->id)}}" class="customer_text_icon me-2 bg-dark text-white rounded-circle">{{substr($client['name'], 0, 1)}}</a>
                                    <a href="{{route('admin.view.client',$client->id)}}">{{$client->name}}</a>
                                </td>
                                <td data-header="Email">{{$client->email}}</td>
                                <td data-header="Phone Number">{{$client->phone}}</td>
                                @if(!blank($client->sub_end_date))
                                    <td data-header="Status">
                                        @if($currentDate >= $subscription_endDate)
                                            <h4 class="badge bg-danger">Subscription Ended</h4>
                                        @else
                                            @if ($client->status == 1)
                                                <h4 class="badge bg-success">Active</h4>
                                            @else
                                                <h4 class="badge bg-danger">Deactive</h4>
                                            @endif
                                        @endif
                                    </td>
                                @else
                                    <td data-header="Status">
                                        @if ($client->status == 1)
                                            <h4 class="badge bg-success">Active</h4>
                                        @else
                                            <h4 class="badge bg-danger">Deactive</h4>
                                        @endif
                                    </td>
                                @endif
                                <td data-header="Created At">{{$client->business_data_count}}</td>
                                <td>
                                    @if(isset($client->paymentData))
                                        @foreach ($client->paymentData as $payment)
                                            @php $amount = $amount + $payment->tax_amount; @endphp
                                        @endforeach
                                    @endif
                                    $ {{number_format($amount,2)}}
                                </td>
                                <td data-header="Created At">{{date('Y-m-d',strtotime($client['created_at']))}}</td>
                                <td data-header="Action" class="gc_flex">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <a href="{{route('admin.business',$client->id)}}"><img src="{{url('/')}}/assets/Images/add-user.png" alt="" width="22px" class="me-2"></a>
                                        <a href="{{route('admin.edit.client',$client->id)}}"><img src="{{url('/')}}/assets/Images/edit.png" alt="" class="ed_btn me-2"></a>
                                        <a href="javascript:void(0);" data-id="{{ $client->id }}" class="delete-btn"><img src="{{url('/')}}/assets/Images/delete.png" alt="" class="ed_btn "></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-btn',function(){
            var user_id = $(this).attr('data-id');
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
                    url : "{{route('delete.client', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "User has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                toastr.success('Client Deleted Successfully')
                                top.location.href="{{ route('admin.clients') }}";
                            }
                        });
                    }, error :function(error){
                        toastr.error(error.responseJSON.message)
                    }
                });
            }
            });
        });
    });
</script>
@endsection
