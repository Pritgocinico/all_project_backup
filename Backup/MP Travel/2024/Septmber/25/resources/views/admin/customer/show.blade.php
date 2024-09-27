@extends('admin.partials.header', ['active' => 'customer'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        @if (isset($customer->profile_image))
                            <img src="{{ asset('storage/' . $customer->profile_image) }}"
                                class="img-fluid rounded-top-start-4" style="height: 100px !important" alt="...">
                        @else
                            <div
                                class="initials-circle d-flex text-white align-items-center justify-content-center text-uppercase fw-bold rounded-circle bg-dark">
                                {{ Common::getInitials($customer->name) }}
                            </div>
                        @endif
                    </div>
                    <div class="col">
                        <h1 class="ls-tight">{{ $customer->name }} ({{ $customer->customer_id }})</h1>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('leads.create') }}?id={{ $customer->id }}" class="btn btn-sm btn-dark">Add
                            Lead</a>
                    </div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-8">
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Customer Name</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->name }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ Utility::convertDMYFormat($customer->birth_date) }}
                        </div>

                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->email }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->mobile_number }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($customer->roleDetail) ? $customer->roleDetail->name : '-' }}
                        </div>
                    </div>
                    @if ($customer->customer_department == 'individual')
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $customer->aadhar_number }}
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Uploded Aadhar Card</label></div>
                            <div class="col-md-4 col-xl-4">
                                @if ($customer->aadhar_card_file)
                                    <a href="{{ asset('storage/' . $customer->aadhar_card_file) }}" target="_blank"
                                        class="btn btn-primary">View</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->pan_card_number }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Uploded Pan Card</label></div>
                        <div class="col-md-4 col-xl-4">
                            @if ($customer->pan_card_file)
                                <a href="{{ asset('storage/' . $customer->pan_card_file) }}" target="_blank"
                                    class="btn btn-primary">View</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    @if ($customer->customer_department == 'corporate')
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">GST Certificate</label></div>
                            <div class="col-md-4 col-xl-4">
                                @if ($customer->gst_certificate)
                                    <a href="{{ asset('storage/' . $customer->gst_certificate) }}" target="_blank"
                                        class="btn btn-primary">View</a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    @endif
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->address }},<br />
                            {{ isset($customer->cityDetail) ? $customer->cityDetail->name : '-' }},
                            {{ isset($customer->stateDetail) ? $customer->stateDetail->name : '-' }},<br />
                            {{ isset($customer->countryDetail) ? $customer->countryDetail->name : '-' }},{{ $customer->pin_code }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                        <div class="col-md-4 col-xl-4">
                            @php
                                $text = 'Active';
                                $color = 'success';
                                if ($customer->status == 0) {
                                    $color = 'danger';
                                    $text = 'Inactive';
                                }
                            @endphp
                            <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Service Preference</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->service_preference }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->reference }}
                        </div>
                    </div>
                    @if (isset($customer->lastServiceTake))
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4">Service Preference Tagging :-
                                {{ isset($customer->lastServiceTake->servicePreferenceDetail) ? $customer->lastServiceTake->servicePreferenceDetail->name : '' }}
                            </div>
                            <div class="col-md-4">
                                Create By:- {{ $customer->lastServiceTake->userDetail->name }}
                            </div>
                            <div class="col-md-4">
                                Create At:- {{ Utility::convertDmyAMPMFormat($customer->lastServiceTake->created_at) }}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    @foreach ($leadList as $lead)
                        <div class="card">
                            <div class="card-header" style="background-color: black">
                                <div class="card-title text-white">
                                    Lead Follow Up Detail - {{ $lead->lead_id }}
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
                                                    {{ Utility::convertDmyAMPMFormat($followUp->cretaed_at) }}
                                                </span>
                                                <h3 class="timeline-header">
                                                    {{ $followUp->event_name }}
                                                    <b>{{ isset($followUp->userDetail) ? '- ' . $followUp->userDetail->name : '-' }}</b>
                                                </h3>
                                                @foreach ($followUp->subTaskData as $subTask)
                                                    <div class="timeline-body">
                                                        {{ $subTask->note }} -
                                                        {{ Utility::convertDmyAMPMFormat($followUp->cretaed_at) }} <br />
                                                        <b>{{ isset($subTask->createdUserDetail) ? $subTask->createdUserDetail->name : '-' }}</b>
                                                    </div>
                                                    <hr class="m-0" />
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection
