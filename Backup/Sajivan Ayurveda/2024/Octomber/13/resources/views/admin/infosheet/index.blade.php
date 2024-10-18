@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar bg-body main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Incentive</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '6')->first()->status == 2)
                        <a href="#" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#kt_modal_export_users"
                                data-bs-toggle="modal">
                                Export</a>
                            <a href="{{ route('incentive.create') }}" class="btn btn-sm btn-dark"><i class="fa-solid fa-plus"></i>
                                Create Incentive</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-8">
            <div id="infosheet_table_ajax" class=" custom-scrollbar">
            </div>  
            </div>
            {{-- export modal --}}
            <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-labelledby="depositLiquidityModalLabel"
                style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content overflow-hidden">
                        <div class="modal-header pb-0 border-0">
                            <h1 class="modal-title h4" id="depositLiquidityModalLabel">Export InfoSheet</h1>
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
        </main>
        
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            infosheetAjax(1);
        })

        function infosheetAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('infosheet-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#infosheet_table_ajax').html('');
                    $('#infosheet_table_ajax').html(res);
                    $("#info_sheet_table").DataTable({
                    initComplete: function() {
                        var $searchInput = $('#info_sheet_table_filter input');
                        $searchInput.attr('id', 'info_sheet_search'); // Assign the ID
                        $searchInput.attr('placeholder', 'Search Incentive');
                    },
                    "pageLength": 20,
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
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            infosheetAjax(page);
        });

        function exportCSV() {
            var exportFile = "{{ route('infosheet-export') }}";
            var format = $('#export_format').val();
            var search = $('#info_sheet_search').val();
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

        function deleteInfoSheet(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this Incentive?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('incentive.destroy', '') }}" + "/" + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(data) {
                            toastr.success(data.message);
                            infosheetAjax(1)
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
