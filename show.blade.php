@extends('admin.partials.header', ['active' => 'lead'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ $lead->lead_id }}</h1>
                    </div>
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    {{-- <div class="col text-end">
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
                    </div> --}}
                </div>
            </div>

            <hr class="my-6">
            <div class="row g-3 mt-6">
                <div class="col-md-8">
                    @if (isset($lead->customerDetail))
                        <h4>Customer Detail</h4>
                        <form action="{{ route('customer_lead_update') }}" id="customer_form" method="POST">
                            <div id="customer_detail_div">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <input type="hidden" name="customer_id" value="{{ $lead->customer_id }}">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{ $customer->name ?? '-' }}
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{ $customer->mobile_number ?? '-' }}
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="age" id="age" class="form-control"
                                            placeholder="Enter Age" value="{{ $customer->cust_age }}">
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="height" id="height" class="form-control"
                                            placeholder="Enter Height" value="{{ $customer->cust_height }}">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <input type="text" name="weight" id="weight" class="form-control"
                                            placeholder="Enter weight" value="{{ $customer->cust_weight }}">
                                    </div>

                                    <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="wa_exist" id="wa_exist" class="form-control">
                                            <option value="">Selcet WA Exist</option>
                                            <option value="1" {{ $customer->wa_exist == 1 ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="0" {{ $customer->wa_exist == 0 ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="disease" id="disease" class="form-control">
                                            <option value="">Select Disease</option>
                                            @foreach ($diseases as $disease)
                                                <option value="{{ $disease->id }}"
                                                    {{ $customer->custDisease->id == $disease->id ? 'selected' : '' }}>
                                                    {{ $disease->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="button" class="btn btn-primary add-alt-num">Add</button>
                                    </div>
                                </div>
                                @foreach ($custAltnumbers as $number)
                                    <div class="row align-items-center gap-3 mt-6" id="alternate_div_{{ $number->id }}">
                                        <input type="hidden" name="alt_num_id[]" value="{{ $number->id }}">
                                        <div class="col-md-6 row align-items-center g-3">
                                            <div class="col-md-4">
                                                <label class="form-label mb-0">Alternate Number</label>
                                            </div>
                                            <div class="col-md-8 col-xl-6">
                                                <input type="text" name="cust_alt_num[]"
                                                    class="form-control cust_alt_num" pattern="\d{10}" minlength="10"
                                                    maxlength="10" data-count="{{ $number->id }}"
                                                    onkeypress="return isNumberKey(event)"
                                                    placeholder="Enter Alternate Number"
                                                    value="{{ $number->cust_alt_num }}">
                                                <span id="alt_number_exist_error_{{ $number->id }}"
                                                    class="text-danger"></span>
                                                @error('cust_alt_num.*')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6 row align-items-center g-3 ">
                                            <div class="col-md-4">
                                                <label class="form-label mb-0">WhatsApp exist?</label>
                                            </div>
                                            <div class="col-md-6 col-xl-6">
                                                <select name="alt_wa_exist[]" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="1"
                                                        {{ $number->alt_wa_exist == 1 ? 'selected' : '' }}>Yes</option>
                                                    <option value="0"
                                                        {{ $number->alt_wa_exist == 0 ? 'selected' : '' }}>No</option>
                                                </select>

                                                @error('alt_wa_exist')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <a href="javascript:void(0)" class="btn btn-primary remove-cust-number"
                                                    data-id="{{ $number->id }}"><i
                                                        class="fa-solid fa-trash-can"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if (old('cust_alt_num'))
                                    @php $cnt = count(old('cust_alt_num')); @endphp
                                    @for ($i = 0; $i < $cnt; $i++)
                                        <div class="add_more_option row align-items-center gap-3 mt-6">
                                            <input type="hidden" name="alt_num_id[]" value="">
                                            <div class="col-md-6 row align-items-center g-3 ">
                                                <div class="col-md-4">
                                                    <label class="form-label mb-0">Alternate Number</label>
                                                </div>
                                                <div class="col-md-8 col-xl-6">
                                                    <input type="text" name="cust_alt_num[]"
                                                        class="form-control cust_alt_num" pattern="\d{10}" minlength="10"
                                                        maxlength="10" data-count="old_{{ $i }}"
                                                        onkeypress="return isNumberKey(event)"
                                                        placeholder="Enter Alternate Number"
                                                        value="{{ old('cust_alt_num.' . $i) }}">
                                                    <span id="alt_number_exist_error_old_{{ $i }}"
                                                        class="text-danger"></span>
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
                                                        <option value="1"
                                                            @if ('1' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>
                                                            Yes</option>
                                                        <option value="0"
                                                            @if ('0' == old('alt_wa_exist.' . $i)) {{ 'selected' }} @endif>No
                                                        </option>
                                                    </select>
                                                    @error('alt_wa_exist.' . $i)
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button"
                                                        class="btn btn-primary add-alt-num">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                                <hr class="my-6">
                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        <h4>Address Detail</h4>
                                    </div>
                                    <div class="col-md-4 text-end">

                                        <button type="button" class="btn btn-primary add-address">Add More
                                            Address</button>
                                    </div>
                                </div>

                                <div id="address-containers">
                                    @foreach ($custAddresses as $index => $address)
                                        <div class="address-block" data-index="{{ $index }}">
                                            <hr class="my-6">
                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Address Type
                                                        </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <select name="add_type[]" class="form-control">
                                                            <option value="">Select Type</option>
                                                            <option value="office_add"
                                                                {{ $address->add_type == 'office_add' ? 'selected' : '' }}>
                                                                Office
                                                                Address
                                                            </option>
                                                            <option value="shop_add"
                                                                {{ $address->add_type == 'shop_add' ? 'selected' : '' }}>
                                                                Shop Address</option>
                                                            <option value="home_add"
                                                                {{ $address->add_type == 'home_add' ? 'selected' : '' }}>
                                                                Home Address</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Pin Code </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="number" name="pin_code[]"
                                                            class="form-control pin-code" min="0"
                                                            placeholder="Enter Pin Code" value="{{ $address->pin_code }}"
                                                            data-index="{{ $index }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Address </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ $address->address }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Village </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <select name="village[]" class="form-control village"
                                                            id="village_id_{{ $index }}">
                                                            <option value="{{ $address->village }}">
                                                                {{ $address->village }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Post
                                                            Office(B.O/S.O/H.O)
                                                        </label></div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <select name="office_name[]" class="form-control office"
                                                            id="office_id_{{ $index }}">
                                                            <option value="{{ $address->office_name }}">
                                                                {{ $address->office_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">City </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="text" name="dist_city[]"
                                                            class="form-control city" value="{{ $address->dist_city }}"
                                                            id="dist_city_{{ $index }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">State </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="text" name="dist_state[]"
                                                            class="form-control state" value="{{ $address->dist_state }}"
                                                            id="dist_state_{{ $index }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="address_id[]" value="{{ $address->id }}">

                                            <div class="col-md-12 text-end mt-3">
                                                <button type="button" class="btn btn-danger delete_address"
                                                    data-id="{{ $address->id }}">Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div id="address-container"></div>

                            </div>

                            <hr class="my-6">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 mt-6">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Source</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="lead_source" class="form-control" id="lead_source">
                                            <option value="">Select Lead Source</option>
                                            <option value="Whatsapp"
                                                {{ $lead->lead_source == 'Whatsapp' ? 'selected' : '' }}>
                                                Whatsapp
                                            </option>
                                            <option value="Facebook"
                                                {{ $lead->lead_source == 'Facebook' ? 'selected' : '' }}>
                                                Facebook
                                            </option>
                                            <option value="Instagram"
                                                {{ $lead->lead_source == 'Instagram' ? 'selected' : '' }}>
                                                Instagram
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
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Date</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="date" name="lead_date" class="form-control"
                                            placeholder="Enter Lead Date" min="{{ date('Y-m-d') }}" id="lead_date"
                                            value="{{ $lead->lead_date ?? date('Y-m-d') }}"
                                            @if (auth()->user()->role_id == 1) @else readonly @endif>
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
                                            <option value="Hindi"
                                                {{ $lead->customer_language == 'Hindi' ? 'selected' : '' }}>Hindi
                                            </option>
                                            <option value="Gujarati"
                                                {{ $lead->customer_language == 'Gujarati' ? 'selected' : '' }}>
                                                Gujarati
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

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Product</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        <select name="product[]" class="form-control" id="product_id" multiple>
                                            <option value="" disabled>Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ in_array($product->id, $leadProduct ?? []) ? 'selected' : '' }}>
                                                    {{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="my-6">
                            <div class="d-flexjustify-content-end gap-2">
                                <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
                            </div>
                        </form>
                    @endif
                    <hr class="my-6">
                    <div class="row align-items-center g-3 mt-6">
                        <h5>
                            <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                Remarks
                            </a>
                        </h5>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="lead_id" id="lead_id" value="{{ $lead->id }}">
                        <div class="row align-items-center mt-6">
                            <div class="col-md-12">
                                <div class="label mb-2">Title <span class="text-danger">*</span></div>
                                <select name="title" id="remark_title" class ="form-control">
                                    <option value="">Select Title</option>
                                    <option value="No Problem">No Problem</option>
                                    <option value="Call Back/Follow Up">Call Back/Follow Up</option>
                                    <option value="Sale Done">Sale Done</option>
                                    <option value="No Need/Not Interested">No Need/Not Interested</option>
                                    <option value="Call Cut By Customer">Call Cut By Customer</option>
                                    <option value="Resale Done">Resale Done</option>
                                    <option value="Other Disease">Other Disease</option>

                                    <option value="Pending For Approvel">Pending For Approvel</option>
                                    <option value="On Call">On Call</option>
                                    <option value="Incoming Stop/Switch off/Auto cut/Invalid Number">Incoming Stop/Switch
                                        off/Auto
                                        cut/Invalid Number</option>
                                    <option value="Not Connected/BUSY/Ringing">Not Connected/BUSY/Ringing</option>
                                    <option value="F2F Coming">F2F Coming</option>
                                    <option value="DND/Time Pass/Abuse Language">DND/Time Pass/Abuse Language</option>
                                    <option value="Marketing & Selling Purpose Only">Marketing & Selling Purpose Only
                                    </option>
                                    <option value="Already Sale Done">Already Sale Done</option>
                                    <option value="Duplicate Lead">Duplicate Lead</option>
                                    <option value="Not Connected">Not Connected</option>
                                    <option value="other">Other</option>
                                </select>
                                <span class="error-title text-danger"></span>
                            </div>
                        </div>
                        <div class="row align-items-center mt-6 other_title_div d-none">
                            <div class="label mb-2">Other Title <span class="text-danger">*</span></div>
                            <input type="text" name="other_title" id="other_title" class="form-control"
                                placeholder="Enter Title">
                            <span class="other_title_error text-danger"></span>
                        </div>
                        <div class="row align-items-center mt-6">
                            <div class="label mb-2">Remark</div>
                            <textarea name="remark" class="lead-remark form-control" id="text_remark" rows="2" placeholder="Enter Remark"></textarea>
                            <span class="error-remark text-danger"></span>
                        </div>
                    </div>
                    <a href="javascript:void(0);" class="btn-comment btn btn-primary btn-icon mt-3 fs-12"
                        id="btn-comment" style="float:right;">
                        Add Remark
                    </a>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header" style="background-color: #5D8E47">
                            <div class="card-title text-white">
                                Lead Remark Detail
                            </div>
                        </div>

                        <div class="cus-card-body" style="background-color: #dfdfdf;">
                            <div class="timeline">
                                @foreach ($leadRemarks as $remark)
                                    <div>
                                        <i class="fa-regular fa-message bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i>
                                                {{ Utility::convertDmyAMPMFormat($remark->cretaed_at) }}
                                            </span>
                                            <h3 class="timeline-header">
                                                {{ $remark->title }} {{ $remark->remark? " - ".$remark->remark :"" }}
                                            </h3>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('#product_id').select2();
        $('#remark_title').select2();
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

        $(document).on('click', '.btn-comment', function() {
            var title = $('#remark_title').val();
            var other_title = $('#other_title').val();
            var remark = $('.lead-remark').val();
            var lead_id = $('#lead_id').val();

            $('.error-title').html("");
            $('.error-title').html("");
            var cnt = 0;
            if (title.trim() == "") {
                $('.error-title').html("Please Select Title.");
                cnt = 1;
            }
            if (title == "other" && other_title.trim() == "") {
                $('.other_title_error ').html("Please Enter Other Title");
                cnt = 1;
            }
            if (cnt == 1) {
                return false;
            }
            $(this).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('lead-remark') }}",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content')
                },
                data: {
                    title: title,
                    remark: remark,
                    other_title: other_title,
                    lead_id: lead_id
                },
                success: function(data) {
                    toastr.success(data.message);
                    
                    $('#btn-comment').html('Add Remarks');
                    console.log($('#btn-comment').html());
                    $('#remark_title').val('');
                    $('#other_title').val('');
                    $('#other_title_div').addClass('d-none')
                    $('#remark_title').val(null).trigger('change');
                    $('#text_remark').val('');
                    var remark = data.data;
                    var title = remark.title;
                    if (title == "other") {
                        title = remark.other_title
                    }
                    var date = "";
                    if (remark.created_at) {
                        date = moment(remark.created_at).format('YYYY-MM-DD HH:mm:ss');
                    }
                    var remark = "";
                    if (remark.remark) {
                        remark = " - "+remark.remark
                    }
                    
                    var html = `
                    <div>
                                        <i class="fa-regular fa-message bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i>
                                                ${date}
                                            </span>
                                            <h3 class="timeline-header">
                                                ${title}  ${remark}
                                            </h3>
                                        </div>
                                    </div>
                    `;
                    $('.timeline').append(html);
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message);
                    $('#btn-comment').html('Add Remarks');
                }
            });

        });
        var altCount = 1;
        $('.add-alt-num').on('click', function() {
            altCount++;
            var newField = `
                    <div class="add_more_option row align-items-center gap-3 mt-3">
                        <input type="hidden" name="alt_num_id[]" value="">
                        <div class="col-md-6 row align-items-center g-3 mt-3">
                        <div class="col-md-4"><label class="form-label mb-0">Alternate Number</label></div>
                        <div class="col-md-8 col-xl-6">
                            <input type="text" name="cust_alt_num[]" minlength="10" maxlength="10" onkeypress="return isNumberKey(event)" class="form-control cust_alt_num" placeholder="Enter Alternate Number" data-count="${altCount}">
                            <span class="text-danger" id="alt_number_exist_error_${altCount}"></span>
                            @error('cust_alt_num')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                        <div class="col-md-4">
                            <label class="form-label mb-0">WhatsApp exist?</label>
                        </div>
                        <div class="col-md-6 col-xl-6">
                            <select name="alt_wa_exist[]" class="form-control">
                                <option value="">Select Option</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @error('alt_wa_exist')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2">
                            <a href="javascript:void(0)" class="btn btn-primary remove-alt-num"><i class="fa-solid fa-trash-can"></i></a>
                        </div>
                        </div>
                    </div>
                `;
            $('#alt-num-container').append(newField);
        });

        // Remove the alternate number field
        $(document).on('click', '.remove-alt-num', function() {
            $(this).closest('.add_more_option').remove();
        });
        $(document).on('click', '.remove-cust-number', function() {
            var alt_num_id = $(this).data("id");
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Number?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('alt_num.delete') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            id: alt_num_id
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            $(`#alternate_div_${alt_num_id}`).remove();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        });
        var addressIndex = 0; // Initialize index for unique ID management

        $('.add-address').on('click', function() {
            addressIndex++;
            console.log("hello");

            var newField = `
                <div class="address-block" data-index="${addressIndex}">
                    <input type="hidden" name="address_id[]" value="">
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
                            <div class="col-md-4"><label class="form-label mb-0">Post Office(B.O/S.O/H.O) </label></div>
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
        $(document).on('click', '.remove-address', function() {
            $(this).closest('.address-block').remove();
        });
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

        function isNumberKey(evt) {
            if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }
        $('#lead_source').on('change', function() {
            $('#other_lead_source_div').addClass('d-none');
            if ($(this).val() == "Other") {
                $('#other_lead_source_div').removeClass('d-none');
            }
        });
        var numberExist = 0;
        $('.cust_alt_num').on('keyup', function(e) {
            var alt_num = $(this).val();
            var number = $(this).data('count');
            if (alt_num.length >= 10) {
                $.ajax({
                    url: "{{ route('alt_num.check') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        alt_num: alt_num
                    },
                    success: function(data) {
                        var numberExist = 1;
                        $('#alt_number_exist_error_' + number).text(
                            "This Number is already registered");
                    },
                    error: function(xhr, status, error) {
                        var numberExist = 0;
                        $('#alt_number_exist_error_' + number).text('');
                    }
                })
            }
        })
        $('#customer_form').on('submit', function(e) {
            $('#saveSubmitButton').html('<i class="fa-solid fa-spinner fa-spin"></i>');
        })
        $('#remark_title').on('change', function(e) {
            var title = $(this).val();
            $('.other_title_div').addClass('d-none');
            if (title == "other") {
                $('.other_title_div').removeClass('d-none');
            }
        })
    </script>
@endsection
