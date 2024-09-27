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
                    @php
                        $route = route('leads.store');
                    @endphp
                    <form action="{{$route}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="invest_type" class="form-control" id="invest_type">
                                    <option value="">Select Insurance</option>
                                    <option value="investments">Investments
                                    </option>
                                    <option value="general insurance">General Insurance
                                    </option>
                                    <option value="travel">Travel
                                    </option>
                                </select>
                                @error('invest_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 insurance_label" style="display: none;"><label class="form-label mb-0">Insurance Type</label></div>
                            <div class="col-md-4 col-xl-4" id="investmentRadio" style="display: none;">
                                <input class="form-check-input" type="radio" name="insurance_type" id="pms_type" value="pms">
                                    <label class="form-check-label" for="disabled">
                                        PMS
                                    </label>
                                    
                                    <input class="form-check-input" type="radio" name="insurance_type" id="mf_type" value="mf">
                                    <label class="form-check-label" for="view">
                                        MF
                                    </label>
                                    
                                    <input class="form-check-input" type="radio" name="insurance_type" id="fd_type" value="fd">
                                    <label class="form-check-label" for="corporate">
                                        FD
                                    </label>

                                    <input class="form-check-input" type="radio" name="insurance_type" id="bond_type" value="bond">
                                    <label class="form-check-label" for="corporate">
                                        Bond
                                    </label>
                            </div>
                            <div class="col-md-4 col-xl-4" id="generalRadio" style="display: none;">
                                <input class="form-check-input" type="radio" name="insurance_type" id="health_insurance" value="health">
                                <label class="form-check-label" for="disabled">
                                    Health
                                </label>
                                
                                <input class="form-check-input" type="radio" name="insurance_type" id="motor_insurance" value="motor">
                                <label class="form-check-label" for="view">
                                    Motor
                                </label>
                                
                                <input class="form-check-input" type="radio" name="insurance_type" id="sme_insurance" value="sme">
                                <label class="form-check-label" for="corporate">
                                    SME
                                </label>
                            </div>
                        </div>
                        
                        <div id="type_of_investments" style="display: none;">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name"
                                        value="">
                                    @error('product_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="amount_of_investment" id="amount_of_investment" class="form-control" placeholder="Amount Of Investment">
                                    @error('amount_of_investment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="investment_date" id="investment_date" value="{{ old('investment_date', date('Y-m-d')) }}" class="form-control">
                                    @error('investment_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6" id="mf_lumsum" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">SIP</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="sip" id="sip" class="form-control" value="">
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
                                    <input type="number" name="sip_amount" id="sip_amount" class="form-control" value="">
                                    @error('sip_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="sip_date" id="sip_date" value="{{ old('sip_date', date('Y-m-d')) }}" class="form-control">
                                    @error('sip_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6" id="mf_installment" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">No. of Installment</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="installment_no" id="installment_no" class="form-control" value="">
                                    @error('installment_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="interest_rate" id="interest_rate" class="form-control" value="">
                                    @error('interest_rate')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="maturity_date" id="maturity_date" value="{{ old('maturity_date', date('Y-m-d')) }}" class="form-control">
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
                                    <input type="date" name="lead_date" value="{{ old('lead_date', date('Y-m-d')) }}" class="form-control">
                                    @error('lead_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="general_insurance_div" style="display: none;">
                            <hr class="my-6">
                            <h4>General Insurance</h4>
                            <div id="healthDiv" style="display: none;">
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
                                        <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" class="form-control">
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
                                        <input type="date" name="insurer_dob" value="{{ old('insurer_dob', date('Y-m-d')) }}" class="form-control">
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
                                        <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" class="form-control">
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
                                        <input type="file" name="rc_copy" class="form-control" placeholder="Upload RC Copy">
                                        @error('rc_copy')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6" id="select_assignee" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="assignee" class="form-control">
                                        <option value="">Select Assignee</option>
                                    </select>
                                    @error('assignee')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
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
        
                                    <div class="col-md-2"><label class="form-label mb-0">Professional Indemnity</label></div>
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

                        <div id="travel_div" style="display: none;">
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
                                    <input type="date" name="travel_from_date" value="{{ old('travel_from_date', date('Y-m-d')) }}" class="form-control">
                                    @error('travel_start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="travel_to_date" value="{{ old('travel_to_date', date('Y-m-d')) }}" class="form-control" value="">
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
                                    <input class="form-check-input" type="radio" name="travel_destination" id="health_insurance" value="0" required checked>
                                        <label class="form-check-label" for="disabled">
                                            Domestic
                                        </label>
                                        
                                        <input class="form-check-input" type="radio" name="travel_destination" id="motor_insurance" value="1">
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

                                <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="assigned_to" class="form-control">
                                        <option value="">Select Assignee</option>
                                    </select>
                                    @error('assigned_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">
                        <div class="d-flexjustify-content-end gap-2">
                            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('#invest_type').on('change', function(){
            var type = $(this).val();
            if(type == 'investments') {
                $('#type_of_investments').show();
                $('#investmentRadio').show();
                $('#general_insurance_div').hide();
                $('#travel_div').hide();
                $('#generalRadio').hide();
                $('.insurance_label').show();
                document.getElementById('pms_type').checked = true;
            }else if (type == 'general insurance') {
                $('#type_of_investments').hide();
                $('#general_insurance_div').show();
                $('#travel_div').hide();
                $('#investmentRadio').hide();
                $('#generalRadio').show();
                $('#healthDiv').show();
                $('.insurance_label').show();
                document.getElementById('health_insurance').checked = true;0.
            }else{
                $('#travel_div').show();
                $('#general_insurance_div').hide();
                $('#type_of_investments').hide();
                $('#investmentRadio').hide();
                $('#generalRadio').hide();
                $('.insurance_label').hide();
            }
        });

        $('#pms_type').on('click', function(){
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').hide();
        });

        $('#mf_type').on('click', function(){
            $('#type_of_investments').show();
            $('#mf_lumsum').show();
            $('#mf_sip').show();
            $('#mf_installment').show();
            $('#fd_interest').hide();
        });

        $('#fd_type').on('click', function(){
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
        });

        $('#bond_type').on('click', function(){
            $('#type_of_investments').show();
            $('#mf_lumsum').hide();
            $('#mf_sip').hide();
            $('#mf_installment').hide();
            $('#fd_interest').show();
        });

        // General Insurance
        $('#health_insurance').on('click', function(){
            $('#healthDiv').show();
            $('#motorDiv').hide();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#motor_insurance').on('click', function(){
            $('#healthDiv').hide();
            $('#motorDiv').show();
            $('#smeDiv').hide();
            $('#select_assignee').show();
        });

        $('#sme_insurance').on('click', function(){
            $('#healthDiv').hide();
            $('#motorDiv').hide();
            $('#smeDiv').show();
            $('#select_assignee').hide();
        });
        
    </script>
@endsection
