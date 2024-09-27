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
                            <li><a class="dropdown-item status @if ($lead->lead_status == 1) d-none @endif"
                                    href="javascript:void(0)" data-status="1" onclick="changeStatusLead(1)">Pending
                                    Lead</a></li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 2) d-none @endif"
                                    href="javascript:void(0)" data-status="2">In Process Lead</a>
                            </li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 3) d-none @endif"
                                    href="javascript:void(0)" data-status="3">Complete Lead</a></li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 4) d-none @endif"
                                    href="javascript:void(0)" data-status="4">Cancel
                                    Lead</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            @if (isset($lead->customerDetail))
                @php $customer = $lead->customerDetail; @endphp
                <hr class="my-6">
                <h4>Customer Detail</h4>
                <div id="customer_detail_div">
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                        <div class="col-md-4 col-xl-4">{{ $customer->name }}</div>
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

                        <div class="col-md-2"><label class="form-label mb-0">Customer Type</label></div>
                        <div class="col-md-4 col-xl-4">{{ $customer->customer_department }}
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
                            {{ $customer->address }},
                            {{ isset($customer->cityDetail) ? $customer->cityDetail->name : '' }},
                            {{ isset($customer->stateDetail) ? $customer->stateDetail->name : '' }},<br />
                            {{ isset($customer->countryDetail) ? $customer->countryDetail->name : '' }}-
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
                        <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                        <div class="col-md-4 col-xl-4"> {{ $customer->service_preference }}</div>
                        <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>
                        <div class="col-md-4 col-xl-4">{{ $customer->reference }}</div>
                    </div>
                </div>
            @endif
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
                        if ($lead->lead_status == 3) {
                            $class = 'success';
                            $name = 'Completed';
                        }
                        if ($lead->lead_status == 4) {
                            $class = 'danger';
                            $name = 'Cancel';
                        }
                    @endphp
                    <span class="badge bg-{{ $class }}">{{ $name }}</span>
                </div>
                @if ($lead->lead_status == 4)
                    <div class="col-md-2"> Cancel Lead Remark</div>
                    <div class="col-md-4">
                        {{ $lead->lead_cancel_remarks }}
                    </div>
                @elseif ($lead->lead_status == 3)
                    <div class="col-md-2"> Complete Lead Remark</div>
                    <div class="col-md-4">
                        {{ $lead->lead_complete_remarks }}
                    </div>
                @elseif ($lead->lead_status == 2)
                    <div class="col-md-2"> In Progress Lead Remark</div>
                    <div class="col-md-4">
                        {{ $lead->lead_in_process_remarks }}
                    </div>
                @endif
            </div>
            <div class="row align-items-center g-3 mt-6">
                @if ($lead->lead_status == 4)
                    <div class="col-md-2"> Cancel Lead At</div>
                    <div class="col-md-4">
                        {{ Utility::convertDmyAMPMFormat($lead->lead_cancel_date_time) }}
                    </div>
                @elseif ($lead->lead_status == 3)
                    <div class="col-md-2"> Complete Lead At</div>
                    <div class="col-md-4">
                        {{ Utility::convertDmyAMPMFormat($lead->lead_complete_date_time) }}
                    </div>
                @endif
                <div class="col-md-2"><label class="form-label mb-0">Created By</label></div>
                <div class="col-md-4">
                    {{ isset($lead->userDetail) ? $lead->userDetail->name : '-' }}
                </div>
            </div>
        </main>
    </div>
    <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Department</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="addForm">
                    @csrf
                    <input type="hidden" name="lead_status" id="lead_status">
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Remark</label></div>
                                <div class="col-md-10 col-xl-10">
                                    <textarea name="remark" id="status_remark" class="form-control" placeholder="Enter Status Remarks"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-dark" id="submitBtn"
                            onclick="changeStatusLead()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.lead_status_dropdown').on('click', function(e) {
            e.preventDefault();
            var status = $(this).data('status');
            $('#lead_status').val(status);
            var headerModalText = "In Process Lead Remark";
            if (status == 3) {
                var headerModalText = "Complete Lead Remark";
            }
            if (status == 4) {
                var headerModalText = "Cancel Lead Remark";
            }
            $('#depositLiquidityModalLabel').html(headerModalText)
            if (status != 1) {
                $('#depositLiquidityModal').modal('show');
            }
        })

        function changeStatusLead(id) {
            var status = $('#lead_status').val();
            if (id !== undefined) {
                status = id;
            }
            var remark = $('#status_remark').val();
            var statusName = "Pending";
            if (status == 2) {
                var statusName = "In Process";
            }
            if (status == 3) {
                var statusName = "Complete";
            }
            if (status == 4) {
                var statusName = "Cancel";
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to " + statusName + " this ticket?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submitBtn').prop('disabled', true);
                    $('#submitBtn').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                    );
                    $.ajax({
                        url: "{{ route('lead.status') }}",
                        data: {
                            lead_id: "{{ $lead->id }}",
                            status: status,
                            remarks: remark
                        },
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
        }
    </script>
@endsection
