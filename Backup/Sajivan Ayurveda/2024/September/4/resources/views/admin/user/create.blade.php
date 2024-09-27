@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">Create Employee</h1>
                    </div>
                </div>
            </div>
            <div
                class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
                <main class="container-fluid px-6 pb-10">
                    <form action="{{ route('user.store') }}" enctype="multipart/form-data" method="POST">
                        @method("POST")
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="name" class="form-control" placeholder="Enter Name"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="email" class="form-control" placeholder="Enter Email"
                                    value="{{ old('email') }}">
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Password</label></div>
                            <div class="col-md-4 col-xl-4">
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
                            <div class="col-md-2"><label class="form-label mb-0">Joining Date</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="date" name="joining_date" class="form-control" value="{{old('joining_date')??date('Y-m-d')}}" max="{{date('Y-m-d')}}">
                                @error('joining_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="text" name="phone_number" class="form-control"
                                    placeholder="Enter Phone Number"
                                    value="{{ old('phone_number') }}">
                                @error('phone_number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                            <div class="col-md-4 col-xl-4">
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
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="department" class="form-control" id="department" onchange="getDesignation()">
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
                            <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="designation" class="form-control" id="designation"></select>
                                @error('designation')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6" id="salary-input" style="display: none;">
                            <div class="col-md-2"><label class="form-label mb-0">Salary</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="employee_salary" class="form-control"
                                    placeholder="Enter Salary"
                                    value="{{ old('employee_salary') }}">
                                @error('employee_salary')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Shift</label></div>
                            <div class="col-md-4 col-xl-4">
                                <select name="shift" class="form-control" id="shift">
                                    <option value="">Select Shift</option>
                                    @foreach ($shiftList as $shift)
                                        <option value="{{ $shift->id }}"
                                            @if (old('shift') == $shift->id) selected @endif>
                                            {{ $shift->shift_name . " ( ". Utility::convertHIAFormat($shift->shift_start_time) . ' - ' . Utility::convertHIAFormat($shift->shift_end_time) ." )" }}</option>
                                    @endforeach
                                </select>
                                @error('shift')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">

                            <div class="col-md-2"><label class="form-label mb-0">Profile Image</label></div>
                            <div class="col-md-4 col-xl-4">
                                <div class="hstack gap-2 ms-5">
                                    <label for="file_upload" class="btn btn-sm btn-neutral"><span>Upload</span>
                                        <input type="file" name="profile_image" id="file_upload"
                                            class="visually-hidden profile_image"
                                            value="{{ old('profile_image') }}">
                                    </label>
                                </div>
                                <div id="profile_image_preview"></div>
                                @error('profile_image')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Image</label></div>
                            <div class="col-md-2 col-xl-2">
                                <div class="hstack gap-2 ms-5">
                                    <label for="aadhar_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                        <input type="file" name="aadhar_card" id="aadhar_card"
                                            class="visually-hidden aadhar_card"
                                            value="{{ old('aadhar_card') }}">
                                    </label>
                                </div>
                                <div id="aadhar_card_preview"></div>
                                @error('aadhar_card')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                            <div class="col-md-2 col-xl-2">
                                <div class="hstack gap-2 ms-5">
                                    <label for="pan_card" class="btn btn-sm btn-neutral"><span>Upload</span>
                                        <input type="file" name="pan_card" id="pan_card"
                                            class="visually-hidden pan_card"
                                            value="{{ old('pan_card') }}">
                                    </label>
                                </div>
                                <div id="pan_card_preview"></div>
                                @error('pan_card')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Agreement</label></div>
                            <div class="col-md-2 col-xl-2">
                                <div class="hstack gap-2 ms-5">
                                    <label for="user_agreement" class="btn btn-sm btn-neutral"><span>Upload</span>
                                        <input type="file" name="user_agreement" id="user_agreement"
                                            class="visually-hidden user_agreement"
                                            value="{{ old('user_agreement') }}">
                                    </label>
                                </div>
                                <div id="user_agreement_preview"></div>
                                @error('user_agreement')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Basics</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="basic_amount" class="form-control"
                                placeholder="Enter Basic Amount"
                                value="{{ old('basic_amount')??0}}">
                                @error('basic_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">HRA</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="hra_amount" class="form-control"
                                    placeholder="Enter HRA Amount"
                                    value="{{ old('hra_amount')??0 }}">
                                @error('hra_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Allowances</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="allowance_amount" class="form-control"
                                    placeholder="Enter Allowances Amount"
                                    value="{{ old('allowance_amount')??0 }}">
                                @error('allowance_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Petrol</label></div>
                            <div class="col-md-4 col-xl-4">
                                <input type="number" name="petrol_amount" class="form-control"
                                    placeholder="Enter Petrol Amount"
                                    value="{{ old('petrol_amount')??0 }}">
                                @error('petrol_amount')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="container mt-6 d-none" id="deduction_input_div">
                            <hr class="my-6">
                            @php $id = 0; @endphp
                            <div id="deduction-container">
                                @if (old('deduction_type'))
                                    <?php $cnt = count(old('deduction_type')); ?>
                                    @for ($i = 0; $i < $cnt; $i++)
                                        <input type="hidden" name="deduction_id[]"
                                            value="{{ old('deduction_id.'.$i) }}">
                                        <div class="row align-items-center g-3 mt-3">
                                            <div class="col-md-2"><label class="form-label mb-0">Deduction
                                                    Type</label></div>
                                            <div class="col-md-3 col-xl-3">
                                                <input type="text" name="deduction_type[]" class="form-control"
                                                    placeholder="Enter Type"
                                                    value="{{ old('deduction_type.' . $i) }}">
                                                @error('deduction_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2"><label class="form-label mb-0">Deduction
                                                    Amount</label></div>
                                            <div class="col-md-3 col-xl-3">
                                                <input type="number" name="amount[]" class="form-control"
                                                    placeholder="Enter Amount"
                                                    value="{{ old('amount.' . $i) }}">
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
        $('form').on('submit',function(e){
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
        })
        $('.profile_image').on('change', function() {
            var fileName = $(this).val();
            var ext = fileName.split('.').pop();
            let substringToRemove = "C:\\fakepath\\";
            let resultString = fileName.replace(substringToRemove, "");
            $('#profile_image_preview').html(resultString);

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

        $(document).ready(function() {
            function toggleSalaryInput() {
                var roleID = $('#role').val();
                if (roleID && roleID != 1) {
                    $('#salary-input').show();
                    $('#deduction_input_div').removeClass('d-none');
                } else {
                    $('#salary-input').hide();
                    $('#deduction_input_div').addClass('d-none');
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
                    <div class="row align-items-center g-3 mt-3 deduction-row">
                        <div class="col-md-2"><label class="form-label mb-0">Deduction Type</label></div>
                        <div class="col-md-3 col-xl-3">
                            <input type="text" name="deduction_type[]" class="form-control" placeholder="Enter Type" value="">
                            @error('deduction_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2"><label class="form-label mb-0">Deduction Amount</label></div>
                        <div class="col-md-3 col-xl-3">
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
        $('.remove_document').on('click',function(e){
            var type = $(this).data('type');
            var id = "{{$user->id ?? ""}}";
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
                        url: "{{ route('remove.card.users') }}" ,
                        type: 'get',
                        data: {
                            type: type,
                            id:id,
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
    </script>
@endsection
