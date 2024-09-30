@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="page-header">
                <div>
                    <h3>Add New Project Question</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('admin.add.purchase') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="userName">Question Type<span class="text-danger">*</span></label>
                                            <select name="question_type" id="question_type" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1" {{ old('question_type') == 1 ? 'selected' : '' }}>Workshop</option>
                                                <option value="2" {{ old('question_type') == 2 ? 'selected' : '' }}>Fitting</option>
                                                <option value="3" {{ old('question_type') == 3 ? 'selected' : '' }}>Quality Analyst</option>
                                            </select>
                                            @if ($errors->has('question_type'))
                                                <small id="nameError"
                                                    class="form-text text-danger">{{ $errors->first('question_type') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Question</label>
                                            <textarea class="form-control" name="question" id="question" placeholder="Enter Question">{{ old('question') }}</textarea>
                                            @if ($errors->has('question'))
                                                <small id="emailError"
                                                    class="form-text text-danger">{{ $errors->first('question') }}</small>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="page-header d-md-flex justify-content-between">
                <div>
                    <h3 class="mb-0">Project Question</h3>

                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table id="example2" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Qustion Type</th>
                                <th>Question</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questionList as $question)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        @if ($question->question_type == 1)
                                            Workshop
                                        @elseif($question->question_type == 2)
                                            Fitting
                                        @elseif($question->question_type == 3)
                                            Quality Analyst
                                        @endif
                                    </td>
                                    <td>{{ $question->question }}</td>
                                    <td>{{ date('d/m/Y - H:i:s', strtotime($question->created_at)) }}</td>
                                    <td>
                                        <a href="javascript:voide(0)" class="editCustomer" data-id="{{ $question->id }}"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i
                                                data-feather="edit"></i></a>
                                        <a href="javascript:void(0);" data-id="{{ $question->id }}"
                                            class="ms-2 delete-btn"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Qustion Type</th>
                                <th>Question</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-dialog-centered  modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="updateCustomerForm" name="updatecustomerform" action="#" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="userName">Question Type<span class="text-danger">*</span></label>
                                    <select name="question_type" id="edit_question_type" class="form-control">
                                        <option value="">Select</option>
                                        <option value="1" {{ old('question_type') == 1 ? 'selected' : '' }}>Workshop
                                        </option>
                                        <option value="2" {{ old('question_type') == 2 ? 'selected' : '' }}>Fitting
                                        </option>
                                        <option value="3" {{ old('question_type') == 3 ? 'selected' : '' }}>Quality
                                            Analyst</option>
                                    </select>
                                    @if ($errors->has('question_type'))
                                        <small id="nameError"
                                            class="form-text text-danger">{{ $errors->first('question_type') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Question</label>
                                    <textarea class="form-control" name="question" id="edit_question" placeholder="Enter Question">{{ old('question') }}</textarea>
                                    @if ($errors->has('question'))
                                        <small id="emailError"
                                            class="form-text text-danger">{{ $errors->first('question') }}</small>
                                    @endif
                                </div>
                                <input type="hidden" name="question_id" value="" id="question_id">
                                <button type="button" class="btn btn-primary" id="updateCustomerBtn">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).on('click', '.editCustomer', function() {
            var id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.edit.project.question') }}",
                data: {
                    'id': id
                },
                success: function(data) {
                    $('#edit_question_type').val(data.question_type)
                    $('#edit_question').val(data.question)
                    $('#question_id').val(data.id)
                },
                error: function(data) {
                }
            });
        });

        $(document).on('click', '#updateCustomerBtn', function() {
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.update.project.question') }}',
                data: $('#updateCustomerForm').serialize(),
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: data.message,
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                },
                error: function(data) {
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.delete.project.question', '') }}" + "/" +
                                id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Customer has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    title: error.responseJSON.message,
                                    text: "Cannot Delete Customer.",
                                    icon: 'error',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
