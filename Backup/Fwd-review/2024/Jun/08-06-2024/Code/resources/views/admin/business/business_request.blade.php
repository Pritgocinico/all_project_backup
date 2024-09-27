@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-3">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Client Request</li>
                </ol>
            </nav>
        </div>

        <div class="card mt-md-3 mb-3 p-3 d-flex">
            <div class="card-body">
                <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-links" class="nav-link active" id="pills-links-tab" data-bs-toggle="pill" data-bs-target="#pills-links" type="button" role="tab" aria-controls="pills-links" aria-selected="true">Stop Subscription</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-content" class="nav-link" id="pills-content-tab" data-bs-toggle="pill" data-bs-target="#pills-content" type="button" role="tab" aria-controls="pills-content" aria-selected="false">Update Business</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-links" role="tabpanel" aria-labelledby="pills-links-tab">
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
                                                    <a
                                                        href="{{ route('admin.business.detail', $business->id) }}">{{ $business->name }}</a><br />
                                                    <a href="{{ url('/') }}/{{ $business->shortname }}"
                                                        target="_blank">{{ url('/') }}/{{ $business->shortname }}</a>
                                                </td>
                                                <td data-header="Email">{{ $business->client->name }}</td>
                                                <td data-header="Status">
                                                    @if ($business->status == 1)
                                                        <h4 class="badge bg-success">Active</h4>
                                                    @else
                                                        <h4 class="badge bg-danger">Deactive</h4>
                                                    @endif
                                                </td>
                                                <td>{{ $business->add_type }}</td>
                                                <td>{{ isset($business->planDetail) ? $business->planDetail->plan_title : '-' }}
                                                </td>
                                                <td>$
                                                    {{ isset($business->planDetail) ? number_format($business->planDetail->price, 2) : 0.0 }}
                                                </td>
                                                <td data-header="Created At">
                                                    {{ date('Y-m-d', strtotime($business->created_at)) }}</td>
                                                <td data-header="Action" class="gc_flex">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <a href="javascript:void(0);" data-id="{{ $business->id }}"
                                                            class="delete-btn gc_btn btn ">Stop</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-content" role="tabpanel" aria-labelledby="pills-content-tab">
                        <div class="table-responsive">
                            <div class="table-responsive p-3">
                                <table id="example123" class="table rwd-table mb-0 w-100">
                                    <thead>
                                        <tr>
                                            <th>Business Name</th>
                                            <th>Client Name</th>
                                            <th>Purchase From</th>
                                            <th>Plan Title</th>
                                            <th>Plan Amount</th>
                                            <th>Short Name</th>
                                            <th>Place Id</th>
                                            <th>Api key</th>
                                            <th>Created At</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!blank($businessEditList))
                                            @foreach ($businessEditList as $businessRequest)
                                                <tr>
                                                    <td data-header="Business Name" class="pt-2">
                                                        @php
                                                            $id = isset($businessRequest->businessDetail)
                                                                ? $businessRequest->businessDetail->id
                                                                : '';
                                                        @endphp
                                                        <a
                                                            href="{{ route('admin.business.detail', $id) }}">{{ $businessRequest->name }}</a>
                                                        <br /> <br />
                                                        <a href="{{ route('admin.business.detail', $id) }}">Old:-
                                                            {{ isset($businessRequest->businessDetail) ? $businessRequest->businessDetail->name : '-' }}</a>
                                                    </td>
                                                    <td data-header="Client Nam">
                                                        {{ isset($businessRequest->clientDetail) ? $businessRequest->clientDetail->name : '-' }}
                                                    </td>
                                                    <td data-header="Purchase From">
                                                        {{ isset($businessRequest->businessDetail) ? $businessRequest->businessDetail->add_type : '-' }}
                                                    </td>
                                                    <td data-header="Plan Title">
                                                        {{ isset($businessRequest->planDetail) ? $businessRequest->planDetail->plan_title : '-' }}
                                                    </td>
                                                    <td data-header="Plan Amount">$
                                                        {{ isset($businessRequest->planDetail) ? number_format($businessRequest->planDetail->price, 2) : 0.0 }}
                                                    </td>
                                                    <td>
                                                        {{ $businessRequest->shortname }} <br /> <br />
                                                        Old:-
                                                        {{ isset($businessRequest->businessDetail) ? $businessRequest->businessDetail->shortname : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $businessRequest->place_id }} <br /> <br />
                                                        Old:-
                                                        {{ isset($businessRequest->businessDetail) ? $businessRequest->businessDetail->place_id : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $businessRequest->api_key }} <br /> <br />
                                                        Old:-
                                                        {{ isset($businessRequest->businessDetail) ? $businessRequest->businessDetail->api_key : '-' }}
                                                    </td>
                                                    <td data-header="Created At">
                                                        {{ date('Y-m-d', strtotime($businessRequest->created_at)) }}
                                                    </td>

                                                    <td data-header="Action" class="gc_flex">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <a href="javascript:void(0);"
                                                                data-id="{{ $businessRequest->id }}"
                                                                data-business="{{ $id }}" data-type="approve"
                                                                class="approve-btn gc_btn btn ">Approve</a>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-end mt-1">
                                                            <a href="javascript:void(0);"
                                                                data-id="{{ $businessRequest->id }}"
                                                                data-business="{{ $id }}" data-type="cancel"
                                                                class="cancel-btn gc_btn btn">Cancel</a>
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
                </div>
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
                    text: "Are you sure you want to stop subcription?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, stop it!'
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
                                        toastr.success(
                                            "Subscription Stop Successfully."
                                        );
                                        top.location.href="{{ route('admin.business-request') }}";
                                    }
                                });
                            },
                            error: function(error) {
                                toastr.error(error.responseJSON.message)
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.cancel-btn', function() {
                var req_id = $(this).attr('data-id');
                var business_id = $(this).attr('data-business');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Ara you sure you want to cancel edit business request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('update.business.request') }}",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                request_id: req_id,
                                business_id: business_id,
                                type: $(this).attr('data-type')
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: 'cancel!',
                                    text: "Business has been cancelled.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        toastr.success(
                                            "Business Request Cancelled Successfully."
                                        );
                                        top.location.href="{{ route('admin.business-request') }}";
                                    }
                                });
                            },
                            error: function(error) {
                                toastr.error(error.responseJSON.message)
                            }
                        });
                    }
                });
            });
            $(document).on('click', '.approve-btn', function() {
                var req_id = $(this).attr('data-id');
                var business_id = $(this).attr('data-business');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Ara you sure you want to approve edit business request?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, approve it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('update.business.request') }}",
                            type: 'GET',
                            dataType: 'json',
                            data: {
                                request_id: req_id,
                                business_id: business_id,
                                type: $(this).attr('data-type')
                            },
                            success: function(data) {
                                Swal.fire({
                                    title: 'cancel!',
                                    text: "Business has been Approved.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        toastr.success(
                                            "Business Request Approved Successfully."
                                        );
                                        top.location.href="{{ route('admin.business-request') }}";
                                    }
                                });
                            },
                            error: function(error) {
                                toastr.error(error.responseJSON.message)
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
