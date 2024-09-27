@extends('admin.layouts.app')

@section('content')
    <!-- Add these CDN links to your Blade file -->

    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">All Users</h3>
                        @if (PermissionHelper::checkUserPermission('Employee Add/Edit/Delete'))
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table" id="userTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        @if (PermissionHelper::checkUserPermission('Employee List/View'))
                                        <a
                                            href="{{ route('admin.users.show', $user->id) }}">{{ $user->first_name . ' ' . $user->last_name }}</a>
                                        @else
                                            {{ $user->first_name . ' ' . $user->last_name }}
                                        @endif
                                    </td>

                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->role_name }}</td>
                                    <td>{{ $user->status }}</td>
                                    <td>
                                        @if (PermissionHelper::checkUserPermission('Employee List/View'))
                                        <a href="{{ route('admin.users.show', ['id' => $user->id]) }}"
                                            class="btn btn-success mb-0">
                                            <i class="fas fa-eye"></i> <!-- Edit Icon -->
                                        </a>
                                        @endif
                                        @if (PermissionHelper::checkUserPermission('Employee Add/Edit/Delete'))
                                        <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                                            class="btn btn-primary">
                                            <i class="fas fa-edit"></i> <!-- Edit Icon -->
                                        </a>
                                        <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}"
                                            method="post" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger delete-user-btn"
                                                data-user-id="{{ $user->id }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i> <!-- Delete Icon -->
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add these CDN links to your Blade file -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();

            // SweetAlert for delete confirmation
            $('.delete-user-btn').click(function() {
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, trigger the form submission
                        form.submit();
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
        });
    </script>
@endsection
