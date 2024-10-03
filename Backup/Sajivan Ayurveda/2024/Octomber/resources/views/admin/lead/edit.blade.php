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
                    <form action="{{ route('leads.update', $lead->id) }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $lead->id }}">
                        <input type="hidden" name="lead_id" value="{{ $lead->lead_id }}">

                        <div class="row align-items-center gap-3 mt-6">
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Mobile Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="mobile_number" class="form-control" id="customer_number"
                                        pattern="\d*" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                        placeholder="Enter Mobile Number"
                                        value="{{ $lead->customerDetail->mobile_number ?? '' }}" onkeyup="customerDetail()">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Lead Type <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    @if($lead->customer_type)
                                        <input type="text" name="lead_type" id="lead_type" class="form-control" value="{{$lead->customer_type}}" readonly>
                                    @else
                                        <select name="lead_type" class="form-control" id="lead_type">
                                            <option value="">Select Lead Type</option>
                                            <option value="New Lead" {{ old('lead_type') == 'New Lead' ? 'selected' : '' }}>New Lead
                                            </option>
                                            <option value="Resale Lead" {{ old('lead_type') == 'Resale Lead' ? 'selected' : '' }}>Resale Lead
                                            </option>
                                            <option value="Referance Lead" {{ old('lead_type') == 'Referance Lead' ? 'selected' : '' }}>Referance Lead
                                            </option>
                                        </select>
                                    @endif
                                    @error('lead_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6 {{ old('lead_type') == 'Referance Lead' ? '' : 'd-none' }}"
                            id="reference_div">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Referenced By? <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="reference_name" class="form-control"
                                        placeholder="Enter Reference Name" value="{{ $lead->reference_name }}">
                                    @error('reference_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="lead_field_div">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Source</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="lead_source" class="form-control" id="lead_source">
                                            <option value="">Select Lead Source</option>
                                            <option value="Whatsapp"
                                                {{ $lead->lead_source == 'Whatsapp' ? 'selected' : '' }}>Whatsapp
                                            </option>
                                            <option value="Facebook"
                                                {{ $lead->lead_source == 'Facebook' ? 'selected' : '' }}>Facebook
                                            </option>
                                            <option value="Instagram"
                                                {{ $lead->lead_source == 'Instagram' ? 'selected' : '' }}>Instagram
                                            </option>
                                            <option value="Other" {{ $lead->lead_source == 'Other' ? 'selected' : '' }}>
                                                Other
                                            </option>
                                        </select>
                                        @error('lead_source')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 {{ $lead->lead_source == 'Other' ? '' : 'd-none' }}"
                                    id="other_lead_source_div">
                                    <div class="col-md-4"><label class="form-label mb-0">Other Lead Source <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="other_lead_source" class="form-control"
                                            placeholder="Enter Other Lead Source" value="{{ $lead->other_lead_source }}">
                                        @error('other_lead_source')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Date</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="date" name="lead_date" class="form-control"
                                            placeholder="Enter Lead Date" min="{{ date('Y-m-d') }}" id="lead_date"
                                            value="{{ $lead->lead_date }}">
                                        @error('lead_date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer Language</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="customer_language" class="form-control" id="customer_language">
                                            <option value="">Select Language</option>
                                            <option value="Hindi" {{ $lead->customer_language == 'Hindi' ? 'selected' : '' }}>Hindi
                                            </option>
                                            <option value="Gujarati" {{ $lead->customer_language == 'Gujarati' ? 'selected' : '' }}>Gujarati
                                            </option>
                                        </select>
                                        @error('customer_language')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Problem Duration</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="problem_duration" class="form-control"
                                            placeholder="Enter Duration eg. 1year, 1month, etc."
                                            value="{{ $lead->problem_duration }}">
                                        @error('problem_duration')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Medicine for whom?</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="for_whom" class="form-control"
                                            placeholder="eg. for self, for mother, for father etc."
                                            value="{{ $lead->for_whom }}">
                                        @error('for_whom')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Product</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="product[]" class="form-control" id="product_id" multiple>
                                            <option value="" disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    @if (in_array($product->id, $custProducts)) selected @endif>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product')
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
                                                <option value="{{ $emp->id }}"
                                                    {{ $lead->assigned_to == $emp->id ? 'selected' : '' }}>
                                                    {{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Send Whatsapp?</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="send_whatsapp"
                                                {{ $lead->send_whatsapp == 'on' ? 'checked' : '' }} id="send_whatsapp">
                                        </div>
                                        @error('send_whatsapp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="customer_detail_div">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_name">
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_disease">
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_mobile">
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                <div class="col-md-4 col-xl-4" id="alternate_number">
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_height">
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_weight">
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_age">
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Customer Id</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_id_div">
                                </div>
                            </div>
                            <hr class="my-6">
                            <h4>Address Detail</h4>
                            <div id="address_detail" class="mt-3"></div>
                        </div>
                        <hr class="my-6">
                        <div class="d-flex justify-content-end gap-2" id="submit_div">
                            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
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
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode < 48 || charCode > 57) {
                return false;
            }
            return true;
        }

        $('#lead_source').on('change', function(){
            if($(this).val() == "Other"){
                $('#other_lead_source_div').removeClass('d-none');
            }else{
                $('#other_lead_source_div').addClass('d-none');
            }
        });

        $(document).ready(function(e) {
            $('#product_id').select2();
            $('#cust_disease').select2();
            $('#employee_id').select2();
            $('#product_id').select2();
        })

        $(document).ready(function() {
            var mobileNumber = $('#customer_number').val();

            // If the mobile number exists and is 10 digits, fetch the customer details
            if (mobileNumber.length === 10) {
                customerDetail();  // Call the function to fetch details
            }
        });

        function customerDetail(){
            var number = $('#customer_number').val();
            if (number.length === 10) {
                $.ajax({
                    url: "{{ route('customer.get') }}",
                    type: 'GET',
                    data: { number: number },
                    success: function(data) {
                        console.log(data);
                        
                        // Show customer details and fill the data
                        $('#customer_name').html(data.data.name);
                        $('#customer_id_div').html(data.data.customer_id);
                        $('#customer_mobile').html(data.data.mobile_number);
                        $('#customer_height').html(data.data.cust_height);
                        $('#customer_weight').html(data.data.cust_weight);
                        $('#customer_age').html(data.data.cust_age);
                        $('#customer_disease').html(data.data.cust_disease.name);
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
                    },
                    error: function(error) {
                        console.log(error);
                        $('#customer_detail_div').addClass('d-none');
                        $('#lead_field_div').removeClass('d-none');
                    }
                });
            }
        }
    </script>
@endsection
