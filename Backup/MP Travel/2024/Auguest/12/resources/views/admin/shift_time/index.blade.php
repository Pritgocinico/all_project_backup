@extends('admin.partials.header', ['active' => 'Shift Time'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Shift Detail</h1>
                    <div class="hstack gap-2 ms-auto">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-user-table-filter="search" id="search_data"
                                class="form-control w-250px ps-13" onkeyup="shiftTimeAjaxList()"
                                placeholder="Search Lead" />
                        </div>
                        @if (collect($accesses)->where('menu_id', '17')->first()->status == 2)
                        @if(Auth()->user()->role_id !== "2")
                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                Export</a>
                        @endif
                            <a href="#" class="btn btn-sm btn-primary" data-bs-target="#depositLiquidityModal"
                                data-bs-toggle="modal"><i class="bi bi-plus-lg me-2"></i>
                                New Shift</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div id="shift_time_ajax"></div>
            </div>
        </main>
        <div class="modal fade" id="depositLiquidityModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Shift</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Shift Name</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="text" name="shift_name" class="form-control" id="shift_name"
                                            placeholder="Enter Shift Name">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Shift Start Time</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="time" name="shift_start_time" class="form-control"
                                            id="shift_start_time" placeholder="Enter Shift Start Time">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-3"><label class="form-label mb-0">Shift End Time</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <input type="time" name="shift_end_time" class="form-control" id="shift_end_time"
                                            placeholder="Enter Shift End Time">
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-3"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-9 col-xl-9">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Lead</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="" class="form" action="#">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-2"><label class="form-label mb-0">Export Format:</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <select name="format" class="form-control" id="export_format">
                                            <option value="">Select Format</option>
                                            <option value="excel">Excel</option>
                                            <option value="pdf">PDF</option>
                                            <option value="csv">CSV</option>
                                        </select>
                                        <span id="format_error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="row align-items-center g-3 mt-6 d-none" id="status_div">
                                    <div class="col-md-2"><label class="form-label mb-0">Status</label></div>
                                    <div class="col-md-10 col-xl-10">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="status">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtn" onclick="exportCSV()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e){
            shiftTimeAjaxList(1);
        })
        function shiftTimeAjaxList(page){
            $.ajax({
                'method': 'get',
                'url': "{{ route('shift-ajax') }}",
                data: {
                    page: page,
                    search: $('#search_data').val(),
                },
                success: function(res) {
                    $('#shift_time_ajax').html('');
                    $('#shift_time_ajax').html(res);
                },
            });
        }
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            shiftTimeAjaxList(page);
        });
        function exportCSV() {
            var exportFile = "{{ route('shift-export') }}";
            var format = $('#export_format').val();
            var search = $('#search_data').val();
            $('#format_error').html('');
            if(format.trim() == ""){
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv','excel','pdf'];
            if(!allowValues.includes(format)){
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }
        function submitForm() {
            $.ajax({
                url: "{{ route('shift-time.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
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
                            shiftTimeAjaxList(1)
                        }
                    });
                },
                error: function(error) {
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
            });
        }

        function editShift(id) {
            $.ajax({
                url: "{{ route('shift-time.edit', ['shift_time' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#shift_name').val(data.data.shift_name);
                    $('#shift_start_time').val(moment(data.data.shift_start_time, "HH:mm:ss").format("HH:mm"));
                    $('#shift_end_time').val(moment(data.data.shift_end_time, "HH:mm:ss").format("HH:mm"));
                    $('#status').prop('checked', data.data.status);
                    if (data.data.status == 0) {
                        $('#status').prop('checked', "");
                    }
                    $('#status_div').removeClass('d-none');
                    $('#depositLiquidityModalLabel').text('Edit Shift');
                    $('#submitBtn').text('Update');
                    $('#submitBtn').attr('onclick', "updateShift(" + id + ")");
                    $('#depositLiquidityModal').modal('show');
                },
                error: function(error) {
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
            });
        }

        function deleteShift(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Shift?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('shift-time.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
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
                                    shiftTimeAjaxList(1)
                                }
                            });
                        },
                        error: function(error) {
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
                    });
                }
            });
        }

        function updateShift(id) {
            $.ajax({
                url: "{{ route('shift-time.update', ['shift_time' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#addForm').trigger("reset");
                    $('#depositLiquidityModal').modal('hide');
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
                            shiftTimeAjaxList(1)
                        }
                    });
                },
                error: function(error) {
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
            });
        }
    </script>
@endsection
