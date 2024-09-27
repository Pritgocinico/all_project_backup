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
                        <button type="button" class="btn btn-sm btn-primary filterDate"
                            onclick="dateFilter()">Search</button>
                    </div>
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div id="attendance-list">
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            dateFilter();
        });
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            dateFilter(page);
        });
        function dateFilter(page) {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $.ajax({
                url: '{{ route('attendance.ajax') }}',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    page: page,
                },
                success: function(response) {
                    $('#attendance-list').html("")
                    $('#attendance-list').html(response);
                },
                error: function(xhr) {
                }
            });
        }
    </script>
@endsection
