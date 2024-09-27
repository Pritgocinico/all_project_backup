@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card my-3">
            <div class="card-body d-sm-flex d-block  align-items-center p-lg-3 p-2 staff_header ">
                <div class="pe-4 fs-5">Business List
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
                            <th>Created At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!blank($businessList))
                            @foreach ($businessList as $business)
                                <tr>
                                    <td data-header="Name" class="pt-2">
                                        <a href="">{{ $business->name }}</a>
                                    </td>
                                    <td data-header="Email">{{ $business->client->name }}</td>
                                    <td data-header="Status">
                                        @if ($business->status == 1)
                                            <h4 class="badge bg-success">Active</h4>
                                        @else
                                            <h4 class="badge bg-danger">Deactive</h4>
                                        @endif
                                    </td>
                                    <td data-header="Created At">{{ date('Y-m-d', strtotime($business->created_at)) }}</td>
                                    <td data-header="Action" class="gc_flex">
                                        <div class="d-flex align-items-center justify-content-end">
                                            @if ($business->delete_request == '0')
                                                <a href="javascript:void(0);" data-id="{{ $business->id }}"
                                                    class="delete-btn"><img
                                                        src="{{ url('/') }}/assets/Images/delete.png" alt=""
                                                        class="ed_btn "></a>
                                            @else
                                                <span class="badge bg-success">Requested</span>
                                            @endif
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
        $('.delete-btn').on('click', function(e) {
            var user_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure sent request to admin for delete business?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, sent it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('client.business.request', '') }}" + "/" + user_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Requested!',
                                text: "Business request sent to admin.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    top.location.href =
                                        "{{ route('client.business') }}";
                                }
                            });
                        }
                    });
                }
            });
            3
        })
    </script>
@endsection
