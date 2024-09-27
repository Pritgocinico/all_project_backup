@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('employees.update', $employee->id) }}" method="post"
                        enctype="multipart/form-data" onsubmit="return updateEmployeeValidate()">
                        <input type="hidden" name="id" value="{{ $employee->id }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Shift Type</label>
                                <select name="shift_type" id="shift_type" class="form-select">
                                    <option value="">Select Shift Type</option>
                                    <option value="1" @if ($employee->shift_type == "1") selected @endif>Shift - 1 (08:30 AM to 05:30 PM)</option>
                                    <option value="2" @if ($employee->shift_type == "2") selected @endif>Shift - 2 (09:30 AM to 06:30 PM)</option>
                                </select>
                                <span class="text-danger"
                                    id="shift_type_error">{{ $errors->getBag('default')->first('shift_type') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <input type="text" class="form-control mb-3 mb-lg-0" placeholder="Enter Name"
                                    name="name" value="{{ $employee->name }}" id="name">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('name') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                    value="{{ $employee->email }}" id="email">
                                <span class="text-danger"
                                    id="email_error">{{ $errors->getBag('default')->first('email') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Phone Number</label>
                                <input type="text" class="form-control" placeholder="Enter Phone Number"
                                    name="phone_number" value="{{ $employee->phone_number }}" id="phone_number">
                                <span class="text-danger"
                                    id="phone_number_error">{{ $errors->getBag('default')->first('phone_number') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6 employeePassword">
                                <label class="required fs-6 fw-semibold mb-2">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password"
                                    name="password" value="{{ old('password') }}" id="password">
                                <i class="far fa-eye" id="togglePasswordEmployee" onclick="passwordHideShow()"></i>
                                <span class="text-danger"
                                id="password_error">{{ $errors->getBag('default')->first('password') }}</span>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">Aadhar Card</label>
                                <input type="file" class="form-control" name="aadhar_card" value="test.jpg"
                                    id="aadhar_card">
                                <span class="text-danger"
                                    id="aadhar_card_error">{{ $errors->getBag('default')->first('aadhar_card') }}</span>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url('/') }}/public/assets/media/{{ $employee->aadhar_card }}" download>
                                    <img src="{{ url('/') }}/public/assets/media/png_images/file.png" width="60px">
                                </a>
                            </div>
                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">Pan Card</label>
                                <input type="file" class="form-control" name="pan_card" value="{{ old('pan_card') }}"
                                    id="pan_card">
                                <span class="text-danger"
                                    id="pan_card_error">{{ $errors->getBag('default')->first('pan_card') }}</span>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url('/') }}/public/assets/media/{{ $employee->pan_card }}" download>
                                    <img src="{{ url('/') }}/public/assets/media/png_images/file.png"
                                        width="60px">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">Qualification</label>
                                <input type="file" class="form-control" name="qualification"
                                    value="{{ old('qualification') }}" id="qualification">
                                <span class="text-danger"
                                    id="qualification_error">{{ $errors->getBag('default')->first('qualification') }}</span>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ url('/') }}/public/assets/media/{{ $employee->qualification }}"
                                    download>
                                    <img src="{{ url('/') }}/public/assets/media/png_images/file.png"
                                        width="60px">
                                </a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Role</label>
                                <select name="role" id="role" class="form-select">
                                    <option value="">Select Role</option>
                                    @foreach ($roleList as $role)
                                        <option value="{{ $role->id }}"
                                            @if ($employee->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger"
                                    id="role_error">{{ $errors->getBag('default')->first('role') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">System Code (For System Engineer)</label>
                                <input type="text" class="form-control" name="system_code"
                                    placeholder="Enter System Code" value="{{ $employee->system_code }}"
                                    id="system_code">
                                <span class="text-danger"
                                    id="system_code_error">{{ $errors->getBag('default')->first('system_code') }}</span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="fs-6 fw-semibold mb-2">Is Manager</label>
                                    <select name="is_manager" id="is_manager" class="form-select">
                                        <option value="">Select Option</option>
                                        <option value="yes"
                                            @if ($employee->is_manager == '1') {{ 'selected' }} @endif>Yes</option>
                                        <option value="no"
                                            @if ($employee->is_manager == '0  ') {{ 'selected' }} @endif>No</option>
                                    </select>
                                </div>
                                       <div class="col-md-6" id="department_check_div"
                                    style="display: @if ($employee->role_id !== '2') none @endif">
                                    <label class="fs-6 fw-semibold mb-2">Department</label></br>
                                    <select name="department[]" id="department" class="form-select">
                                        <option value="">Select Department</option>
                                        @foreach ($departmentList as $key => $department)
                                            <option value="{{ $department->id }}"
                                                @if (in_array($department->id, $employee['department'])) {{ 'selected' }} @endif>
                                                {{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">Employee Salary</label>
                                    <input type="number" name="employee_salary" id="employee_salary"
                                        class="form-control" value="{{ $employee->employee_salary }}"
                                        placeholder="Enter Employee Salary" />
                                    <span class="text-danger"
                                        id="employee_salary_error">{{ $errors->getBag('default')->first('employee_salary') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">Joining Date</label></br>
                                    <input type="date" name="join_date" id="join_date" class="form-control"
                                        value="{{ $employee->join_date }}" />
                                    <span class="text-danger"
                                        id="join_date_error">{{ $errors->getBag('default')->first('join_date') }}</span>
                                </div>
                            </div>
                            <div class="row">
                                @php $class=6; $show="d-none" @endphp
                                @if($employee->join_agreement !== null)
                                    @php $class=4; $show="" @endphp
                                @endif
                                <div class="col-md-{{$class}}">
                                    <label class="fs-6 fw-semibold mb-2">Agreement</label>
                                    <input type="file" name="join_agreement" id="join_agreement" onchange="checkFileType()"
                                        class="form-control">
                                    <span class="text-danger"
                                        id="join_agreement_error">{{ $errors->getBag('default')->first('join_agreement') }}</span>
                                </div>
                                <div class="col-md-2 {{$show}}">
                                    <a href="{{ url('/') }}/public/assets/media/{{ $employee->join_agreement }}"
                                        download>
                                        <img src="{{ url('/') }}/public/assets/media/png_images/file.png"
                                            width="60px">
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <label for="Status" class="required fs-6 fw-semibold mb-2">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status"
                                            id="flexSwitchCheckChecked"
                                            @if ($employee->status == 1) {{ 'checked' }} @endif>
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" name="Update" value="Update" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('page')
    <script src="{{ asset('public\assets\js\custom\admin\employee.js') }}?{{ time() }}"></script>
@endsection
