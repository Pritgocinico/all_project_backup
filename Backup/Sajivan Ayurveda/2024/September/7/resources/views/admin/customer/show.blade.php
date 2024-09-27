@extends('admin.partials.header', ['active' => 'customer'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
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
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->name ?? "-" }}
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->mobile_number ?? "-" }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->cust_age ?? "-" }}
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->cust_height ?? "-" }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->cust_weight ?? "-" }}
                </div>

                <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                <div class="col-md-4 col-xl-4">
                    @if($customer->wa_exist == 1) {{ "YES" }} @else {{"NO"}} @endif
                </div>
            </div>
            
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->cust_disease ?? "-" }}
                </div>
                @if(isset($customer->getAlternativeNumber))
                    <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                    <div class="col-md-4 col-xl-4">
                        @foreach($customer->getAlternativeNumber as $numbers)
                            {{ $numbers->cust_alt_num }} <br/>
                        @endforeach
                    </div>
                @endif
            </div>
            
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
        </main>
    </div>
@endsection
