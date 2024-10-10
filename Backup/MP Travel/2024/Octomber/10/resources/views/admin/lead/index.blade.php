@extends('admin.partials.header', ['active' => 'user'])

@section('content')

    <div

        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">

        <main class="container-fluid p-0">

            <div class="px-6 px-lg-7 pt-8 border-bottom">

                <div class="d-flex align-items-center mb-5">

                    <h1>Leads</h1>

                    <div class="hstack gap-2 ms-auto">
                        <select name="lead_status" id="lead_status" class="form-control" onchange="leadAjax()">
                            <option value="" selected>All</option>
                            <option value="1">Pending</option>
                            <option value="2">In Progress</option>
                            <option value="3">On Hold</option>
                            <option value="4">Completed</option>
                        </select>
                        @if (collect($accesses)->where('menu_id', '18')->first()->status == 2)

                            @if (Auth()->user()->role_id == "1" || Auth()->user()->is_manager == "yes")

                                <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"

                                    data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">

                                    Export</a>

                            @endif

                            <a href="{{ route('leads.create') }}" class="btn btn-sm btn-dark lead_new_button"><i class="fa-solid fa-plus"></i>

                                New Lead</a>

                        @endif

                    </div>

                </div>

            </div>



            <div class="px-6 px-lg-7 pt-6">

                <div id="lead_table_ajax" class="table-responsive custom-scrollbar">

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



    </div>

@endsection

@section('script')

    <script>

        $(document).ready(function(e) {

            leadAjax(1);

        })



        function leadAjax(page) {

            var search = $('#search_data').val();
            var status = $('#lead_status').val();

            $.ajax({

                'method': 'get',

                'url': "{{ route('lead-ajax') }}",

                data: {

                    search: search,

                    page: page,
                    status: status,

                    type: "{{$type}}",

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
'pageLength': 30,
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

            var type= "{{$type}}";
            var status = $('#lead_status').val();

            window.open(exportFile + '?format=' + format + '&search=' + search + "&type="+type + "&status=" + status, '_blank');

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
                            toastr.error(error.responseJSON.message)
                        }

                    });

                }

            });

        }

    </script>

@endsection

