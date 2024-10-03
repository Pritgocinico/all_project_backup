@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Push Leads</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '23')->first()->status == 2)
                            @if (Auth()->user()->role_id !== 2)
                                <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_export_users" data-bs-toggle="modal">
                                    Export</a>
                            @endif
                            <a href="{{ route('pushleads.create') }}" class="btn btn-sm btn-dark"><i class="fa-solid fa-plus"></i>
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
            $.ajax({
                'method': 'get',
                'url': "{{ route('push-lead-ajax') }}",
                data: {
                    search: search,
                    page: page,
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
            var exportFile = "{{ route('push-lead-export') }}";
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
            window.open(exportFile + '?format=' + format + '&search=' + search + "&type="+type, '_blank');
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
                        url: "{{ route('pushleads.destroy', '') }}" + "/" + id,
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
    </script>
@endsection
