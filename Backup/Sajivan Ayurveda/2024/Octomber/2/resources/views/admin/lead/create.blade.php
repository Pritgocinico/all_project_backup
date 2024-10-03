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
                    <form action="{{ route('leads.store') }}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="row align-items-center gap-3 mt-6">
                            <input type="hidden" name="customer_id" id="customer_id" value="">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Mobile Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="mobile_number" class="form-control" id="customer_number" pattern="\d*" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                        placeholder="Enter Mobile Number" value="{{ old('mobile_number') }}" onkeyup="customerDetail()">
                                    @error('mobile_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Lead Type <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="lead_type" class="form-control" id="lead_type">
                                        <option value="">Select Lead Type</option>
                                        <option value="New Lead" {{ old('lead_type') == 'New Lead' ? 'selected' : '' }}>New Lead
                                        </option>
                                        <option value="Resale Lead" {{ old('lead_type') == 'Resale Lead' ? 'selected' : '' }}>Resale Lead
                                        </option>
                                        <option value="Referance Lead" {{ old('lead_type') == 'Referance Lead' ? 'selected' : '' }}>Referance Lead
                                        </option>
                                    </select>
                                    @error('lead_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6 {{ old('lead_type') ==  'Referance Lead' ? '' : 'd-none' }}" id="reference_div">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Referenced By? <span
                                    class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="reference_name" class="form-control"
                                            placeholder="Enter Reference Name"
                                            value="{{ old('reference_name') }}">
                                    @error('reference_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="lead_field_div" class="{{ in_array(old('lead_type'), ['New Lead', 'Referance Lead', 'Resale Lead']) ? '' : 'd-none' }}">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Source</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="lead_source" class="form-control" id="lead_source">
                                            <option value="">Select Lead Source</option>
                                            <option value="Whatsapp" {{ old('lead_source') == 'Whatsapp' ? 'selected' : '' }}>Whatsapp
                                            </option>
                                            <option value="Facebook" {{ old('lead_source') == 'Facebook' ? 'selected' : '' }}>Facebook
                                            </option>
                                            <option value="Instagram" {{ old('lead_source') == 'Instagram' ? 'selected' : '' }}>Instagram
                                            </option>
                                            <option value="Other" {{ old('lead_source') == 'Other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                        @error('lead_source')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 {{ old('lead_source') ==  'Other' ? '' : 'd-none' }}" id="other_lead_source_div">
                                    <div class="col-md-4"><label class="form-label mb-0">Other Lead Source <span
                                        class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="other_lead_source" class="form-control"
                                            placeholder="Enter Other Lead Source"
                                            value="{{ old('other_lead_source') }}">
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
                                            value="{{ old('lead_date') ?? date('Y-m-d') }}">
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
                                            <option value="Hindi" {{ old('customer_language') == 'Hindi' ? 'selected' : '' }}>Hindi
                                            </option>
                                            <option value="Gujarati" {{ old('customer_language') == 'Gujarati' ? 'selected' : '' }}>Gujarati
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
                                            value="{{ old('problem_duration') }}">
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
                                            value="{{ old('for_whom') }}">
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
                                                <option value="{{ $product->id }}" {{ in_array($product->id, old('product') ?? []) ? 'selected' : '' }}>{{ $product->name }}</option>
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
                                                <option value="{{ $emp->id }}" {{ old('assigned_to') == $emp->id ? 'selected' : '' }} >{{ $emp->name }}</option>
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
                                            <input class="form-check-input" type="checkbox" name="send_whatsapp" {{ old('send_whatsapp') == "on" ? "checked" : "" }}
                                                id="send_whatsapp">
                                        </div>
                                        @error('send_whatsapp')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
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

                        <div id="new_customer_div" class="{{ in_array(old('lead_type'), ['New Lead', 'Referance Lead']) ? '' : 'd-none' }}">
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Name <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="name" class="form-control capitalize_letter"
                                            placeholder="Enter Name" value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Disease <span
                                        class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="cust_disease" class="form-control" id="cust_disease">
                                            <option value="">Select Disease</option>
                                            @foreach ($diseases as $disease)
                                                <option value="{{ $disease->id }}"
                                                    @if ($disease->id == old('cust_disease')) {{ 'selected' }} @endif>
                                                    {{ $disease->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('cust_disease')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Age </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="number" name="cust_age" class="form-control"
                                            placeholder="Enter Age" min="0" value="{{ old('cust_age') }}">
                                        @error('cust_age')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Height</label></div>
                                    <!-- Height Dropdown -->
                                    <div class="col-md-1 col-xl-1 height_unit_outer">
                                        <select class="form-control height_feet" name="height_unit">
                                            <option value="foot" {{ old('height_unit') == 'foot' ? 'selected' : '' }}>Ft
                                            </option>
                                            <option value="cm" {{ old('height_unit') == 'cm' ? 'selected' : '' }}>CM
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-xl-3 height_unit_input">
                                        <input type="number" class="form-control" name="cust_height"
                                            placeholder="Enter Height" min="0" step="0.01"
                                            value="{{ old('cust_height') }}">
                                        @error('cust_height')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center  gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Weight (Kg.)</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="number" class="form-control" name="cust_weight"
                                            placeholder="Enter Weight" min="0" step="0.01"
                                            value="{{ old('cust_weight') }}">
                                        @error('cust_weight')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="wa_exist" class="form-control">
                                            <option value="">Select Option</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        @error('wa_exist')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (old('cust_alt_num'))
                                @php $cnt = count(old('cust_alt_num')); @endphp
                                @for ($i = 0; $i < $cnt; $i++)
                                    <div class="row align-items-center gap-3 mt-6">
                                        <div class="col-md-6 row align-items-center g-3 ">
                                            <div class="col-md-4">
                                                <label class="form-label mb-0">Alternate Number</label>
                                            </div>
                                            <div class="col-md-8 col-xl-6">
                                                <input type="text" name="cust_alt_num[]" class="form-control" pattern="\d*" minlength="10" maxlength="10"
                                                onkeypress="return isNumberKey(event)"
                                                    placeholder="Enter Alternate Number" value="{{ old('cust_alt_num.' . $i) }}">
                                                @error('cust_alt_num.' . $i)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
            
                                        <div class="col-md-6 row align-items-center g-3 ">
                                            <div class="col-md-4">
                                                <label class="form-label mb-0">WhatsApp exist?</label>
                                            </div>
                                            <div class="col-md-3 col-xl-6">
                                                <select name="alt_wa_exist[]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="1" @if ('1' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>Yes</option>
                                                    <option value="0" @if ('0' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>No</option>
                                                </select>
                                                @error('alt_wa_exist.' . $i)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-primary add-alt-num">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            @else
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0">Alternate Number</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="cust_alt_num[]" class="form-control" pattern="\d*" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)"
                                                placeholder="Enter Alternate Number" value="">
                                            @error('cust_alt_num.*')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0">WhatsApp exist?</label>
                                        </div>
                                        <div class="col-md-3 col-xl-6">
                                            <select name="alt_wa_exist[]" class="form-control">
                                                <option value="">Select Option</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                            @error('alt_wa_exist')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary add-alt-num">Add</button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div id="alt-num-container"></div>

                            <hr class="my-6">
                            <h4>Address Detail</h4>
                            @if (old('pin_code'))
                            @php $cnt = count(old('pin_code')); @endphp
                            @for ($i = 0; $i < $cnt; $i++)
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="add_type[]" class="form-control">
                                                <option value="">Select Type</option>
                                                <option value="office_add"
                                                    @if ('office_add' == old('add_type.' . $i)) {{ 'selected' }} @endif>Office
                                                    Address</option>
                                                <option value="shop_add"
                                                    @if ('shop_add' == old('add_type.' . $i)) {{ 'selected' }} @endif>Shop
                                                    Address</option>
                                                <option value="home_add"
                                                    @if ('home_add' == old('add_type.' . $i)) {{ 'selected' }} @endif selected>Home
                                                    Address</option>
                                            </select>
                                            @error('add_type.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="number" name="pin_code[]" min="0" class="form-control"
                                                placeholder="Enter Pin Code" value="{{ old('pin_code.' . $i) }}"
                                                id="pin_code">
                                            @error('pin_code.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ old('address.' . $i) }}</textarea>
                                            @error('address.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="village[]" class="form-control" id="village_id">
                                                <option value="">Select Village</option>
                                            </select>
                                            @error('village.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Post Office </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="office_name[]" class="form-control" id="office_id">
                                                <option value="">Select Post Office</option>
                                            </select>
                                            @error('office_name.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="dist_city[]" class="form-control" id="dist_city"
                                                value="{{ old('dist_city.' . $i) }}">
                                            @error('dist_city.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="dist_state[]" class="form-control"
                                                id="dist_state" value="{{ old('dist_state.' . $i) }}">
                                            @error('dist_state.0')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @else
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="add_type[]" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="office_add"
                                                {{ old('add_type.0') == 'office_add' ? 'selected' : '' }}>Office Address
                                            </option>
                                            <option value="shop_add"
                                                {{ old('add_type.0') == 'shop_add' ? 'selected' : '' }}>Shop Address
                                            </option>
                                            <option value="home_add"
                                                {{ old('add_type.0') == 'home_add' ? 'selected' : '' }} selected>Home Address
                                            </option>
                                        </select>
                                        @error('add_type.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="number" name="pin_code[]" min="0" class="form-control"
                                            placeholder="Enter Pin Code" value="{{ old('pin_code.0') }}" id="pin_code">
                                        @error('pin_code.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ old('address.0') }}</textarea>
                                        @error('address.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="village[]" class="form-control" id="village_id">
                                            <option value="">Select Village</option>
                                        </select>
                                        @error('village.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">Post Office </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="office_name[]" class="form-control" id="office_id">
                                            <option value="">Select Post Office</option>
                                        </select>
                                        @error('office_name.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_city[]" class="form-control" id="dist_city"
                                            value="{{ old('dist_city.0') }}">
                                        @error('dist_city.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3">
                                    <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="dist_state[]" class="form-control" id="dist_state"
                                            value="{{ old('dist_state.0') }}">
                                        @error('dist_state.0')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                            <div id="address-container"></div>

                            <div class="row mt-3">
                                <div class="col-md-12 text-end">
                                    <button type="button" class="btn btn-primary add-address">Add More Address</button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">
                        <div class="d-flex justify-content-end gap-2">
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
            $('#cust_disease').select2();
            $('#employee_id').select2();
            $('#product_id').select2();
            var customerExists = "{{ old('mobile_number') ? true : false }}";
            
            if(customerExists){
                customerDetail();
            }
            
        })

        $('#lead_type').on('change', function() {
            if ($(this).val() == 'Resale Lead') {
                $('#new_customer_div').addClass('d-none')
                $('#lead_field_div').removeClass('d-none')
                $('#customer_detail_div').removeClass('d-none')
                $('#reference_div').addClass('d-none')
            } else if($(this).val() == 'New Lead') {
                $('#customer_detail_div').addClass('d-none')
                $('#new_customer_div').removeClass('d-none')
                $('#lead_field_div').removeClass('d-none')
                $('#reference_div').addClass('d-none')
            } else if($(this).val() == 'Referance Lead'){
                $('#reference_div').removeClass('d-none')
                $('#customer_detail_div').addClass('d-none')
                $('#new_customer_div').removeClass('d-none')
                $('#lead_field_div').removeClass('d-none')
            }
        })

        function customerDetail(){    
            var number = $('#customer_number').val();

            if (number.length === 10) {
                $.ajax({
                    url: "{{ route('customer.get') }}",
                    type: 'GET',
                    data: { number: number },
                    success: function(data) {
                        if (data && data.data) {
                            // Show customer details and fill the data
                            $('#customer_detail_div').removeClass('d-none');
                            $('#lead_field_div').removeClass('d-none')
                            $('#lead_type').val('Resale Lead');
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
                                let addType = (address.add_type === 'office_add') ? 'Office Address' :
                                            (address.add_type === 'shop_add') ? 'Shop Address' : 'Home Address';
                                addressHtml += `<div class="address-item" style="margin-right: 20px;">
                                                    <strong>${addType}</strong>, ${address.address}, ${address.village}, 
                                                    ${address.office_name}, ${address.dist_city}, ${address.dist_state}, ${address.pin_code}
                                                </div> <div> `;
                            });
                            addressHtml += '</div>';
                            $('#address_detail').html(addressHtml);
                        } else {
                            $('#lead_type').val('New Lead');
                            $('#customer_detail_div').addClass('d-none');
                            $('#new_customer_div').removeClass('d-none')
                            $('#lead_field_div').removeClass('d-none')
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('#customer_detail_div').addClass('d-none');
                    }
                });
            } else {
                $('#customer_detail_div').addClass('d-none');
            }
        }

        // New Customer
        $('#pin_code').bind('keyup', function(e) {
            var pincode = $(this).val();

            if (pincode.length === 6) {
                $.ajax({
                    method: 'get',
                    url: "{{ route('pin-code-ajax') }}",
                    data: {
                        pincode: pincode,
                    },
                    success: function(result) {
                        var data = result.data;
                        var html = '';
                        var html2 = '';

                        if (data.length > 0) {
                            var html = "<option value=''>Select Village</option>";
                            var html2 = "<option value=''>Select Post Office</option>";

                            var officeNamesSet = new Set(); // To keep track of unique office names

                            $.each(data, function(i, v) {
                                // For village dropdown
                                html += "<option value='" + v.village + "'>" + v.village +
                                    "</option>";

                                // For office dropdown, check for uniqueness
                                if (!officeNamesSet.has(v.office_name)) {
                                    html2 += "<option value='" + v.office_name + "'>" + v
                                        .office_name + "</option>";
                                    officeNamesSet.add(v
                                        .office_name); // Add office name to the Set
                                }
                            });

                            $('#village_id').html("");
                            $('#village_id').html(html);
                            $('#village_id').select2();

                            $('#office_id').html("");
                            $('#office_id').html(html2);
                            $('#office_id').select2();
                        }

                        $('#dist_state').val(data[0].state_name);
                        $('#dist_city').val(data[0].district_name);
                    },
                });
            }
        });

        $(document).ready(function() {
            $('.add-alt-num').on('click', function() {
                var newField = `
                    <div class="row align-items-center g-3 mt-3">
                        <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-4 col-xl-4">
                            <input type="number" name="cust_alt_num[]" class="form-control" placeholder="Enter Alternate Number" >
                            @error('cust_alt_num')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label mb-0">WhatsApp exist?</label>
                        </div>
                        <div class="col-md-3 col-xl-6">
                            <select name="alt_wa_exist[]" class="form-control">
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('alt_wa_exist')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger remove-alt-num">Remove</button>
                        </div>
                    </div>
                `;
                $('#alt-num-container').append(newField);
            });

            // Remove the alternate number field
            $(document).on('click', '.remove-alt-num', function() {
                $(this).closest('.row').remove();
            });
        });

        $(document).ready(function() {
            var addressIndex = 0; // Initialize index for unique ID management

            $('.add-address').on('click', function() {
                addressIndex++; // Increment the index for each new address block

                var newField = `
                <div class="address-block" data-index="${addressIndex}">
                    <hr class="my-6">
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="add_type[]" class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="office_add">Office Address</option>
                                    <option value="shop_add">Shop Address</option>
                                    <option value="home_add" selected>Home Address</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="number" name="pin_code[]" min="0" class="form-control pin-code" placeholder="Enter Pin Code" data-index="${addressIndex}">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address </label></div>
                            <div class="col-md-8 col-xl-6">
                                <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Village </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="village[]" class="form-control village" id="village_id_${addressIndex}">
                                    <option value="">Select Village</option>
                                </select>
                            </div>
                        </div> 
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Post Office </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="office_name[]" class="form-control office" id="office_id_${addressIndex}">
                                    <option value="">Select Office</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">City </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_city[]" class="form-control city" id="dist_city_${addressIndex}">
                            </div>
                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_state[]" class="form-control state" id="dist_state_${addressIndex}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end mt-3">
                        <button type="button" class="btn btn-danger remove-address">Remove</button>
                    </div>
                </div>`;

                $('#address-container').append(newField);
            });

            // Handle Pin Code change for each block
            $(document).on('keyup', '.pin-code', function() {
                var index = $(this).data('index'); // Get the unique index for this block
                var pincode = $(this).val();

                if (pincode.length === 6) {
                    $.ajax({
                        method: 'get',
                        url: "{{ route('pin-code-ajax') }}",
                        data: {
                            pincode: pincode,
                        },
                        success: function(result) {
                            var data = result.data;
                            var villageHtml = "<option value=''>Select Village</option>";
                            var officeHtml = "<option value=''>Select Post Office</option>";
                            var officeNamesSet = new Set();

                            $.each(data, function(i, v) {
                                villageHtml += "<option value='" + v.village + "'>" + v
                                    .village + "</option>";
                                if (!officeNamesSet.has(v.office_name)) {
                                    officeHtml += "<option value='" + v.office_name +
                                        "'>" + v.office_name + "</option>";
                                    officeNamesSet.add(v.office_name);
                                }
                            });

                            $('#village_id_' + index).html(villageHtml);
                            $('#village_id_' + index).select2();

                            $('#office_id_' + index).html(officeHtml);
                            $('#office_id_' + index).select2();

                            $('#dist_state_' + index).val(data[0].state_name);
                            $('#dist_city_' + index).val(data[0].district_name);
                        },
                    });
                }
            });

            // Remove an address block
            $(document).on('click', '.remove-address', function() {
                $(this).closest('.address-block').remove();
            });
        });
    </script>
@endsection
