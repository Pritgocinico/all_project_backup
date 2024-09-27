<!-- resources/views/inquiries/index.blade.php -->

@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-header">
                <div class="container">
                    <h3>Contact Inquiries</h3>
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First name</th>
                                <th scope="col">Email</th>
                                <th scope="col">User Type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inquiry)
                            <tr>
                            <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $inquiry->first_name }}</td>
                                <td>{{ $inquiry->email }}</td>
                                <td>{{ $inquiry->user_type }}</td>
                                <td style="display: flex;">
                                    <a href="{{ route('inquiries.show', $inquiry->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Delete Button with SweetAlert Confirmation -->

                                    <button type="button" class="btn btn-danger btn-sm delete-inquiry-btn" data-inquiry-id="{{ $inquiry->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Hidden Form for Deletion -->
                                    <form id="delete-form-{{ $inquiry->id }}" action="{{ route('inquiries.destroy', $inquiry->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">No inquiries found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script for SweetAlert Confirmation -->
<script>
    $(document).ready(function () {
        // SweetAlert for delete confirmation
        $('.delete-inquiry-btn').click(function () {
            var inquiryId = $(this).data('inquiry-id');

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
                    $('#delete-form-' + inquiryId).submit();
                }
            });
        });
    });
</script>

@endsection
