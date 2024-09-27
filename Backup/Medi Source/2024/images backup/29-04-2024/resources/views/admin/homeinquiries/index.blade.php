<!-- resources/views/frontend/homeinquiries/index.blade.php -->
@extends('admin.layouts.app')
<!-- Adjust as needed -->

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h3 class="mb-0">Inquiries</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>State</th>
                                <th>Message</th>
                                <th>Consent</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($inquiries->isEmpty())
                                <tr>
                                    <td colspan="8">Records Not Found.</td>
                                </tr>
                            @else
                                @foreach($inquiries as $inquiry)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $inquiry->name }}</td>
                                        <td>{{ $inquiry->contact }}</td>
                                        <td>{{ $inquiry->email }}</td>
                                        <td>{{ $inquiry->state }}</td>
                                        <td>{{ $inquiry->message }}</td>
                                        <td>{{ $inquiry->consent ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('homeinquiries.show', $inquiry->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('homeinquiries.destroy', $inquiry->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm delete-inquiry-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>

<!-- Add this script tag to include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script>
    $(document).ready(function () {
        // SweetAlert for delete confirmation
        $('.delete-inquiry-btn').click(function (e) {
            e.preventDefault(); // Prevent the default form submission

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
