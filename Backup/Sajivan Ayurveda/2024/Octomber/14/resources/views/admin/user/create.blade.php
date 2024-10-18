@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill  scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Employee</h1>
                    </div>
                </div>
            </div>

            <div
                class="flex-fill scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
                        @method('POST')
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

                        <div class="row align-items-center gap-3 mt-6">

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Name <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Personal Email Id <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="personal_email" class="form-control"
                                        placeholder="Enter Personal Email" value="{{ old('personal_email') }}">
                                    @error('personal_email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Email <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Password <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter Password" value="{{ old('password') }}">
                                        <div class="input-group-append login_button_password">
                                            <span class="input-group-text" id="password_eye_button"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Joining Date <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="date" name="joining_date" class="form-control"
                                        value="{{ old('joining_date') ?? date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                                    @error('joining_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Phone Number <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="text" name="phone_number" minlength="10" maxlength="10"
                                        onkeypress="return isNumberKey(event)" class="form-control"
                                        placeholder="Enter Phone Number" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Role <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="role" class="form-control" id="role">
                                        <option value="">Select Role</option>
                                        @foreach ($roleList as $role)
                                            <option value="{{ $role->id }}"
                                                @if (old('role') == $role->id) selected @endif>
                                                {{ Str::ucfirst($role->name) }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Date Of Birth <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="date" name="date_of_birth" class="form-control"
                                        value="{{ old('date_of_birth') ?? date('Y-m-d') }}" id="date_of_birth">
                                    @error('date_of_birth')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if (old('office_calling_number'))
                            @php $cnt = count(old('office_calling_number')); @endphp
                            @for ($i = 0; $i < $cnt; $i++)
                                <div class="row align-items-center gap-3 mt-6">
                                    <div class="col-md-6 row align-items-center g-3 ">
                                        <div class="col-md-4">
                                            <label class="form-label mb-0">Office Calling Number</label>
                                        </div>
                                        <div class="col-md-8 col-xl-6">
                                            <input type="text" name="office_calling_number[]" class="form-control"
                                                onkeypress="return isNumberKey(event)" minlength="10" maxlength="10"
                                                value="{{ old('office_calling_number.' . $i) }}"
                                                placeholder="Enter Office Calling Number">
                                            @error('office_calling_number.' . $i)
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-2 row align-items-center g-3">
                                        <div class="col-md-6">
                                            <!-- Empty label space for alignment -->
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <button type="button" class="btn btn-success add-office-number">Add</button>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        @else
                            <div class="row align-items-center gap-3 mt-6">
                                <div class="col-md-6 row align-items-center g-3 ">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Office Calling Number</label>
                                    </div>
                                    <div class="col-md-8 col-xl-6">
                                        <input type="text" name="office_calling_number[]" class="form-control"
                                            onkeypress="return isNumberKey(event)" minlength="10" maxlength="10"
                                            value="" placeholder="Enter Office Calling Number">
                                        @error('office_calling_number.*')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-2 row align-items-center g-3">
                                    <div class="col-md-6">
                                        <!-- Empty label space for alignment -->
                                    </div>
                                    <div class="col-md-6 col-xl-6">
                                        <button type="button" class="btn btn-success add-office-number">Add</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="office-number-container"></div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Department <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="department" class="form-control" id="department"
                                        onchange="getDesignation()">
                                        <option value="">Select Department</option>
                                        @foreach ($departmentList as $department)
                                            <option value="{{ $department->id }}"
                                                @if (old('department') == $department->id) selected @endif>
                                                {{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Designation</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="designation" class="form-control" id="designation"></select>
                                    @error('designation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div
                            class="row align-items-center gap-3 mt-6 is_manager_div_select {{ old('role') == '1' ? 'd-none' : '' }}">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Is Manager<span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="is_manager" class="form-control" id="is_manager"
                                        onchange="menagerMember()">
                                        <option value="">Select Is Manager</option>
                                        <option value="yes" @if (old('is_manager') == 'yes') selected @endif>Yes
                                        </option>
                                        <option value="no" @if (old('is_manager') == 'no') selected @endif>No
                                        </option>
                                    </select>
                                    @error('is_manager')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div
                                class="col-md-6 row align-items-center g-3 team_member_select {{ old('is_manager') == 'no' ? 'd-none' : '' }}">
                                <div class="col-md-4"><label class="form-label mb-0">Team Member</label></div>
                                <div class="col-md-8 col-xl-6">
                                    <select name="user_id[]" class="form-control" id="user_id" multiple>
                                        <option value="">Select Team Member</option>
                                        @foreach ($userList as $user)
                                            <option value="{{ $user->id }}"
                                                {{ in_array($user->id, old('user_id') ?? []) ? 'selected' : '' }}>
                                                {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center gap-3 mt-6" id="salary-input" style="display: none;">
                            <div class="col-md-6 row align-items-center g-3 ">
                                <div class="col-md-4"><label class="form-label mb-0">Salary <span
                                            class="text-danger">*</span></label></div>
                                <div class="col-md-8 col-xl-6">
                                    <input type="number" name="employee_salary" class="form-control"
                                        placeholder="Enter Salary" value="{{ old('employee_salary') }}">
                                    @error('employee_salary')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6 ">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Aadhar Card Image</label></div>
                                <div class="col-md-3 col-xl-2">
                                    <div class="hstack gap-2 ">
                                        <label for="aadhar_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="aadhar_card" id="aadhar_card"
                                                class="visually-hidden aadhar_card" value="{{ old('aadhar_card') }}">
                                        </label>
                                    </div>
                                    <div id="aadhar_card_preview"></div>
                                    @error('aadhar_card')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Pan Card</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ">
                                        <label for="pan_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="pan_card" id="pan_card"
                                                class="visually-hidden pan_card" value="{{ old('pan_card') }}">
                                        </label>
                                    </div>
                                    <div id="pan_card_preview"></div>
                                    @error('pan_card')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Address Proof</label></div>
                                <div class="col-md-3 col-xl-2 ">
                                    <div class="hstack gap-2 ">
                                        <label for="address_proof" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="address_proof" id="address_proof"
                                                class="visually-hidden address_proof" value="{{ old('address_proof') }}">
                                        </label>
                                    </div>
                                    <div class="w-100" id="address_proof_preview"></div>
                                    @error('address_proof')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Passport Size Photo</label></div>
                                <div class="col-md-3 col-xl-2">
                                    <div class="hstack gap-2 ">
                                        <label for="passport_photo" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="passport_photo" id="passport_photo"
                                                class="visually-hidden passport_photo"
                                                value="{{ old('passport_photo') }}">
                                        </label>
                                    </div>
                                    <div id="passport_photo_preview"></div>
                                    @error('passport_photo')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center gap-3 mt-6">
                            <div class="col-md-6 row align-items-center g-3">
                                <div class="col-md-4"><label class="form-label mb-0">Last Marksheet</label></div>
                                <div class="col-md-4 col-xl-4">
                                    <div class="hstack gap-2 ">
                                        <label for="marksheet" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="marksheet" id="marksheet"
                                                class="visually-hidden marksheet" value="{{ old('marksheet') }}">
                                        </label>
                                    </div>
                                    <div id="marksheet_preview"></div>
                                    @error('marksheet')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 row align-items-center g-3 mt-0 ">
                                <div class="col-md-4 "><label class="form-label mb-0">Bank Details</label></div>
                                <div class="col-md-3 col-xl-2">
                                    <div class="hstack gap-2 ">
                                        <label for="bank_details" class="btn btn-sm btn-neutral"><span>Upload</span>
                                            <input type="file" name="bank_details" id="bank_details"
                                                class="visually-hidden bank_details" value="{{ old('bank_details') }}">
                                        </label>
                                    </div>
                                    <div id="bank_details_preview"></div>
                                    @error('bank_details')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 d-none" id="deduction_input_div">
                            <hr class="my-6">
                            @php $id = 0; @endphp
                            <div id="deduction-container">
                                @if (old('deduction_type'))
                                    <?php $cnt = count(old('deduction_type')); ?>
                                    @for ($i = 0; $i < $cnt; $i++)
                                        <input type="hidden" name="deduction_id[]"
                                            value="{{ old('deduction_id.' . $i) }}">
                                        <div class="row align-items-center gap-3 mt-3">
                                            <div class="col-md-6 row align-items-center g-3 ">
                                                <div class="col-md-4"><label class="form-label mb-0">Deduction
                                                        Type</label></div>
                                                <<div class="col-md-8 col-xl-6">
                                                    <input type="text" name="deduction_type[]" class="form-control"
                                                        placeholder="Enter Type"
                                                        value="{{ old('deduction_type.' . $i) }}">
                                                    @error('deduction_type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 row align-items-center g-3 ">
                                            <div class="col-md-4"><label class="form-label mb-0">Deduction
                                                    Amount</label></div>
                                            <div class="col-md-6 col-xl-6">
                                                <input type="number" name="amount[]" class="form-control"
                                                    placeholder="Enter Amount" value="{{ old('amount.' . $i) }}">
                                                @error('amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="deduction_status[]" id="status"
                                                        {{ old('deduction_status.' . $i) ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    id="remove-deduction"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                            </div>
                            @endfor
                            @endif

                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-primary" id="add-deduction"><i
                                        class="fa fa-plus"></i> Add Deduction</button>
                            </div>
                        </div>
            </div>
            <hr class="my-6">
            <div class="d-flex justify-content-start gap-2">
                <a href="{{ route('user.index') }}" class="btn btn-sm btn-neutral">Cancel</a>
                <button type="submit" class="btn btn-sm btn-dark" id="saveSubmitButton">Save</button>
            </div>
            </form>
        </main>
    </div>
    </main>
    </div>
@endsection
@section('script')
    <script>
        $('#user_id').select2();

        function isNumberKey(evt) {
            if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {
                event.preventDefault();
            }
        }
        $('form').on('submit', function(e) {
            $('#saveSubmitButton').html('<i class="fa fa-spinner fa-spin"></i>');
        });
        $(".login_button_password").click(function() {
            var input = $("#password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#password_eye_button').html('<i class="fa fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#password_eye_button').html('<i class="fa fa-eye"></i>');
            }
        });
        $(".confirm_button_password").click(function() {
            var input = $("#confirm_password");
            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $('#confirm_eye_button').html('<i class="fa fa-eye-slash"></i>');
            } else {
                input.attr("type", "password");
                $('#confirm_eye_button').html('<i class="fa fa-eye"></i>');
            }
        });
        $(document).ready(function(e) {
            $('#department').select2();
            getDesignation();

            $('.add-office-number').on('click', function() {
                var newField = `
                    <div class="row align-items-center office_num_row gap-3 mt-6">
                        <div class="col-md-6 row align-items-center g-3 ">
                            <div class="col-md-4"><label class="form-label mb-0">Office Calling Number</label></div>
                            <div class="col-md-8 col-xl-6">
                                <input type="text" name="office_calling_number[]" class="form-control" onkeypress="return isNumberKey(event)" minlength="10" maxlength="10" value="" placeholder="Enter Office Calling Number">
                                @error('office_calling_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2 row align-items-center g-3">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <button type="button" class="btn btn-danger remove-office-num">Remove</button>
                            </div>
                        </div>
                    </div>
                `;
                $('#office-number-container').append(newField);
            });

            // Remove the alternate number field
            $(document).on('click', '.remove-office-num', function() {
                $(this).closest('.row.office_num_row').remove();
            });
        })
        $('.aadhar_card').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#aadhar_card_preview').html(resultString);

        })
        $('.pan_card').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#pan_card_preview').html(resultString);

        })
        $('.user_agreement').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#user_agreement_preview').html(resultString);

        })
        $('.address_proof').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#address_proof_preview').html(resultString);

        })
        $('.passport_photo').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#passport_photo_preview').html(resultString);

        })
        $('.marksheet').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#marksheet_preview').html(resultString);

        })
        $('.bank_details').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#bank_details_preview').html(resultString);

        })

        $(document).ready(function() {
            function toggleSalaryInput() {
                var roleID = $('#role').val();
                if (roleID && roleID != 1) {
                    $('#salary-input').show();
                    $('#deduction_input_div').removeClass('d-none');
                    $('.is_manager_div_select').removeClass('d-none');
                } else {
                    $('#salary-input').hide();
                    $('#deduction_input_div').addClass('d-none');
                    $('.is_manager_div_select').addClass('d-none');
                }
            }

            // Check on page load
            toggleSalaryInput();

            // Check on role change
            $('#role').on('change', function() {
                toggleSalaryInput();
            });
        });

        $(document).ready(function() {
            var oldDesignation = "{{ old('designation') }}";
            getDesignation(oldDesignation);
        });

        function getDesignation(oldDesignation) {
            var department = $('#department').val();
            $.ajax({
                method: 'get',
                url: "{{ route('designation-by-department') }}",
                data: {
                    department: department,
                },
                success: function(res) {
                    var html = "<option value=>Select Designation</option>";
                    $.each(res, function(i, v) {
                        var select = "";
                        if (oldDesignation == v.id) {
                            select = "selected";
                        }
                        html += "<option value='" + v.id + "'" + select + ">" + v.name + "</option>"
                    })
                    $('#designation').html("")
                    $('#designation').html(html)
                    $('#designation').select2();
                }
            });
        }
        $('#add-deduction').click(function() {
            var newDeduction = `
            <input type="hidden" name="deduction_id[]" value="add_more_deduction">
                   
                         <div class="row align-items-center gap-3 ">
                            <div class="col-md-6 row align-items-center g-3 ">
                        <div class="col-md-4"><label class="form-label mb-0">Deduction Type</label></div>
                        <div class="col-md-8 col-xl-6">
                            <input type="text" name="deduction_type[]" class="form-control" placeholder="Enter Type" value="">
                            @error('deduction_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        </div>
                         <div class="col-md-6 row align-items-center g-3 ">
                        <div class="col-md-4"><label class="form-label mb-0">Deduction Amount</label></div>
                        <div class="col-md-6 col-xl-6">
                            <input type="number" name="amount[]" class="form-control" placeholder="Enter Amount" value="">
                            @error('amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-1 text-end">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="deduction_status[]" id="status" checked>
                                            </div>
                                        </div>
                        <div class="col-md-1 col-xl-1 text-end">
                            <button type="button" class="btn btn-danger remove-deduction"><i class="fa fa-trash-can"></i></button>
                        </div>
                       
                    </div>`;
            $('#deduction-container').append(newDeduction);
        });

        // Event delegation to handle dynamically added delete buttons
        $(document).on('click', '.remove-deduction', function() {
            $(this).closest('.deduction-row').remove();
        });
        $('.remove_document').on('click', function(e) {
            var type = $(this).data('type');
            var id = "{{ $user->id ?? '' }}";
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('remove.card.users') }}",
                        type: 'get',
                        data: {
                            type: type,
                            id: id,
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            location.reload();
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        })
        $('#is_manager').on('change', function() {
            $('.team_member_select').addClass('d-none');
            if ($(this).val() == "yes") {
                $('.team_member_select').removeClass('d-none');
            }
        })
    </script>
@endsection
