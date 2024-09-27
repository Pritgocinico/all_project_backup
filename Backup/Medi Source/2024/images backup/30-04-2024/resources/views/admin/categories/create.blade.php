@extends('admin.layouts.app')

@section('content')
<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center-justify-content-center">
                    <h3 class="mb-0">Add Category</h3>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary ms-auto">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data" id="categoryForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <span id="nameError" class="text-danger"></span>
                    </div>

                    <div class="mb-3">
                        <label for="img" class="form-label">Category Image</label>
                        <input type="file" class="form-control" id="img" name="img" accept="image/*">
                        <span id="imgError" class="text-danger"></span>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                        <span id="descriptionError" class="text-danger"></span>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Parent Category</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">No Parent</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <span id="parentError" class="text-danger"></span>
                    </div>

                    <button type="button" onclick="validateForm()" class="btn btn-primary">Create Category</button>

                    <!-- "Go Back" button -->

                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add this in the head section or include from a CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function validateForm() {
        var name = $("#name").val();
        var img = $("#img").val();
        var description = $("#description").val();
        var parent_id = $("#parent_id").val();

        // Reset error messages
        $("#nameError").text("");
        $("#imgError").text("");
        $("#descriptionError").text("");
        $("#parentError").text("");

        // Perform validation
        if (name === "") {
            $("#nameError").text("Category Name is required.");
        }

        if (img === "") {
            $("#imgError").text("Category Image is required.");
        }

        if (description === "") {
            $("#descriptionError").text("Description is required.");
        }

        // You can add more validation as needed...

        // if (parent_id === "") {
        //     $("#parentError").text("Parent Category is required.");
        // }

        // Submit the form if there are no errors
        if (name !== "" && img !== "" && description !== "") {
            $("#categoryForm").submit();
        }
    }
</script>

@endsection
