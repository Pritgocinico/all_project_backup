@extends('admin.partials.header', ['active' => 'user'])
@section('content')
    <div
        class="flex-fill scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid p-0">
            <div class="px-6 px-lg-7 pt-8 border-bottom">
                <div class="d-flex align-items-center mb-5">
                    <h1>Roles</h1>
                    <div class="hstack gap-2 ms-auto">
                        @if (collect($accesses)->where('menu_id', '4')->where('add', 1)->first())
                            <a href="{{ route('role.create') }}" class="btn btn-sm btn-dark"><i
                                    class="fa-solid fa-plus me-2"></i>
                                New Role</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="px-6 px-lg-7 pt-8">
                <div id="role_table_ajax" class=" custom-scrollbar">
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(e) {
            roleAjax(1);
        })

        function roleAjax(page) {
            var search = $('#search_data').val();
            $.ajax({
                'method': 'get',
                'url': "{{ route('role-ajax') }}",
                data: {
                    search: search,
                    page: page,
                },
                success: function(res) {
                    $('#role_table_ajax').html('');
                    $('#role_table_ajax').html(res);
                    $("#role_table").DataTable({
                        initComplete: function() {
                            var $searchInput = $('#role_table_filter input');
                            $searchInput.attr('id', 'role_search'); // Assign the ID
                            $searchInput.attr('placeholder', 'Search Role');
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
            roleAjax(page);
        });

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
                            toastr.success(data.message);
                            roleAjax(1);
                        },
                        error: function(error) {
                            toastr.error(error.responseJSON.message)
                        }
                    });
                }
            });
        }

        function changeStatus(element) {
            var roleId = $(element).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to change the Status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('changeStatus', '') }}" + "/" + roleId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                if (response.active) {
                                    $(element).removeClass('bg-danger').addClass('bg-success').text('Active');
                                } else {
                                    $(element).removeClass('bg-success').addClass('bg-danger').text('Inactive');
                                }
                                toastr.success('Status changed successfully!');
                                roleAjax(1);
                            } else {
                                toastr.error('Something went wrong.');
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong.');
                        }
                    });
                }
            });
        }

    </script>
@endsection