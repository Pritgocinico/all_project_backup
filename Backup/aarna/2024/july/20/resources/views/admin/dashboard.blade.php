@extends('admin.layouts.app')
@section('style')
    <style>
        #myChart {
            /*height: 600px;*/
            width: 100%;
        }

        /* Styles for the previous LOB chart */
        #previousChart {
            /*height: 500px;*/
            width: 100%;
        }
    </style>
@endsection
@if (Auth::user()->role != 3)
    @section('content')
        <form action="{{ route('admin.dashboard') }}" method="GET">
            <!-- Container-fluid starts-->
            <!-- Container-fluid starts-->
            <div class="container-fluid ecommerce-dashboard">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                        <div class="card total-sales">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4 xl-3 col-md-5 col-sm-12 col box-col-12">
                                        <?php
                                        if (isset($_GET['from_date'])) {
                                            $from_date = $_GET['from_date'];
                                        } else {
                                            $from_date = date('01-m-Y');
                                        }
                                        ?>
                                        <div class="form-floating">
                                            <input type="date" name="from_date" class="form-control" id=""
                                                value="{{ date('Y-m-d', strtotime($from_date)) }}">
                                            <label for="from_date">From Date</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 xl-3 col-md-5 col-sm-12 col box-col-12">
                                        <?php
                                        if (isset($_GET['to_date'])) {
                                            $to_date = $_GET['to_date'];
                                        } else {
                                            $to_date = date('d-m-Y');
                                        }
                                        ?>
                                        <div class="form-floating">
                                            <input type="date" name="to_date" class="form-control" id=""
                                                value="{{ date('Y-m-d', strtotime($to_date)) }}">
                                            <label for="to_date">To Date</label>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 xl-2 col-md-1 col-sm-12 col box-col-12">
                                        <button type="submit" class="btn btn-secondary"><i class="fa fa-filter"></i>
                                            Filter</button>
                                    </div>
                                    <div class="col-xl-2 xl-2 col-md-1 col-sm-12 col box-col-12">
                                        <a class="btn btn-primary" href="{{ route('admin.dashboard') }}"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-xl-8 col-lg-8 box-col-8">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex"> <span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-bonus-kit">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $covernotes }}</h4>
                                                        <h6>Total <br>CoverNote</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex up-sales"><span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-icons">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $renewal }}</h4>
                                                        <h6>Total <br>Renewal Policy</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex total-customer"><span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-user">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $agents }}</h4>
                                                        <h6>Total <br>Agents</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex up-sales"><span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-editors">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $health_policy }}</h4>
                                                        <h6>Total <br>Health Policy</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex total-product"><span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-learning">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $vehicle_policy }}</h4>
                                                        <h6>Total <br>Vehicle Policy</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <div class="card total-sales">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-8 xl-12 col-md-8 col-sm-12 col box-col-12">
                                                <div class="d-flex"><span>
                                                        <svg>
                                                            <use
                                                                href="{{ url('/') }}/assets/svg/icon-sprite.svg#fill-layout">
                                                            </use>
                                                        </svg></span>
                                                    <div class="flex-shrink-0">
                                                        <h4>{{ $payout_list }}</h4>
                                                        <h6>Generated <br>Payouts</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-xl-40 col-md-7 box-col-5e">
                        <div class="card selling-product">
                            <div class="card-header pb-0">
                                <div class="header-top">
                                    <h4>Sales Overview</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs border-tab border-0 mb-0 nav-danger" id="topline-tab"
                                    role="tablist">
                                    <li class="nav-item">
                                        <a href="#pills-today" class="nav-link me-2 active" id="pills-today-tab"
                                            data-bs-toggle="tab" role="tab" aria-controls="pills-today"
                                            aria-selected="true">Today</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#pills-month" class="nav-link me-2" id="pills-month-tab"
                                            data-bs-toggle="tab" role="tab" aria-controls="pills-month"
                                            aria-selected="false">Till Month</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#pills-year" class="nav-link me-2" id="pills-year-tab"
                                            data-bs-toggle="tab" role="tab" aria-controls="pills-year"
                                            aria-selected="false">Year Till Date</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#pills-finance" class="nav-link me-2" id="pills-finance-tab"
                                            data-bs-toggle="tab" role="tab" aria-controls="pills-finance"
                                            aria-selected="false">Last Finance Year
                                            ({{ date('Y', strtotime($startOfLastFinancialYear)) . ' - ' . date('Y', strtotime($endOfLastFinancialYear)) }})</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="topline-tabContent">
                                    <div class="tab-pane fade show active" id="pills-today" role="tabpanel"
                                        aria-labelledby="pills-today-tab">
                                        <div class="table-responsive custom-scrollbar">
                                            <table class="table display" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h5>NET PREMINUM AMOUNT</h5>
                                                        </td>
                                                        <td>
                                                            <h5>₹{{ number_format($sales_overview['npa'], 2) }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NOP</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['nop'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['motor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NON MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['nonmotor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>HELTH</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['helth'] }}</h5>
                                                        </td>
                                                    <tr>
                                                        <td>
                                                            <h5>RENEWALS</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['renewal'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NEW</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $sales_overview['new'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-month" role="tabpanel"
                                        aria-labelledby="pills-month-tab">
                                        <div class="table-responsive custom-scrollbar">
                                            <table class="table display" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h5>NET PREMINUM AMOUNT</h5>
                                                        </td>
                                                        <td>
                                                            <h5>₹{{ number_format($month_overview['npa'], 2) }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NOP</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['nop'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['motor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NON MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['nonmotor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>HELTH</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['helth'] }}</h5>
                                                        </td>
                                                    <tr>
                                                        <td>
                                                            <h5>RENEWALS</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['renewal'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NEW</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $month_overview['new'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-year" role="tabpanel"
                                        aria-labelledby="pills-year-tab">
                                        <div class="table-responsive custom-scrollbar">
                                            <table class="table display" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h5>NET PREMINUM AMOUNT</h5>
                                                        </td>
                                                        <td>
                                                            <h5>₹{{ number_format($year_overview['npa'], 2) }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NOP</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['nop'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['motor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NON MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['nonmotor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>HELTH</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['helth'] }}</h5>
                                                        </td>
                                                    <tr>
                                                        <td>
                                                            <h5>RENEWALS</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['renewal'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NEW</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $year_overview['new'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-finance" role="tabpanel"
                                        aria-labelledby="pills-attachmenfinancets-tab">
                                        <div class="table-responsive custom-scrollbar">
                                            <table class="table display" style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <h5>NET PREMINUM AMOUNT</h5>
                                                        </td>
                                                        <td>
                                                            <h5>₹{{ number_format($finance_overview['npa'], 2) }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NOP</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['nop'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['motor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NON MOTOR</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['nonmotor'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>HELTH</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['helth'] }}</h5>
                                                        </td>
                                                    <tr>
                                                        <td>
                                                            <h5>RENEWALS</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['renewal'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <h5>NEW</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $finance_overview['new'] }}</h5>
                                                        </td>
                                                    </tr>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 box-col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="header-top">
                                    <h4>Claim </h4>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="display border" id="basic-1">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Claim No</th>
                                                <th>Vehicle Chassis No</th>
                                                <th>Vehicle Make</th>
                                                <th>Vehicle Model</th>
                                                <th>Vehicle Registration No</th>
                                                <th>Policy Type</th>
                                                <th>Claim Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!blank($claims))
                                                @foreach ($claims as $claim)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td><a class="table-url"
                                                                href="{{ route('admin.view_claim', $claim->id) }}">{{ $claim->claim_no }}</a>
                                                        </td>
                                                        <td>{{ $claim->policy->vehicle_chassis_no }}</td>
                                                        <td>{{ $claim->policy->vehicle_make }}</td>
                                                        <td>{{ $claim->policy->vehicle_model }}</td>
                                                        <td>{{ $claim->policy->vehicle_registration_no }}</td>
                                                        <td>
                                                            @if (!is_null($claim->policy->sub_category))
                                                                @php $sub_category = App\Models\Category::firstWhere('id', $claim->policy->sub_category) @endphp
                                                                @if (!is_null($sub_category))
                                                                    {{ $sub_category->name }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($claim->claim_status == 1)
                                                                <span class="badge badge-success">Open</span>
                                                            @elseif ($claim->claim_status == 2)
                                                                <span class="badge badge-info">Close</span>
                                                            @else
                                                                <span class="badge badge-warning">Repuidated</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <ul class="action">
                                                                <li class="edit"> <a
                                                                        href="{{ route('admin.claim.remarks', $claim->id) }}"><i
                                                                            class="icon-comment"></i></a> </li>
                                                                <li class="edit"> <a
                                                                        href="{{ route('admin.view_claim', $claim->id) }}"><i
                                                                            class="icon-eye"></i> </a> </li>
                                                                <li class="edit"> <a
                                                                        href="{{ route('admin.edit_claim', $claim->id) }}"><i
                                                                            class="icon-pencil-alt"></i></a> </li>
                                                                <li class="delete"><a href="javascript:void(0);"
                                                                        data-id="{{ $claim->id }}"
                                                                        class="delete-btn"><i class="icon-trash"></i></a>
                                                                </li>
                                                            </ul>
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
                <!-- Container-fluid Ends-->
                <div class="container-fluid default-dashboard">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mt-3">
                            <div class="card total-sales">
                                <div class="card-body">
                                    <div class="justify-content-between d-flex">
                                        <div style="width:15%">
                                            <div class="form-floating">
                                                <?php
                                                $lob_month = date('m');
                                                if (isset($_GET['lob_month'])) {
                                                    $lob_month = $_GET['lob_month'];
                                                } else {
                                                    $lob_month = date('m');
                                                }
                                                ?>
                                                <select class="form-control m-input col-lg-3" name="lob_month"
                                                    id="year">
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}" <?php if ($i == $lob_month) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <label for="lob_month">Select Current Month</label>
                                            </div>
                                        </div>
                                        <div style="width:15%">
                                            <div class="form-floating">
                                                <?php
                                                $lob_year = date('Y');
                                                if (isset($_GET['lob_year'])) {
                                                    $lob_year = $_GET['lob_year'];
                                                } else {
                                                    // $lob_year = date('Y');
                                                    $lob_year = 2019;
                                                }
                                                ?>
                                                <select class="form-control m-input col-lg-3" name="lob_year"
                                                    id="year">
                                                    @for ($i = 2018; $i <= 2100; $i++)
                                                        <option value="{{ $i }}" <?php if ($i == $lob_year) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <label for="lob_year">Select Current Year</label>
                                            </div>
                                        </div>
                                        <div style="width:15%">
                                            <div class="form-floating">
                                                <?php
                                                $p_lob_month = date('m');
                                                if (isset($_GET['p_lob_month'])) {
                                                    $p_lob_month = $_GET['p_lob_month'];
                                                } else {
                                                    if (date('m') == 1) {
                                                        $p_lob_month = 12;
                                                    } else {
                                                        $p_lob_month = date('m') - 1;
                                                    }
                                                }
                                                ?>
                                                <select class="form-control m-input col-lg-3" name="p_lob_month"
                                                    id="year">
                                                    @for ($i = 1; $i <= 12; $i++)
                                                        <option value="{{ $i }}" <?php if ($i == $p_lob_month) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <label for="p_lob_month">Select Previous Month</label>
                                            </div>
                                        </div>
                                        <div style="width:15%">
                                            <div class="form-floating">
                                                <?php
                                                $p_lob_year = date('Y');
                                                if (isset($_GET['p_lob_year'])) {
                                                    $p_lob_year = $_GET['p_lob_year'];
                                                } else {
                                                    // $p_lob_year = date('Y');
                                                    $p_lob_year = 2018;
                                                }
                                                ?>
                                                <select class="form-control m-input col-lg-3" name="p_lob_year"
                                                    id="year">
                                                    @for ($i = 2018; $i <= 2100; $i++)
                                                        <option value="{{ $i }}" <?php if ($i == $p_lob_year) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $i }}</option>
                                                    @endfor
                                                </select>
                                                <label for="p_lob_year">Select Previous Year</label>
                                            </div>
                                        </div>
                                        <div style="width:10%">
                                            <button type="submit" class="btn btn-secondary"> Filter</button>
                                        </div>
                                        <div style="width:8%">
                                            <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">
                                                Refresh</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-md-0 mb-3">
                                <div class="card p-2">
                                    <h4 class="text-center">{{ date('F', mktime(0, 0, 0, $lob_month, 1)) }},
                                        {{ $lob_year }} LOB</h4>
                                    <div class="chart-wrapper">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card p-2">
                                    <h4 class="text-center">{{ date('F', mktime(0, 0, 0, $p_lob_month, 1)) }},
                                        {{ $p_lob_year }} LOB</h4>
                                    <div class="chart-wrapper">
                                        <canvas id="previousChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <div class="card mb-3 p-3">
                                    <div class="row mt-sm-3 mb-sm-3 ">
                                        <div class="col-md-8 row">
                                            <div class="col-md-5  mb-md-0 mb-sm-3 mb-2">
                                                <div class="form-floating">
                                                    <?php
                                                    $previous_year = date('Y') - 1;
                                                    if (isset($_GET['previous_year'])) {
                                                        $previous_year = $_GET['previous_year'];
                                                    } else {
                                                        // $previous_year = date('Y')-1;
                                                        $previous_year = 2018;
                                                    }
                                                    ?>
                                                    <select class="form-control m-input col-lg-3" name="previous_year"
                                                        id="year">
                                                        @for ($i = 2018; $i <= 2100; $i++)
                                                            <option value="{{ $i }}" <?php if ($i == $previous_year) {
                                                                echo 'selected';
                                                            } ?>>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    <label for="previous_year">Previous Year</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5  mb-md-0 mb-sm-3 mb-2">
                                                <div class="form-floating">
                                                    <?php
                                                    $current_year = date('Y');
                                                    if (isset($_GET['current_year'])) {
                                                        $current_year = $_GET['current_year'];
                                                    } else {
                                                        // $current_year = date('Y');
                                                        $current_year = 2019;
                                                    }
                                                    ?>
                                                    <select class="form-control m-input col-lg-3" name="current_year"
                                                        id="year">
                                                        @for ($i = 2018; $i <= 2100; $i++)
                                                            <option value="{{ $i }}" <?php if ($i == $current_year) {
                                                                echo 'selected';
                                                            } ?>>
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                    <label for="current_year">Current Year</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end  mb-md-0 mb-sm-3 mb-2 ">
                                                <button type="submit" class="btn btn-secondary"><i
                                                        class="fa fa-filter"></i>Filter</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card p-5">
                                    <canvas id="myChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </form>
    @endsection
    @section('script')
        <script src="{{ url('/') }}/assets/js/chart/apex-chart/apex-chart.js"></script>
        <script src="{{ url('/') }}/assets/js/chart/apex-chart/stock-prices.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js"></script>
        <script>
            // Generate random colors for current LOB
            var coloR = [];
            for (var i = 0; i < {{ count($lob) }}; i++) {
                const randomNum = () => Math.floor(Math.random() * (235 - 52 + 1) + 52);
                const randomRGB = () => `rgb(${randomNum()}, ${randomNum()}, ${randomNum()})`;
                coloR.push(randomRGB());
            }

            // Generate random colors for last month LOB
            var coloR1 = [];
            for (var i = 0; i < {{ count($last_month_lob) }}; i++) {
                const randomNum = () => Math.floor(Math.random() * (235 - 52 + 1) + 52);
                const randomRGB = () => `rgb(${randomNum()}, ${randomNum()}, ${randomNum()})`;
                coloR1.push(randomRGB());
            }

            // Data for current LOB
            const data = {
                labels: @json($lob->map(fn($lob) => $lob->name)),
                datasets: [{
                    label: '{{ $lob_month }}, {{ $lob_year }} LOB',
                    backgroundColor: coloR,
                    borderColor: 'rgb(255, 255, 255)',
                    borderWidth: 1,
                    data: @json($lob->map(fn($lob) => $lob->total)),
                }],
            };

            // Configuration for current LOB column chart
            const config = {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    legend: {
                        display: true,
                        labels: {
                            padding: 20
                        },
                    },
                    tooltips: {
                        enabled: true,
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            };

            // Render current LOB column chart
            const myChart = new Chart(
                document.getElementById('myChart'),
                config
            );

            // Data for last month LOB
            const data1 = {
                labels: @json($last_month_lob->map(fn($last_month_lob) => $last_month_lob->name)),
                datasets: [{
                    label: '{{ $p_lob_month }}, {{ $p_lob_year }} LOB',
                    backgroundColor: coloR1,
                    borderColor: 'rgb(255, 255, 255)',
                    borderWidth: 1,
                    data: @json($last_month_lob->map(fn($last_month_lob) => $last_month_lob->total)),
                }],
            };
            // Configuration for last month LOB column chart
            const config1 = {
                type: 'bar',
                data: data1,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        labels: {
                            padding: 20
                        },
                    },
                    tooltips: {
                        enabled: true,
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            beginAtZero: true,
                        }
                    }
                }
            };

            // Render last month LOB column chart
            const previousChart = new Chart(
                document.getElementById('previousChart'),
                config1
            );
        </script>
        <script>
            var ctx = document.getElementById("myChart4").getContext('2d');
            var fixedDarkerColors = [
                'rgba(255, 140, 85, 1)', // Darker version of lightened #e6340d
                'rgba(140, 140, 255, 1)', // Darker version of lightened #333399
                'rgba(255, 191, 128, 1)', // Darker light orange
                'rgba(128, 179, 255, 1)', // Darker light blue
                'rgba(255, 203, 153, 1)', // Darker light peach
                'rgba(153, 214, 179, 1)', // Darker light green
                'rgba(255, 255, 128, 1)', // Darker light yellow
                'rgba(153, 153, 255, 1)', // Darker light lavender
                'rgba(255, 153, 255, 1)', // Darker light pink
                'rgba(128, 255, 255, 1)', // Darker light cyan
                'rgba(255, 153, 128, 1)', // Darker light salmon
                'rgba(179, 128, 255, 1)', // Darker light purple
                'rgba(255, 255, 153, 1)', // Darker light cream
                'rgba(153, 255, 204, 1)', // Darker light mint
                'rgba(255, 204, 153, 1)', // Darker light coral
                'rgba(204, 255, 153, 1)', // Darker light lime
                'rgba(153, 204, 255, 1)', // Darker light sky blue
                'rgba(255, 153, 204, 1)', // Darker light magenta
                'rgba(204, 153, 255, 1)', // Darker light violet
                'rgba(153, 255, 153, 1)', // Darker light sea green
                'rgba(255, 128, 128, 1)', // Darker light rose
                'rgba(128, 255, 128, 1)', // Darker light pastel green
                'rgba(255, 255, 128, 1)', // Darker light pale yellow
                'rgba(153, 153, 255, 1)', // Darker light periwinkle
                'rgba(255, 128, 255, 1)', // Darker light orchid
                'rgba(128, 255, 255, 1)', // Darker light aqua
                'rgba(255, 179, 179, 1)', // Darker light blush
                'rgba(179, 179, 255, 1)', // Darker light powder blue
                'rgba(255, 179, 153, 1)', // Darker light apricot
                'rgba(179, 153, 255, 1)', // Darker light mauve
                'rgba(255, 179, 128, 1)', // Darker light gold
                'rgba(128, 255, 179, 1)' // Darker light pastel green
            ];

            var datasets = {!! json_encode($chart) !!};
            var numberOfColors = datasets.length;

            datasets.forEach((dataset, index) => {
                var colorIndex = index % fixedDarkerColors.length;
                dataset.backgroundColor = fixedDarkerColors[colorIndex];
                dataset.borderColor = fixedDarkerColors[colorIndex];
            });

            var myChart1 = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September",
                        "October", "November", "December"
                    ],
                    datasets: datasets,
                },
                options: {
                    tooltips: {
                        displayColors: true,
                        callbacks: {
                            mode: 'x',
                        },
                    },
                    scales: {
                        x: {
                            stacked: true,
                            gridLines: {
                                display: false,
                            }
                        },
                        y: {
                            stacked: true,
                            ticks: {
                                beginAtZero: true,
                            },
                            type: 'linear',
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    },
                }
            });



            $(document).ready(function() {
                $(document).on('click', '.delete-btn', function() {
                    var claim_id = $(this).attr('data-id');
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
                                url: "{{ route('delete.claim', '') }}" + "/" + claim_id,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: "Sourcing Agent has been deleted.",
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
        </script>
    @endsection
@else
    @section('content')
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-md-3 mb-3" style="box-shadow:rgb(237 237 245 / 20%) 0px 7px 29px 0px">
                    <div class="card-body align-items-center p-3">
                        <div class="pe-4 fs-5 text-center">
                            <h5>Vehicle Policies</h5>
                            <h1>{{ $vehicle_policy }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mt-md-3 mb-3" style="box-shadow:rgb(237 237 245 / 20%) 0px 7px 29px 0px">
                    <div class="card-body align-items-center p-3">
                        <div class="pe-4 fs-5 text-center">
                            <h5>Health Policies</h5>
                            <h1>{{ $health_policy }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif
