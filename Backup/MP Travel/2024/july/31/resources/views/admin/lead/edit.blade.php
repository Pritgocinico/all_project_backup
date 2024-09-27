@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Edit Lead Management</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    @php
                        $route = route('leads.update', $lead->id);
                        $method = 'PUT';
                    @endphp
                    <form action="{{$route}}" enctype="multipart/form-data" method="POST">
                        @method($method)
                        @csrf
                        <input type="hidden" name="id" value="{{$lead->id}}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2">
                                <label class="form-label mb-0">Insurance</label>
                            </div>
                            <div class="col-md-4 col-xl-4">
                                <select name="invest_type" class="form-control" id="invest_type">
                                    <option value="">Select Insurance</option>
                                    <option value="investments" @if (old('invest_type', $lead->invest_type ?? '') == 'investments') selected @endif>Investments</option>
                                    <option value="general insurance" @if (old('invest_type', $lead->invest_type ?? '') == 'general insurance') selected @endif>General Insurance</option>
                                    <option value="travel" @if (old('invest_type', $lead->invest_type ?? '') == 'travel') selected @endif>Travel</option>
                                </select>
                                @error('invest_type')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2 insurance_label" style="display: {{ in_array(old('invest_type', $lead->invest_type ?? ''), ['investments', 'general insurance']) ? '' : 'none' }};">
                                <label class="form-label mb-0">Insurance Type</label>
                            </div>
                            <div class="col-md-4 col-xl-4" id="investmentRadio" style="display: {{ old('invest_type', $lead->invest_type ?? '') == 'investments' ? '' : 'none' }};">
                                <input class="form-check-input" type="radio" name="insurance_type" id="pms_type" value="pms" {{ old('insurance_type', $lead->insurance_type ?? '') == 'pms' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pms_type">PMS</label>
                                <input class="form-check-input" type="radio" name="insurance_type" id="mf_type" value="mf" {{ old('insurance_type', $lead->insurance_type ?? '') == 'mf' ? 'checked' : '' }}>
                                <label class="form-check-label" for="mf_type">MF</label>
                                <input class="form-check-input" type="radio" name="insurance_type" id="fd_type" value="fd" {{ old('insurance_type', $lead->insurance_type ?? '') == 'fd' ? 'checked' : '' }}>
                                <label class="form-check-label" for="fd_type">FD</label>
                                <input class="form-check-input" type="radio" name="insurance_type" id="bond_type" value="bond" {{ old('insurance_type', $lead->insurance_type ?? '') == 'bond' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bond_type">Bond</label>
                            </div>
                            <div class="col-md-4 col-xl-4" id="generalRadio" style="display: {{ old('invest_type', $lead->invest_type ?? '') == 'general insurance' ? '' : 'none' }};">
                                <input class="form-check-input" type="radio" name="insurance_type" id="health_insurance" value="health" {{ old('insurance_type', $lead->insurance_type ?? '') == 'health' ? 'checked' : '' }}>
                                <label class="form-check-label" for="health_insurance">Health</label>
                                <input class="form-check-input" type="radio" name="insurance_type" id="motor_insurance" value="motor" {{ old('insurance_type', $lead->insurance_type ?? '') == 'motor' ? 'checked' : '' }}>
                                <label class="form-check-label" for="motor_insurance">Motor</label>
                                <input class="form-check-input" type="radio" name="insurance_type" id="sme_insurance" value="sme" {{ old('insurance_type', $lead->insurance_type ?? '') == 'sme' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sme_insurance">SME</label>
                            </div>
                        </div>
                        
                        
                        <div id="type_of_investments" style="display: none;">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ old('product_name', $lead->product_name ?? '') }}">
                                    @error('product_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="amount_of_investment" id="amount_of_investment" class="form-control" placeholder="Amount Of Investment"
                                    value="{{ old('amount_of_investment', $lead->amount_of_investment ?? '') }}">
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
                                    <input type="text" name="sip" id="sip" class="form-control" 
                                    value="{{ old('sip', $lead->sip ?? '') }}">
                                    @error('sip')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
    
                                <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="lumsum_amount" id="lumsum_amount" class="form-control"
                                    value="{{ old('lumsum_amount', $lead->lumsum_amount ?? '') }}">
                                    @error('lumsum_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6" id="mf_sip" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="sip_amount" id="sip_amount" class="form-control" value="{{ old('sip_amount', $lead->sip_amount ?? '') }}">
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
                                    <input type="number" name="installment_no" id="installment_no" class="form-control" 
                                    value="{{ old('installment_no', $lead->installment_no ?? '') }}">
                                    @error('installment_no')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            <div class="row align-items-center g-3 mt-6" id="fd_interest" style="display: none;">
                                <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="number" name="interest_rate" id="interest_rate" class="form-control" 
                                    value="{{ old('interest_rate', $lead->interest_rate ?? '') }}">
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
                                    <input type="text" name="managed_by" class="form-control" 
                                    value="{{ old('managed_by', $lead->managed_by ?? '') }}">
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
                                        <input type="text" name="insurer" class="form-control" 
                                        value="{{ old('insurer', $lead->insurer ?? '') }}">
                                        @error('insurer')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">Insured</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="insured" class="form-control"
                                        value="{{ old('insured', $lead->insured ?? '') }}">
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
                                        <input type="number" name="sum_insurance" class="form-control"
                                        value="{{ old('sum_insurance', $lead->sum_insurance ?? '') }}">
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
                                            <option value="Two Wheeler" @if (old('vehicle', $customer->vehicle ?? '') == 'Two Wheeler') selected @endif>Two Wheeler</option>
                                            <option value="Four Wheeler" @if (old('vehicle', $customer->vehicle ?? '') == 'Four Wheeler') selected @endif>Four Wheeler</option>
                                            <option value="Commercial Vehicle" @if (old('vehicle', $customer->vehicle ?? '') == 'Commercial Vehicle') selected @endif>Commercial Vehicle</option>
                                            <option value="TP Policy Only" @if (old('vehicle', $customer->vehicle ?? '') == 'TP Policy Only') selected @endif>TP Policy Only</option>
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
                                        <input type="text" name="vehicle_make" class="form-control"
                                        value="{{ old('vehicle_make', $lead->vehicle_make ?? '') }}">
                                        @error('vehicle_make')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Model</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="vehicle_model" class="form-control"
                                        value="{{ old('vehicle_model', $lead->vehicle_model ?? '') }}">
                                        @error('vehicle_model')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">RC Copy</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="file" name="rc_copy" class="form-control" placeholder="Upload RC Copy">
                                        @if (isset($lead->rc_copy))
                                            <a href="{{ Storage::url($lead->rc_copy) }}" target="_blank">View current file</a>
                                        @endif
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
                                        <input type="text" name="fire_burglary" class="form-control" 
                                        value="{{ old('fire_burglary', $lead->fire_burglary ?? '') }}">
                                        @error('fire_burglary')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">Marine</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="marine" class="form-control"
                                        value="{{ old('marine', $lead->marine ?? '') }}">
                                        @error('marine')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">WC</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="wc" class="form-control"
                                        value="{{ old('wc', $lead->wc ?? '') }}">
                                        @error('wc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">GMC</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="gmc" class="form-control"
                                        value="{{ old('gmc', $lead->gmc ?? '') }}">
                                        @error('gmc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">GPA</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="gpa" class="form-control"
                                        value="{{ old('gpa', $lead->gpa ?? '') }}">
                                        @error('wc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
        
                                    <div class="col-md-2"><label class="form-label mb-0">Professional Indemnity</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="gmc" class="form-control"
                                        value="{{ old('gmc', $lead->gmc ?? '') }}">
                                        @error('gmc')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Other Insurance</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="other_insurance" class="form-control"
                                        value="{{ old('other_insurance', $lead->other_insurance ?? '') }}">
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
                                    <input type="text" name="client_name" class="form-control" 
                                    value="{{ old('client_name', $lead->client_name ?? '') }}">
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
                                    <input type="number" name="number_of_days" class="form-control"
                                    value="{{ old('number_of_days', $lead->number_of_days ?? '') }}">
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
                                    <input type="text" name="flight_preference" class="form-control"
                                    value="{{ old('flight_preference', $lead->flight_preference ?? '') }}">
                                    @error('flight_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="hotel_preference" class="form-control"
                                    value="{{ old('hotel_preference', $lead->hotel_preference ?? '') }}">
                                    @error('hotel_preference')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <select name="other_services" class="form-control">
                                        <option value="">Select Services</option>
                                        <option value="domestic_air_ticket" @if (old('other_services', $lead->other_services ?? '') == 'domestic_air_ticket') selected @endif>Domestic Air Ticket</option>
                                        <option value="visa" @if (old('other_services', $lead->other_services ?? '') == 'visa') selected @endif>Visa</option>
                                        <option value="railway_ticket" @if (old('other_services', $lead->other_services ?? '') == 'railway_ticket') selected @endif>Railway Ticket</option>
                                        <option value="hotel" @if (old('other_services', $lead->other_services ?? '') == 'hotel') selected @endif>Hotel</option>
                                        <option value="passport" @if (old('other_services', $lead->other_services ?? '') == 'passport') selected @endif>Passport</option>
                                        <option value="rent_cab" @if (old('other_services', $lead->other_services ?? '') == 'rent_cab') selected @endif>Rent a Cab</option>
                                        <option value="other" @if (old('other_services', $lead->other_services ?? '') == 'other') selected @endif>Other</option>
                                    </select>
                                    @error('other_services')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Itinerary Flow</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <textarea name="itinerary_flow" class="form-control" id="description" placeholder="Enter Flow">{{$lead->itinerary_flow ?? ''}}</textarea>
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
        // $('#invest_type').on('change', function(){
        //     var type = $(this).val();
        //     if(type == 'investments') {
        //         $('#type_of_investments').show();
        //         $('#investmentRadio').show();
        //         $('#general_insurance_div').hide();
        //         $('#travel_div').hide();
        //         $('#generalRadio').hide();
        //         $('.insurance_label').show();
        //     }else if (type == 'general insurance') {
        //         $('#type_of_investments').hide();
        //         $('#general_insurance_div').show();
        //         $('#travel_div').hide();
        //         $('#investmentRadio').hide();
        //         $('#generalRadio').show();
        //         $('#healthDiv').show();
        //         $('.insurance_label').show();
        //     }else{
        //         $('#travel_div').show();
        //         $('#general_insurance_div').hide();
        //         $('#type_of_investments').hide();
        //         $('#investmentRadio').hide();
        //         $('#generalRadio').hide();
        //         $('.insurance_label').hide();
        //     }
        // });

        // $('#pms_type').on('click', function(){
        //     $('#type_of_investments').show();
        //     $('#mf_lumsum').hide();
        //     $('#mf_sip').hide();
        //     $('#mf_installment').hide();
        //     $('#fd_interest').hide();
        // });

        // $('#mf_type').on('click', function(){
        //     $('#type_of_investments').show();
        //     $('#mf_lumsum').show();
        //     $('#mf_sip').show();
        //     $('#mf_installment').show();
        //     $('#fd_interest').hide();
        // });

        // $('#fd_type').on('click', function(){
        //     $('#type_of_investments').show();
        //     $('#mf_lumsum').hide();
        //     $('#mf_sip').hide();
        //     $('#mf_installment').hide();
        //     $('#fd_interest').show();
        // });

        // $('#bond_type').on('click', function(){
        //     $('#type_of_investments').show();
        //     $('#mf_lumsum').hide();
        //     $('#mf_sip').hide();
        //     $('#mf_installment').hide();
        //     $('#fd_interest').show();
        // });

        // // General Insurance
        // $('#health_insurance').on('click', function(){
        //     $('#healthDiv').show();
        //     $('#motorDiv').hide();
        //     $('#smeDiv').hide();
        //     $('#select_assignee').show();
        // });

        // $('#motor_insurance').on('click', function(){
        //     $('#healthDiv').hide();
        //     $('#motorDiv').show();
        //     $('#smeDiv').hide();
        //     $('#select_assignee').show();
        // });

        // $('#sme_insurance').on('click', function(){
        //     $('#healthDiv').hide();
        //     $('#motorDiv').hide();
        //     $('#smeDiv').show();
        //     $('#select_assignee').hide();
        // });
        $(document).ready(function(){
            $('#invest_type').on('change', function(){
                var type = $(this).val();
                if(type == 'investments') {
                    $('#type_of_investments').show();
                    $('#investmentRadio').show();
                    $('#general_insurance_div').hide();
                    $('#travel_div').hide();
                    $('#generalRadio').hide();
                    $('.insurance_label').show();
                } else if (type == 'general insurance') {
                    $('#type_of_investments').hide();
                    $('#general_insurance_div').show();
                    $('#travel_div').hide();
                    $('#investmentRadio').hide();
                    $('#generalRadio').show();
                    $('#healthDiv').show();
                    $('.insurance_label').show();
                } else {
                    $('#travel_div').show();
                    $('#general_insurance_div').hide();
                    $('#type_of_investments').hide();
                    $('#investmentRadio').hide();
                    $('#generalRadio').hide();
                    $('.insurance_label').hide();
                }
            }).trigger('change'); // Trigger change event on page load to set initial state

            $('input[name="insurance_type"]').on('click', function(){
                var selectedRadio = $(this).attr('id');
                if(selectedRadio == 'pms_type') {
                    $('#type_of_investments').show();
                    $('#mf_lumsum').hide();
                    $('#mf_sip').hide();
                    $('#mf_installment').hide();
                    $('#fd_interest').hide();
                } else if(selectedRadio == 'mf_type') {
                    $('#type_of_investments').show();
                    $('#mf_lumsum').show();
                    $('#mf_sip').show();
                    $('#mf_installment').show();
                    $('#fd_interest').hide();
                } else if(selectedRadio == 'fd_type') {
                    $('#type_of_investments').show();
                    $('#mf_lumsum').hide();
                    $('#mf_sip').hide();
                    $('#mf_installment').hide();
                    $('#fd_interest').show();
                } else if(selectedRadio == 'bond_type') {
                    $('#type_of_investments').show();
                    $('#mf_lumsum').hide();
                    $('#mf_sip').hide();
                    $('#mf_installment').hide();
                    $('#fd_interest').show();
                } else if(selectedRadio == 'health_insurance') {
                    $('#healthDiv').show();
                    $('#motorDiv').hide();
                    $('#smeDiv').hide();
                    $('#select_assignee').show();
                } else if(selectedRadio == 'motor_insurance') {
                    $('#healthDiv').hide();
                    $('#motorDiv').show();
                    $('#smeDiv').hide();
                    $('#select_assignee').show();
                } else if(selectedRadio == 'sme_insurance') {
                    $('#healthDiv').hide();
                    $('#motorDiv').hide();
                    $('#smeDiv').show();
                    $('#select_assignee').hide();
                }
            });

            // Trigger click event for checked radio buttons on page load
            $('input[name="insurance_type"]:checked').trigger('click');
        });

        
    </script>
@endsection
