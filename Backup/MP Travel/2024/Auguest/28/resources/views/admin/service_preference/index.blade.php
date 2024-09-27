@extends('admin.partials.header', ['active' => 'Service Preference'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Service Preference</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '24')->first()->status == 2)
                            <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                Export</a>
                            <a href="#" class="btn btn-sm btn-dark" data-bs-target="#addServicePreferenceDetail"
                                data-bs-toggle="modal"><i class="fa fa-plus me-2"></i>
                                New Service Preference</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-6">
                <div id="service_preference_ajax" class="table-responsive custom-scrollbar"></div>
            </div>
        </main>
        <div class="modal fade" id="addServicePreferenceDetail" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Add Service Preference</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="addForm">
                        @csrf
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-5"><label class="form-label mb-0">Service Preference Name</label></div>
                                    <div class="col-md-7 col-xl-7">
                                        <input type="text" name="service_preference_name" class="form-control" id="service_preference_name"
                                            placeholder="   Service Preference Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtnCreate"
                                onclick="submitForm()">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editServicePreferenceDetail" tabindex="-1" aria-labelledby="depositLiquidityModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content overflow-hidden">
                    <div class="modal-header pb-0 border-0">
                        <h1 class="modal-title h4" id="depositLiquidityModalLabel">Edit Service Preference</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="vstack" method="POST" id="editForm">
                        @csrf
                        <input type="hidden" name="sevice_preference_id" id="sevice_preference_id">
                        <div class="modal-body undefined">
                            <div class="vstack gap-1">
                                <div class="row align-items-center g-3 mt-6">
                                    <div class="col-md-5"><label class="form-label mb-0">Service Preference Name</label></div>
                                    <div class="col-md-7 col-xl-7">
                                        <input type="text" name="service_preference_name" class="form-control" id="edit_sevice_preference_name"
                                            placeholder="Service Preference Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="submitBtnUpdate"
                                onclick="updateServicePreference()">Submit</button>
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
                        <button type="button" class="btn btn-primary" id="submitBtn"
                            onclick="exportCSV()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            servicePreferenceAjaxList(1);
        })

        function servicePreferenceAjaxList(page) {
            $.ajax({
                'method': 'get',
                'url': "{{ route('service-preference-ajax') }}",
                data: {
                    page: page,
                    search: $('#service_preference_search').val(),
                },
                success: function(res) {
                    $('#service_preference_ajax').html('');
                    $('#service_preference_ajax').html(res);
                    $("#service_preference_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#service_preference_table_filter input');
                            $searchInput.attr('id', 'service_preference_search');
                            $searchInput.attr('placeholder', 'Search Service Preference');
                        },
                        lengthChange: false,
                        "order": [
                            [0, 'asc']
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 0
                        }]
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            servicePreferenceAjaxList(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('service-preference-export') }}";
            var format = $('#export_format').val();
            var search = $('#service_preference_search').val();
            $('#format_error').html('');
            if (format.trim() == "") {
                $('#format_error').html('Please Select Export Format.');
                return false;
            }
            var allowValues = ['csv', 'excel', 'pdf'];
            if (!allowValues.includes(format)) {
                $('#format_error').html('Please Select Valid Export Format.');
                return false;
            }
            window.open(exportFile + '?format=' + format + '&search=' + search, '_blank');
        }

        function submitForm() {
            $('#submitBtnCreate').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('service-preference.store') }}",
                type: 'POST',
                data: $('#addForm').serialize(),
                success: function(data) {
                    $('#submitBtnCreate').html('Submit');
                    $('#addForm').trigger("reset");
                    $('#addServicePreferenceDetail').modal('hide');
                    toastr.success(data.message);
                    servicePreferenceAjaxList(1)
                },
                error: function(error) {
                    $('#submitBtnCreate').html('Submit');
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function editServicePreference(id) {
            $.ajax({
                url: "{{ route('service-preference.edit', ['service_preference' => 'empid']) }}".replace('empid', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#sevice_preference_id').val(id);
                    $('#edit_sevice_preference_name').val(data.data.name);
                    $('#editServicePreferenceDetail').modal('show');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }

        function deleteServicePreference(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this service preference?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: "{{ route('service-preference.destroy', ['service_preference' => 'empid']) }}".replace('empid', id),
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            servicePreferenceAjaxList(1)
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        function updateServicePreference() {
            var id = $('#sevice_preference_id').val();
            $('#submitBtnUpdate').html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ route('service-preference.update', ['service_preference' => 'empid']) }}".replace('empid', id),
                type: 'PUT',
                data: $('#editForm').serialize(),
                success: function(data) {
                    $('#editForm').trigger("reset");
                    $('#editServicePreferenceDetail').modal('hide');
                    toastr.success(data.message);
                    servicePreferenceAjaxList(1)
                    $('#submitBtnUpdate').html('Submit');
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                    $('#submitBtnUpdate').html('Submit');
                }
            });
        }
    </script>
@endsection