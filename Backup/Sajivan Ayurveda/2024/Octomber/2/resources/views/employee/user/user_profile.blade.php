@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('storage/' . $user->profile_image) }}" class="img-fluid rounded-top-start-4"
                            style="height: 100px !important" alt="...">
                    </div>
                    <div class="col">
                        <h1 class="ls-tight">{{ $user->name }} -
                            {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }}</h1>
                    </div>
                    
                </div>
            </div>
            <div id="user_detail" class="d-block">
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $user->name }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $user->phone_number }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($user->roleDetail) ? $user->roleDetail->name : '-' }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Department</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($user->departmentDetail) ? $user->departmentDetail->name : '-' }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Designation</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($user->designationDetail) ? $user->designationDetail->name : '-' }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Shift Detail</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($user->shiftDetail) ? $user->shiftDetail->shift_name . ' (' . Utility::convertHIAFormat($user->shiftDetail->shift_start_time) . ' - ' . Utility::convertHIAFormat($user->shiftDetail->shift_end_time) . ')' : '-' }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Aadhar Card Image</label></div>
                    <div class="col-md-4 col-xl-4">
                        @if ($user->aadhar_card)
                            <a href="{{ asset('storage/' . $user->aadhar_card) }}" target="_blank"
                                class="btn btn-primary">View</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Pan Card</label></div>
                    <div class="col-md-4 col-xl-4">
                        @if ($user->pan_card)
                            <a href="{{ asset('storage/' . $user->pan_card) }}" target="_blank"
                                class="btn btn-primary">View</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Agreement</label></div>
                    <div class="col-md-4 col-xl-4">
                        @if ($user->user_agreement)
                            <a href="{{ asset('storage/' . $user->user_agreement) }}" target="_blank"
                                class="btn btn-primary">View</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Basics</label></div>
                    <div class="col-md-4 col-xl-4">
                        &#x20B9; {{ number_format($user->basic_amount ?? 0, 2) }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">HRA</label></div>
                    <div class="col-md-4 col-xl-4">
                        &#x20B9; {{ number_format($user->hra_amount ?? 0, 2) }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Allowances</label></div>
                    <div class="col-md-4 col-xl-4">
                        &#x20B9; {{ number_format($user->allowance_amount ?? 0, 2) }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Petrol</label></div>
                    <div class="col-md-4 col-xl-4">
                        &#x20B9; {{ number_format($user->petrol_amount ?? 0, 2) }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    @foreach ($user->deductionDetail as $deduct)
                        <div class="col-md-2"><label class="form-label mb-0">{{$deduct->deduction_type}}</label></div>
                        <div class="col-md-4 col-xl-4">
                            &#x20B9; {{ number_format($deduct->amount ?? 0, 2) }}
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection
