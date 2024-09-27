@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Lead Management</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('leads.store') }}" enctype="multipart/form-data" method="POST">
                        @php
                            $departmentDetail = Auth()->user()->departmentDetail;
                            $type = 0;
                            if (isset($departmentDetail) && Auth()->user()->role_id !== 1) {
                                $deptName = $departmentDetail->name;
                                if (strpos($deptName, 'Financial')) {
                                    $type = 1;
                                }
                                if (strpos($deptName, 'Insurance')) {
                                    $type = 2;
                                }
                                if (strpos($deptName, 'travel')) {
                                    $type = 3;
                                }
                            }
                        @endphp
                        @csrf
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Customer</label></div>
                            <div class="col-md-3 col-xl-3">
                                <select name="customer_id" class="form-control" id="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0)"
                                    class="btn btn-white mx-xs-3 mx-0 text-nowrap openAddCustomerDataForm">
                                    <i class="bi bi-plus-square-fill"></i></a>
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Lead Amount</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="lead_amount" class="form-control" id="lead_amount"
                                    placeholder="Enter Lead Amount" value="{{ old('lead_amount') }}">
                                @error('lead_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                            <div class="col-md-4 col-xl-4">
                                @if (Auth()->user()->role_id == 1)
                                    <select name="invest_type" class="form-control" id="invest_type">
                                        <option value="">Select Insurance</option>
                                        <option value="investments">Investments</option>
                                        <option value="general insurance">General Insurance</option>
                                        <option value="travel">Travel</option>
                                    </select>
                                @else
                                    <select name="invest_type" class="form-control read-only" id="invest_type_data">
                                        <option value="">Select Insurance</option>
                                        <option value="investments" {{ $type == 1 ? 'selected' : '' }}>
                                            Investments</option>
                                        <option value="general insurance" {{ $type == 2 ? 'selected' : '' }}>General
                                            Insurance</option>
                                        <option value="travel" {{ $type == 3 ? 'selected' : '' }}>Travel
                                        </option>
                                    </select>
                                @endif
                                @error('invest_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 insurance_label"
                                style="display: @if ($type == 0) none @endif;"><label
                                    class="form-label mb-0">Insurance Type</label></div>
                            <div class="col-md-4 col-xl-4" id="investmentRadio"
                                style="display: @if ($type !== 1) none @endif;">
                                <input class="form-check-input" type="radio" name="insurance_type" id="pms_type"
                                    value="pms">
                                <label class="form-check-label" for="disabled">
                                    PMS
                                </label>

                                <input class="form-check-input" type="radio" name="insurance_type" id="mf_type"
                                    value="mf">
                                <label class="form-check-label" for="view">
                                    MF
                                </label>

                                <input class="form-check-input" type="radio" name="insurance_type" id="fd_type"
                                    value="fd">
                                <label class="form-check-label" for="corporate">
                                    FD
                                </label>

                                <input class="form-check-input" type="radio" name="insurance_type" id="bond_type"
                                    value="bond">
                                <label class="form-check-label" for="corporate">
                                    Bond
                                </label>
                            </div>
                            <div class="col-md-4 col-xl-4" id="generalRadio"
                                style="display: @if ($type !== 2) none @endif;">
                                <input class="form-check-input" type="radio" name="insurance_type" id="health_insurance"
                                    value="health">
                                <label class="form-check-label" for="disabled">
                                    Health
                                </label>

                                <input class="form-check-input" type="radio" name="insurance_type" id="motor_insurance"
                                    value="motor">
                                <label class="form-check-label" for="view">
                                    Motor
                                </label>

                                <input class="form-check-input" type="radio" name="insurance_type" id="sme_insurance"
                                    value="sme">
                                <label class="form-check-label" for="corporate">
                                    SME
                                </label>
                            </div>
                        </div>

                        <div id="type_of_investments" style="display: none;">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="product_name" id="product_name" class="form-control"
                                        placeholder="Enter Product Name" value="">
                                    @error('product_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="amount_of_investment" id="amount_of_investment"
                                        class="form-control" placeholder="Amount Of Investment">
                                    @error('amount_of_investment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="investment_date" id="investment_date"
                                        value="{{ old('investment_date', date('Y-m-d')) }}" class="form-control">
                                    @error('investment_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">SIP</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="sip" id="sip" class="form-control"
                                        value="">
                                    @error('sip')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="lumsum_amount" id="lumsum_amount" class="form-control">
                                    @error('lumsum_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="mf_sip" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="sip_amount" id="sip_amount" class="form-control"
                                        value="">
                                    @error('sip_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="sip_date" id="sip_date"
                                        value="{{ old('sip_date', date('Y-m-d')) }}" class="form-control">
                                    @error('sip_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="mf_installment" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">No. of Installment</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="installment_no" id="installment_no" class="form-control"
                                        value="">
                                    @error('installment_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="interest_rate" id="interest_rate" class="form-control"
                                        value="">
                                    @error('interest_rate')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="maturity_date" id="maturity_date"
                                        value="{{ old('maturity_date', date('Y-m-d')) }}" class="form-control">
                                    @error('maturity_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Managed By</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="managed_by" class="form-control" value="">
                                    @error('managed_by')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="lead_date" value="{{ old('lead_date', date('Y-m-d')) }}"
                                        class="form-control">
                                    @error('lead_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="general_insurance_div" style="display: @if ($type !== 2) none @endif;">
                            <hr class="my-6">
                            <h4>General Insurance</h4>
                            <div id="healthDiv" style="display: @if ($type !== 2) none @endif;">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Insurer</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="insurer" class="form-control" value="">
                                        @error('insurer')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Insured</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="insured" class="form-control">
                                        @error('insured')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="product" class="form-control">
                                            <option value="">Select Product</option>
                                        </select>
                                        @error('product')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Sub Product</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="sub_product" class="form-control">
                                            <option value="">Select Sub Product</option>
                                        </select>
                                        @error('sub_product')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="received_date"
                                            value="{{ old('received_date', date('Y-m-d')) }}" class="form-control">
                                        @error('received_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="number" name="sum_insurance" class="form-control">
                                        @error('sum_insurance')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Insurer DOB</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="insurer_dob"
                                            value="{{ old('insurer_dob', date('Y-m-d')) }}" class="form-control">
                                        @error('insurer_dob')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="motorDiv" style="display: none;">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="vehicle" class="form-control">
                                            <option value="">Select Vehicle</option>
                                            <option value="Two Wheeler">Two Wheeler</option>
                                            <option value="Four Wheeler">Four Wheeler</option>
                                            <option value="Commercial Vehicle">Commercial Vehicle</option>
                                            <option value="TP Policy Only">TP Policy Only</option>
                                        </select>
                                        @error('vehicle')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Client</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="client" class="form-control">
                                            <option value="">Client</option>
                                        </select>
                                        @error('client')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="date" name="received_date"
                                            value="{{ old('received_date', date('Y-m-d')) }}" class="form-control">
                                        @error('received_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Make</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="vehicle_make" class="form-control">
                                        @error('vehicle_make')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Model</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="vehicle_model" class="form-control">
                                        @error('vehicle_model')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">RC Copy</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="file" name="rc_copy" class="form-control"
                                            placeholder="Upload RC Copy">
                                        @error('rc_copy')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div id="smeDiv" style="display: none;">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Fire & Burglary</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="fire_burglary" class="form-control" value="">
                                        @error('fire_burglary')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Marine</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="marine" class="form-control">
                                        @error('marine')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">WC</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="wc" class="form-control">
                                        @error('wc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">GMC</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="gmc" class="form-control">
                                        @error('gmc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">GPA</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="gpa" class="form-control">
                                        @error('wc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Professional Indemnity</label>
                                    </div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="professional_indemnity" class="form-control">
                                        @error('gmc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Other Insurance</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="other_insurance" class="form-control">
                                        @error('other_insurance')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="travel_div" style="display: @if ($type !== 3) none @endif;">
                            <hr class="my-6">
                            <h4>Travel</h4>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="client_name" class="form-control" value="">
                                    @error('client_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="travel_from_date"
                                        value="{{ old('travel_from_date', date('Y-m-d')) }}" class="form-control">
                                    @error('travel_start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="travel_to_date"
                                        value="{{ old('travel_to_date', date('Y-m-d')) }}" class="form-control"
                                        value="">
                                    @error('travel_to_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Number Of Days</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="number_of_days" class="form-control">
                                    @error('number_of_days')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input class="form-check-input" type="radio" name="travel_destination"
                                        id="health_insurance" value="0" required checked>
                                    <label class="form-check-label" for="disabled">
                                        Domestic
                                    </label>

                                    <input class="form-check-input" type="radio" name="travel_destination"
                                        id="motor_insurance" value="1">
                                    <label class="form-check-label" for="view">
                                        International
                                    </label>
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="flight_preference" class="form-control">
                                    @error('flight_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="hotel_preference" class="form-control">
                                    @error('hotel_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="other_services" class="form-control">
                                        <option value="">Select Services</option>
                                        <option value="domestic_air_ticket">Domestic Air Ticket</option>
                                        <option value="visa">Visa</option>
                                        <option value="railway_ticket">Railway Ticket</option>
                                        <option value="hotel">Hotel</option>
                                        <option value="passport">Passport</option>
                                        <option value="rent_cab">Rent a Cab</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('other_services')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Itinerary Flow</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Flow"></textarea>
                                    @error('itinerary_flow')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="assigned_to[]" class="form-control js-example-basic-single"
                                    id="assigned_to" multiple>
                                    <option value="">Select Assignee</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ in_array($user->id, old('assigned_to') ?? []) ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flex justify-content-start gap-2">
                            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Customer</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="addForm">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                        value="{{ old('name', $customer->name ?? '') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                        value="{{ old('email', $customer->email ?? '') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Password</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter Password" value="{{ old('password') }}">
                                        <div class="input-group-append login_button_password">
                                            <span class="input-group-text" id="password_eye_button"><i
                                                    class="bi bi-eye"></i></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Confirm Password</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="input-group">
                                        <input type="password" name="confirm_password" class="form-control"
                                            placeholder="Enter Confirm Password" id="confirm_password"
                                            value="{{ old('confirm_password') }}">
                                        <div class="input-group-append confirm_button_password">
                                            <span class="input-group-text" id="confirm_eye_button"><i
                                                    class="bi bi-eye"></i></span>
                                        </div>
                                    </div>
                                    @error('confirm_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="mobile_number" class="form-control"
                                        placeholder="Enter Phone Number"
                                        value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Gender</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="gender" class="form-control" id="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" @if (old('gender', $customer->gender ?? '') == 'male') selected @endif>Male
                                        </option>
                                        <option value="female" @if (old('gender', $customer->gender ?? '') == 'female') selected @endif>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" class="form-control" name="birth_date"
                                        max="{{ date('Y-m-d') }}"
                                        value="{{ old('birth_date', $customer->birth_date ?? '') }}">
                                    @error('birth_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Pan Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="pan_card_number" class="form-control"
                                        id="pan_card_number" placeholder="Enter Pan Card Number"
                                        value="{{ old('pan_card_number', $customer->pan_card_number ?? '') }}">
                                    @error('pan_card_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="aadhaar_number" class="form-control" id="aadhaar_number"
                                        placeholder="Enter Aadhar Card Number"
                                        value="{{ old('aadhaar_number', $customer->aadhaar_number ?? '') }}">
                                    @error('aadhaar_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="service_preference" class="form-control"
                                        id="service_preference" placeholder="Enter Service Preference"
                                        value="{{ old('service_preference', $customer->service_preference ?? '') }}">
                                    @error('service_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Reference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="reference" class="form-control" id="reference"
                                        placeholder="Enter Reference"
                                        value="{{ old('reference', $customer->reference ?? '') }}">
                                    @error('reference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-6">
                            <h4>Address Detail</h4>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address', $customer->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Country</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="country" class="form-control" id="country" onchange="getState()">
                                        <option value="">Select Country</option>
                                        @foreach ($countryList as $country)
                                            <option value="{{ $country->iso2 }}">{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">State</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="state" class="form-control" id="state" onchange="getCity()">
                                        <option value="">Select State</option>
                                    </select>
                                    @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">City</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="city" class="form-control" id="city">
                                        <option value="">Select City</option>
                                    </select>
                                    @error('city')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Pin Code</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="pin_code" class="form-control" id="pin_code"
                                        placeholder="Enter Pin Code"
                                        value="{{ old('pin_code', $customer->pin_code ?? '') }}">
                                    @error('pin_code')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submitBtn"
                            onclick="submitForm()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $('#assigned_to').select2()
            $("#country").select2({
                dropdownParent: $("#depositLiquidityModal")
            });
        })
        $('.openAddCustomerDataForm').on('click', function(e) {
            $('#depositLiquidityModal').modal('show')
        })

        function getState() {
            var country = $('#country').val();
            $.ajax({
                method: 'get',
                url: "{{ route('state-by-country') }}",
                data: {
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=''>Select State</option>";
                    $.each(res.data, function(i, v) {
                        html += "<option value='" + v.id + "'>" + v.name + "</option>"
                    })
                    $('#state').html("")
                    $('#state').html(html)
                    $("#state").select2({
                        dropdownParent: $("#depositLiquidityModal")
                    });
                }
            });
        }

        function getCity() {
            var state = $('#state').val();
            var country = $('#country').val();
            $.ajax({
                method: 'get',
                url: "{{ route('city-by-state') }}",
                data: {
                    state: state,
                    country_code: country,
                },
                success: function(res) {
                    var html = "<option value=''>Select City</option>";
                    $.each(res.data, function(i, v) {
                        html += "<option value='" + v.id + "'>" + v.name + "</option>"
                    })
                    $('#city').html("")
                    $('#city').html(html)
                    $("#city").select2({
                        dropdownParent: $("#depositLiquidityModal")
                    });
                }
            });
        }
        $('#invest_type').on('change', function() {
            var type = $(this).val();
            if (type == 'investments') {
                $('#type_of_investments').show();
                $('#investmentRadio').show();
                $('#general_insurance_div').hide();
                $('#travel_div').hide();
                $('#generalRadio').hide();
                $('.insurance_label').show();
                document.getElementById('pms_type').checked = true;
            } else if (type == 'general insurance') {
                $('#type_of_investments').hide();
                $('#general_insurance_div').show();
                $('#travel_div').hide();
                $('#investmentRadio').hide();
                $('#generalRadio').show();
                $('#healthDiv').show();
                $('.insurance_label').show();
                document.getElementById('health_insurance').checked = true;
                0.
            } else {
                $('#travel_div').show();
                $('#general_insurance_div').hide();
                $('#type_of_investments').hide();
                $('#investmentRadio').hide();
                $('#generalRadio').hide();
                $('.insurance_label').hide();
            }
        });

        $('#pms_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').hide();
        });

        $('#mf_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').show();
            $('#mf_sip').show();
            $('#mf_installment').show();
            $('#fd_interest').hide();
        });

        $('#fd_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
        });

        $('#bond_type').on('click', function() {
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
        });

        // General Insurance
        $('#health_insurance').on('click', function() {
            $('#healthDiv').show();
            $('#motorDiv').hide();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#motor_insurance').on('click', function() {
            $('#healthDiv').hide();
            $('#motorDiv').show();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#sme_insurance').on('click', function() {
            $('#healthDiv').hide();
            $('#motorDiv').hide();
            $('#smeDiv').show();
            $('#select_assignee').hide();
        });

        function submitForm() {
            $.ajax({
                url: "{{ route('customer.add') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    console.log(data);
                    var html = '<option value="' + data.data.id + '">"' + data.data.name + '"</option>';
                    $('#customer_id').append(html);
                    $('#depositLiquidityModal').modal('hide')
                    $('#addForm').reset()
                    toastr.success(data.message);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        }
    </script>
@endsection
