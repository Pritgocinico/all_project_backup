@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Lead</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('pushleads.update', $lead->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row align-items-center gap-3 mt-6">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{$lead->customerDetail->id}}">
                            <input type="hidden" name="id" value="{{ $lead->id }}">
                            <input type="hidden" name="lead_id" value="{{ $lead->lead_id }}">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Mobile Number <span class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="number" name="customer_number" class="form-control" id="customer_number"
                                           placeholder="Enter Customer Number" value="{{ $lead->customerDetail->mobile_number ?? '' }}">
                                    @error('customer_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Assign <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="assigned_to" class="form-control" id="employee_id">
                                        <option value="">Select Employee</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ $lead->assigned_to == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_to')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div id="customer_detail_div" class="{{ $lead->customerDetail ? '' : 'd-none' }}">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_name">
                                    {{ $lead->customerDetail->name ?? '' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Customer Id</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_id_div">
                                    {{ $lead->customerDetail->customer_id ?? '' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_mobile">
                                    {{ $lead->customerDetail->mobile_number ?? '' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                <div class="col-md-4 col-xl-4" id="alternate_number">
                                    {{ implode(', ', $lead->customerDetail->getAlternativeNumber->pluck('cust_alt_num')->toArray()) ?? '' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_height">
                                    {{ $lead->customerDetail->cust_height ?? '' }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_weight">
                                    {{ $lead->customerDetail->cust_weight ?? '' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_age">
                                    {{ $lead->customerDetail->cust_age ?? '' }}
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_disease">
                                    {{ $lead->customerDetail->custDisease->name ?? '' }}
                                </div>
                            </div>
                        
                            <!-- Address Details -->
                            <div id="address_detail" class="mt-3">
                                @if($lead->customerDetail->customerAddress)
                                    <div class="address-container d-flex flex-wrap">
                                        @foreach($lead->customerDetail->customerAddress as $address)
                                            <div class="address-item" style="margin-right: 20px;">
                                                <strong>
                                                    @if($address->add_type === 'office_add')
                                                        Office Address
                                                    @elseif($address->add_type === 'shop_add')
                                                        Shop Address
                                                    @else
                                                        Home Address
                                                    @endif
                                                </strong>, {{ $address->address }}, {{ $address->village }}, 
                                                {{ $address->office_name }}, {{ $address->dist_city }}, 
                                                {{ $address->dist_state }}, {{ $address->pin_code }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="lead_field_div" class="{{ $lead->customerDetail ? 'd-none' : '' }}">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer Name</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter Customer Name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Disease <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="disease" class="form-control" id="employee_id">
                                            <option value="">Select Disease</option>
                                            @foreach ($diseases as $cus_disease)
                                                <option value="{{ $cus_disease->id }}" {{ old('disease') == $cus_disease->id ? 'selected' : '' }}>
                                                    {{ $cus_disease->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('disease')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">
                        <div class="d-flex justify-content-end gap-2" id="submit_div">
                            <a href="{{ route('pushleads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                            <button type="submit" class="btn btn-sm btn-dark">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </main>
    </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $('#employee_id').select2();  
        })

        $(document).ready(function() {
            // On page load, check if customer details are already available
            var customerExists = "{{ $lead->customerDetail ? true : false }}";

            if (customerExists) {
                $('#customer_detail_div').removeClass('d-none');
                $('#lead_field_div').addClass('d-none');
            } else {
                $('#customer_detail_div').addClass('d-none');
                $('#lead_field_div').removeClass('d-none');
            }

            // Existing keyup event handler
            $('#customer_number').bind('keyup', function(e) {
                var number = $(this).val();

                if (number.length === 10) {
                    $.ajax({
                        url: "{{ route('customer.get') }}",
                        type: 'GET',
                        data: { number: number },
                        success: function(data) {
                            if (data && data.data) {
                                // Show customer details and fill the data
                                $('#customer_detail_div').removeClass('d-none');
                                $('#lead_field_div').addClass('d-none');
                                $('#customer_name').html(data.data.name);
                                $('#customer_id_div').html(data.data.customer_id);
                                $('#customer_mobile').html(data.data.mobile_number);
                                $('#customer_height').html(data.data.cust_height);
                                $('#customer_weight').html(data.data.cust_weight);
                                $('#customer_age').html(data.data.cust_age);
                                // $('#customer_disease').html(data.data.cust_disease.name);
                                $('#customer_id').val(data.data.id);
                                console.log(data.data);
                                

                                // Handle alternate numbers
                                let alternateNumbers = [];
                                $.each(data.data.get_alternative_number, function(i, v) {
                                    alternateNumbers.push(v.cust_alt_num);
                                });
                                $('#alternate_number').html(alternateNumbers.join(', '));

                                // Handle address details
                                let addressHtml = '<div class="address-container d-flex flex-wrap">';
                                $.each(data.data.customer_address, function(i, address) {
                                    let addType = (address.add_type === 'office_add') ? 'Office Address' :
                                                (address.add_type === 'shop_add') ? 'Shop Address' : 'Home Address';
                                    addressHtml += `<div class="address-item" style="margin-right: 20px;">
                                                        <strong>${addType}</strong>, ${address.address}, ${address.village}, 
                                                        ${address.office_name}, ${address.dist_city}, ${address.dist_state}, ${address.pin_code}
                                                    </div>`;
                                });
                                addressHtml += '</div>';
                                $('#address_detail').html(addressHtml);
                            } else {
                                $('#customer_detail_div').addClass('d-none');
                                $('#lead_field_div').removeClass('d-none');
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            $('#customer_detail_div').addClass('d-none');
                            $('#lead_field_div').removeClass('d-none');
                        }
                    });
                } else {
                    $('#customer_detail_div').addClass('d-none');
                    $('#lead_field_div').addClass('d-none');
                }
            });
        });

    </script>
@endsection
