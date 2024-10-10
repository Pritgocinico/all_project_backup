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

                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                    <div class="col text-end">

                        <a href="javascript:void(0)" onclick="openAddFollowUpModal()" class="btn btn-dark btn-sm">Add Follow
                            Up</a>

                        @if ($lead->lead_status == 1)
                            <button class="btn btn-warning text-white dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" id="lead_view_pending">

                                Pending Lead

                            </button>
                        @elseif($lead->lead_status == 2)
                            <button class="btn btn-info text-white dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false" id="lead_view_info">

                                In Process Lead

                            </button>
                        @elseif($lead->lead_status == 4)
                            <button class="btn btn-success text-white dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" id="lead_view_success">

                                Complete Lead

                            </button>
                        @elseif($lead->lead_status == 3)
                            <button class="btn btn-danger text-white dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false" id="lead_view_danger">

                                On Hold Lead

                            </button>
                        @endif

                        <ul class="dropdown-menu task-status">

                            <li><a class="dropdown-item status @if ($lead->lead_status == 1) d-none @endif lead_status_dropdown"
                                    href="javascript:void(0)" data-status="1">Pending

                                    Lead</a></li>

                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 2) d-none @endif"
                                    href="javascript:void(0)" data-status="2">In Process Lead</a>

                            </li>

                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 4) d-none @endif"
                                    href="javascript:void(0)" data-status="4">Complete Lead</a></li>

                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 3) d-none @endif"
                                    href="javascript:void(0)" data-status="3">On Hold

                                    Lead</a></li>

                        </ul>

                    </div>

                </div>

            </div>


            <div class="row g-3 mt-6">
                <div class="col-md-8">

                    @if (isset($lead->customerDetail))
                        @php $customer = $lead->customerDetail; @endphp

                        <hr class="my-6">

                        <h4>Customer Detail</h4>

                        <div id="customer_detail_div">

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->name_title }} {{ $customer->name }}</div>

                                <div class="col-md-2"><label class="form-label mb-0">Customer Id</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->customer_id }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->birth_date }}



                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Email</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->email }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->mobile_number }}

                                </div>



                            </div>



                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->aadhar_number }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->pan_card_number }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Address</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ $customer->address ? $customer->address . ',' : '' }}

                                    {{ isset($customer->cityDetail) ? $customer->cityDetail->name . ',' : '' }}

                                    {{ isset($customer->stateDetail) ? $customer->stateDetail->name . ',' : '' }}<br />

                                    {{ isset($customer->countryDetail) ? $customer->countryDetail->name . '-' : '' }}

                                    {{ $customer->pin_code }}



                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Status</label></div>

                                <div class="col-md-4 col-xl-4 lead_show_status">

                                    @php

                                        $class = 'danger';

                                        $text = 'Inactive';

                                        if ($customer->status == 1) {
                                            $class = 'success';

                                            $text = 'Active';
                                        }

                                    @endphp

                                    <span class="badge bg-{{ $class }}">{{ $text }}</span>

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>

                                <div class="col-md-4 col-xl-4">{{ $customer->reference }}</div>

                            </div>
                            @if (isset($lead->customerDetail) && isset($lead->customerDetail->servicePreferenceTagDetail))
                                    @foreach ($lead->customerDetail->servicePreferenceTagDetail as $tag)
                                        <div class="row align-items-center g-3 mt-6">
                                            <div class="col-md-4">
                                                Tag Name:- {{ $tag->servicePreferenceDetail->name }}
                                            </div>
                                            <div class="col-md-4">
                                                Tag Created By:- {{ $tag->userDetail->name }}
                                            </div>
                                            <div class="col-md-4">
                                                Tag Created At:- {{ $tag->created_at }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                        </div>
                    @endif

                    @if ($lead->invest_type == 'investments')

                        <hr class="my-6">

                        <h4>{{ ucfirst($lead->invest_type) }}</h4>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Investment Field</label></div>

                            <div class="col-md-4 col-xl-4">

                                {{ isset($lead->investmentLeadData) ? ucfirst($lead->investmentLeadData->investment_type) : '-' }}

                            </div>
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->product_name != '')
                                <div class="col-md-2"><label class="form-label mb-0">Product Name</label></div>

                                <div class="col-md-4 col-xl-4 text-uppercase">
                                    {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->product_name : '-' }}

                                </div>
                            @endif

                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->kyc_status != '')
                                <div class="col-md-2"><label class="form-label mb-0">KYC Status</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->investmentLeadData) ? ucfirst($lead->investmentLeadData->kyc_status) : '-' }}

                                </div>
                            @endif
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->invest_amount != '')
                                <div class="col-md-2"><label class="form-label mb-0">Amount Of Investment</label></div>

                                <div class="col-md-4 col-xl-4">

                                    &#x20B9;
                                    {{ isset($lead->investmentLeadData) ? number_format($lead->investmentLeadData->invest_amount ?? 0, 2) : '-' }}

                                </div>
                            @endif

                        </div>



                        <div class="row align-items-center g-3 mt-6">
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->investment_date != '')
                                <div class="col-md-2"><label class="form-label mb-0">Investment Date</label></div>

                                <div class="col-md-4 col-xl-4">
                                    {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->investment_date : '-' }}

                                </div>
                            @endif
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->cancel_cheque != null)
                                <div class="col-md-2"><label class="form-label mb-0">Cancel Cheque</label></div>

                                <div class="col-md-4 col-xl-4">
                                    <a href="{{ asset('storage/' . $lead->investmentLeadData->cancel_cheque) }}"
                                        class="btn btn-dark" target="_blank">View</a>
                                </div>
                            @endif

                        </div>

                        @if ($lead->investment_field == 'existing')
                            <div class="row align-items-center g-3 mt-6">
                                @if ($lead->investment_code != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Investment Detail</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ ucfirst($lead->investment_code) }}

                                    </div>
                                @endif
                                @if ($lead->investment_code != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Investment Remark</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ ucfirst($lead->investment_remark) }}

                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($lead->insurance_type == 'mf')
                            <div class="row align-items-center g-3 mt-6">
                                @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->sip_amount != '')
                                    <div class="col-md-2"><label class="form-label mb-0">SIP</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->sip_amount : '-' }}

                                    </div>
                                @endif
                                @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->lumsum_amount != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Lumsum Amount</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->lumsum_amount : '0' }}

                                    </div>
                                @endif
                            </div>
                            <div class="row align-item-center  g-3 mt-6">
                                @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->sip_date != '')
                                    <div class="col-md-2"><label class="form-label mb-0">SIP Date</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->sip_date : '-' }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($lead->insurance_type == 'mf' || $lead->insurance_type == 'mf')
                            <div class="row align-items-center g-3 mt-6">
                                @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->rate_of_interest != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Rate of Interest</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->rate_of_interest : '-' }}

                                    </div>
                                @endif
                                @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->maturity_date != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Maturity Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->maturity_date : '-' }}

                                    </div>
                                @endif


                            </div>

                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->maturity_amount != '')
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Maturity amount</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->maturity_amount : '-' }}
                                    </div>

                                </div>
                            @endif
                            @if (isset($lead->investmentLeadData) && $lead->investmentLeadData->investment_payout != '')
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Investment Payout</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->investmentLeadData) ? $lead->investmentLeadData->investment_payout : '-' }}
                                    </div>

                                </div>
                            @endif
                        @endif

                        <div class="row align-items-center g-3 mt-6">
                            @if ($lead->managed_by != '')
                                <div class="col-md-2"><label class="form-label mb-0">Managed By</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ $lead->managed_by }}

                                </div>
                            @endif
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

                            <div class="col-md-4 col-xl-4 text-capitalize">

                                {{ $lead->invest_type }}

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>

                            <div class="col-md-4 col-xl-4 text-capitalize">

                                {{ str_replace('_', ' ', $lead->insurance_type) }}

                            </div>

                        </div>
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Policy Start Date</label>
                            </div>

                            <div class="col-md-4 col-xl-4">

                                {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_start_date : '-' }}

                            </div>

                            <div class="col-md-2"><label class="form-label mb-0">Policy End Date</label></div>

                            <div class="col-md-4 col-xl-4">

                                {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_start_date : '-' }}

                            </div>

                        </div>

                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'fire_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Policy Type</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_type : '-' }}

                                </div>
                                @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                    <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->previous_policy : '-' }}

                                    </div>
                                @endif
                            </div>
                            @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sum_insurance : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->expiry_date : '-' }}

                                    </div>
                                </div>
                            @endif
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Financier Interest or
                                        Hypothecation</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->financier_interest_hypo : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">BUILDING VALUE (Rs.):</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->building_value : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">PLANT & MACHINARY VALUE (Rs.):
                                    </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->plant_machinery : '-' }}

                                </div>
                                @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->total_stock_in_value != '')
                                    <div class="col-md-2"><label class="form-label mb-0">Total STOCK VALUE IN PROCESS /
                                            RAW /FINISHED(Rs.)</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_stock_in_value : '-' }}

                                    </div>
                                @endif
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">FFF & OTHER EE VALUE (Rs.):</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->fff_other_ee : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">OTHER CONTENT (Rs.):</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->other_content : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Total Sum Insured</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_sum_insured : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Operational Fire
                                        Hydrant / Sprinkler / water
                                        Spray System / Fire alarm / smoke detectors</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->operational_fire : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Burglary coverage required</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->burglary_coverage : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Burglary Sum Insured (Rs.)</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->burglary_sum_insured : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">First Loss Percentage</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->first_loss_percentage : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Theft Extension</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->theft_extension : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Well Maintained Electrical Standard
                                        equipments Installations</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->maintain_electric : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Provision of Storm water drainage
                                        system
                                        and building with plinth Level at least 1.5 ft above ground</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->maintain_electric : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">24x7 Security and CCTV </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->security_cctv : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">PAST 3 YEAR CLAIM history</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->three_year_claim_history : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">BASEMENT</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->basement : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Basement in the building used for
                                        operations / Storage / Plant and Machinery installed there in</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->use_basement : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Insured premises located within 1 KM
                                        distance
                                        of water body</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->insured_premises : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Risk is located in a thickly
                                        populated area
                                        with No access to fire brigade vehicle</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->insured_premises : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Age of building </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->age_of_building : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label>
                                </div>

                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->existingCopyFiles))
                                        @foreach ($lead->existingCopyFiles as $exist)
                                            <a href="{{ asset('storage/' . $exist->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Photographs</label></div>

                                <div class="col-md-4 col-xl-4">

                                    @if (isset($lead->photographData))
                                        @foreach ($lead->photographData as $photo)
                                            <a href="{{ asset('storage/' . $photo->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Investigation Report</label></div>

                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->InvestigationReportData))
                                        @foreach ($lead->InvestigationReportData as $investion)
                                            <a href="{{ asset('storage/' . $investion->file_path) }}"
                                                class="btn btn-dark" target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'wc_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Policy Type</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_type : '-' }}

                                </div>
                                @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                    <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->previous_policy : '-' }}

                                    </div>
                                @endif
                            </div>
                            @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sum_insurance : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->expiry_date : '-' }}

                                    </div>
                                </div>
                            @endif
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">NATURE OF BUSINESS</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->nature_business : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Risk Occupancy/Scope of Work</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->risk_occupancy : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Policy Period</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_period : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Sub-Contractor Employee Coverage
                                        Required</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sub_contractor : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Occupational Diseases Coverage
                                        Required</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sub_contractor : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Total Number of Employees</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_employees : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Total Wages (Rs.)</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_wages : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Skilled</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_wages : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Unskilled</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->un_skilled : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Commercial Traveller</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->commercial_travel : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Medical Extension Limit per
                                        person</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->medical_extension : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Number of Shifts</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->medical_extension : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Distance from Nearest Hospital in
                                        kms.</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->distance_near_hospital : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">First Aid Kit Available at work
                                        site</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->first_aid_kit : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Fire Extinguishers, Fire hydrant
                                        system</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->first_extinguishers : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">24x7 Security person in Premise
                                    </label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->security_cctv : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">CCTV Camera Installed</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->security_cctv : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label>
                                </div>

                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->existingCopyFiles))
                                        @foreach ($lead->existingCopyFiles as $exist)
                                            <a href="{{ asset('storage/' . $exist->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label></div>

                                <div class="col-md-4 col-xl-4">

                                    @if (isset($lead->employeeDataSheet))
                                        @foreach ($lead->employeeDataSheet as $sheet)
                                            <a href="{{ asset('storage/' . $sheet->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'health_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Policy Type</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_type : '-' }}

                                </div>
                                @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                    <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->previous_policy : '-' }}

                                    </div>
                                @endif
                            </div>
                            @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sum_insurance : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->expiry_date : '-' }}

                                    </div>
                                </div>
                            @endif
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Type of Case</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->type_case : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Plan Type</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->plan_type : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Sum Insured (Rs.)</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_sum_insured : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Claim History</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->health_claim_history : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Claim History Details</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->health_claim_history : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Alcohol consumer</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->alcohol_consumer : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Tobacco consumer</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->tobacco_consumer : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Smoking</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->smoking : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">If in case of PED Medicines
                                        details</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->ped_medical : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">CIR</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->CIR : '-' }}

                                </div>
                            </div>
                            @if (isset($lead->insuranceFamilyData))
                                <hr class="my-6" />
                                @foreach ($lead->insuranceFamilyData as $family)
                                    <div class="row align-items-center ">
                                        <div class="col-md-2 g-3 mt-6"><label class="form-label mb-0">Name</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->name }}
                                        </div>
                                        <div class="col-md-2 g-3 mt-6"><label class="form-label mb-0">Date Of
                                                Birth</label></div>

                                        <div class="col-md-4 col-xl-4">
                                            {{ $family->dob }}
                                        </div>
                                        <div class="col-md-2 g-3 mt-6"><label class="form-label mb-0">Gender</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->gender }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Relation</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->relation }}
                                        </div>
                                        <div class="col-md-2 g-3 mt-6"><label class="form-label mb-0">Pre Existing</label>
                                        </div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->pre_existing }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Height</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->height }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->weight }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Education</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->education }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Profession</label></div>

                                        <div class="col-md-4 col-xl-4 g-3 mt-6">
                                            {{ $family->profession }}
                                        </div>
                                    </div>
                                @endforeach
                                <hr class="my-6" />
                            @endif
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'pa_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Occupation (Nature of duties)</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->nature_business : '-' }}

                                </div>
                                @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                    <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->previous_policy : '-' }}

                                    </div>
                                @endif
                            </div>
                            @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->policy_type == 'renewal')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sum_insurance : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Expiry Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->expiry_date : '-' }}

                                    </div>
                                </div>
                            @endif
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Gross Monthly Salary Amount</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->monthly_salary : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Plan Type</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->plan_type : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Type of Case</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->type_case : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Plan Type </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->plan_type : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Sum Insured (Rs.)</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->monthly_salary : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Physical disability/defect</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->physical_disable : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Accidental Dealth Coverage</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->accident_coverage : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Permanent Partial
                                        Disability+Permanent Total
                                        Disability</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->permanent_disability : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Loss of Income Benefit/TTD</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->loss_income_benefit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Fracture Care</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->fracture_care : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Hospital Cash Benefit</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->hospital_cash_benefit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Road Ambulance Cover</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->road_ambulance_cover : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Travel Expenses benefit</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->travel_expense_benefit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Accidental Hospitalization
                                        Expenses</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->accidental_hospitalization_expenses : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Adventure Sports Benefit</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->adventure_sports_benefit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Air Ambulance Cover</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->air_ambulance_cover : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Children's Education Benefit</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->child_education_benefit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Coma Due to Accidental Bodily
                                        Injury</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->comma_due_accident : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">EMI Payment Cover</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->emi_payment_cover : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">We want Last 3 years ITR Returns with
                                        COI or
                                        Form No. 16 of last 1 year and Salary slip of last 3 month</label></div>

                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->leadReturnAttachment))
                                        @foreach ($lead->leadReturnAttachment as $return)
                                            <a href="{{ asset('storage/' . $return->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'gpa_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Sum Insured</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_sum_insured : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Type of Business/Occupancy</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->business_type : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Accidental Death Cover</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->accident_coverage : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Permanent Total Disability ( PTD
                                        )</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->permanent_total_disability : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Permanent Partial Disability ( PPD
                                        )</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->permanent_partial_disability : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Loss of Income Benefit/TTD</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->loss_income_benefit : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Accidental Hospitalization
                                        Cover</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->accidental_hospital_cover : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Ambulance Cover</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->road_ambulance_cover : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Cashless Facility during
                                        Hospitalization</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->cashless_facility_hospital : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Burn Expenses</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->burn_expense : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">WBroken Bone Cover</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->broken_bone_cover : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Repatriation of mortal
                                        remains</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->report_mortal_remain : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">24x7 Security and CCTV </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->security_cctv : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Daily cash allowances</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->daily_cash_allowance : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Carriage of dead body</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->carriage_dead_body : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">children education grant</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->child_education_benefit : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->existingCopyFiles))
                                        @foreach ($lead->existingCopyFiles as $exist)
                                            <a href="{{ asset('storage/' . $exist->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->employeeDataSheet))
                                        @foreach ($lead->employeeDataSheet as $dataSheet)
                                            <a href="{{ asset('storage/' . $dataSheet->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Last 3 years claim history</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->claimHistoryData))
                                        @foreach ($lead->claimHistoryData as $claimHistory)
                                            <a href="{{ asset('storage/' . $claimHistory->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Claim Dump with MIS</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->claimDumpAttachment))
                                        @foreach ($lead->claimDumpAttachment as $claimDump)
                                            <a href="{{ asset('storage/' . $claimDump->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'gmc_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Total Lives Insured</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->total_sum_insured : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">COMPANY NAME :</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->pa_company_name : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Pre-Exzisting Diseases</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->exist_diseases : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">9 Month waiting period waiver</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->nine_month_period : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">1st year waiting period</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->one_year_waiting : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Room Rent Capping</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->room_rent_capping : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Maternity Benefits </label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->maternity_benefit : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Pre and Post Hospitalization</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->pre_post_hospital : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Ambulance Charges per
                                        Hospitalization</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->ambulance_charge : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Day Care procedures</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->day_care_procedures : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Terrorism</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->terrorism : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Organ Donor Medical Exp.</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->organ_donor : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Air Ambulance</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->air_ambulance_cover : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Internal/Ezternal Congenital
                                        disease</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->internal_external_disease : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Lucentis</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->lucentis : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Attendant Charges</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->attendant_charge : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Reasonable and Customary Charges
                                    </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->reasonable_charge : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Dental Treatment due to Accident
                                        only</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->dental_treatment_accident : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Automatic Sum Insured
                                        Reinstatement</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->sum_insurance : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">All Modern treatment</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->modern_treatment : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Domiciliary Hospitalization</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->domiciliary_hospital : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">AYUSH Treatment</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->ayush_treatment : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->existingCopyFiles))
                                        @foreach ($lead->existingCopyFiles as $exist)
                                            <a href="{{ asset('storage/' . $exist->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->employeeDataSheet))
                                        @foreach ($lead->employeeDataSheet as $dataSheet)
                                            <a href="{{ asset('storage/' . $dataSheet->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Last 3 years claim history</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->claimHistoryData))
                                        @foreach ($lead->claimHistoryData as $claimHistory)
                                            <a href="{{ asset('storage/' . $claimHistory->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Claim Dump with MIS</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->claimDumpAttachment))
                                        @foreach ($lead->claimDumpAttachment as $claimDump)
                                            <a href="{{ asset('storage/' . $claimDump->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if (isset($lead->insuranceLeadData) && $lead->insuranceLeadData->insurance_type == 'marine_policy')
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Type of Policy </label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->policy_type : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Other Policy</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->other_marine_policy : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Hyothecation</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->hyphenation : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Previous Policy Number</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->previous_policy : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Commodity Description</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->commodity_description : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Mode of Transit</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->transit_mode : '-' }}

                                </div>

                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Voyage Type</label>
                                </div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->voyage_type : '-' }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Voyage Details</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->voyage_detail : '-' }}

                                </div>

                            </div>

                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Packaging</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->packaging : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Per Bottom Limit</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->per_bottom_limit : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Per Location limit</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->per_location_limit : '-' }}

                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Type of Vehicle</label></div>

                                <div class="col-md-4 col-xl-4">

                                    {{ isset($lead->insuranceLeadData) ? $lead->insuranceLeadData->vehicle_type : '-' }}

                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Existing Policy copies if any</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->existingCopyFiles))
                                        @foreach ($lead->existingCopyFiles as $exist)
                                            <a href="{{ asset('storage/' . $exist->file_path) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Employee data sheet</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->employeeDataSheet))
                                        @foreach ($lead->employeeDataSheet as $dataSheet)
                                            <a href="{{ asset('storage/' . $dataSheet->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Invoice copy</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->invoiceCopyAttachment))
                                        @foreach ($lead->invoiceCopyAttachment as $invoiceCopy)
                                            <a href="{{ asset('storage/' . $invoiceCopy->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">ODC Cargo - Survey Report</label>
                                </div>
                                <div class="col-md-4 col-xl-4">
                                    @if (isset($lead->surveyReportAttachment))
                                        @foreach ($lead->surveyReportAttachment as $surveyReport)
                                            <a href="{{ asset('storage/' . $surveyReport->file) }}" class="btn btn-dark"
                                                target="_blank">View</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Other Attachment</label>
                            </div>

                            <div class="col-md-10 col-xl-10">
                                @if (isset($lead->leadAttachment))
                                    @foreach ($lead->leadAttachment as $attach)
                                        <a href="{{ asset('storage/' . $attach->attachments) }}" class="btn btn-dark"
                                            target="_blank">View</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @else
                        <hr class="my-6">

                        <h4>Travel</h4>

                        @if ($lead->travelLeadData)
                            <div class="row align-items-center g-3 mt-6">

                                <div class="col-md-2"><label class="form-label mb-0">Travel Type</label></div>

                                <div class="col-md-4 col-xl-4">
                                    {{ ucfirst($lead->travelLeadData->travel_inquiry_type) }}

                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Inquiry Date</label></div>

                                <div class="col-md-4 col-xl-4">
                                    {{ $lead->travelLeadData->inquiry_date }}
                                </div>

                            </div>
                            @if ($lead->travelLeadData->travel_inquiry_type == 'flight')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Travelling From Date</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->travel_form_date }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Travelling To Date</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->travel_to_date }}

                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Flight Form</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->flight_form }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Flight To</label></div>
                                    <div class="col-md-4">
                                        {{ $lead->travelLeadData->flight_to }}
                                    </div>

                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Number of Passengers</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->no_of_passengers }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Travel Sector</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ ucfirst($lead->travelLeadData->travel_sector) }}
                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Travelling Mode</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ str_replace('_', ' ', ucfirst($lead->travelLeadData->travel_mode)) }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">All passengers are travelling
                                            back to the same sector?</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->all_passengers_are_traveling_back }}

                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Passenger Travel Other
                                            Sector</label></div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->passenger_travel_other_sector }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Booking Status</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        @php

                                        $class = 'warning';
                                        if ($lead->travelLeadData->booking_status == "hold_ticket") {
                                            $class = 'danger';
                                        }
                                        if ($lead->travelLeadData->booking_status == "confirm") {
                                            $class = 'success';
                                        }
        
                                    @endphp
                                    <span class="badge bg-{{ $class }}">{{ ucfirst($lead->travelLeadData->booking_status) }}</span>
                                    </div>
                                </div>
                                @if ($lead->travelLeadData->booking_status == 'pending')
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Followup date</label></div>

                                        <div class="col-md-4 col-xl-4">

                                            {{ $lead->travelLeadData->followup_date }}

                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>

                                        <div class="col-md-4 col-xl-4">

                                            {{ $lead->travelLeadData->pending_remarks }}

                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($lead->travelLeadData->travel_inquiry_type == 'visa')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Duration of their stay</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->duration_of_stay }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Travelling Date</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->visa_travel_date }}

                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->number_of_customers }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Country</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ isset($lead->travelLeadData->visaCountryName) ? $lead->travelLeadData->visaCountryName->name : '-' }}

                                    </div>

                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Purpose of Travel</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ ucfirst($lead->travelLeadData->purpose_of_travel) }}
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Visa Type</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->visa_type }}

                                    </div>

                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Expense bearer </label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_',' ',ucfirst($lead->travelLeadData->expense_bearer)) }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">First Time Traveller or
                                            not?</label></div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->first_time_traveler }}

                                    </div>
                                </div>
                                @if($lead->travelLeadData->first_time_traveler == "no")
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Travel History</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->travel_history) }}

                                    </div>
                                </div>
                                @endif
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Visa Rejection</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->visa_rejection }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->travel_other_services }}

                                    </div>
                                </div>
                                @if($lead->travelLeadData->visa_rejection == "yes")
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Reject Country Name</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->travelLeadData->rejectVisaCountryName) ? $lead->travelLeadData->rejectVisaCountryName->name : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Visa Rejection Reason</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->visa_rejection_reason }}

                                    </div>
                                </div>
                                @endif
                                @if($lead->travelLeadData->travel_other_services == "yes")
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Other Service Document</label>
                                    </div>
                                    @if ($lead->travelLeadData->other_service_document !== null)
                                        @php
                                            $document = json_decode($lead->travelLeadData->other_service_document);
                                        @endphp
                                        @foreach ($document as $key => $doc)
                                            <div class="col-md-2 col-xl-2">
                                                <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                                                    class="btn btn-dark">view</a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                @endif
                                @if(isset($lead->visaMember))
                                    @foreach ($lead->visaMember as $visa)
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-4">Given Name:- {{ $visa->given_name }}</div>
                                        <div class="col-md-4">Middle Name:- {{ $visa->middle_name }}</div>
                                        <div class="col-md-4">Last Name:- {{ $visa->last_name }}</div>
                                        <div class="col-md-4">Issue Date:- {{ $visa->issue_date }}</div>
                                        <div class="col-md-4">Expiry Date:- {{ $visa->expiry_date }}</div>
                                        <div class="col-md-4">Date Of Birth:- {{ $visa->date_of_birth }}</div>
                                        <div class="col-md-4">Issue Place:- {{ $visa->issuing_place }}</div>
                                        <div class="col-md-4">Occupation:- {{ $visa->occupation }}</div>
                                    </div>                  
                                    @endforeach
                                @endif
                            @endif
                            @if ($lead->travelLeadData->travel_inquiry_type == 'domestic')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->travel_destination }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination To</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->flight_to }}
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->number_of_customers }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Specific Place Interest</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ ucfirst($lead->travelLeadData->specific_place_interest) }}
                                    </div>
                                </div>



                                <div class="row align-items-center g-3 mt-6">


                                    <div class="col-md-2"><label class="form-label mb-0">Type of Travel</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->travel_type }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->hotel_category }}

                                    </div>
                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    

                                    <div class="col-md-2"><label class="form-label mb-0">Meal Plan Preference</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->meal_plan_preference }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->departure_date }}

                                    </div>
                                </div>
                                
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Transport Category</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->transport_category) }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->duration_of_stay }}

                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->travelLeadData->otherServiceName) ? $lead->travelLeadData->otherServiceName->name : '-' }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Other Services Remarks</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->domestic_other_services_remarks }}

                                    </div>
                                </div>
                                @if($lead->travelLeadData->departure_date == "flexible")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Month and Year</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->flexible_month_year }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Week</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_week }} Week
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "fixed")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Fixed Date</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_fixed_date }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "anytime")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($lead->travelLeadData->travel_inquiry_type == 'international')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->travel_destination }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination To</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->flight_to }}

                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->number_of_customers }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Specific Place Interest</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ ucfirst($lead->travelLeadData->specific_place_interest) }}
                                    </div>
                                </div>



                                <div class="row align-items-center g-3 mt-6">


                                    <div class="col-md-2"><label class="form-label mb-0">Type of Travel</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->travel_type }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->hotel_category }}

                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">


                                    <div class="col-md-2"><label class="form-label mb-0">Meal Plan Preference</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->meal_plan_preference }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Transport Category</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->transport_category) }}

                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->duration_of_stay }}

                                    </div>
                                    
                                    <div class="col-md-2"><label class="form-label mb-0">Other Services</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ isset($lead->travelLeadData->otherServiceName) ? $lead->travelLeadData->otherServiceName->name : '-' }}

                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Other Services Remarks</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->domestic_other_services_remarks }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->departure_date }}

                                    </div>
                                    @if($lead->travelLeadData->departure_date == "flexible")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Month and Year</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->flexible_month_year }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Week</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_week }} Week
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "fixed")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Fixed Date</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_fixed_date }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "anytime")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                                </div>
                            @endif
                            @if ($lead->travelLeadData->travel_inquiry_type == 'hotel')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Duration of Stay</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->duration_of_stay }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Depture Date</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->departure_date }}

                                    </div>

                                </div>
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->number_of_customers }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Preferable Area for stay</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->hotel_category }}

                                    </div>
                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->travel_destination }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Travel Destination To</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->flight_to }}

                                    </div>
                                </div>



                                <div class="row align-items-center g-3 mt-6">



                                    <div class="col-md-2"><label class="form-label mb-0">Hotel Category</label></div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->hotel_category }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Meal Plan</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->meal_plan_preference) }}

                                    </div>

                                </div>
                                @if($lead->travelLeadData->departure_date == "flexible")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Month and Year</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->flexible_month_year }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Week</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_week }} Week
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "fixed")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Fixed Date</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_fixed_date }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "anytime")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if ($lead->travelLeadData->travel_inquiry_type == 'transport')
                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Departure Date</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->departure_date }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Number of Customers</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->number_of_customers }}

                                    </div>

                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Destination</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->travel_destination }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Destination To</label></div>

                                    <div class="col-md-4 col-xl-4">
                                        {{ $lead->travelLeadData->flight_to }}

                                    </div>
                                    
                                </div>



                                <div class="row align-items-center g-3 mt-6">

                                    <div class="col-md-2"><label class="form-label mb-0">Pickup Date</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ $lead->travelLeadData->pickup_date }}

                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Drop Date</label></div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->drop_date }}

                                    </div>
                                    

                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Self Drive</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->self_drive) }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Chauffer</label></div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->vehicle_chauffer }}

                                    </div>
                                    

                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Vehicle Type</label></div>

                                    <div class="col-md-4 col-xl-4">

                                        {{ str_replace('_', '', $lead->travelLeadData->vehicle_type) }}

                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Specific Requirement</label>
                                    </div>

                                    <div class="col-md-4 col-xl-4 text-capitalize">

                                        {{ $lead->travelLeadData->specific_requirement }}

                                    </div>
                                </div>
                                @if($lead->travelLeadData->departure_date == "flexible")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Month and Year</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->flexible_month_year }}
                                        </div>
                                        <div class="col-md-2"><label class="form-label mb-0">Week</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_week }} Week
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "fixed")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Fixed Date</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->domestic_fixed_date }}
                                        </div>
                                    </div>
                                @endif
                                @if($lead->travelLeadData->departure_date == "anytime")
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-2"><label class="form-label mb-0">Number Of Day</label></div>
                                        <div class="col-md-4">
                                            {{ $lead->travelLeadData->number_of_day }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                        @if (isset($lead->leadTravelDetail))
                            @foreach ($lead->leadTravelDetail as $child)
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-4">Member Type:- {{ $child->child_type }}</div>
                                    <div class="col-md-4">Member Name:- {{ $child->child_name }}</div>
                                    <div class="col-md-4">Member Age:- {{ $child->child_age }}</div>
                                    <div class="col-md-4">Member DOB:- {{ $child->dob }}</div>
                                    <div class="col-md-4">Member Document:-
                                        @if (isset($child->doc_file))
                                            @php $docArray = json_decode($child->doc_file); @endphp
                                            @foreach ($docArray as $doc)
                                                <a href="{{ asset('storage/' . $doc) }}" class="btn btn-dark"
                                                    target="_blank">View</a>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                    <hr class="my-6" />
                    <div class="row align-items-center g-3 mt-6 lead_show_status">

                        <div class="col-md-2">Status</div>

                        <div class="col-md-4">

                            @php

                                $class = 'warning';

                                $name = 'Pending';

                                if ($lead->lead_status == 2) {
                                    $class = 'info';

                                    $name = 'In Progress';
                                }

                                if ($lead->lead_status == 4) {
                                    $class = 'success';

                                    $name = 'Completed';
                                }

                                if ($lead->lead_status == 3) {
                                    $class = 'danger';

                                    $name = 'On Hold';
                                }

                            @endphp

                            <span class="badge bg-{{ $class }}">{{ $name }}</span>

                        </div>

                        @if ($lead->lead_status == 4)
                            <div class="col-md-2"> Complete Lead Remark</div>

                            <div class="col-md-4">

                                {{ $lead->lead_complete_remarks }}

                            </div>
                        @elseif ($lead->lead_status == 3)
                            <div class="col-md-2"> Hold Lead Remark</div>

                            <div class="col-md-4">

                                {{ $lead->lead_hold_remarks }}

                            </div>
                        @elseif ($lead->lead_status == 2)
                            <div class="col-md-2"> In Progress Lead Remark</div>

                            <div class="col-md-4">

                                {{ $lead->lead_in_process_remarks }}

                            </div>
                        @elseif ($lead->lead_status == 1 && isset($lead->lead_pending_remarks))
                            <div class="col-md-2"> Pending Lead Remark</div>

                            <div class="col-md-4">

                                {{ $lead->lead_pending_remarks }}

                            </div>
                        @endif

                    </div>

                    @if($lead->lead_pending_date_time !== null)
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"> Pending Lead At</div>

                            <div class="col-md-4">

                                {{ Utility::convertDmyAMPMFormat($lead->lead_pending_date_time) }}

                            </div>
                            <div class="col-md-2">Pending Remarks</div>

                            <div class="col-md-4">

                                {{$lead->lead_pending_remarks }}

                            </div>
                        </div>
                    @endif
                    @if($lead->hold_date_time !== null)
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"> Hold Lead At</div>

                            <div class="col-md-4">

                                {{ Utility::convertDmyAMPMFormat($lead->hold_date_time) }}

                            </div>
                            <div class="col-md-2">Hold Remarks</div>

                            <div class="col-md-4">

                                {{$lead->lead_hold_remarks }}

                            </div>
                        </div>
                    @endif
                    @if($lead->lead_complete_date_time !== null)
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"> Complete Lead At</div>

                            <div class="col-md-4">

                                {{ Utility::convertDmyAMPMFormat($lead->lead_complete_date_time) }}

                            </div>
                            <div class="col-md-2">Complete Remarks</div>

                            <div class="col-md-4">

                                {{$lead->lead_complete_remarks }}

                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"> Eligible For TCS</div>

                            <div class="col-md-4">
                                {{isset($lead->travelLeadData)? ucfirst($lead->travelLeadData->eligible_tcs_amount) : "-"}}

                            </div>
                            @if(isset($lead->travelLeadData) && $lead->travelLeadData->eligible_tcs_amount == "yes")
                            <div class="col-md-2">TCS Percentage</div>

                            <div class="col-md-4">
                                {{isset($lead->travelLeadData)? $lead->travelLeadData->tcs_percentage : "-"}}

                            </div>
                            @endif
                        </div>
                        @if(isset($lead->travelLeadData) && $lead->travelLeadData->eligible_tcs_amount == "yes" && $lead->travelLeadData->tcs_declaration_form !== "")
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2">TCS Declaration Form</div>

                            <div class="col-md-4">
                                <a href="{{asset('storage/'.$lead->travelLeadData->tcs_declaration_form)}}" target="_blank" class="btn btn-dark">View</a>

                                </div>
                            </div>
                        @endif
                    @endif
                    @if($lead->lead_in_process_date_time !== null)
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"> In Process Lead At</div>

                            <div class="col-md-4">

                                {{ Utility::convertDmyAMPMFormat($lead->lead_in_process_date_time) }}

                            </div>
                            <div class="col-md-2">In Process Remarks</div>

                            <div class="col-md-4">

                                {{$lead->lead_in_process_remarks }}

                            </div>
                        </div>
                    @endif
                    <div class="row align-items-center g-3 mt-6">

                        <div class="col-md-2"><label class="form-label mb-0">Created By</label></div>

                        <div class="col-md-4">

                            {{ isset($lead->userDetail) ? $lead->userDetail->name : '-' }}

                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header" style="background-color: black">
                            <div class="card-title text-white">
                                Lead Follow Up Detail
                            </div>
                        </div>

                        <div class="cus-card-body" style="background-color: #dfdfdf; display: block;">
                            <div class="timeline">
                                @foreach ($lead->followUpDetail as $followUp)
                                    <div>
                                        <i class="fa-regular fa-message bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i>
                                                {{ Utility::convertDmyAMPMFormat($followUp->created_at) }} <br />
                                                {{ isset($followUp->userDetail) ? $followUp->userDetail->name : '-' }}
                                            </span>
                                            <h3 class="timeline-header">
                                                {{ $followUp->event_name }}
                                            </h3>
                                            @foreach ($followUp->subTaskData as $subTask)
                                                <div class="timeline-body">
                                                    {{ $subTask->note }} -
                                                    {{ Utility::convertDmyAMPMFormat($followUp->created_at) }} </br>
                                                    <b>{{ isset($subTask->createdUserDetail) ? $subTask->createdUserDetail->name : '-' }}</b>
                                                </div>
                                                <hr class="m-0" />
                                            @endforeach
                                            @foreach ($followUp->commentDetail as $comment)
                                                <div class="timeline-body">
                                                    {{ $comment->comment }} -
                                                    {{ Utility::convertDmyAMPMFormat($comment->created_at) }} </br>
                                                    <b>{{ isset($comment->userDetail) ? $comment->userDetail->name : '-' }}</b>
                                                </div>
                                                <hr class="m-0" />
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content overflow-hidden">

                <div class="modal-header pb-0 border-0">

                    <h1 class="modal-title h4" id="depositLiquidityModalLabel"></h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <form class="vstack" method="POST" id="addForm" enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="lead_status" id="complete_lead_status">
                    <input type="hidden" name="lead_id" id="lead_id" value="{{ $lead->id }}">

                    <div class="modal-body undefined">

                        <div class="vstack gap-1">


                            <div id="customer_data_Model_div" class="d-none">
                                <div class="row align-items-center g-3 mt-6">
                                    @if ($customer->pan_card_number == null)
                                        <div class="col-md-3"><label class="form-label mb-0">Pan Card Number<span
                                                    class="error_span">*</span></label>
                                        </div>

                                        <div class="col-md-3 col-xl-3">
                                            <input type="text" name="pan_card_number" class="form-control"
                                                id="pan_card_number" placeholder="Enter Pan Card Number">
                                            <span class="error_span" id="pan_card_number_error"></span>
                                        </div>
                                    @endif
                                    @if ($customer->pan_card_file == null)
                                        <div class="col-md-3"><label class="form-label mb-0">Pan Card File<span
                                                    class="error_span">*</span></label></div>

                                        <div class="col-md-3 col-xl-3">
                                            <input type="file" name="pan_card_file" class="form-control"
                                                id="pan_card_file">
                                            <span class="error_span" id="pan_card_file_error"></span>
                                        </div>
                                    @endif
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    @if ($customer->aadhar_number == null)
                                        <div class="col-md-3"><label class="form-label mb-0">Aadhar Card Number<span
                                                    class="error_span">*</span></label>
                                        </div>

                                        <div class="col-md-3 col-xl-3">
                                            <input type="number" name="aadhar_number" class="form-control"
                                                placeholder="Enter Aadhar Card Number">
                                        </div>
                                    @endif
                                    @if ($customer->aadhar_card_file == null)
                                        <div class="col-md-3"><label class="form-label mb-0">Aadhar Card File<span
                                                    class="error_span">*</span></label>
                                        </div>

                                        <div class="col-md-3 col-xl-3">
                                            <input type="file" name="aadhar_card_file" class="form-control">
                                        </div>
                                    @endif
                                </div>

                                @if ($lead->invest_type == 'travel')
                                    <div class="row align-items-center g-3 mt-6">
                                        @if ($customer->passport_number == null)
                                            <div class="col-md-3"><label class="form-label mb-0">Passport
                                                    Number<span class="error_span">*</span></label></div>

                                            <div class="col-md-3 col-xl-3">
                                                <input type="text" name="passport_number" class="form-control"
                                                    placeholder="Enter Passport Number">
                                            </div>
                                        @endif
                                        @if ($customer->passport_file == null)
                                            <div class="col-md-3"><label class="form-label mb-0">Passport File<span
                                                        class="error_span">*</span></label>
                                            </div>

                                            <div class="col-md-3 col-xl-3">
                                                <input type="file" name="passport_file" class="form-control">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if ($lead->invest_type == 'travel')
                                    <div class="row align-items-center g-3 mt-6">
                                        <div class="col-md-3"><label class="form-label mb-0">Eligible For TCS<span
                                                    class="error_span">*</span></label></div>
                                        <div class="col-md-3 col-xl-3">
                                            <select class="form-select" name="eligible_tcs_amount"
                                                id="eligible_tcs_amount">
                                                <option value="">Select Eligible TCS</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 tcs_amount_div d-none"><label class="form-label mb-0">TCS
                                                Percentage<span class="error_span">*</span></label></div>
                                        <div class="col-md-3 col-xl-3 tcs_amount_div d-none">
                                            <input type="number" name="tcs_percentage" class="form-control"
                                                id="tcs_percentage" placeholder="Enter TCS Percentage">
                                        </div>
                                    </div>
                                    <div class="row align-items-center g-3 mt-6 tcs_amount_div d-none">
                                        <div class="col-md-3"><label class="form-label mb-0">TCS
                                            Declaration Form<span class="error_span">*</span></label></div>
                                    <div class="col-md-3 col-xl-3">
                                        <input type="file" name="tcs_declaration_form" class="form-control"
                                            id="tcs_declaration_form" placeholder="Enter TCS Percentage">
                                    </div>
                                    </div>
                                @endif
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Lead Customer Type<span
                                                class="error_span">*</span></label></div>
                                    <div class="col-md-3">
                                        <select class="form-select" name="lead_customer_type"
                                            id="lead_customer_type">
                                            <option value="">Select</option>
                                            <option value="corporate">Corporate</option>
                                            <option value="individual">Individual</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 gst_certificate_div d-none">
                                        <label class="form-label mb-0">GST Certificate<span
                                            class="error_span">*</span></label>
                                    </div>
                                    <div class="col-md-3 gst_certificate_div d-none">
                                        <input type="file" name="gst_certificate" id="gst_certificate" class="form-control">
                                    </div>
                                    <div class="col-md-3 other_document_div d-none">
                                        <label class="form-label mb-0">Other Document<span
                                            class="error_span">*</span></label>
                                    </div>
                                    <div class="col-md-3 other_document_div d-none">
                                        <input type="file" name="other_document[]" id="other_document" class="form-control" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Remarks<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-10 col-xl-10">
                                    <textarea name="remark" id="complete_status_remark" class="form-control" placeholder="Enter Status Remarks"></textarea>
                                    <span class="error_span" id="complete_status_remark_error"></span>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-dark completeSaveButtonStatus"
                            id="submitBtn">Submit</button>
                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="modal fade" id="otherModalProcess" tabindex="-1" aria-labelledby="otherModalProcessLabel"
        style="display: none;" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content overflow-hidden">

                <div class="modal-header pb-0 border-0">

                    <h1 class="modal-title h4" id="otherModalProcessLabel"></h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <form class="vstack" method="POST" id="updateForm" enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="lead_status" id="lead_status">
                    <input type="hidden" name="lead_id" id="lead_id" value="{{ $lead->id }}">

                    <div class="modal-body undefined">

                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Remarks<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-10 col-xl-10">
                                    <textarea name="remark" id="status_remark" class="form-control" placeholder="Enter Status Remarks"></textarea>
                                    <span class="error_span" id="status_remark_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-dark otherSaveButtonStatus"
                            id="submitBtn">Submit</button>
                    </div>

                </form>

            </div>

        </div>

    </div>
    <div class="modal fade" id="addFollowUpModel" aria-labelledby="otherModalProcessLabel" style="display: none;"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content overflow-hidden">

                <div class="modal-header pb-0 border-0">

                    <h1 class="modal-title h4" id="otherModalProcessLabel">Add Follow Up - {{ $lead->lead_id }}</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                <form class="vstack" method="POST" id="addFollowUpForm" enctype="multipart/form-data">

                    @csrf

                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">

                    <div class="modal-body undefined">

                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Assign To<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    @if (Auth()->user()->role_id == '1')
                                        <select class="form-control" name="related_to" id="related_to" multiple>
                                            <option value="">Select</option>
                                            @foreach ($userList as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control read-only" name="related_to" id="reddlated_to">
                                            <option value="">Select</option>
                                            <option value="{{ Auth()->user()->id }}" selected>
                                                {{ Auth()->user()->name }}</option>
                                        </select>
                                    @endif
                                    <span class="error_span" id="lead_assign_to_error"></span>
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Subject<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="text" name="subject" class="form-control"
                                        placeholder="Enter Subject" id="subject">
                                    <span class="error_span" id="subject_error"></span>
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Start Date<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="start_date" class="form-control" id="start_date"
                                        value="{{ date('Y-m-d') }}">
                                    <span class="error_span" id="start_date_error"></span>
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">End Date<span
                                            class="error_span">*</span></label></div>
                                <div class="col-md-4 col-xl-4">
                                    <input type="date" name="end_date" class="form-control"
                                        placeholder="Enter Subject" id="end_date"
                                        value="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <span class="error_span" id="lead_assign_to_error"></span>
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
                                <div class="col-md-10 col-xl-10">
                                    <textarea name="remarks" class="form-control" id="remarks" placeholder="Enter Remarks"></textarea>
                                    <span class="error_span" id="start_date_error"></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-dark addFollowUpButtonStatus"
                            id="submitBtn">Submit</button>
                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        $('#eligible_tcs_amount').on('change', function(e) {
            var type = $(this).val();
            $('.tcs_amount_div').addClass('d-none');
            if (type == 'yes') {
                $('.tcs_amount_div').removeClass('d-none');
            }
        })
        $('.lead_status_dropdown').on('click', function(e) {

            e.preventDefault();

            var status = $(this).data('status');


            var headerModalText = "In Process Lead Remarks";

            $('#customer_data_Model_div').addClass('d-none')
            if (status == 4) {

                var headerModalText = "Complete Lead Remark";
                $('#customer_data_Model_div').removeClass('d-none')

            }

            if (status == 3) {

                var headerModalText = "On Hold Lead Remarks";

            }
            if (status == 1) {

                var headerModalText = "Pending Lead Remarks";

            }
            if (status == 4) {
                $('#complete_lead_status').val(status);
                $('#depositLiquidityModalLabel').html(headerModalText)

                $('#depositLiquidityModal').modal('show');
                $('#complete_status_remark_error').html('');
            } else {
                $('#lead_status').val(status);
                $('#otherModalProcessLabel').html(headerModalText)
                $('#status_remark_error').html('');

                $('#otherModalProcess').modal('show');
            }

        })



        $('#addForm').on('submit', function(e) {
            e.preventDefault();

            var remark = $('#complete_status_remark').val();
            $('#complete_status_remark_error').html('')
            if (remark.trim() == "") {
                $('#complete_status_remark_error').html('Please Enter Remarks.')
                return false;
            }
            Swal.fire({

                title: 'Are you sure?',

                text: "Are you sure you want to put this Lead in Complete?",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Yes, change it!',

                allowOutsideClick: false

            }).then((result) => {
                var formData = new FormData(this);

                if (result.isConfirmed) {
                    $('.completeSaveButtonStatus').prop('disabled', true);
                    $('.completeSaveButtonStatus').html('<i class="fa fa-spinner fa-spin"></i>');
                    $.ajax({
                        url: "{{ route('lead.status') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            Swal.fire({

                                title: 'Success!',

                                text: data.message,

                                icon: 'success',

                                showCancelButton: false,

                                confirmButtonColor: '#3085d6',

                                cancelButtonColor: '#d33',

                                confirmButtonText: 'Ok'

                            }).then((result) => {

                                if (result.isConfirmed) {

                                    $('textarea[name="remark"]').val('');

                                    $('#submitBtn').prop('disabled', false);

                                    $('#submitBtn').html('Submit');

                                    $('#depositLiquidityModal').modal('hide');

                                    location.reload();

                                }

                            });

                        },

                        error: function(error) {

                            $('#submitBtn').prop('disabled', false);

                            $('#submitBtn').html('Submit');

                            Swal.fire({

                                title: 'error!',

                                text: error.responseJSON.message,

                                icon: 'error',

                                showCancelButton: false,

                                confirmButtonColor: '#3085d6',

                                cancelButtonColor: '#d33',

                                confirmButtonText: 'Ok'

                            })

                        }

                    })

                }

            })
        });
        $('#updateForm').on('submit', function(e) {
            e.preventDefault();
            var status = $('#lead_status').val();
            if (status == 2) {

                var statusName = "In Process";

            }

            if (status == 3) {

                var statusName = "on hold";

            }
            var remark = $('#status_remark').val();
            $('#status_remark_error').html('')
            if (remark.trim() == "") {
                $('#status_remark_error').html('Please Enter Remarks.')
                return false;
            }
            Swal.fire({

                title: 'Are you sure?',

                text: "Are you sure you want to put this Lead " + statusName + "?",

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Yes, change it!',

                allowOutsideClick: false

            }).then((result) => {
                var formData = new FormData(this);

                if (result.isConfirmed) {

                    $('.otherSaveButtonStatus').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('.otherSaveButtonStatus').prop('disabled', true);

                    $.ajax({
                        url: "{{ route('lead.status') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false, // Prevent jQuery from setting the content type header
                        processData: false,

                        success: function(data) {



                            Swal.fire({

                                title: 'Success!',

                                text: data.message,

                                icon: 'success',

                                showCancelButton: false,

                                confirmButtonColor: '#3085d6',

                                cancelButtonColor: '#d33',

                                confirmButtonText: 'Ok'

                            }).then((result) => {

                                if (result.isConfirmed) {

                                    $('textarea[name="remark"]').val('');

                                    $('#submitBtn').prop('disabled', false);

                                    $('#submitBtn').html('Submit');

                                    $('#depositLiquidityModal').modal('hide');

                                    location.reload();

                                }

                            });

                        },

                        error: function(error) {

                            $('#submitBtn').prop('disabled', false);

                            $('#submitBtn').html('Submit');

                            Swal.fire({

                                title: 'error!',

                                text: error.responseJSON.message,

                                icon: 'error',

                                showCancelButton: false,

                                confirmButtonColor: '#3085d6',

                                cancelButtonColor: '#d33',

                                confirmButtonText: 'Ok'

                            })

                        }

                    })

                }

            })
        });

        function openAddFollowUpModal() {
            $('#addFollowUpModel').modal('show')
            $('#related_to').select2({
                dropdownParent: $('#addFollowUpModel')
            });
        }
        $('#addFollowUpForm').on('submit', function(e) {
            var assign = $('#related_to').val();
            var subject = $('#subject').val();
            $('#subject_error').html('');
            $('#related_to_error').html('');
            var cnt = 0;

            if (assign.length == 0) {
                $('#lead_assign_to_error').html('Please Select Assign.')
                cnt = 1;
            }
            if (subject.trim() == "") {
                $('#subject_error').html('Please Enter Subject.')
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            e.preventDefault();
            $('#addFollowUpButton').html('<i class="fa fa-spinner fa-spin"></i>');
            var formData = new FormData(this);
            $.ajax({
                url: "{{ route('lead-follow-up-add') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.fire({

                        title: 'Success!',

                        text: data.message,

                        icon: 'success',

                        showCancelButton: false,

                        confirmButtonColor: '#3085d6',

                        cancelButtonColor: '#d33',

                        confirmButtonText: 'Ok'

                    }).then((result) => {

                        if (result.isConfirmed) {
                            $('#addFollowUpModel').modal('hide');
                            window.location.href =
                                `{{ route('follow-up.index') }}?lead_id=${data.data.lead_id}`;
                        }
                    })
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                }
            });
        })
        $('#start_date').on('change', function() {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date > end_date) {
                var nextDay = moment(start_date).add(1, 'days').format('YYYY-MM-DD');
                $('#end_date').val(nextDay);
                $('#end_date').attr('min', nextDay);
            }
        })
        $('#lead_customer_type').on('change',function(){
            var type = $('#lead_customer_type').val();
            if(type == "corporate"){
                $('.gst_certificate_div').removeClass('d-none');
                $('.other_document_div').addClass('d-none');
            }
            if(type == "individual"){
                $('.gst_certificate_div').addClass('d-none');
                $('.other_document_div').removeClass('d-none');
            }
        })
    </script>
@endsection
