<!-- resources/views/admin/lot/index_files.blade.php -->

@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <h3 class="mb-0">Resources</h3>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table" id="lotFilesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lot Number</th>
                            <th>Product</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lots as $lot)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $lot->lot_number }}</td>
                                <td>{{ $lot->product->productname }}</td>
                                <td>
                                    <a href="{{ asset('storage/lot-files/'.$lot->file) }}" target="_blank">View</a> |
                                    <a href="{{ asset('storage/lot-files/' . $lot->file) }}" download="{{ $lot->file }}">Download</a>
                                </td>
                                <td>
                                    <form action="{{ route('admin.lots.destroy', $lot->id) }}" method="post" style="display: inline-block;" id="deleteForm{{ $lot->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn" data-lot-id="{{ $lot->id }}">
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

<!-- Include SweetAlert library directly -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize DataTables
        $('#lotFilesTable').DataTable();

        // Listen for click on delete button
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const lotId = this.getAttribute('data-lot-id');

                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if user confirms
                        document.getElementById('deleteForm' + lotId).submit();
                    }
                });
            });
        });
    });
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

@endsection
