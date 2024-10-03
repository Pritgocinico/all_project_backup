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
                    <form action="{{ route('pushleads.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Mobile Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="customer_number" class="form-control" id="customer_number" onkeyup="customerDetail()"
                                        pattern="\d*" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                        placeholder="Enter Customer Number" value="{{ old('customer_number') }}">
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
                                            <option value="{{ $emp->id }}"
                                                {{ old('assigned_to') == $emp->id ? 'selected' : '' }}>
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

                        <div id="customer_detail_div" class="d-none">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_name">
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Customer Id</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_id_div">
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

                                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                <div class="col-md-4 col-xl-4" id="customer_disease">
                                </div>
                            </div>
                            <hr class="my-6">
                            <h4>Address Detail</h4>
                            <div id="address_detail" class="mt-3"></div>

                            <hr class="my-6">

                        </div>

                        <div id="lead_field_div" class="d-none">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Customer Name <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter Customer Name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Disease <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="disease" class="form-control" id="disease_id">
                                            <option value="">Select Disease</option>
                                            @foreach ($diseases as $cus_disease)
                                                <option value="{{ $cus_disease->id }}"
                                                    {{ old('disease') == $cus_disease->id ? 'selected' : '' }}>
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
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode < 48 || charCode > 57) {
                return false;
            }
            return true;
        }
        $(document).ready(function(e) {
            customerDetail();
            $('#employee_id').select2();
            $('#disease_id').select2();
        })

        function customerDetail() {
            var number = $('#customer_number').val();
            if (number.length === 10) {
                $.ajax({
                    url: "{{ route('customer.get') }}",
                    type: 'GET',
                    data: {
                        number: number
                    },
                    success: function(data) {
                        // Check if customer data exists
                        if (data && data.data) {
                            // Show customer detail div and fill the data
                            $('#customer_detail_div').removeClass('d-none');
                            $('#lead_field_div').addClass('d-none'); // Hide lead form div
                            $('#customer_name').html(data.data.name);
                            $('#customer_id_div').html(data.data.customer_id);
                            $('#customer_mobile').html(data.data.mobile_number);
                            $('#customer_height').html(data.data.cust_height);
                            $('#customer_weight').html(data.data.cust_weight);
                            $('#customer_age').html(data.data.cust_age);
                            $('#customer_disease').html(data.data.cust_disease.name);
                            $('#customer_id').val(data.data.id);


                            // Handle alternate numbers
                            let alternateNumbers = [];
                            $.each(data.data.get_alternative_number, function(i, v) {
                                alternateNumbers.push(v.cust_alt_num);
                            });
                            $('#alternate_number').html(alternateNumbers.join(', '));

                            // Handle address details
                            let addressHtml = '<div class="address-container d-flex flex-wrap">';
                            $.each(data.data.customer_address, function(i, address) {
                                let addType = '';
                                if (address.add_type === 'office_add') {
                                    addType = 'Office Address';
                                } else if (address.add_type === 'shop_add') {
                                    addType = 'Shop Address';
                                } else {
                                    addType = 'Home Address';
                                }

                                addressHtml += `<div class="address-item" style="margin-right: 20px;">
                                                        <strong>${addType}</strong>, ${address.address}, ${address.village}, 
                                                        ${address.office_name}, ${address.dist_city}, ${address.dist_state}, ${address.pin_code}
                                                    </div>`;
                            });
                            addressHtml += '</div>';
                            $('#address_detail').html(addressHtml);
                        } else {
                            // Show lead form div and hide customer detail div if no data found
                            $('#customer_detail_div').addClass('d-none');
                            $('#lead_field_div').removeClass('d-none');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        // Show lead form div and hide customer detail div in case of error
                        $('#customer_detail_div').addClass('d-none');
                        $('#lead_field_div').removeClass('d-none');
                    }
                });
            } else {
                // Hide both divs if the number is less than 10 digits
                $('#customer_detail_div').addClass('d-none');
                $('#lead_field_div').addClass('d-none');
            }
        }
    </script>
@endsection
