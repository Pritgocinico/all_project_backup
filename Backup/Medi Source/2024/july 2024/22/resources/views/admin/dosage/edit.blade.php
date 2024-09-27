@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items center">
                    <h3 class="mb-0">Edit Dosage Form</h3>
                    <a href="{{ route('dosage.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('dosage.update', $dosageForm->id) }}" method="post" id="editDosageForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $dosageForm->name) }}" required>
                        <span id="nameError" class="text-danger"></span>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description"
                            name="description">{{ old('description', $dosageForm->description) }}</textarea>
                        <span id="descriptionError" class="text-danger"></span>
                    </div>

                    <button type="button" onclick="validateForm()" class="btn btn-primary">Update Dosage Form</button>
                    <a href="{{ route('dosage.index') }}" class="btn btn-secondary">Go Back</a>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function validateForm() {
        var name = $("#name").val();

        // Reset error messages
        $("#nameError").text("");
        $("#descriptionError").text("");

        // Perform validation
        if (name === "") {
            $("#nameError").text("Name is required.");
        }

        // Add more validation as needed...

        // Submit the form if there are no errors
        if (name !== "") {
            $("#editDosageForm").submit();
        }
    }
</script>

@endsection
