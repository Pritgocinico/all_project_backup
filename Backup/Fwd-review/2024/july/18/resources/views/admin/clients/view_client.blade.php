@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background: #232323;
        }

        .accordion {
            margin: 40px 0;
        }

        .accordion .item {
            border: none;
            margin-bottom: 50px;
            background: none;
        }

        .t-p {
            color: rgb(193 206 216);
            padding: 40px 30px 0px 30px;
        }

        .accordion .item .item-header h2 button.btn.btn-link {
            background: #333435;
            color: white;
            border-radius: 0px;
            font-family: 'Poppins';
            font-size: 16px;
            font-weight: 400;
            line-height: 2.5;
            text-decoration: none;
        }

        .accordion .item .item-header {
            border-bottom: none;
            background: transparent;
            padding: 0px;
            margin: 2px;
        }

        .accordion .item .item-header h2 button {
            color: white;
            font-size: 20px;
            padding: 15px;
            display: block;
            width: 100%;
            text-align: left;
        }

        .accordion .item .item-header h2 i {
            float: right;
            font-size: 30px;
            color: #eca300;
            background-color: black;
            width: 60px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
        }

        button.btn.btn-link.collapsed i {
            transform: rotate(0deg);
        }

        button.btn.btn-link i {
            transform: rotate(180deg);
            transition: 0.5s;
        }
    </style>
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
                <div class="pe-4 fs-5">View Client</div>
                <div class="ms-auto">
                    <a href="{{ route('admin.clients') }}" class="btn gc_btn">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card mt-md-3 mb-3 p-3 d-flex">
            <div class="card-body">
                <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-profile-overview" class="nav-link active" id="pills-profile-overview-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-profile-overview" type="button" role="tab"
                            aria-controls="pills-profile-overview" aria-selected="true">Client Detail</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-campaings" class="nav-link" id="pills-campaings-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-campaings" type="button" role="tab" aria-controls="pills-campaings"
                            aria-selected="false">Business Details</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profile-overview" role="tabpanel"
                        aria-labelledby="pills-profile-overview-tab">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="pe-4 fs-5 mb-3">Client Details </div>
                                <div class="mt-4">
                                    <label class="form-label">Client Name </label>
                                    <h6>{{ $client->name }}</h6>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="mt-4">
                                    <label class="form-label">Client Email </label>
                                    <h6>{{ $client->email }}</h6>
                                </div>
                            </div>
                            <div class="col-md-4">

                                <div class="mt-4">
                                    <label class="form-label">Client Phone </label>
                                    <h6>{{ $client->phone }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-campaings" role="tabpanel" aria-labelledby="pills-campaings-tab">
                        <div class="table-responsive">

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
                                                    <a
                                                        href="{{ route('admin.business.detail', $business->id) }}">{{ $business->name }}</a><br />
                                                    @if (isset($business->shortname))
                                                        <a href="{{ url('/') }}/{{ $business->shortname }}"
                                                            target="_blank">{{ url('/') }}/{{ $business->shortname }}</a>
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
                                                <td data-header="Email">
                                                    {{ isset($business->planDetail) ? $business->planDetail->plan_title : '' }}
                                                </td>
                                                <td data-header="Email">
                                                    {{ isset($business->planDetail) ? "$ " . $business->planDetail->price : '' }}
                                                </td>
                                                <td data-header="Email">{{ $business->place_id }}</td>
                                                <td data-header="Email">{{ $business->api_key }}</td>
                                                <td data-header="Created At">
                                                    @php $date = ""; @endphp
                                                    @if ($business->sub_end_date !== null)
                                                        @php $date = date('Y-m-d', strtotime($business->sub_end_date))@endphp
                                                    @endif
                                                    {{ $date }}
                                                </td>
                                                <td data-header="Created At">
                                                    {{ date('Y-m-d', strtotime($business->created_at)) }}</td>
                                                <td data-header="Action" class="gc_flex">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <a href="{{ route('admin.edit.business', $business->id) }}"><img
                                                                src="{{ url('/') }}/assets/Images/edit.png"
                                                                alt="" class="ed_btn me-2"></a>
                                                        <a href="javascript:void(0);" data-id="{{ $business->id }}"
                                                            class="delete-btn"><img
                                                                src="{{ url('/') }}/assets/Images/delete.png"
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
            </div>
        </div>
    </div>
@endsection
