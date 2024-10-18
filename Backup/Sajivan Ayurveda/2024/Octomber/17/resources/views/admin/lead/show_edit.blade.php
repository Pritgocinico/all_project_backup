@extends('admin.partials.header', ['active' => 'lead'])
@section('style')
    <style>
        .tooltip {
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <div
        class="flex-fill scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            <div class="row g-3">
                <div class="col-md-8">
                    @if (isset($lead->customerDetail))
                        <h4>Customer Detail</h4>
                        <form action="{{ route('customer_lead_update') }}" id="customer_form" method="POST"
                            enctype="multipart/form-data">
                            <div id="customer_detail_div">
                                @csrf
                                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                <input type="hidden" name="customer_id" value="{{ $lead->customer_id }}">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{ $customer->name ?? '-' }}
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="disease" id="disease" class="form-control">
                                            <option value="" disabled>Select Disease</option>
                                            @foreach ($diseases as $disease)
                                                <option value="{{ $disease->id }}"
                                                    {{ isset($customer->custDisease) && $customer->custDisease->id == $disease->id ? 'selected' : '' }}>
                                                    {{ $disease->name }}
                                                </option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{ $customer->mobile_number ?? '-' }}
                                    </div>
                                    <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        <select name="wa_exist" id="wa_exist" class="form-control">
                                            <option value="" disabled>Selcet WA Exist</option>
                                            <option value="1" {{ $customer->wa_exist == 1 ? 'selected' : '' }}>Yes
                                            </option>
                                            <option value="0" {{ $customer->wa_exist == 0 ? 'selected' : '' }}>No
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <hr class="my-6">
                                <div class="row">
                                    <div class="col-md-4">
                                        <h4>Alternative Number</h4>
                                    </div>
                                    <div class="col-md-8 text-end">
                                        <button type="button" class="btn btn-primary add-alt-num">Add</button>
                                    </div>
                                </div>
                                @foreach ($custAltnumbers as $number)
                                    <div class="row align-items-center gap-3 mt-6"
                                        id="alternate_div_{{ $number->id }}">
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
                                                    <option value="" disabled>Select Option</option>
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
                                <div id="alt-num-container"></div>
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
                                                        <option value="" disabled>Select Option</option>
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
                                @elseif(count($custAltnumbers) == 0)
                                    <div class="add_more_option row align-items-center gap-3 mt-6">
                                        <input type="hidden" name="alt_num_id[]" value="">
                                        <div class="col-md-6 row align-items-center g-3 ">
                                            <div class="col-md-4">
                                                <label class="form-label mb-0">Alternate Number</label>
                                            </div>
                                            <div class="col-md-8 col-xl-6">
                                                <input type="text" name="cust_alt_num[]"
                                                    class="form-control cust_alt_num" pattern="\d{10}" minlength="10"
                                                    maxlength="10" data-count="old_0"
                                                    onkeypress="return isNumberKey(event)"
                                                    placeholder="Enter Alternate Number" value="">
                                                <span id="alt_number_exist_error_old_0" class="text-danger"></span>
                                                @error('cust_alt_num')
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
                                                    <option value="" disabled>Select Option</option>
                                                    <option value="1"
                                                        @if ('1' == old('alt_wa_exist.0')) {{ 'selected' }} @endif>
                                                        Yes</option>
                                                    <option value="0"
                                                        @if ('0' == old('alt_wa_exist.0')) {{ 'selected' }} @endif>No
                                                    </option>
                                                </select>
                                                @error('alt_wa_exist.0')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0)" class="btn btn-primary remove-alt-num"><i
                                                        class="fa-solid fa-trash-can"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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
                                
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4"><label class="form-label mb-0">Weight (Kg.)</label></div>
                                    <div class="col-md-8 col-xl-8">
                                        <input type="text" name="weight" id="weight" class="form-control"
                                            placeholder="Enter weight" value="{{ $customer->cust_weight }}">
                                    </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4"><label class="form-label mb-0">Problem Duration</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="problem_duration" class="form-control"
                                                placeholder="Enter Duration eg. 1year, 1month, etc."
                                                value="{{ $lead->problem_duration }}">
                                            @error('problem_duration')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4"><label class="form-label mb-0">Medicine for whom?</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="for_whom" class="form-control" id="for_whom" onchange="addOtherOptions('','medicine')">
                                                <option value="" disabled>Select for Whom</option>
                                                @foreach ($whomList as $whom)
                                                    <option value="{{$whom}}" {{$lead->for_whom == $whom ? "selected" : ""}}>{{ucwords($whom)}}</option>
                                                @endforeach
                                                @if(!in_array('for_brother', $whomList))
                                                    <option value="for_brother" {{$lead->for_whom == 'for_brother' ? "selected" : ""}}>For Brother</option>
                                                @endif
                                                @if(!in_array('for_father', $whomList))
                                                    <option value="for_father" {{$lead->for_whom == 'for_father' ? "selected" : ""}}>For Father</option>
                                                @endif
                                                @if(!in_array('for_mother', $whomList))
                                                    <option value="for_mother" {{$lead->for_whom == 'for_mother' ? "selected" : ""}}>For Mother</option>
                                                @endif
                                                <option value="other">Other</option>
                                            </select>
                                            @error('for_whom')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4"><label class="form-label mb-0">Customer Language</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="customer_language" class="form-control" id="customer_language" onchange="addOtherOptions('','language')">
                                                <option value="" disabled>Select Language</option>
                                                @foreach ($languageList as $language)
                                                <option value="Hindi"
                                                    {{ $lead->customer_language == $language ? 'selected' : '' }}>
                                                    {{$language}}
                                                </option>
                                                    
                                                @endforeach
                                                @if(!in_array('Gujarati', $whomList))
                                                    <option value="Gujarati" {{$lead->for_whom == 'Gujarati' ? "selected" : ""}}>Gujarati</option>
                                                @endif
                                                <option value="other">Other</option>
                                            </select>
                                            @error('customer_language')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 other_disease_div d-none">
                                    <div class="col-md-2"><label class="form-label mb-0">Other
                                        Disease</label></div>
                                <div class="col-md-4 col-xl-4 ">
                                    <input type="text" name="other_disease" id="other_disease" class="form-control"
                                        value="{{ old('other_disease') }}" placeholder="Enter Other Disease">
                                </div>
                                </div>
                                <hr>
                                <div class="address-accordion mt-6">
                                    <ul class="accordion-list ">
                                        <li>
                                            <h4>Address Detail</h4>
                                            <div class="answer">
                                                <div class="accordion-body">
                                                    <div id="address-containers">
                                                        @foreach ($custAddresses as $index => $address)
                                                            <div class="address-block" data-index="{{ $index }}">
                                                                <div class="row align-items-center g-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Address Type
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <select name="add_type[]"
                                                                                class="form-control">
                                                                                <option value="" disabled>Select Type
                                                                                </option>
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
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Pin Code
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="pin_code[]"
                                                                                class="form-control pin-code"
                                                                                min="0" maxlength="6"
                                                                                placeholder="Enter Pin Code"
                                                                                value="{{ $address->pin_code }}"
                                                                                data-index="{{ $index }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Address
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address">{{ $address->address }}</textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Village
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-7 col-xl-5">
                                                                            <select name="village[]"
                                                                                class="form-control village"
                                                                                onchange="addOtherOptions({{ $index }},'village')"
                                                                                id="village_id_{{ $index }}">
                                                                                <option value="{{ $address->village }}">
                                                                                    {{ $address->village }}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage({{ $index }},'village')"
                                                                                data-index="{{ $index }}"><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Post Office
                                                                                (B.O/S.O/H.O)
                                                                            </label></div>
                                                                        <div class="col-md-7 col-xl-5">
                                                                            <select name="office_name[]"
                                                                                class="form-control office"
                                                                                onchange="addOtherOptions({{ $index }},'office_name')"
                                                                                id="office_id_{{ $index }}">
                                                                                <option
                                                                                    value="{{ $address->office_name }}">
                                                                                    {{ $address->office_name }}
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage(${index},'office')"
                                                                                data-index="{{ $index }}"><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Taluka
                                                                                / Tehsil</label></div>
                                                                        <div class="col-md-7 col-xl-7">
                                                                            <input type="text" name="taluka_tehsil[]"
                                                                                id="taluka_tehsil_{{ $index }}"
                                                                                value="{{ old('taluka_tehsil.0') }}"
                                                                                class="form-control">
                                                                            @error('taluka_tehsil.0')
                                                                                <div class="text-danger">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage({{ $index }},'taluka')"
                                                                                data-index="{{ $index }}"><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">City / District </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="dist_city[]"
                                                                                class="form-control city"
                                                                                value="{{ $address->dist_city }}"
                                                                                id="dist_city_{{ $index }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">State </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="dist_state[]"
                                                                                class="form-control state"
                                                                                value="{{ $address->dist_state }}"
                                                                                id="dist_state_{{ $index }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="address_id[]"
                                                                    value="{{ $address->id }}">
                                                                <div class="col-md-12 text-end mt-3">
                                                                    <button type="button"
                                                                        class="btn btn-danger delete_address"
                                                                        data-id="{{ $address->id }}">Remove</button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if (count($custAddresses) == 0)
                                                            <div class="address-block">
                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Address Type
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <select name="add_type[]"
                                                                                class="form-control">
                                                                                <option value="" disabled>Select Type
                                                                                </option>
                                                                                <option value="office_add">
                                                                                    Office
                                                                                    Address
                                                                                </option>
                                                                                <option value="shop_add">
                                                                                    Shop Address</option>
                                                                                <option value="home_add" selected>
                                                                                    Home Address</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Pin Code
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="pin_code[]"
                                                                                class="form-control pin-code"
                                                                                min="0" maxlength="6"
                                                                                placeholder="Enter Pin Code"
                                                                                value="" data-index="0">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Address
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <textarea name="address[]" class="form-control capitalize_letter" placeholder="Enter Address"></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Village
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-7 col-xl-5">
                                                                            <select name="village[]"
                                                                                class="form-control village"
                                                                                onchange="addOtherOptions('0','village')"
                                                                                id="village_id_0">
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage(0,'village')"
                                                                                data-index="0"><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Post Office
                                                                                (B.O/S.O/H.O)
                                                                            </label></div>
                                                                        <div class="col-md-7 col-xl-5">
                                                                            <select name="office_name[]"
                                                                                onchange="addOtherOptions('0','office')"
                                                                                class="form-control office"
                                                                                id="office_id_0">
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage(0,'office')"
                                                                                data-index="0"><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Taluka
                                                                                / Tehsil</label></div>
                                                                        <div class="col-md-7 col-xl-7">
                                                                            <input type="text" name="taluka_tehsil[]"
                                                                                id="taluka_tehsil_0"
                                                                                value="{{ old('taluka_tehsil.0') }}"
                                                                                class="form-control">
                                                                            @error('taluka_tehsil.0')
                                                                                <div class="text-danger">
                                                                                    {{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            <a href="javascript:void(0)"
                                                                                class="btn btn-primary copy-detail"
                                                                                onclick="copyVillage(0,'taluka')"
                                                                                data-index=""><i
                                                                                    class="fa-solid fa-copy"></i></a>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">City / District</label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="dist_city[]"
                                                                                class="form-control city" value=""
                                                                                id="dist_city_0">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">State </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="dist_state[]"
                                                                                class="form-control state" value=""
                                                                                id="dist_state_0">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input type="hidden" name="address_id[]" value="">

                                                                <div class="col-md-12 text-end mt-3">
                                                                    <button type="button"
                                                                        class="btn btn-danger delete_address"
                                                                        data-id="0">Remove</button>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div id="address-container"></div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-8">
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <button type="button" class="btn btn-primary add-address">Add
                                                                More
                                                                Address</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <hr class="my-6">
                            <h4>Lead Detail</h4>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 mt-6">
                                    <div class="col-md-4"><label class="form-label mb-0">Lead Source</label></div>
                                    <div class="col-md-8 col-xl-6">
                                        {{ $lead->lead_source }}
                                    </div>

                                </div>
                                <div class="col-md-6 row align-items-center g-3 {{ $lead->lead_source == 'Other' ? '' : 'd-none' }}"
                                    id="other_lead_source_div">
                                    <div class="col-md-4"><label class="form-label mb-0">Other Lead Source <span
                                                class="text-danger">*</span></label></div>
                                    <div class="col-md-8 col-xl-6">
                                        {{ $lead->other_lead_source }}
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4"><label class="form-label mb-0">Lead Generation Date</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="date" name="lead_date" class="form-control"
                                                placeholder="Enter Lead Date" id="lead_date"
                                                value="{{ $lead->lead_date ?? date('Y-m-d') }}"
                                                @if (auth()->user()->role_id == 1) @else readonly @endif>
                                            @error('lead_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                </div>
                                <hr class="my-6">
                                <div class="product-accordion mt-6">
                                    <ul class="accordion-list ">
                                        <li>
                                            <h4>Product Detail</h4>
                                            <div class="answer">
                                                <div class="accordion-body">
                                                    <div id="address-containers">
                                                        @php $ind = 0; @endphp
                                                        @foreach ($leadProduct as $index => $product)
                                                            @php $ind = $ind +1; @endphp
                                                            <input type="hidden" name="cust_product_id[]"
                                                                value="{{ $product->id }}">
                                                            <input type="hidden" name="per_product_price[]"
                                                                value="{{ isset($product->leadProduct) ? $product->leadProduct->price : '' }}"
                                                                id="per_product_price_{{ $product->id }}">
                                                            <div class="product-block" data-index="{{ $ind }}">
                                                                <div class="row align-items-center g-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label mb-0">Product
                                                                                Name</label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <select name="product_id[]"
                                                                                class="form-control product_id"
                                                                                id="product_id_{{ $product->id }}"
                                                                                data-id="{{ $product->id }}"
                                                                                onchange="getProductPrice({{ $product->id }})">
                                                                                <option value="" disabled>Select
                                                                                    Product</option>
                                                                                @foreach ($products as $productID)
                                                                                    <option value="{{ $productID->id }}"
                                                                                        {{ $productID->id == $product->product_id ? 'selected' : '' }}>
                                                                                        {{ $productID->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('product')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Quantity
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="hidden" name="old_quantity[]" value="{{ $product->quantity }}" id="old_quantity_{{ $product->id }}">
                                                                            <input type="text" name="quantity[]"
                                                                                class="form-control pin-code"
                                                                                min="0"
                                                                                id="quantity_{{ $product->id }}"
                                                                                maxlength="6"
                                                                                data-id="{{ $product->id }}"
                                                                                onchange="getQuantityPrice({{ $product->id }})"
                                                                                placeholder="Enter Quantity" max="{{ isset($product->leadProduct) ? $product->leadProduct->stock : 0 }}"
                                                                                value="{{ $product->quantity }}"
                                                                                data-index="{{ $ind }}">
                                                                            <div class="error_span" id="total_stock_error_{{$product->id}}"></div>
                                                                            <div id="total_stock_div_{{$ind}}">Available Stock:- <span id="total_stock_{{$ind}}">{{ isset($product->leadProduct) ? $product->leadProduct->stock : 0 }}</span></div>
                                                                            <input type="hidden" id="total_product_stock_{{$product->id}}" name="total_product_stock_{{$product->id}}" value="{{ isset($product->leadProduct) ? $product->leadProduct->stock : 0 }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Amount
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6"> 
                                                                            <input type="text" name="amount[]" onkeydown="return disableKeyPress(event)"
                                                                                class="form-control amount_check"
                                                                                value="{{ $product->amount }}"
                                                                                id="amount_{{ $product->id }}"
                                                                                data-id="{{ $product->id }}"
                                                                                placeholder="Enter Amount"></input>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Tax
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="number" name="tax[]"
                                                                                class="form-control"
                                                                                placeholder="Enter Tax"
                                                                                placeholder="Enter Tax"
                                                                                value="{{ $product->tax }}"
                                                                                id="tax_{{ $product->id }}"
                                                                                data-id="{{ $product->id }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center g-3 mt-6">
                                                                    <div class="col-md-2"></div>
                                                                    <div class="col-md-4"></div>
                                                                    <div class="col-md-1 col-xl-1 mt-0"></div>
                                                                    <div class="col-md-2 col-xl-1 mt-0"></div>
                                                                    <div class="col-md-3 text-end mt-3">
                                                                        <button type="button"
                                                                            class="btn btn-danger product_address"
                                                                            data-id="{{ $product->id }}">Remove</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        @if (count($leadProduct) == 0)
                                                            <input type="hidden" name="cust_product_id[]"
                                                                value="">
                                                            <input type="hidden" name="per_product_price[]"
                                                                value="" id="per_product_price_0">
                                                            <div class="product-block" id="add_more_product_option_0">
                                                                <div class="row align-items-center g-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4">
                                                                            <label class="form-label mb-0">Product
                                                                                Name</label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <select name="product_id[]"
                                                                                class="form-control product_id"
                                                                                onchange="getProductPrice(0)"
                                                                                id="product_id_0" data-id="0">
                                                                                <option value="" disabled>Select Product
                                                                                </option>
                                                                                @foreach ($products as $product)
                                                                                    <option value="{{ $product->id }}">
                                                                                        {{ $product->name }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('product')
                                                                                <div class="text-danger">{{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Quantity
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="hidden" name="old_quantity[]" value="0" id="old_quantity_0">
                                                                            <input type="text" name="quantity[]"
                                                                                class="form-control quantity"
                                                                                min="0" data-id="0" 
                                                                                id="quantity_0"
                                                                                placeholder="Enter Quantity"
                                                                                onchange="getQuantityPrice(0)"
                                                                                value="" data-index="0">
                                                                                <div class="error_span" id="total_stock_error_0"></div>
                                                                            <div id="total_stock_div_0">Available Stock:- <span id="total_stock_0">0</span></div>
                                                                            <input type="hidden" id="total_product_stock_0" name="total_product_stock_0" value="0">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Amount
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="text" name="amount[]" onkeydown="return disableKeyPress(event)"
                                                                                class="form-control amount amount_check"
                                                                                data-id="0" id="amount_0" value="0"
                                                                                placeholder="Enter Amount"></input>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"><label
                                                                                class="form-label mb-0">Tax
                                                                            </label>
                                                                        </div>
                                                                        <div class="col-md-8 col-xl-6">
                                                                            <input type="number" name="tax[]"
                                                                                class="form-control" data-id="0"
                                                                                placeholder="Enter Tax" id="tax_0">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row align-items-center gap-3 mt-6">
                                                                    <div class="col-md-6 row align-items-center g-3">
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-8 col-xl-6"></div>
                                                                    </div>

                                                                    <div class="col-md-6 row align-items-center g-3">

                                                                        <div class="col-md-12 text-end mt-3">
                                                                            <button type="button" class="btn btn-danger"
                                                                                onclick="removeProduct(0)"
                                                                                data-id="0">Remove</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div id="product-container"></div>
                                                    <div class="row mt-3">
                                                        <div class="col-md-8">
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <button type="button"
                                                                class="btn btn-primary add-product">Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Total Amount
                                            </label></div>
                                        <div class="col-md-8 col-xl-6" id="total_amount_div">
                                            {{ $lead->total_amount }}
                                        </div>
                                        <input type="hidden" name="total_amount" class="form-control"
                                            value="{{ $lead->total_amount }}" id="total_amount">
                                    </div>

                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Discount Amount
                                            </label></div>
                                        <div class="col-md-8 col-xl-8">
                                            <input type="text" name="discount_amount" class="form-control" onchange="discountAmount()"
                                                placeholder="Enter Discount Amount"
                                                value="{{ $lead->discount_amount ?? 0 }}" id="discount_amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Collectable Amount
                                            </label></div>
                                        <div class="col-md-8 col-xl-8" id="collact_amount_div">
                                            {{ $lead->collectable_amount }}
                                        </div>
                                        <input type="hidden" name="collect_amount" class="form-control"
                                            placeholder="Enter Collectable Amount"
                                            value="{{ $lead->collectable_amount }}" id="collect_amount">
                                    </div>
                                    <div class="col-md-6 row align-items-center g-3">
                                        <div class="col-md-4"><label class="form-label mb-0">Payment Type</label></div>
                                        <div class="col-md-8 col-xl-6">
                                            <select name="payment_type" class="form-control" id="payment_type">
                                                <option value="" disabled>Select Payment Type</option>
                                                <option value="cod" {{$lead->payment_type == 'cod' ? 'selected' : ''}}>COD</option>
                                                <option value="prepaid" {{$lead->payment_type == 'prepaid' ? 'selected' : ''}}>Prepaid</option>
                                            </select>
                                        </div>
                                        
                                    </div>

                                </div>
                                <hr class="my-6">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('leads.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-dark"
                                        id="saveSubmitButton">Save</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    <hr class="my-6">
                    <div class="row align-items-center g-3 mt-6">
                        <h5>
                            <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button" data-bs-animation="false"
                                aria-expanded="false" aria-controls="collapseExample">
                                <h4>Remarks</h4>
                            </a>
                        </h5>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="lead_id" id="lead_id" value="{{ $lead->id }}">
                        <div class="row align-items-center mt-6">
                            <div class="col-md-12">
                                <div class="label mb-2">Title <span class="text-danger">*</span></div>
                                <select name="title" id="remark_title" class ="form-control">
                                    <option value="" disabled>Select Title</option>
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
                        <div class="row align-items-center mt-6 call_back_div d-none">
                            <div class="col-md-12">
                                <div class="label mb-2">Next Follow Up Date <span class="text-danger">*</span></div>
                                <input type="datetime-local" name="next_call_date_time" id="next_call_date_time"
                                    class="form-control" placeholder="Enter Title" value="{{ date('Y-m-d\TH:i') }}"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                <span class="next_call_date_time_error text-danger"></span>
                            </div>
                        </div>
                        <div class="row align-items-center mt-6 other_title_div d-none">
                            <div class="col-md-12">
                                <div class="label mb-2">Other Title <span class="text-danger">*</span></div>
                                <input type="text" name="other_title" id="other_title" class="form-control"
                                    placeholder="Enter Title">
                                <span class="other_title_error text-danger"></span>
                            </div>
                        </div>
                        <div class="row align-items-center mt-6">
                            <div class="col-md-12">
                                <div class="label mb-2">Remark <span class="text-danger">*</span></div>
                                <textarea name="remark" class="lead-remark form-control" id="text_remark" rows="2"
                                    placeholder="Enter Remark"></textarea>
                                <span class="error-remark text-danger"></span>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn-comment btn btn-primary btn-icon mt-3 fs-12"
                            id="btn-comment" style="float:right;">
                            Add Remark
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card remarks-right-side">
                        <div class="card-header" style="background-color: #5D8E47">
                            <div class="card-title text-white">
                                Remarks Timeline {{ $latestRemark ? ' - ' . $latestRemark->title : '' }}
                            </div>
                        </div>

                        <div class="cus-card-body" style="background-color: #dfdfdf;">
                            <div class="timeline">
                                @foreach ($leadRemarks as $remark)
                                    <div class="timeline-inner">
                                        @php
                                            $icon = 'fa-regular fa-message';
                                            if ($remark->remark_icon != '') {
                                                $icon = $remark->remark_icon;
                                            }
                                        @endphp
                                        <i class="{{ $icon }} bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header-outer">
                                                <h3 class="timeline-header">
                                                    {{ $remark->title }}
                                                    @if ($remark->remark != '')
                                                        -
                                                        <span class="remark-description-title"
                                                            data-id="{{ $remark->id }}">{{ $remark->remark }}</span>
                                                    @endif
                                                </h3>
                                                <div class="time d-flex">
                                                    <div class="time-data">
                                                        @php $name = ""; @endphp
                                                        @if (isset($remark->userDetail))
                                                            @php $name = "Name:- ".$remark->userDetail->name; @endphp
                                                            @php $nameData = $remark->userDetail->name; @endphp
                                                            @if (isset($remark->userDetail->departmentDetail))
                                                                @php $name .= "<br> Department:- ".$remark->userDetail->departmentDetail->name; @endphp
                                                            @endif
                                                        @endif
                                                        @if ($name != '')
                                                            <h3 data-bs-placement="top" 
                                                                data-bs-toggle="tooltip" data-bs-html="true" data-bs-animation="false"
                                                                title="{{ $name }}">
                                                                {{ $nameData }}</h3>
                                                        @endif
                                                    </div>
                                                    <div class="time_date">
                                                        <div>
                                                            <i class="fas fa-calendar-alt"></i>
                                                            <span>
                                                                {{ Utility::convertDMYFormat($remark->created_at) }}
                                                            </span>
                                                        </div> 
                                                        <div>
                                                            <i class="fas fa-clock"></i>
                                                            <span>
                                                                {{ Utility::convertHIAFormat($remark->created_at) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <div class="modal fade" id="addOtherOptionModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        data-bs-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4">Edit Disease</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="updateForm">
                    @csrf
                    <input type="hidden" name="disease_id" id="disease_id">
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0 other_field_text"> <span
                                            class="text_danger_require">*</span></label></div>
                                <div class="col-md-8 col-xl-8">
                                    <input type="text" name="name" class="form-control" id="other_detail_name"
                                        placeholder="Enter Other Name">
                                    <span style="color: red;" id="other_detail_name_error"></span>
                                </div>
                                <input type="hidden" name="field_type" id="field_type">
                                <input type="hidden" name="index_id" id="index_id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-dark" id="update_button_disease"
                            onclick="addOtherData()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            $('[data-bs-toggle="tooltip"]').tooltip()
            $('.timeline .timeline-inner:last .fa-message').addClass('blink_me');
            var windowHeight = $(window).outerHeight();
    $('.remarks-right-side .cus-card-body').css('height', windowHeight + 'px');
        })
        $('#disease').select2();
        $('#for_whom').select2();
        $('.product_id').select2();
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
            var nextCall = $('#next_call_date_time').val();

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
                    lead_id: lead_id,
                    next_call: nextCall,
                },
                success: function(data) {
                    toastr.success(data.message);

                    $('#btn-comment').html('Add Remarks');
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
                    var remarks = "";
                    if (remark.remark) {
                        remarks = " - " + remark.remark
                    }
                    var userName = "";
                    var nameData = "";
                    if (remark.user_detail) {
                        userName = remark.user_detail.name;
                        nameData += "Name:- " + remark.user_detail.name;
                        if (remark.user_detail.department_detail) {
                            nameData += "<br> Department:- " + remark.user_detail.department_detail
                                .name;
                        }
                    }
                    var nameData1 = "";
                    if (userName !== "") {
                        nameData1 += `<p class="text-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-animation="false"
                                                            data-bs-html="true" title="${nameData}">
                    ${userName}</p>`
                    }
                    var html = `<div>
                                        <i class="fa-regular fa-message bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header-outer">
                                                <h3 class="timeline-header">
                                                ${title} ${remarks}
                                                </h3>
                                                <div class="time d-flex">
                                                    <div class="time-tooltip">
                                                    ${nameData1}
                                                    </div>
                                                    <div class="time_date">
                                                        <i class="fas fa-clock"></i>
                                                        <span>${date}</span>
                                                    </div>
                                                </div>
                                            </div>
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
                                <option value="" disabled>Select Option</option>
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
        var addressIndex = "{{ count($custAddresses) }}"; // Initialize index for unique ID management

        $('.add-address').on('click', function() {
            addressIndex++;

            var newField = `
                <div class="address-block" id="add_more_address_${addressIndex}" data-index="${addressIndex}">
                    <input type="hidden" name="address_id[]" value="">
                    <hr class="my-6">
                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Address Type </label></div>
                            <div class="col-md-8 col-xl-6">
                                <select name="add_type[]" class="form-control">
                                    <option value="" disabled>Select Type</option>
                                    <option value="office_add">Office Address</option>
                                    <option value="shop_add">Shop Address</option>
                                    <option value="home_add" selected>Home Address</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Pin Code </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="pin_code[]" min="0" class="form-control pin-code" placeholder="Enter Pin Code" data-index="${addressIndex}" maxlength="6">
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
                            <div class="col-md-7 col-xl-5">
                                <select name="village[]" class="form-control village" id="village_id_${addressIndex}" onchange="addOtherOptions(${addressIndex},'village')">
                                    <option value="" disabled>Select Village</option>
                                </select>
                            </div>
                        <div class="col-md-1">
                                            <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                                onclick="copyVillage(${addressIndex},'village')"
                                                data-index="${addressIndex}"><i class="fa-solid fa-copy"></i></a>
                                        </div>
                                        </div>
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">Post Office (B.O/S.O/H.O) </label></div>
                            <div class="col-md-7 col-xl-5">
                                <select name="office_name[]" class="form-control office" id="office_id_${addressIndex}" onchange="addOtherOptions(${addressIndex},'office')">
                                    <option value="" disabled>Select Office</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                            <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                                onclick="copyVillage(${addressIndex},'office')"
                                                data-index="${addressIndex}"><i class="fa-solid fa-copy"></i></a>
                                        </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                                                                            <div class="col-md-4"><label class="form-label mb-0">Taluka / Tehsil</label></div>
                                                                            <div class="col-md-7 col-xl-7">
                                                                                <input type="text" name="taluka_tehsil[]" id="taluka_tehsil_${addressIndex}"
                                                                                    value="{{ old('taluka_tehsil.0') }}" class="form-control">
                                                                                @error('taluka_tehsil.0')
                                                                                    <div class="text-danger">{{ $message }}</div>
                                                                                @enderror
                                                                            </div>
                                                                            <div class="col-md-1">
                                                                                <a href="javascript:void(0)" class="btn btn-primary copy-detail"
                                                                                    onclick="copyVillage(${addressIndex},'taluka')" data-index="${addressIndex}"><i
                                                                                        class="fa-solid fa-copy"></i></a>
                                                                            </div>
                                                                        </div>
                        
                    </div>

                    <div class="row align-items-center gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">City / District</label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_city[]" class="form-control city" id="dist_city_${addressIndex}">
                            </div>
                        </div>
                        <div class="col-md-6 row align-items-center g-3">
                            <div class="col-md-4"><label class="form-label mb-0">State </label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="dist_state[]" class="form-control state" id="dist_state_${addressIndex}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-end mt-3">
                        <button type="button" class="btn btn-danger remove-address" onclick="removeAddress(${addressIndex})" data-id="${addressIndex}">Remove</button>
                    </div>
                </div>`;

            $('#address-container').append(newField);
        });
        function removeAddress(id) {
            $(`#add_more_address_${id}`).remove();
        }
        $(document).on('keypress', '.pin-code', function(e) {
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        })
        $(document).on('keyup', '.pin-code', function(e) {
            var index = $(this).data('index');
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
                        villageHtml += `<option value="other">Other</option>`
                        officeHtml += `<option value="other">Other</option>`
                        $('#village_id_' + index).html(villageHtml);
                        $('#village_id_' + index).select2();

                        $('#office_id_' + index).html(officeHtml);
                        $('#office_id_' + index).select2();
                        if (data && data.length > 0) {
                            $('#taluka_tehsil_' + index).val(data[0].sub_distname);
                            $('#dist_state_' + index).val(data[0].state_name);
                            $('#dist_city_' + index).val(data[0].district_name);
                        }
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
            $('#alt_number_exist_error_' + number).text('')
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
            $('.call_back_div').addClass('d-none');
            if (title == "Call Back/Follow Up") {
                $('.call_back_div').removeClass('d-none');
            }
        })
        $(document).on('click', '.delete_address', function() {
            var address_id = $(this).data("id"); // Get the address ID from the button

            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Address?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('address-delete') }}",
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: address_id
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            $(this).closest('.address-block')
                                .remove();
                        }.bind(this),
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        });
        $('#disease').on('change', function() {
            $('.other_disease_div').addClass('d-none');
            if ($(this).val() == 'other') {
                $('.other_disease_div').removeClass('d-none');
            }
        })
        var product = "{{ count($leadProduct) }}";
        $('.add-product').on('click', function(e) {
            product = parseInt(product) + 1;
            var html = `<div id="add_more_product_option_${product}"><div class="row align-items-center g-3 mt-6">
                <input type="hidden" name="cust_product_id[]" value="">
                <input type="hidden" name="per_product_price[]" value="" id="per_product_price_${product}">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label mb-0">Product Name</label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <select name="product_id[]" class="form-control product_id" id="product_id_${product}" data-id="${product}" onchange="getProductPrice(${product})">
                                                            <option value="" disabled>Select Product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">
                                                                    {{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('product')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Quantity
                                                        </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="hidden" name="old_quantity[]" value="0" id="old_quantity_${product}">
                                                        <input type="text" name="quantity[]"
                                                            class="form-control quantity" min="0"  id="quantity_${product}" data-id="${product}" onchange="getQuantityPrice(${product})"
                                                            placeholder="Enter Quantity" value=""
                                                            data-index="${product}">
                                                        <div class="error_span" id="total_stock_error_${product}"></div>
                                                        <div id="total_stock_div_${product}">Available Stock:- <span id="total_stock_${product}">0</span></div>
                                                        <input type="hidden" id="total_product_stock_${product}" name="total_product_stock_${product}" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Amount
                                                        </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="text" name="amount[]" class="form-control amount_check" data-id="${product}" onkeydown="return disableKeyPress(event)"
                                                            id="amount_${product}" placeholder="Enter Amount"></input>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    <div class="col-md-4"><label class="form-label mb-0">Tax
                                                        </label>
                                                    </div>
                                                    <div class="col-md-8 col-xl-6">
                                                        <input type="number" name="tax[]" class="form-control" data-id="${product}" placeholder="Enter Tax"
                                                            id="tax_${product}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center gap-3 mt-6">
                                                <div class="col-md-6 row align-items-center g-3">
                                                </div>

                                                <div class="col-md-6 row align-items-center g-3">
                                                    
                                                    <div class="col-md-12 text-end mt-3">
                                                        <button type="button" class="btn btn-danger product_remove_button" onclick="removeProduct(${product})"
                                                            data-id="${product}">Remove</button>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>`;
            $('#product-container').append(html);
        })

        function removeProduct(index) {
            var quantity = $('#quantity_' + index).val();
            var amount = $('#amount_' + index).val();
            var totalAmount = $('#total_amount').val();
            var lastAmount = parseInt(totalAmount) - parseInt(quantity) * parseInt(amount);
            $('#collact_amount_div').html(lastAmount);
            $('#collect_amount').val(lastAmount);
            $('#total_amount_div').html(lastAmount);
            $('#total_amount').val(lastAmount);
            $('#add_more_product_option_' + index).remove();
        }

        function getProductPrice(id) {
            var product_id = $(`#product_id_${id}`).val();
            $.ajax({
                type: "GET",
                url: "{{ route('get-product') }}",
                data: {
                    id: product_id
                },
                success: function(data) {
                    var res = data.data
                    var existAmount = $('#amount_' + id).val();
                    var totalAmount = $('#total_amount').val();
                    if(existAmount == ""){
                        existAmount = 0;
                    }
                    var finalAmount = parseInt(totalAmount) - parseInt(existAmount) + parseInt(res.price);
                    $('#total_amount_div').html(finalAmount);
                    $('#total_amount').val(finalAmount);
                    
                    $('#amount_' + id).val(res.price);
                    $('#quantity_' + id).val(1);
                    $('#quantity_' + id).attr('max',res.stock);
                    $('#per_product_price_' + id).val(res.price);
                    $('#total_stock_' + id).html(res.stock);
                    $('#total_product_stock_' + id).val(res.stock);
                    $(`#old_quantity_${id}`).val(1);

                    var discount = $('#discount_amount').val();
                    $('#collect_amount').val(finalAmount - parseInt(discount));
                    $('#collact_amount_div').html(finalAmount- parseInt(discount));
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message);
                }
            })
        }

        function getQuantityPrice(id) {
            var quantity = $(`#quantity_${id}`).val();
            var stock = $(`#total_product_stock_${id}`).val();
            $(`#total_stock_error_${id}`).html('');
            if(parseInt(quantity) > parseInt(stock)){
                $(`#total_stock_error_${id}`).html('Stock Not Available.');
            }
            var oldQuantity = $(`#old_quantity_${id}`).val();
            var amount = $(`#per_product_price_${id}`).val();
            var totalAmount = $('#total_amount').val();
            var productAmount = $('#amount_' + id).val();
            var calc = parseInt(oldQuantity) * parseInt(productAmount);
            if(totalAmount != 0){
                var amount = parseInt(totalAmount) - parseInt(calc); 
            } else {
                var amount = parseInt(productAmount);
            }
            var finalAmount = parseInt(amount) + parseInt(quantity) * parseInt(productAmount);
            
            $('#total_amount').val(parseInt(amount) + (parseInt(quantity) * parseInt(productAmount)));
            $('#total_amount_div').html(parseInt(amount) + (parseInt(quantity) * parseInt(productAmount)))
            $(`#old_quantity_${id}`).val(quantity);

            var discount = $('#discount_amount').val();
            $('#collect_amount').val(finalAmount - parseInt(discount));
            $('#collact_amount_div').html(finalAmount- parseInt(discount));
                    
        }
        $('.product_address').on('click', function(e) {
            var id = $(this).data('id');
            swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this product?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submitBtn').prop('disabled', true);
                    $('#submitBtn').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                    );
                    $.ajax({
                        url: "{{ route('remove-cust-product') }}",
                        type: "GET",
                        data: {
                            id: id,
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

        })

        function addOtherOptions(index, type) {
            if (type == 'village') {
                var input = $('#village_id');
                if (index !== "") {
                    input = $(`#village_id_${index}`);
                }
                if (input.val() == "other") {
                    $('#other_village').val('yes')
                    if (index !== "") {
                        $(`#other_village_${index}`).val('yes')
                    }
                }
            } else if (type == "office") {
                var input = $('#office_id');
                if (index !== "") {
                    input = $(`#office_id_${index}`);
                }
                if (input.val() == "other") {
                    $('#other_office').val('yes')
                    if (index !== "") {
                        $(`#other_office_${index}`).val('yes')
                    }
                }
            }  else if(type == "medicine") {
                var input = $('#for_whom');
            } else if(type == "language"){
                var input = $('#customer_language');
            }

            if (input.val() == "other") {
                $('#index_id').val(index);
                $('#field_type').val(type);
                $('.other_field_text').html(`Other ${type} <span class="text_danger_require">*</span>`);
                $('#other_detail_name').attr('placeholder', `Enter Other ${type}`);
                $('.modal-title').html(`Add Other ${type}`);
                if (type == "office") {
                    $('.other_field_text').html(`Other Post ${type} <span class="text_danger_require">*</span>`);
                    $('.modal-title').html(`Add Other Post ${type}`);
                    $('#other_detail_name').attr('placeholder', `Enter Other Post ${type}`);
                }
                $('#addOtherOptionModal').modal('show')
            }
        }

        function addOtherData() {
            $('#other_detail_name_error').html('');
            var other_detail_name = $('#other_detail_name').val();
            if (other_detail_name == "") {
                $('#other_detail_name_error').html(`Please Enter Other ${$('#field_type').val()}`);
                return false;
            }
            var index_id = $('#index_id').val();
            var field_type = $('#field_type').val();
            if (field_type == 'village') {
                if (index_id == "") {
                    $('#village_id').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
                } else {
                    $(`#village_id_${index_id}`).html(
                        `<option value="${other_detail_name}" selected>${other_detail_name}</option>`);

                }
            } else if (field_type == "office") {
                if (index_id == "") {
                    $('#office_id').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
                } else {
                    $(`#office_id_${index_id}`).html(
                        `<option value="${other_detail_name}" selected>${other_detail_name}</option>`);

                }
            } else if(field_type == "medicine") {
                $('#for_whom').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
            } else if(field_type == "language"){
                $('#customer_language').html(`<option value="${other_detail_name}" selected>${other_detail_name}</option>`);
            }
            $('#other_detail_name_error').html('');
            $('#other_detail_name').val('');
            $('#addOtherOptionModal').modal('hide')
        }

        function copyVillage(index, type) {
            if (type == "village") {
                var input = document.getElementById('village_id');
                if (index !== "") {
                    input = document.getElementById(`village_id_${index}`);
                }
            } else {
                var input = document.getElementById('office_id');
                if (index !== "") {
                    input = document.getElementById(`office_id_${index}`);
                }
            }
            if (type == "taluka") {
                var taluka = document.getElementById('taluka_tehsil');
                if (index !== "") {
                    taluka = document.getElementById(`taluka_tehsil_${index}`);
                }
                taluka.select();
                taluka.setSelectionRange(0, 99999);
                document.execCommand('copy');
            } else {
                var selectedValue = input.options[input.selectedIndex].value;
                var tempInput = document.createElement("input");
                tempInput.value = selectedValue;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                document.body.removeChild(tempInput);
            }
        }
        $(document).ready(function() {
            $('.address-accordion .accordion-list > li > .answer').hide();
            $('.address-accordion .accordion-list > li').click(function() {
                if ($(this).hasClass("active")) {
                    $(this).removeClass("active").find(".answer").slideUp();
                } else {
                    $(".address-accordion .accordion-list > li.active .answer").slideUp();
                    $(".address-accordion .accordion-list > li.active").removeClass("active");
                    $(this).addClass("active").find(".answer").slideDown();
                }
                return false;
            });
            $('.address-accordion .accordion-list > li > .answer').click(function(event) {
                event.stopPropagation();
            });
            $('.product-accordion .accordion-list > li > .answer').hide();
            $('.product-accordion .accordion-list > li').click(function() {
                if ($(this).hasClass("active")) {
                    $(this).removeClass("active").find(".answer").slideUp();
                } else {
                    $(".product-accordion .accordion-list > li.active .answer").slideUp();
                    $(".product-accordion .accordion-list > li.active").removeClass("active");
                    $(this).addClass("active").find(".answer").slideDown();
                }
                return false;
            });
            $('.product-accordion .accordion-list > li > .answer').click(function(event) {
                event.stopPropagation();
            });
        });
        function disableKeyPress(event){
            return false;
        }
        function discountAmount(){
            var discount = $('#discount_amount').val();
            var amount = $('#total_amount').val();
            var total = amount - discount;
            $('#collect_amount').val(total);
            $('#collact_amount_div').html(total);
        }
        
    </script>
@endsection
