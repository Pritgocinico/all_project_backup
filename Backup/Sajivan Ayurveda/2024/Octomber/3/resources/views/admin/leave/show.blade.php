@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}   {{ isset($leave->userDetail) ? " - ".$leave->userDetail->name : '-' }}</h1>
                    </div>
                </div>
            </div>
            <hr class="my-6">
            <div id="user_detail" class="d-block">
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">User Name</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ isset($leave->userDetail) ? $leave->userDetail->name : '-' }} -
                        {{ isset($leave->userDetail) && isset($leave->userDetail->departmentDetail) ? $leave->userDetail->departmentDetail->name : '' }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Leave Type</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ ucwords(str_replace('_', ' ', $leave->leave_type)) }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Leave From</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y') }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Leave To</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y') }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Leave Reason</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $leave->reason }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Leave Feature</label></div>
                    <div class="col-md-4 col-xl-4">
                        @php
                            $feature = 'Half Day';
                            if ($leave->leave_feature == 1) {
                                $feature = 'Full Day';
                            }
                        @endphp
                        {{ $feature }}
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Total Leave Day</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $leave->total_leave_day }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Leave Status</label></div>
                    <div class="col-md-4 col-xl-4">
                        @php
                            $status = 'Pending';
                            $class = 'warning';
                            if ($leave->leave_status == 1) {
                                $status = 'Approved';
                                $class = 'success';
                            } elseif ($leave->leave_status == 2) {
                                $status = 'Rejected';
                                $class = 'danger';
                            }
                        @endphp
                        <span class="badge bg-{{ $class }} w-120">{{ $status }}</span>
                    </div>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    @if ($leave->leave_status == 2)
                        <div class="col-md-2"><label class="form-label mb-0">Leave Reject Reson</label></div>
                        <div class="col-md-4 col-xl-4">
                            {{ $leave->reject_reason ?? '-' }}
                        </div>
                    @endif
                    @if ($leave->attachment)
                    <div class="col-md-2"><label class="form-label mb-0">Leave Attachment</label></div>
                    <div class="col-md-4 col-xl-4">
                            <a href="{{ asset('storage/' . $leave->attachment) }}" target="_blank"
                                class="btn btn-primary">View</a>
                    </div>
                    @endif
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Created At</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ Utility::convertDmyAMPMFormat($leave->created_at) }}
                    </div>
                </div>                 
            </div>
            
        </main>
    </div>
@endsection
@section('script')
    <script>
    </script>
@endsection
