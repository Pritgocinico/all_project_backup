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
                        <a href="{{ route('customer.change-profile', $customer->id) }}" class="btn btn-sm btn-dark">Edit</a>
                    </div>
                </div>
            </div>
            <form action="{{ route('customer.update', $customer->id) }}" enctype="multipart/form-data" method="POST">
                @method('PUT')
                @csrf
                <input type="hidden" name="id" value="{{ $customer->id }}">
                <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">
                <div class="row align-item-center">
                    <div class="col-md-8">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Customer Id:</label></div>
                            <div class="col-md-8">
                                {{ $customer->customer_id }}
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Name <span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ $customer->name }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Phone Number <span
                                        class="text-danger">*</span></label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="mobile_number" class="form-control"
                                    placeholder="Enter Phone Number" value="{{ $customer->mobile_number }}">
                                @error('mobile_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Email <span
                                        class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ $customer->email }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Birth Date</label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="date" class="form-control" name="birth_date" max="{{ date('Y-m-d') }}"
                                    value="{{ $customer->birth_date }}">
                                @error('birth_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4 gst_certificate_div">
                                <label class="form-label mb-0">GST Certificate </label>
                            </div>
                            <div class="col-md-7 col-xl-7 gst_certificate_div">
                                <input type="file" name="gst_certificate" class="form-control" id="gst_certificate">
                                @error('gst_certificate')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                @if (isset($customer->gst_certificate))
                                    <a href="{{ asset('storage/' . $customer->gst_certificate) }}" target="_blank"
                                        class="btn btn-dark btn-sm">View</a>
                                @endif
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Pan Card Number </label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="pan_card_number" class="form-control" id="pan_card_number"
                                    placeholder="Enter Pan Card Number" value="{{ $customer->pan_card_number }}">
                                @error('pan_card_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Upload Pan Card</label></div>
                            <div class="col-md-7 col-xl-7">
                                <input type="file" name="pan_card_file" class="form-control" id="pan_card_file">
                                @error('pan_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                @if (isset($customer->pan_card_file))
                                    <a href="{{ asset('storage/' . $customer->pan_card_file) }}" target="_blank"
                                        class="btn btn-dark btn-sm">View</a>
                                @endif
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6" id="aadhar_card_div">
                            <div class="col-md-4"><label class="form-label mb-0">Aadhar Card Number</label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="aadhar_number" class="form-control" id="aadhar_number"
                                    placeholder="Enter Aadhar Card Number" value="{{ $customer->aadhar_number }}">
                                @error('aadhar_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Upload Aadhar Card</label></div>
                            <div class="col-md-7 col-xl-7">
                                <input type="file" name="aadhar_card_file" class="form-control"
                                    id="aadhar_card_file">
                                @error('aadhar_card_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                @if (isset($customer->aadhar_card_file))
                                    <a href="{{ asset('storage/' . $customer->aadhar_card_file) }}" target="_blank"
                                        class="btn btn-dark btn-sm">View</a>
                                @endif
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Passport Number</label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="passport_number" class="form-control" id="passport_number"
                                    placeholder="Enter Passport Number" value="{{ $customer->passport_number }}">
                                @error('passport_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Passport File</label></div>
                            <div class="col-md-7 col-xl-7">
                                <input type="file" name="passport_file" class="form-control" id="passport_file">
                                @error('passport_file')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1">
                                @if (isset($customer->passport_file))
                                    <a href="{{ asset('storage/' . $customer->passport_file) }}" target="_blank"
                                        class="btn btn-dark btn-sm">View</a>
                                @endif
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-4"><label class="form-label mb-0">Customer Reference</label></div>
                            <div class="col-md-8 col-xl-8">
                                <input type="text" name="reference" class="form-control" id="reference"
                                    placeholder="Enter Reference" value="{{ $customer->reference }}">
                                @error('reference')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @forelse ($customer->servicePreferenceTagDetail as $preference)
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6"><label class="form-label mb-0">Service Preference Name</label></div>
                                <div class="col-md-6 col-xl-6">
                                    {{isset($preference->servicePreferenceDetail)?$preference->servicePreferenceDetail->name : "-"}}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6"><label class="form-label mb-0">Last Modified By</label></div>
                                <div class="col-md-6 col-xl-6">
                                    {{isset($preference->userDetail)?$preference->userDetail->name : "-"}}
                                     ({{ Utility::convertDmyAMPMFormat($preference->created_at) }})
                                </div>
                            </div>
                            <hr class="my-6">
                        @empty
                            
                        @endforelse
                    </div>
                </div>
                <hr class="my-6">
                <h4>Address Detail</h4>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                    <div class="col-md-4 col-xl-4">
                        <textarea name="address" class="form-control" id="address"
                            placeholder="Enter Address">{{ $customer->address }}</textarea>
                        @error('address')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Country</label></div>
                    <div class="col-md-4 col-xl-4">
                        <select name="country" class="form-control" id="country" onchange="getState()">
                            <option value="">Select Country</option>
                            @foreach ($countryList as $country)
                                <option value="{{ $country->iso2 }}" @if ($customer->country == $country->iso2) selected @endif>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">State</label></div>
                    <div class="col-md-4 col-xl-4">
                        <select name="state" class="form-control" id="state" onchange="getCity()">
                            <option value="">Select State</option>
                        </select>
                        @error('state')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">City</label></div>
                    <div class="col-md-4 col-xl-4">
                        <select name="city" class="form-control" id="city">
                            <option value="">Select City</option>
                        </select>
                        @error('city')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Pin Code</label></div>
                    <div class="col-md-4 col-xl-4">
                        <input type="text" name="pin_code" class="form-control" id="pin_code"
                            placeholder="Enter Pin Code" value="{{ $customer->pin_code }}">
                        @error('pin_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <hr class="my-6">
                <div class="d-flexjustify-content-end gap-2">
                    <a href="{{ route('customer.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                    <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                </div>
            </form>
        </main>
    </div>
@endsection