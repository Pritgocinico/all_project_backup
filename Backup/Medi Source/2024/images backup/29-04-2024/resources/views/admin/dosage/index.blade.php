@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center-justify-content-center">
                    <h3 class="mb-0">Dosage Forms</h3>
                    <a href="{{ route('dosage.create') }}" class="btn btn-primary ms-auto">Add Dosage</a>
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
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dosageForms as $dosageForm)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $dosageForm->name }}</td>
                            <td>{{ $dosageForm->description }}</td>
                            <td>
                                <a href="{{ route('dosage.edit', $dosageForm->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm delete-dosage-btn" data-dosage-id="{{ $dosageForm->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 script from CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    // SweetAlert for delete confirmation
    document.addEventListener('DOMContentLoaded', function () {
        let deleteButtons = document.querySelectorAll('.delete-dosage-btn');

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                let dosageId = this.getAttribute('data-dosage-id');

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
                        // If confirmed, trigger the form submission
                        let deleteForm = document.createElement('form');
                        deleteForm.action = "{{ url('dosage') }}" + '/' + dosageId + '/destroy';
                        deleteForm.method = 'post';
                        deleteForm.innerHTML = '@csrf @method("DELETE")';
                        document.body.appendChild(deleteForm);
                        deleteForm.submit();
                    }
                });
            });
        });
    });
</script>

@endsection
