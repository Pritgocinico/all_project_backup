<!-- resources/views/admin/doctor/index.blade.php -->

@extends('admin.layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Doctor Users</h3>
                        @if (PermissionHelper::checkUserPermission('Doctor Add/Edit/Delete'))
                            <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">Add Doctor</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="">
                        @if(session('success') || session('error'))
                        <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                            {{ session('success') ?? session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <table class="table" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    @if (PermissionHelper::checkUserPermission('Password View'))
                                    <th>Password</th>
                                    @else
                                    <th>Activation</th>
                                    @endif
                                    <th>status</th>

                                    <!-- Add more fields as needed -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($doctors as $user)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td><a
                                            href="{{ route('admin.users.show', $user->id) }}">{{ $user->first_name . ' ' . $user->last_name }}</a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @if ($user->password1 == null)
                                            @if (PermissionHelper::checkUserPermission('Doctor Status Update'))
                                                <a href="{{ route('admin.users.show', $user->id) }}">New User</a>
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                    class="btn btn-primary">Active Now</a>
                                            @endif
                                        @else
                                            @if (PermissionHelper::checkUserPermission('Password View'))
                                                {{ $user->password1 }}
                                            @else
                                                 -                                               
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (PermissionHelper::checkUserPermission('Doctor Status Update'))
                                            <form action="{{ route('admin.doctors.updateStatus', ['id' => $user->id]) }}"
                                                method="post" id="updateStatusForm">
                                                @csrf
                                                @method('PUT')

                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-{{ $user->status === 'active' ? 'success' : 'danger' }} btn-sm">
                                                        {{ $user->status === 'active' ? 'Active' : 'Inactive' }}
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-{{ $user->status === 'active' ? 'success' : 'danger' }} btn-sm dropdown-toggle dropdown-toggle-split"
                                                        id="statusDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                                                        <li>
                                                            <button class="dropdown-item btn-sm" type="submit"
                                                                name="status"
                                                                value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                                                {{ $user->status === 'active' ? 'Inactive' : 'Active' }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </form>
                                        @endif
                                        @if (session('success'))
                                            <script>
                                                Swal.fire({
                                                    title: 'Success!',
                                                    text: '{{ session('
                                                                                        success ') }}',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                });
                                            </script>
                                        @endif
                                    </td>






                                    <!-- Add more fields as needed -->
                                    <td>
                                        @if (PermissionHelper::checkUserPermission('Doctor List/View'))
                                            <a href="{{ route('admin.doctors.show', ['id' => $user->id]) }}"
                                                class="btn btn-success mb-0">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        @endif
                                        @if (PermissionHelper::checkUserPermission('Doctor Add/Edit/Delete'))
                                            <a href="{{ route('admin.doctor.edit', ['id' => $user->id]) }}"
                                                class="btn btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(is_null($user->quick_book_cus_id) && $user->password1 != null)
                                                        <a class="btn btn-icon btn-secondary"
                                                        href="{{ route('user.quickbooks', $user->id) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Doctor Sync To Quickbooks">
                                                        <i class="fa-solid fa-q"></i></a>
                                            @endif
                                            <form action="{{ route('admin.doctor.destroy', ['id' => $user->id]) }}"
                                                method="post" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger  delete-doctor-btn">
                                                    <i class="fas fa-trash"></i>
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
    </div>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
            // SweetAlert for delete confirmation
            $('.delete-doctor-btn').click(function() {
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
@endsection
