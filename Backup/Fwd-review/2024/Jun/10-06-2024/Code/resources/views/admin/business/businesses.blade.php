@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">All Business
                    @if (!blank($id))
                        <?php $client = DB::table('users')->where('id', $id)->first(); ?>
                        - {{ $client->name }}
                    @endif
                </div>
                <div class="ms-auto">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.add.business') }}"
                            class="btn gc_btn align-items-center d-none d-md-flex"><span class="fs-4 me-2">+</span>Add New</a>
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
                            <th>Place Id</th>
                            <th>Api Key</th>
                            <th>Sub End Date</th>
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($businesses))
                            @foreach ($businesses as $business)
                                <tr>
                                    <td data-header="Name" class="pt-2">
                                        <a href="{{route('admin.business.detail',$business->id)}}">{{ $business->name }}</a> <br/>
                                        @if(isset($business->shortname))
                                        <a href="{{url('/')}}/{{ $business->shortname}}" target="_blank">{{url('/')}}/{{ $business->shortname}}</a> <br/>
                                        @endif
                                    </td>
                                    <td data-header="Email">{{ $business->client->name }}</td>
                                    <td data-header="Status">
                                        @if ($business->status == 1)
                                            <h4 class="badge bg-success">Active</h4>
                                        @else
                                            <h4 class="badge bg-danger">Deactive</h4>
                                        @endif
                                    </td>
                                    <td data-header="Email">{{ $business->add_type }}</td>
                                    <td data-header="Email">{{ isset($business->planDetail) ? $business->planDetail->plan_title : '' }}</td>
                                    <td data-header="Email">{{ isset($business->planDetail) ? "$ ".$business->planDetail->price : '' }}</td>
                                    <td data-header="Email">{{ $business->place_id }}</td>
                                    <td data-header="Email">{{ $business->api_key }}</td>
                                    <td data-header="Created At">
                                        @php $date = ""; @endphp
                                        @if ($business->sub_end_date !== null)
                                            @php $date = date('Y-m-d', strtotime($business->sub_end_date))@endphp
                                        @endif
                                        {{ $date }}
                                    </td>
                                    <td data-header="Created At">{{ date('Y-m-d', strtotime($business->created_at)) }}</td>
                                    <td data-header="Action" class="gc_flex">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <a href="{{url('/')}}/{{$business->shortname}}" target="_blank"><img
                                                src="{{ url('/') }}/assets/Images/arrow-up.png" alt=""
                                                class="ed_btn me-2"></a>
                                            <a href="{{ route('admin.edit.business', $business->id) }}"><img
                                                    src="{{ url('/') }}/assets/Images/edit.png" alt=""
                                                    class="ed_btn me-2"></a>
                                            <a href="javascript:void(0);" data-id="{{ $business->id }}"
                                                class="delete-btn"><img src="{{ url('/') }}/assets/Images/delete.png"
                                                    alt="" class="ed_btn "></a>
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
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
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
                            url: "{{ route('delete.business', '') }}" + "/" + user_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
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
                                        toastr.success(data.message);
                                        top.location.href =
                                            "{{ route('admin.business') }}";
                                    }
                                });
                            },error:function(error){
                                toastr.error(error.responseJSON.message)
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
