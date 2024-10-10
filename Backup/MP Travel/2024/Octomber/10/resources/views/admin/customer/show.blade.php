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
                        <h1 class="ls-tight">{{ $customer->name_title }} {{ $customer->name }}
                            ({{ $customer->customer_id }})</h1>
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
                            {{ $customer->name_title }} {{ $customer->name }}
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
                            {{ $customer->phone_code ? '+' . $customer->phone_code : '' }} {{ $customer->mobile_number }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ isset($customer->roleDetail) ? $customer->roleDetail->name : '-' }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->age ?? 0 }}
                        </div>
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Aadhar Card</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->aadhar_number }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Uploded Aadhar Card</label></div>
                        @if ($customer->aadhar_card_file)
                            @if (json_decode($customer->aadhar_card_file) != null)
                                @foreach (json_decode($customer->aadhar_card_file) as $key => $aadhar)
                                    <div class="col-md-4 col-xl-4">
                                        <a href="{{ asset('storage/' . $aadhar) }}" target="_blank"
                                            class="btn btn-primary">View</a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-4 col-xl-4">
                                    <a href="{{ asset('storage/' . $customer->aadhar_card_file) }}" target="_blank"
                                        class="btn btn-primary">View</a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->pan_card_number }}
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Uploded Pan Card</label></div>
                        @if ($customer->pan_card_file)
                            @if (json_decode($customer->pan_card_file) != null)
                                @foreach (json_decode($customer->pan_card_file) as $key => $pan)
                                    <div class="col-md-2 col-xl-2">
                                        <a href="{{ asset('storage/' . $pan) }}" target="_blank"
                                            class="btn btn-primary">View</a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-1 col-xl-1">
                                    <a href="{{ asset('storage/' . $customer->pan_card_file) }}" target="_blank"
                                        class="btn btn-primary">View</a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">GST Certificate</label></div>
                        @if ($customer->gst_certificate)
                            @if (json_decode($customer->gst_certificate) != null)
                                @foreach (json_decode($customer->gst_certificate) as $key => $gst)
                                    <div class="col-md-2 col-xl-2">
                                        <a href="{{ asset('storage/' . $gst) }}" target="_blank"
                                            class="btn btn-primary">View</a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-2 col-xl-2">
                                    <a href="{{ asset('storage/' . $customer->gst_certificate) }}" target="_blank"
                                        class="btn btn-primary">View</a>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="row align-items-center g-3 mt-6">
                        <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->address ? $customer->address . ',' : '' }}<br />
                            {{ isset($customer->cityDetail) ? $customer->cityDetail->name . ',' : '' }}
                            {{ isset($customer->stateDetail) ? $customer->stateDetail->name . ',' : '' }}<br />
                            {{ isset($customer->countryDetail) ? $customer->countryDetail->name . '-' : '' }}
                            {{ $customer->pin_code ?? '' }}
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
                        <div class="col-md-2"><label class="form-label mb-0">Customer Reference</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $customer->reference }}
                        </div>
                    </div>
                    @if (isset($customer->servicePreferenceTagDetail))
                        @foreach ($customer->servicePreferenceTagDetail as $key => $tag)
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-4">Service Preference Tagging :-
                                    {{ isset($tag->servicePreferenceDetail) ? $tag->servicePreferenceDetail->name : '' }}
                                    @if ($key == 0)
                                        -
                                        <b>Last Service</b>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    Create By:- {{ $tag->userDetail->name }}
                                </div>
                                <div class="col-md-4">
                                    Create At:- {{ Utility::convertDmyAMPMFormat($tag->created_at) }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-4">
                    @php $i =0; @endphp
                    @foreach ($leadList as $lead)
                        @php $i++; @endphp
                        <li class="list row1 ui-state-default list-unstyled ui-sortable-handle customer"
                            data-id="{{ $lead->id }}">
                            <div class="houmanity-card card">
                                <div class="accordion" id="accordionFlushExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne" style="background-color: black">
                                            <button class="accordion-button collapsed text-white" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapse{{ $i }}" aria-expanded="false"
                                                aria-controls="flush-collapseOne">
                                                <span class="text-white">{{ $lead->lead_id }}-
                                                    {{ ucfirst($lead->invest_type) }} - @if ($lead->invest_type == 'travel' && isset($lead->travelLeadData))
                                                        {{ ucfirst($lead->travelLeadData->travel_inquiry_type) }}
                                                    @else
                                                        {{ ucfirst($lead->insurance_type) }}
                                                    @endif
                                                </span> </h4>
                                            </button>
                                        </h2>
                                        <div id="flush-collapse{{ $i }}" class="accordion-collapse collapse"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample"
                                            style="">
                                            <div class="accordion-body">
                                                <div class="card">
                                                    <div class="cus-card-body"
                                                        style="background-color: #dfdfdf; display: block;">
                                                        <div class="timeline">
                                                            @foreach ($lead->followUpDetail as $followUp)
                                                                <div>
                                                                    <i
                                                                        class="fa-regular fa-message bg-dark text-white"></i>
                                                                        <span class="time">
                                                                            <i class="fas fa-clock"></i>
                                                                            {{ Utility::convertDmyAMPMFormat($followUp->cretaed_at) }}
                                                                            
                                                                            {{ isset($followUp->userDetail) ? $followUp->userDetail->name : '-' }}
                                                                        </span>
                                                                    <div class="timeline-item">
                                                                        
                                                                        <h3 class="timeline-header">
                                                                            {{ $followUp->event_name }}
                                                                        </h3>
                                                                        @foreach ($followUp->subTaskData as $subTask)
                                                                            <div class="timeline-body">
                                                                                {{ $subTask->note }} -
                                                                                {{ Utility::convertDmyAMPMFormat($followUp->cretaed_at) }}
                                                                            </div>
                                                                            <hr class="m-0" />
                                                                        @endforeach
                                                                        @foreach ($followUp->commentDetail as $comment)
                                                                            <div class="timeline-body">
                                                                                {{ $comment->comment }} -
                                                                                {{ Utility::convertDmyAMPMFormat($comment->created_at) }}
                                                                                </br>
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
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection
