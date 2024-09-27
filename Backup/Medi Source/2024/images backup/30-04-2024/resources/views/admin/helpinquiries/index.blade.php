@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-0">Help Inquiries</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                            <table class="table" id="userTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Message</th>
                                        <th>Team Member Contact</th>
                                        <th>Email List</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inquiries as $inquiry)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><a href="{{ route('helpinquiries.show', $inquiry->id) }}">{{ $inquiry->name }}</a></td>
                                            <td>{{ $inquiry->email }}</td>
                                            <td>{{ $inquiry->phone }}</td>
                                            <td>{{ $inquiry->message }}</td>
                                            <td>{{ $inquiry->team_member_contact }}</td>
                                            <td>{{ $inquiry->sign_up_for_email_list ? 'Yes' : 'No' }}</td>
                                            <td class="d-flex gap-2">
                                                <a href="{{ route('helpinquiries.show', $inquiry->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <!-- Delete Button with SweetAlert Confirmation -->
                                                <button type="button" class="btn btn-danger btn-sm delete-inquiry-btn"
                                                    data-inquiry-id="{{ $inquiry->id }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                                <!-- Hidden Form for Deletion -->
                                                <form id="delete-form-{{ $inquiry->id }}"
                                                    action="{{ route('helpinquiries.delete', $inquiry->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable();
            // SweetAlert for delete confirmation
            $('.delete-inquiry-btn').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var form = $(this).closest('td').find(
                'form'); // Update to find the closest form within the same table cell
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
