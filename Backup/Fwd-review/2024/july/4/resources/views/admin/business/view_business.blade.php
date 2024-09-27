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
                <div class="pe-4 fs-5">Business Detail</div>
                <div class="ms-auto">
                    <a href="javascript:void(0)" onclick="history.back()" class="btn gc_btn">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card mt-md-3 mb-3 p-3 d-flex">
            <div class="card-body">
                <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-profile-overview" class="nav-link active" id="pills-profile-overview-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-profile-overview" type="button" role="tab"
                            aria-controls="pills-profile-overview" aria-selected="true">Business Detail</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-campaings" class="nav-link" id="pills-campaings-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-campaings" type="button" role="tab" aria-controls="pills-campaings"
                            aria-selected="false">Payment Details</a>
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
                            <div class="col-md-3">
                                <div class="pe-4 fs-5 mb-3">Business Details </div>
                                <div class="mt-4">
                                    <label class="form-label">Business Name </label>
                                    <h6>{{ $business->name }}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <label class="form-label">Client Name </label>
                                    <h6>{{ $client->email }}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <label class="form-label">Purchase From </label>
                                    <h6>{{ $business->add_type }}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <label class="form-label">Subscription End Date </label>
                                    <h6>
                                        @php $date = ""; @endphp
                                        @if($business->sub_end_date != null)
                                            @php $date = date('Y-m-d',strtotime($business->sub_end_date)); @endphp
                                        @endif
                                    {{$date}}    
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <label class="form-label">Place Id</label>
                                    <h6>{{ $business->place_id }}</h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mt-4">
                                    <label class="form-label">Api Key</label>
                                    <h6>{{ $business->api_key }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-campaings" role="tabpanel" aria-labelledby="pills-campaings-tab">
                        <table id="example" class="table rwd-table mb-0">
                            <thead>
                                <tr>
                                    <th>Business Name</th>
                                    <th>Transaction Number</th>
                                    <th>Plan Title</th>
                                    <th>Subscription End Date</th>
                                    <th>Amount</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($paymentDetail))
                                    @foreach ($paymentDetail as $payment)
                                        <tr>
                                            <td data-header="Name" class="pt-2">
                                                {{ $business->name }}
                                            </td>
                                            <td data-header="Status">
                                                {{$payment->transaction_number}}
                                            </td>
                                            <td data-header="Email">{{ $payment->plan_title }}</td>
                                            <td data-header="Created At">
                                                @php $date = ""; @endphp
                                                @if ($payment->expiry_date !== null)
                                                    @php $date = date('Y-m-d', strtotime($payment->expiry_date))@endphp
                                                @endif
                                                {{ $date }}
                                            </td>
                                            <td>$ {{number_format($payment->tax_amount,2)}}</td>
                                            <td data-header="Created At">
                                                {{ date('Y-m-d', strtotime($payment->created_at)) }}</td>
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
@endsection
