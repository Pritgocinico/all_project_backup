@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Employees</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '5')->where('export',1)->first())
                        <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                        Export</a>
                        @endif
                        @if (collect($accesses)->where('menu_id', '5')->where('add',1)->first())
                            <a href="{{ route('user.create') }}" class="btn btn-sm btn-dark"><i class="fa fa-plus me-2"></i>
                                Create Employee</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
                <div id="employee_table_ajax" class=" custom-scrollbar">


                </div>
            </div>
            <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="modal-header pb-0 border-0">
                            <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export Employee</h1>
                            <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="" class="form">
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
                                                <input class="form-check-input" type="checkbox" name="status"
                                                    id="status">
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
        </main>
    </div>
    <div class="modal fade " id="assignLeadModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        data-bs-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4">Assign Lead</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="changeStatusForm" action="{{ route('de-active-user-data') }}">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="user_status" id="user_status">
                    <div class="assign_member_div"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark" id="update_button_department">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade " id="deleteLeadModal" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
        data-bs-backdrop="static" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content overflow-hidden">
                <div class="modal-header pb-0 border-0">
                    <h1 class="modal-title h4">Assign Lead</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="vstack" method="POST" id="changeStatusForm" action="">
                    @csrf
                    <input type="hidden" name="user_id" id="delete_user_id">
                    <div class="assign_member_div"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-neutral" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-dark" id="update_button_department"
                            onclick="deleteConfirmUser()">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            employeeAjax(1);
        })

        function employeeAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('employee-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#employee_table_ajax').html('');
                    $('#employee_table_ajax').html(res);
                    $("#employee_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#employee_table_filter input');
                            $searchInput.attr('id', 'employee_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Employee');
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
                    // $('#employee_table ').DataTable({ "scrollY": "200px","scrollCollapse": true, 
                    //     "paging": false,
                    //     "info": false,
                    //     "searching": false, // Disable the search bar if you don't need it"order": [], // Disable initial sorting"fixedHeader": { header: true, footer: true } });
                    $('[data-bs-toggle="tooltip"]').tooltip()
                },
            });
        }
        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            employeeAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('employee-export') }}";
            var format = $('#export_format').val();
            var search = $('#employee_search').val();
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

        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this Employee?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.lead.data') }}",
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            $('#delete_user_id').val(id)
                            var lead = data.data.lead;
                            var users = data.data.users;
                            var option = ``;
                            $.each(users, function(i, v) {
                                option += `<option value="${v.id}">${v.name}</option>`
                            })
                            var html = ``;
                            if (lead.length == 0) {
                                html = `<div class="d-flex justify-content-center gap-2 mt-6 mb-6">
                <p>No Lead Data Found.</p>
            </div>`;
                            }
                            $.each(lead, function(i, v) {
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
                                        Customer Disease:- ${v.customer_detail.cust_disease.name}
                                    </div>
                                    <div class="col-md-3">
                                        Assign To:-
                                        <select name="assign_to[]" class="form-control" required>
                                            <option value="">Select</option>
                                        ${option}
                                        </select>
                                            
                                    </div>
                                </div>
                                <hr />
                                `;
                            })
                            $('.assign_member_div').html(html)
                            $('#deleteLeadModal').modal('show')
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        function changeUserStatus(element) {
            var id = $(element).data('id');
            var status = $(element).data('status');
            swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to change the Status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed && status == 2) {
                    $.ajax({
                        url: "{{ route('user.lead.data') }}",
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function(data) {
                            $('#user_id').val(id)
                            $('#user_status').val(2);
                            var lead = data.data.lead;
                            var users = data.data.users;
                            var option = ``;
                            $.each(users, function(i, v) {
                                option += `<option value="${v.id}">${v.name}</option>`
                            })
                            var html = ``;
                            if (lead.length == 0) {
                                html = `<div class="d-flex justify-content-center gap-2 mt-6 mb-6">
                <p>No Lead Data Found.</p>
            </div>`;
                            }
                            $.each(lead, function(i, v) {
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
                                        Customer Disease:- ${v.customer_detail.cust_disease.name}
                                    </div>
                                    <div class="col-md-3">
                                        Assign To:-
                                        <select name="assign_to[]" class="form-control" required>
                                            <option value="">Select</option>
                                        ${option}
                                        </select>
                                            
                                    </div>
                                </div>
                                <hr />
                                `;
                            })
                            $('.assign_member_div').html(html)
                            $('#assignLeadModal').modal('show')
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                } else {
                    $.ajax({
                        url: "{{ route('change-user-status') }}",
                        type: 'GET',
                        data: {
                            id: id,
                            status: status
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            employeeAjax(1)
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            })
        }

        function deleteConfirmUser() {
            var id = $('#delete_user_id').val();
            $.ajax({
                url: "{{ route('user.destroy', '') }}" + "/" + id,
                type: 'DELETE',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(data) {
                    toastr.success(data.message);
                    employeeAjax(1)
                    $('#deleteLeadModal').modal('hide')
                },
                error: function(error) {
                    toastr.error(error.responseJSON.message)
                }
            });
        }
    </script>
@endsection
