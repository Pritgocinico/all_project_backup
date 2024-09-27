@extends('admin.partials.header', ['active' => 'lead'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ $lead->lead_id }}</h1>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('follow-up.index') }}" class="btn btn-dark btn-sm">Add Follow Up</a>
                        @if ($lead->lead_status == 1)
                            <button class="btn btn-warning text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Pending Lead
                            </button>
                        @elseif($lead->lead_status == 2)
                            <button class="btn btn-success text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                In Process Lead
                            </button>
                        @elseif($lead->lead_status == 3)
                            <button class="btn btn-info text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Complete Lead
                            </button>
                        @elseif($lead->lead_status == 4)
                            <button class="btn btn-danger text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Cancel Lead
                            </button>
                        @endif
                        <ul class="dropdown-menu task-status">
                            <li><a class="dropdown-item status @if ($lead->lead_status == 1) hide_detail @endif"
                                    href="#" data-status="1" data-task="{{ $lead->id }}">Pending
                                    Lead</a></li>
                            <li><a class="dropdown-item status @if ($lead->lead_status == 2) hide_detail @endif"
                                    href="#" data-status="2" data-task="{{ $lead->id }}">In Process Lead</a>
                            </li>
                            <li><a class="dropdown-item status @if ($lead->lead_status == 3) hide_detail @endif"
                                    href="#" data-status="3" data-task="{{ $lead->id }}">Complete Lead</a></li>
                            <li><a class="dropdown-item status @if ($lead->lead_status == 4) hide_detail @endif"
                                    href="#" data-status="4" data-task="{{ $lead->id }}">Cancel
                                    Lead</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($lead->invest_type == 'investments')
                <hr class="my-6">
                <h4>{{ ucfirst($lead->invest_type) }}</h4>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ ucfirst($lead->invest_type) }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>
                    <div class="col-md-4 col-xl-4 text-uppercase">
                        {{ $lead->insurance_type }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->product_name }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->amount_of_investment }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->investment_date }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Investment Field</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ ucfirst($lead->investment_field) }}
                    </div>
                </div>
                @if ($lead->investment_field == 'existing')
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Investment Detail</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->investment_code }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Investment Remark</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->investment_remark }}
                        </div>
                    </div>
                @endif
                @if ($lead->insurance_type == 'mf')
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">SIP</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->sip }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->lumsum_amount }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">SIP Amount</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->sip_amount }}
                        </div>
                    </div>

                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">SIP Start Date</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->sip_date }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">No. of Installment</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->installment_no }}
                        </div>
                    </div>
                @endif
                @if ($lead->insurance_type == 'mf' || $lead->insurance_type == 'mf')
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->interest_rate }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->maturity_date }}
                        </div>

                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Maturity amount</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $lead->maturity_amount }}
                        </div>
                    </div>
                @endif
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Managed By</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->managed_by }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->lead_date }}
                    </div>
                </div>
            @elseif ($lead->invest_type == 'general insurance')
                <hr class="my-6">
                <h4>General Insurance</h4>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->invest_type }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->insurance_type }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Insurer</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->insurer }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Insured</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->insured }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->product }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Sub Product</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->sub_product }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->received_date }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->sum_insurance }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Insurer DOB</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->insurer_dob }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Vehicle</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->vehicle }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Client</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->client }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->received_date }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Make</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->vehicle_make }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Model</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->vehicle_model }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">RC Copy</label></div>
                    <div class="col-md-4 col-xl-4">
                        <a href="{{ Storage::url($lead->rc_copy) }}" target="_blank">View RC Copy</a>
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->assignee }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Fire & Burglary</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->fire_burglary }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Marine</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->marine }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">WC</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->wc }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">GMC</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->gmc }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">GPA</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->gpa }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Professional Indemnity</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->professional_indemnity }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Other Insurance</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->other_insurance }}
                    </div>
                </div>
            @else
                <hr class="my-6">
                <h4>Travel</h4>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Insurance</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->invest_type }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->insurance_type }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Client Name</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->client_name }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Travel From Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->travel_from_date }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Travel To Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->travel_to_date }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Number Of Days</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->number_of_days }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->travel_destination }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Flight Preference</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->flight_preference }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Hotel Preference</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->hotel_preference }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->other_services }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Itinerary Flow</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->itinerary_flow }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Assigned To</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->assigned_to }}
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection
