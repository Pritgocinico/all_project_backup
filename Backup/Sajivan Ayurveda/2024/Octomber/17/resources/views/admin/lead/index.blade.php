@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Leads</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '15')->first()->status == 2)
                            @if (Auth()->user()->role_id !== 2)
                                <a href="javascript:void(0)" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#bulk_import_div" data-bs-toggle="modal">Bulk Import</a>
                                <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                    Export</a>
                            @endif
                            <a href="{{ route('leads.create') }}" class="btn btn-sm btn-dark"><i
                                    class="fa-solid fa-plus"></i>
                                Add Lead</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-6 px-lg-7 pt-6">
                <div id="lead_table_ajax" class=" custom-scrollbar">
                </div>
            </div>
        </main>
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
                                        <span id="format_error" class="error_span"></span>
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

    </div>
    <div class="modal fade" id="bulk_import_div" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4" id="depositLiquidityModalLabel">Import Lead</h1>
                    <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="bulkImportLead" class="form" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="modal-body undefined">
                        <div class="vstack gap-1">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-3"><label class="form-label mb-0">Import File:</label></div>
                                <div class="col-md-9 col-xl-9">
                                    <input type="file" name="import_file" class="form-control" id="import_file">
                                    <span id="file_import_error" class="error_span"></span>
                                </div>
                                <a href="{{ asset('assets/excel/lead dummy.xlsx') }}" download>Download Sample File</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="importSubmitBtn"
                            onclick="importFile()">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade " id="bulkAssignLead" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        data-bs-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4">Assign Lead</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="bulkAssignLead" action="{{ route('bulk-assign-lead') }}">
                    @csrf
                    <div class="assign_member_div"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark" id="update_button_department">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            leadAjax(1);
        })

        function importFile() {
            var import_file = $('#import_file').val();
            $('#file_import_error').html('');
            if (import_file == "") {
                $('#file_import_error').html('Please Select File.');
                return false
            }
            $('#importSubmitBtn').html('<i class="fa fa-spinner fa-spin"></i>');
            var data = new FormData($('#bulkImportLead')[0]);
            $.ajax({
                'method': 'POST',
                'url': "{{ route('bulk-lead-import') }}",
                data: data,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(res) {
                    toastr.success(res.message);

                    $('#importSubmitBtn').html('Submit');
                    // $('#bulk_import_div').modal('hide');
                    var html = ``;
                    var lead = res.data.leads;
                    var user = res.data.users;

                    if (lead.length == 0) {
                        html = `<div class="d-flex justify-content-center gap-2 mt-6 mb-6">
                <p>No Lead Data Found.</p>
            </div>`;
                    }
                    
                    var leadIdArray = [];
                    $.each(lead, function(i, v) {
                        var option = ``;
                        var className = "";
                        $.each(user, function(a, b) {
                            var select = ""
                            if (v.assigned_to != null) {
                                className = "read-only";
                                if (v.assigned_to == b.id) {
                                    select = "selected";
                                }
                            }
                            option += `<option value="${b.id}" ${select}>${b.name}</option>`
                        })
                        if (!leadIdArray.includes(v.id)){
                            leadIdArray.push(v.id);
                            html += `
                                <input type="hidden" name="lead_id[]" value="${v.id}">
                                <div class="p-2 row align-items-center mt-6">
                                    <div class="col-md-3">
                                        Customer Name:- ${v.customer_detail.name}
                                    </div>
                                    <div class="col-md-3">
                                        Mobile Name:- ${v.customer_detail.mobile_number}
                                    </div>
                                    <div class="col-md-3">
                                        Customer Disease:- ${v.customer_detail.disease_detail.name}
                                    </div>
                                    <div class="col-md-3">
                                        Assign To:-
                                        <select name="assign_to[]" class="form-control ${className}" required>
                                            <option value="">Select</option>
                                        ${option}
                                        </select>
                                            
                                    </div>
                                </div>
                                <hr />
                                `;
                        }
                    })
                    $('.assign_member_div').html(html)
                    $('#assignLeadModal').modal('show')
                    $('#bulkAssignLead').modal('show');
                    $('#bulkImportLead')[0].reset();
                }, error: function(error) {
                    $('#importSubmitBtn').html('Submit');
                    toastr.error(error.responseJSON.message);
                }
            });
        }

        function leadAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('lead-ajax') }}",
                data: {
                    search: search,
                    page: page,
                    type: "{{ $type }}",
                },
                success: function(res) {
                    $('#lead_table_ajax').html('');
                    $('#lead_table_ajax').html(res);
                    $("#lead_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#lead_table_filter input');
                            $searchInput.attr('id', 'lead_search');
                            $searchInput.attr('placeholder', 'Search Lead');
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
            leadAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('lead-export') }}";
            var format = $('#export_format').val();
            var search = $('#lead_search').val();
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
            var type = "{{ $type }}";
            window.open(exportFile + '?format=' + format + '&search=' + search + "&type=" + type, '_blank');
        }

        function deletelead(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Lead?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leads.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            leadAjax(1);
                        },
                        error: function(error) {
                            toastr.success(error.responseJSON.message);
                        }
                    });
                }
            });
        }

        function saleApproval(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want For Sale Approval this Lead?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leads.sale.approval') }}",
                        type: 'get',
                        data: {
                            'lead_id': id,
                            'status': 2,
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            leadAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        }

        function rejectLead(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the reject this Lead?",
                text: "Enter Reason for Rejection",
                icon: 'warning',
                showCancelButton: true,
                input: 'text',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, reject it!',
                customClass: {
                    validationMessage: 'my-validation-message',
                },
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Reason for cancellation is required')
                    }
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leads.sale.approval') }}",
                        type: 'get',
                        dataType: 'json',
                        data: {
                            lead_id: id,
                            status: 3,
                            reject_reason: result.value,
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            leadAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        }
    </script>
@endsection
