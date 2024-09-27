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
                    <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary">Add Doctor</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <table class="table" id="userTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Password</th>
                                <th>status</th>

                                <!-- Add more fields as needed -->
                                <th>Action</th>
                            </tr>
                        </thead>
                        @foreach($doctors as $user)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if($user->password1 == null)
                                    <a href="{{route('admin.users.show',$user->id)}}">New User</a>
                                    <a href="{{route('admin.users.show',$user->id)}}" class="btn btn-primary">Active Now</a>
                                @else
                                    {{$user->password1}}
                                @endif   
                            </td>
                            <td>
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
                                                <button class="dropdown-item btn-sm" type="submit" name="status"
                                                    value="{{ $user->status === 'active' ? 'inactive' : 'active' }}">
                                                    {{ $user->status === 'active' ? 'Inactive' : 'Active' }}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>

                                @if(session('success'))
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
                                 <a href="{{ route('admin.doctors.show', ['id' => $user->id]) }}"
                                    class="btn btn-success mb-0">
                                    <i class="fas fa-plus"></i>
                                </a>
                                <a href="{{ route('admin.doctor.edit', ['id' => $user->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.doctor.destroy', ['id' => $user->id]) }}" method="post" style="display: inline-block;">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger  delete-doctor-btn">
        <i class="fas fa-trash"></i>
    </button>
</form>

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
    $(document).ready(function () {
        // SweetAlert for delete confirmation
        $('.delete-doctor-btn').click(function () {
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
