@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        {{-- <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Daily Attendance List</h1>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Login Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyAttendanceLists as $key=>$today_Attendance)
                            <tr>
                                <td>{{ $dailyAttendanceLists->firstItem() + $key }}</td>
                                <td>{{ $today_Attendance->employee->name }}</td>
                                <td> 
                                    @php
                                        $text = 'Present';
                                        $color = 'success';
                                        if ($today_Attendance->status == 0) {
                                            $color = 'danger';
                                            $text = 'Absent';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span> 
                                </td>
                                <td>{{Utility::convertDmyAMPMFormat($today_Attendance->login_time)}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $dailyAttendanceLists->links() }}
                </div>
            </div>
        </main> --}}
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Daily Attendance List</h1>
                </div>
            </div>
            <div>
                <table class="table table-hover table-striped table-sm table-nowrap table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Login Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyAttendanceListsPaginated as $key => $today_Attendance)
                            <tr>
                                <td>{{ $dailyAttendanceListsPaginated->firstItem() + $key }}</td>
                                <td>
                                    @if (Auth()->user()->role_id == 1)
                                        <a
                                            href="{{ route('user.show', $today_Attendance->employee->id) }}">{{ $today_Attendance->employee->name }}</a>
                                    @elseif (Auth()->user()->id == $today_Attendance->employee->id)
                                        <a
                                            href="{{ route('user.show', $today_Attendance->employee->id) }}">{{ $today_Attendance->employee->name }}</a>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $text = 'Present';
                                        $color = 'success';
                                        if ($today_Attendance->status == 0) {
                                            $color = 'danger';
                                            $text = 'Absent';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                                </td>
                                <td>{{ $today_Attendance->login_time ? Utility::convertDmyAMPMFormat($today_Attendance->login_time) : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data Available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end me-2 mt-2">
                    {{ $dailyAttendanceListsPaginated->links() }}
                </div>
            </div>
        </main>


    </div>
@endsection
@section('script')
    <script></script>
@endsection
