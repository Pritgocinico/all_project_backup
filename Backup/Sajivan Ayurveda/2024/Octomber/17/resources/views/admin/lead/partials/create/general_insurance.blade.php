<div id="general_insurance_div" style="display: @if ($type !== 2) none @endif;">
    <hr class="my-6">
    <h4>General Insurance</h4>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Policy No</label></div>
        <div class="col-md-4">
            <input type="text" name="policy_no" class="form-control" value="" placeholder="Enter Policy No">
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Policy Type </label></div>
        <div class="col-md-4">
            <input class="form-check-input policy_type" type="radio" name="policy_type" id="policy_type"
                value="individual" required checked>
            <label Individual="form-check-label" for="disabled">
                Individual
            </label>

            <input class="form-check-input policy_type" type="radio" name="policy_type" id="policy_type"
                value="corporate">
            <label class="form-check-label" for="view">
                Corporate
            </label>
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6" id="corporate_detail_div" style="display: none">
        <div class="col-md-2"><label class="form-label mb-0">GST Certificate</label></div>
        <div class="col-md-4">
            <input type="file" name="gst_certificate" class="form-control" value=""
                placeholder="Enter KYC Number">
        </div>
        <div class="col-md-2"><label class="form-label mb-0">No Of Business</label></div>
        <div class="col-md-4">
            <input type="text" name="no_of_business" class="form-control" placeholder="Enter No Of Business">
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Previous Policy</label></div>
        <div class="col-md-4">
            <input type="text" name="previous_policy" class="form-control" value=""
                placeholder="Enter Previous Policy Number">
        </div>
        <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
        <div class="col-md-4">
            <input type="text" name="sum_insurance" class="form-control" placeholder="Enter Sum Insurance">
        </div>
    </div>
    <div id="healthDiv" style="display: @if ($type !== 2) none @endif;">
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Health Policy type</label>
            </div>
            <div class="col-md-4 col-xl-4">
                <select name="health_policy_type" class="form-control" id="health_policy_type">
                    <option value="">Select Health Policy type</option>
                    <option value="new">New</option>
                    <option value="renewal">Renewal</option>
                </select>
                @error('health_policy_type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Insurer</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="insurer" class="form-control" value="">
                @error('insurer')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Insured</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="insured" class="form-control">
                @error('insured')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="product" class="form-control">
                @error('product')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Sub Product</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="sub_product" class="form-control">
                @error('sub_product')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}"
                    class="form-control">
                @error('received_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Sum Insurance</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="number" name="sum_insurance" class="form-control">
                @error('sum_insurance')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="row align-item-center g-3 mt-6" id="health_policy_renew_div" style="display: none">
            <div class="col-md-2"><label class="form-label mb-0">Old Policy Attachment</label>
            </div>
            <div class="col-md-4">
                <input type="file" name="old_policy_attachment[]" class="form-control" multiple>
            </div>
            <div class="col-md-2"><label class="form-label mb-0">Claim History</label></div>
            <div class="col-md-4">
                <input type="file" name="claim_history[]" class="form-control" multiple>
            </div>
        </div>
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Insurer DOB</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="insurer_dob" value="{{ old('insurer_dob', date('Y-m-d')) }}"
                    class="form-control">
                @error('insurer_dob')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div id="motorDiv" style="display: none;">
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Vehicle</label></div>
            <div class="col-md-4 col-xl-4">
                <select name="vehicle" class="form-control">
                    <option value="">Select Vehicle</option>
                    <option value="Two Wheeler">Two Wheeler</option>
                    <option value="Four Wheeler">Four Wheeler</option>
                    <option value="Commercial Vehicle">Commercial Vehicle</option>
                    <option value="TP Policy Only">TP Policy Only</option>
                </select>
                @error('vehicle')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="col-md-2"><label class="form-label mb-0">Client</label></div>
                <div class="col-md-4 col-xl-4">
                    <select name="client" class="form-control">
                        <option value="">Client</option>
                    </select>
                    @error('client')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Received Date</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}"
                    class="form-control">
                @error('received_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Vehicle Make</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="vehicle_make" class="form-control">
                @error('vehicle_make')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Vehicle Model</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="vehicle_model" class="form-control">
                @error('vehicle_model')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">RC Copy</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="file" name="rc_copy" class="form-control" placeholder="Upload RC Copy">
                @error('rc_copy')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div id="smeDiv" style="display: none;">
        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">SME Insurance</label></div>
            <div class="col-md-4 col-xl-4">
                <select name="sme_insurance" class="form-control" id="sme_insurance_type">
                    <option value="">Select</option>
                    <option value="fire&burglary">Fire & Burglary</option>
                    <option value="marine">Marine</option>
                    <option value="wc">WC</option>
                </select>
                @error('sme_insurance')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- <div class="col-md-2"><label class="form-label mb-0">Marine</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="marine" class="form-control">
                    @error('marine')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}
        </div>
        <div id="sme_insurance_fire" style="display: none;">
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Insurance Cover</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="insurance_cover" class="form-control">
                    @error('insurance_cover')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Insurance
                        Value</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="insurance_value" class="form-control">
                    @error('insurance_value')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Hypothication</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="hypothication" class="form-control">
                    @error('hypothication')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Nature Of
                        Business</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="nature_of_business" class="form-control">
                    @error('nature_of_business')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Claim History</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="fire_claim_history" class="form-control">
                    @error('fire_claim_history')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div id="sme_insurance_marine" style="display: none;">
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Nature Of
                        Business</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="nature_of_business" class="form-control">
                    @error('nature_of_business')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Good Description</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <textarea name="good_description" class="form-control" placeholder="Enter Description"></textarea>
                    @error('good_description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Invoice Copy</label>
                </div>
                <div class="col-md-4">
                    <input type="file" name="invoice_copy" class="form-control">
                </div>
            </div>
        </div>

        <div id="sme_insurance_wc" style="display: none;">
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">No. Of Workers</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="workers_number" class="form-control">
                    @error('workers_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-2"><label class="form-label mb-0">Salary Range</label>
                </div>
                <div class="col-md-4 col-xl-4">
                    <input type="number" name="salary_range" class="form-control">
                    @error('salary_range')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                <div class="col-md-4">
                    <textarea name="designation" class="form-control" placeholder="Enter Designation"></textarea>
                    @error('designation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            {{-- <div class="col-md-2"><label class="form-label mb-0">WC</label></div>
                <div class="col-md-4 col-xl-4">
                    <input type="text" name="wc" class="form-control">
                    @error('wc')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div> --}}

            <div class="col-md-2"><label class="form-label mb-0">GMC</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="gmc" class="form-control">
                @error('gmc')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">GPA</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="gpa" class="form-control">
                @error('wc')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2"><label class="form-label mb-0">Professional
                    Indemnity</label>
            </div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="professional_indemnity" class="form-control">
                @error('gmc')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row align-items-center g-3 mt-6">
            <div class="col-md-2"><label class="form-label mb-0">Other Insurance</label></div>
            <div class="col-md-4 col-xl-4">
                <input type="text" name="other_insurance" class="form-control">
                @error('other_insurance')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="row align-items-center g-3 mt-6">
        <div class="col-md-2"><label class="form-label mb-0">Lead Attachment</label></div>
        <div class="col-md-4">
            <input type="file" name="policy_attachment[]" class="form-control" multiple>
        </div>
    </div>
</div>
