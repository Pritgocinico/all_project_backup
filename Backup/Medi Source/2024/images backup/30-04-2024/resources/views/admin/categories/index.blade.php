@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <h3 class="mb-0">Categories</h3>
                        <a href="{{ route('categories.create') }}" class="btn btn-info ms-auto">Add Category</a>
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
                                <th>Name</th>
                                <th>Description</th>
                                <th>Parent Category</th>
                                <th>Image</th> <!-- New column for image -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>{{ $category->parent ? $category->parent->name : 'No Parent' }}</td>
                                    <td>
                                        @if ($category->img)
                                            <img src="{{ asset('storage/categories/' . $category->img) }}"
                                                alt="Category Image" class="img-thumbnail" style="max-width: 60px;">
                                        @else
                                            No Image
                                        @endif

                                    </td>

                                    <td>
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form id="delete-form-{{ $category->id }}"
                                            action="{{ route('categories.destroy', $category->id) }}" method="post"
                                            class="d-inline" style="display: none;">

                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm delete-doctor-btn"
                                                data-category-id="{{ $category->id }}">
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

    <!-- Include SweetAlert script from CDN -->
    <!-- Include SweetAlert script from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Your existing script -->
    <script>
        // Function to show confirmation alert
        function confirmDelete(categoryId) {
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
                    // If confirmed, submit the form
                    document.getElementById('delete-form-' + categoryId).submit();
                } else {
                    // If canceled, you can display a message or do nothing
                    Swal.fire('Cancelled', 'Your category is safe :)', 'info');
                }
            });
        }

        // SweetAlert for delete confirmation
        $(document).ready(function() {
            $('#userTable').DataTable();
            $('.delete-doctor-btn').click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var categoryId = $(this).data('category-id');
                confirmDelete(categoryId);
            });
        });
    </script>

    <!-- Your existing code -->
@endsection
