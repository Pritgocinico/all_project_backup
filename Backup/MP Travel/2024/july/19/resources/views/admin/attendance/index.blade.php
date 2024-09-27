@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Attendance List</h1>
                </div>
                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-3">
                        <label class="form-label mb-0">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-0">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-3 mt-8">
                        <button type="button" class="btn btn-sm btn-primary filterDate">Search</button>
                    </div>
                </div>
            </div>
            
            <div id="attendance-list">
                @include('admin.attendance.table', ['allAttendanceLists' => $allAttendanceLists])
            </div>
        </main>
        
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            $('.filterDate').on('click', function(){
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                $.ajax({
                    url: '{{ route("attendance.index") }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        $('#attendance-list').html(response.view);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
