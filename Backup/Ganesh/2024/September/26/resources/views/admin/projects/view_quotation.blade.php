@extends('admin.layouts.viewproject')

@section('pages')

    <div class="fieldset">
        @if ($projects->add_work == 0)
            <div class="d-flex align-items-center">
                <h2 class="fs-title mb-0">Quotation</h2>
                <div class="add_quotation_btn btn btn-primary mt-1 ms-auto" data-type="add">Add Quotation</div>
            </div>
        @else
            <div class="d-flex align-items-center">
                <h2 class="fs-title mb-0">Quotation</h2>
                <div class="add_quotation_btn btn btn-primary mt-1 ms-auto" data-type="additional">Additional Quotation</div>
            </div>
        @endif
        <form class="" action="{{ route('store.quotations') }}" enctype="multipart/form-data" method="POST" id="storeQuotations">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projects->id }}">
            @if ($quotations)
                <input type="hidden" name="quotation_id" value="{{ $quotations->id }}">
            @else
                <input type="hidden" name="quotation_id" value="">
            @endif
            <?php $i = 0; ?>
            <div class="quotation_new_div">
                <div class="newQuotation">
                </div>
                @if (!blank($quotationfiles))
                    @foreach ($quotationfiles as $quo)
                        <?php $i++; ?>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="">
                                    <h5>Quotation
                                        @if ($quo->final == 1)
                                            <span class="text-success">( Final Quotation )</span>
                                        @endif
                                    </h5>
                                    @if ($quo->status == 0 || $quo->status == null)
                                    @else
                                        @if ($quo->status == 1)
                                            <span class="badge bg-success">Finalize by Admin</span>
                                        @elseif($quo->status == 2)
                                            <span class="text-danger">
                                                Rejected :
                                                @if ($quo->reject_reason == 1)
                                                    Delayed/Cool of
                                                @elseif ($quo->reject_reason == 2)
                                                    Cancel
                                                @elseif ($quo->reject_reason == 3)
                                                    Addon
                                                @endif
                                            </span>
                                            <p class="mb-0">Reject Note : {{ $quo->reject_note }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if ($projects->add_work == 0)
                                <div class="ms-auto">
                                    @if ($quo->status == 0 || $quo->status == null)
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Reject
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item rejectQuotation" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                        data-reason="1" data-id="{{ $quo->id }}">Delayed/Cool of</a>
                                                </li>
                                                <li><a class="dropdown-item rejectQuotation" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                        data-reason="2" data-id="{{ $quo->id }}">Cancel</a></li>
                                                <li><a class="dropdown-item rejectQuotation" href="#"
                                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                                        data-reason="3" data-id="{{ $quo->id }}">Addon</a></li>
                                            </ul>
                                        </div>
                                    @else
                                    @endif
                                    <a class="btn btn-primary" data-bs-toggle="collapse"
                                        href="#collapseExample{{ $quo->id }}" role="button" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        Edit Quotation
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="collapse" id="collapseExample{{ $quo->id }}">
                            <div class="quotationCard row">
                                <div class="form-group col-md-8">
                                    <input type="hidden" name="quotation[{{ $i }}][id]"
                                        value="{{ $quo->id }}">
                                    <label for="formFile" class="form-label">Upload Quotation File</label>
                                    <input class="form-control" type="file" id="formFile"
                                        name="quotation[{{ $i }}][quotations_file][]" multiple>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="measurementdesc">Quotation Description <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="measurementdesc" rows="3" name="quotation[{{ $i }}][description]">
                    @if (!empty($quo->description))
{{ $quo->description }}
@else
{{ old('description') }}
@endif
                    </textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="quotationfinal{{ $i }}"
                                        name="quotation[{{ $i }}][final]"
                                        @if ($quo->final == 1) checked @endif>
                                    <label for="quotationfinal{{ $i }}" class="form-check-label">Final
                                        Quotation?</label>
                                </div>
                                <div class="col-md-12 my-2">
                                    <a href="javascript:void(0);" data-id="{{ $quo->id }}"
                                        class="btn btn-danger delect_btn">Delete</a>
                                    <button type="submit" class="btn btn-primary ms-2">Submit</button>
                                </div>
                            </div>
                        </div>
                        <div class="card bg-light card-body mt-3 rounded">
                            <h5>Uploaded Files</h5>
                            @foreach ($quotation_uploads as $file)
                                @if ($file->quotation_file_id == $quo->id)
                                    @if ($file->add_work == 1)
                                        <h5>Additional Quotation File</h5>
                                    @endif
                                    <?php $image = URL::asset('public/quotationfile/' . $file->file); ?>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="me-2">
                                            <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                class="measurementfiles">
                                        </div>
                                        <a href="{{ $image }}" download>{{ $file->file_name }}
                                            <p class="mb-0 text-danger">
                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                            </p>
                                        </a>
                                        <div class="ms-auto">
                                            <a href="{{ $image }}" class="btn btn-primary" download><i
                                                    class="fa fa-download"></i></a>

                                            <a href="javascript:void(0);" data-id="{{ $file->id }}"
                                                class="btn btn-danger delete_file"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <h5>Description</h5>
                            <p>{{ $quo->description }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            <hr>
            <div class="quotation_new_div">
                @if (!blank($subquotationfiles))
                    @foreach ($subquotationfiles as $quo)
                        <?php $i++; ?>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="">
                                    <h5>Quotation
                                        @if ($quo->final == 1)
                                            <span class="text-success">( Final Quotation )</span>
                                        @endif
                                    </h5>
                                    @if ($quo->status == 0 || $quo->status == null)
                                    @else
                                        @if ($quo->status == 1)
                                            <span class="badge bg-success">Finalize by Admin</span>
                                        @elseif($quo->status == 2)
                                            <span class="text-danger">
                                                Rejected :
                                                @if ($quo->reject_reason == 1)
                                                    Delayed/Cool of
                                                @elseif ($quo->reject_reason == 2)
                                                    Cancel
                                                @elseif ($quo->reject_reason == 3)
                                                    Addon
                                                @endif
                                            </span>
                                            <p class="mb-0">Reject Note : {{ $quo->reject_note }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card bg-light card-body mt-3 rounded">
                            <h5>Other Uploaded Files</h5>
                            @foreach ($sub_quotation_uploads as $file)
                                @if ($file->quotation_file_id == $quo->id)
                                    @if ($file->add_work == 1)
                                        <h5>Additional Quotation File</h5>
                                    @endif
                                    <?php $image = URL::asset('public/quotationfile/' . $file->file); ?>
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="me-2">
                                            <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                class="measurementfiles">
                                        </div>
                                        <a href="{{ $image }}" download>{{ $file->file_name }}
                                            <p class="mb-0 text-danger">
                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                            </p>
                                        </a>
                                        <div class="ms-auto">
                                            <a href="{{ $image }}" class="btn btn-primary" download><i
                                                    class="fa fa-download"></i></a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <h5>Description</h5>
                            <p>{{ $quo->description }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </form>
        <div class="d-flex align-items-center justify-content-end mt-3">
            <a href="{{ route('view.measurement', $projects->id) }}" class="btn btn-primary me-3"><i
                    data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
            @if ($projects->status == 1)
                <a href="{{ !empty($quotations->project_id) ? route('view.material', $projects->id) : '#' }}"
                    class="{{ !empty($quotations->project_id) ? 'active_btn' : 'disabled' }} btn btn-primary">Next<i
                        data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
            @else
                <a href="{{ !empty($quotations->project_id) ? route('view.selection', $projects->id) : '#' }}"
                    class="{{ !empty($quotations->project_id) ? 'active_btn' : 'disabled' }} btn btn-primary">Next<i
                        data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
            @endif
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Reject Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reject.quotation') }}" method="post" id="rejectReasonForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="addressone">Reject Quotation Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="reason" id="reason" placeholder="Enter Reason"></textarea>
                            <span id="quotation_reject_reason_error" class="text-danger"></span>
                        </div>
                        {{-- <div class="form-group">
                    <label for="addressone">Quotation Final Cost *</label>
                    <input type="number" class="form-control" min="0" name="cost" id="cost"
                        placeholder="Enter cost" required>
                    <span class="text-danger"></span>
                </div> --}}
                        <input type="hidden" name="reject_reason" class="reject_reason" value="">
                        <input type="hidden" name="quotation_file_id" class="quotation_file_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @if (Session::has('success'))
        <script>
            Swal.fire('Rejected', 'Quotation Rejected Successfully!', 'success');
        </script>
    @endif
    <script>
        $('#rejectReasonForm').on('submit',function(e){
            var reason = $('#reason').val();
            $('#quotation_reject_reason_error').html('');
            if(reason.trim() == ""){
                $('#quotation_reject_reason_error').html('Please Enter Reason');
                e.preventDefault();
            }
        })
        $(document).on('click', '.finalizeQuotation', function() {
            var quotation_id = $(this).data('id');
            $.ajax({
                url: "{{ route('finalize.quotation', '') }}" + "/" + quotation_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {}
            });
        });
        $(document).on('click', '.rejectQuotation', function() {
            var quotation_id = $(this).data('id');
            var reason = $(this).data('reason');
            $('.reject_reason').val(reason);
            $('#quotation_reject_reason_error').html('');
            $('.quotation_file_id').val(quotation_id);
        });
        $(document).ready(function() {
            // if (window.File && window.FileList && window.FileReader) {
            $(document).on('change', '#formFile', function(e) {
                $('#loader').show();
                var files = e.target.files,
                    filesLength = files.length;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileReader = new FileReader();
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        var name = f.name;
                        var parts = name.split('.');
                        var ext = parts[parts.length - 1];
                        if (ext != 'jpg' || ext != 'png' || ext != 'jpeg') {
                            var img = '../../assets/media/image/docs.png'
                        } else {
                            var img = e.target.result;
                        }
                        $("<span class=\"pip\">" +
                            "<img class=\"imageThumb\" src=\"" + img +
                            "\" title=\"" + name + "\"/>" +
                            "<br/><span class=\"remove btn btn-primary\">Remove</span>" +
                            "</span>").insertAfter("#formFile");
                        $(".remove").click(function() {
                            $(this).parent(".pip").remove();
                            $("#formFile").val(null);
                        });
                    });
                    fileReader.readAsDataURL(f);
                }
                console.log(files);
                $('#loader').hide();
            });
            // } else {
            //     alert("Your browser doesn't support to File API")
            // }
            var quotationFilesExist = @json(!blank($quotationfiles));
            if (quotationFilesExist) {
                $(".quotation_add_div").hide();
            } else {
                $(".quotation_add_div").show();
            }
        });


        // delete file
        $(".delete_file").click(function() {
            var quo_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this file!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.quotation', '') }}" + "/" + quo_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "File has been deleted.",
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
                        }
                    });
                }
            });
        });
    </script>
    <script>
        var i = 100;
        $(".add_quotation_btn").click(function() {
            var type = $(this).data('type');
            i++;
            var html = '';
            html += '<input type="hidden" name="type" value="' + type + '">';
            html += '<div class="quotationCard row">';
            html += '<div class="form-group col-10">';
            html +=
                '<label for="formFile" class="form-label">Upload Quotation File <span class="text-danger">*</span></label>';
            html += '<input class="form-control add-quo-validation" type="file" id="formFile" name="quotations_file[]" multiple required>';
            html += '</div>';
            html += '<div class="me-2" id="loader" style="display:none">';
            html += '<img src="{{ url('/') }}/public/ZKZg.gif" alt=""class="measurementfiles">';
            html += '</div>';
            html += '<div class="form-group">';
            html += '<label for="measurementdesc">Quotation Description </label>';
            html += '<textarea class="form-control" id="measurementdesc" rows="3" name="quotation[' + i +
                '][description]"></textarea>';
            html += '</div>';
            html += '<div class="form-check">';
            html += '<input class="form-check-input" type="checkbox" id="quotationfinal' + i +
                '" name="quotation[' + i + '][final]">';
            html += '<label for="quotationfinal' + i + '" class="form-check-label">Final Quotation?</label>';
            html += '</div>';
            html += '<div class="col-md-12 mb-2">';
            html += '<button type="button" class="btn btn-danger quo-delete-btn">Delete</button>';
            html += '<button type="submit" class="btn btn-primary ms-2">Submit</button>';
            html += '</div>';
            html += '</div>';
            $(".newQuotation").append(html);
        });
    </script>
    <script>
        $(document).on("click", ".quo-delete-btn", function() {
            $(this).parent().parent().remove();
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delect_btn', function() {
                var employee_id = $(this).attr('data-id');
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
                            url: "{{ route('delete.quotation', '') }}" + "/" + employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Employee has been deleted.",
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
                            }
                        });
                    }
                });
            });
            $('input[name="material_option"]').change(function() {
                var option = $(this).val();
                var project_id = $('.projectID').val();
                $.ajax({
                    url: "{{ route('update_material_status', '') }}" + "/" + project_id,
                    type: 'POST',
                    data: {
                        '_token': "{{ csrf_token() }}",
                        "option": option
                    },
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Updated!',
                            text: "Material Selection status has been Updated.",
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
                    }
                });
                if ($(this).val() == 1) {
                    $('#addSelectionBtn').show();
                } else {
                    $('#addSelectionBtn').hide();
                }
            });
        });
    </script>
    <script>
        $(function() {
            if ($("#cgst_data input").val() !== "") {
                $("#gstcheckbox").prop("checked", true);
                $("#gstinclude").show();
            }
            $("#gstcheckbox").click(function() {
                if ($(this).is(":checked")) {
                    $("#gstinclude").show();
                } else {
                    $("#gstinclude").hide();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.delect_btn', function() {
                var employee_id = $(this).attr('data-id');
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
                            url: "{{ route('delete.quotation', '') }}" + "/" +
                                employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Quotation has been deleted.",
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Ok'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // $(".measurement_div").remove();
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
