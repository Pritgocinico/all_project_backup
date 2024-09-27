@extends('layouts.main_layout')
@section('section')
<div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content  flex-column-fluid ">


            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-fluid ">
                <div id="kt_app_content" class="flex-column-fluid ">
                    <div id="kt_app_content_container" class="container-fluid ">
                        <div class="card-header custom-responsive-div d-flex justify-content-between align-items-center flex-column-1199">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                    <input type="text" data-kt-user-table-filter="search" id="search_data" onkeyup="holidayAjaxList(1)" class="form-control w-250px ps-13" placeholder="Search Holiday" />
                                </div>
                            </div>

                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#add_holiday_modal" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                            <i class="ki-outline ki-plus fs-2"></i> Add Holiday
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body py-4 table-responsive" id="holiday_ajax_list_table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
</div>
</div>

</body>
<div class="modal fade" id="add_holiday_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday Date</label>
                    <input class="form-control" type="date" name="holiday_date" id="holiday_date" placeholder="Enter Holiday Date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                    <span id="holiday_date_error" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday name</label>
                    <input class="form-control" type="text" name="holiday_name" id="holiday_name" placeholder="Enter Holiday Name">
                    <span id="holiday_name_error" class="text-danger"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addHoliday()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_holiday_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="id" name="id">
                    <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday Date</label>
                    <input class="form-control" type="date" name="edit_holiday_date" id="edit_holiday_date" placeholder="Enter Holiday Date" value="" min="{{ date('Y-m-d') }}">
                    <span id="edit_holiday_date_error" class="text-danger"></span>
                </div>
                <div class="row mt-2">
                    <label for="Status" class="required fs-6 fw-semibold mb-2">Holiday name</label>
                    <input class="form-control" type="text" name="edit_holiday_name" id="edit_holiday_name" placeholder="Enter Holiday Name">
                    <span id="edit_holiday_name_error" class="text-danger"></span>
                </div>
                <div class="row mt-2">
                    <label for="Status" class="fs-6 fw-semibold mb-2">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateHoliday()">Update</button>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('page')
    <script>
        $(document).ready(function(e) {
            holidayAjaxList(1)
        });

        function holidayAjaxList(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('holiday-list-ajax') }}",
                data: {
                    page: page,
                    search: search,
                },
                success: function(res) {
                    $('#holiday_ajax_list_table').html('');
                    $('#holiday_ajax_list_table').html(res);
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
            });
        }

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            holidayAjaxList(page);
        });

        function addHoliday() {
            var date = $('#holiday_date').val();
            var name = $('#holiday_name').val();

            var cnt = 0;

            $('#holiday_date_error').html('');
            $('#holiday_name_error').html('');

            if (date.trim() == "") {
                $('#holiday_date_error').html('Please Select Holiday Date.');
                cnt = 1;
            }
            if (name.trim() == "") {
                $('#holiday_name_error').html('Please Enter Holiday Name.');
                cnt = 1;
            }

            if (cnt == 1) {
                return false;
            }
            $.ajax({
                'method': 'post',
                'url': "{{ route('holiday-list.store') }}",
                data: {
                    holiday_date: date,
                    holiday_name: name,
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    toastr.success(res.message);
                    $("#add_holiday_modal").modal('hide');
                    $('#holiday_date').val("{{ date('Y-m-d') }}");
                    $('#holiday_name').val('');
                    var textNumber = document.querySelector('.pagination .page-item.active .page-link');
                    if (textNumber !== null) {
                        holidayAjaxList(parseInt(textNumber.innerText));
                    } else {
                        holidayAjaxList(1);
                    }
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function editHoliday(id) {
            var edit = "{{ route('holiday-list.edit', 'holiday_id') }}";
            $.ajax({
                'method': 'get',
                'url': edit.replace('holiday_id', id),
                success: function(res) {
                    $('#id').val(res.data.id);
                    $('#edit_holiday_date').val(res.data.holiday_date);
                    $('#edit_holiday_name').val(res.data.holiday_name);
                    $("#status").removeAttr("checked");
                    if (res.data.status == 1) {
                        $('#status').attr('checked', 'true')
                    }
                    $('#edit_holiday_modal').modal('show');
                },
            });
        }

        function updateHoliday() {
            var update = "{{ route('holiday-list.update', 'holiday_id') }}"
            var status = "on";
            if (!$('#status').is(':checked')) {
                status = "off";
            }
            $.ajax({
                'method': 'PUT',
                'url': update.replace('holiday_id', $('#id').val()),
                data: {
                    holiday_date: $('#edit_holiday_date').val(),
                    holiday_name: $('#edit_holiday_name').val(),
                    status: status,
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    toastr.success(res.message);
                    $('#edit_holiday_modal').modal('hide');
                    var textNumber = document.querySelector('.pagination .page-item.active .page-link');
                    if (textNumber !== null) {
                        holidayAjaxList(parseInt(textNumber.innerText));
                    } else {
                        holidayAjaxList(1);
                    }
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteHoliday(id) {
            var deleteURL = "{{ route('holiday-list.destroy', 'ids') }}";
            new swal({
                title: 'Are you sure delete this Holiday?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes Delete it!'
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        method: "DELETE",
                        url: deleteURL.replace('ids', id),
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(res) {
                            toastr.success(res.message);
                            var textNumber = document.querySelector('.pagination .page-item.active .page-link');
                            if (textNumber !== null) {
                                holidayAjaxList(parseInt(textNumber.innerText));
                            } else {
                                holidayAjaxList(1);
                            }
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    })
                }
            });
        }
    </script>
    @endsection