@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card my-3">
        <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
            <div class="pe-4 fs-5">Stop Subscription Request
                @if (!blank($id))
                    <?php $client = DB::table('users')->where('id',$id)->first(); ?>
                    - {{$client->name}}
                @endif
            </div>
            <div class="ms-auto">
                <div class="d-flex align-items-center">
                    <a href="{{route('admin.add.business')}}" class="btn gc_btn align-items-center d-none d-md-flex"><span class="fs-4 me-2">+</span>Add New</a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="table-responsive p-3">
            <table id="example" class="table rwd-table mb-0">
                <thead>
                    <tr>
                        <th>Business Name</th>
                        <th>Client Name</th>
                        <th>Status</th>
                        <th>Purchase From</th>
                        <th>Plan Title</th>
                        <th>Plan Amount</th>
                        <th>Created At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!blank($businessList))
                        @foreach ($businessList as $business)
                            <tr>
                                <td data-header="Name" class="pt-2">
                                    <a href="{{route('admin.business.detail',$business->id)}}">{{$business->name}}</a><br />
                                    <a href="{{url('/')}}/{{$business->shortname}}" target="_blank">{{url('/')}}/{{$business->shortname}}</a>
                                </td>
                                <td data-header="Email">{{$business->client->name}}</td>
                                <td data-header="Status">
                                    @if ($business->status == 1)
                                        <h4 class="badge bg-success">Active</h4>
                                    @else
                                        <h4 class="badge bg-danger">Deactive</h4>
                                    @endif
                                </td>
                                <td>{{$business->add_type}}</td>
                                <td>{{ isset($business->planDetail) ? $business->planDetail->plan_title : '-' }}</td>
                                    <td>$
                                        {{ isset($business->planDetail) ? number_format($business->planDetail->price, 2) : 0.0 }}
                                    </td>
                                <td data-header="Created At">{{date('Y-m-d',strtotime($business->created_at))}}</td>
                                <td data-header="Action" class="gc_flex">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <a href="javascript:void(0);" data-id="{{ $business->id }}" class="delete-btn bg-success btn ">Approve</a>
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
            text: "Ara you sure you want to stop subcription?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, stop it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.business', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Business has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                toastr.success("Subscription Stop Successfully.");
                                top.location.href="{{ route('admin.business-request') }}";
                            }
                        });
                    },error: function(error){
                        toastr.error(error.responseJSON.message)
                    }
                });
            }
            });
        });
    });
</script>
@endsection
