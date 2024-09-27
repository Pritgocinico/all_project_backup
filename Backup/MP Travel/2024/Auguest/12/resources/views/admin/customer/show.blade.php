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
                </div>
            </div>
            {{-- {{dd($customer)}} --}}
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->name }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->email }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->mobile_number }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ isset($customer->roleDetail) ? $customer->roleDetail->name : '-' }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Gender</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ ucfirst($customer->gender) }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Birth Date</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ Utility::convertDMYFormat($customer->birth_date) }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->insurance }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Aadhar Card</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->aadhaar_number }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Insurance Type</label></div>
                <div class="col-md-4 col-xl-4">
                    @php
                        $type = 'Individual';
                        if ($customer->insurance_type == 1) {
                            $type = 'Health & Motor';
                        } elseif ($customer->insurance_type == 2) {
                            $type = 'Corporate';
                        }
                    @endphp
                    {{ $type }}
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
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </div>
            </div>
            <hr class="my-6" />
            <h4>Bank Detail</h4>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Account Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->account_name }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Bank Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->bank_name }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Branch Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->branch_name }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">IFC Code</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->ifsc_code }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Card Number</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->card_number }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Card Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->card_name }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Card Month</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->card_month }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Card Year</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->card_year }}
                </div>
            </div>
            <hr class="my-6" />
            <h4>Lead Detail</h4>
            <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lead</th>
                        <th>Customer Name</th>
                        <th>Lead Amount</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customer->leadDetail as $key=>$lead)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><a href="{{ route('leads.show', $lead->id) }}">{{ $lead->lead_id }}</a></td>
                            <td>{{ isset($lead->customerDetail) ? $lead->customerDetail->name : '-' }}</td>
                            <td>&#x20B9; {{ number_format($lead->lead_amount ?? 0, 2) }}</td>
                            <td>
                                @php
                                    $status = 'warning';
                                    $text = 'Pending Lead';
                                    if ($lead->lead_status == 2) {
                                        $status = 'info';
                                        $text = 'Assigned Lead';
                                    }
                                    if ($lead->lead_status == 3) {
                                        $status = 'secondary';
                                        $text = 'Hold Lead';
                                    }
                                    if ($lead->lead_status == 4) {
                                        $status = 'success';
                                        $text = 'Complete Lead';
                                    }
                                    if ($lead->lead_status == 5) {
                                        $status = 'warning';
                                        $text = 'Extends Lead';
                                    }
                                    if ($lead->lead_status == 6) {
                                        $status = 'danger';
                                        $text = 'Cancel Lead';
                                    }
                                @endphp
                                <span class="badge bg-{{ $status }}">{{ $text }}</span>
                            </td>
                            <td>{{ isset($lead->userDetail) ? $lead->userDetail->name : '-' }}</td>
                            <td>{{ Utility::convertDmyAMPMFormat($lead->created_at) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No Data Available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </main>
    </div>
@endsection
