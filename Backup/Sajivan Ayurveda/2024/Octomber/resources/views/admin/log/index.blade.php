@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body main-table rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Logs</h1>
                    <div class="hstack gap-2 ms-auto">
                    </div>
                </div>
            </div>
            <div class="px-6 px-lg-7 pt-6">

            <div id="log_table_ajax" class=" custom-scrollbar">
                
            </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            logAjax(1);
        })

        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var page = $(this).attr('href').split('page=')[1];
            logAjax(page);
        });

        function logAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('log-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#log_table_ajax').html('');
                    $('#log_table_ajax').html(res);
                    $("#log_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#log_table_filter input');
                            $searchInput.attr('id', 'log_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Log');
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

        function deleteRole(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure the delete this role?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('role.destroy', '') }}" + "/" + id,
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
                                    location.reload();
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
    </script>
@endsection
