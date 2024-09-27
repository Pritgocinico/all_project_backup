<!-- resources/views/frontend/homeinquiries/index.blade.php -->
@extends('admin.layouts.app')
<!-- Adjust as needed -->

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-header">
                <div class="container">

                    <h1>Inquiries</h1>

                    @if($inquiries->isEmpty())
                    <p>No inquiries yet.</p>
                    @else
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
                                    <form action="{{ route('homeinquiries.destroy', $inquiry->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-inquiry-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
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
