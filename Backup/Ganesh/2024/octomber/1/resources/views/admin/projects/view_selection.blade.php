@extends('admin.layouts.viewproject')

@section('pages')

<div class="fieldset">
    <div class="d-flex align-items-center">
        <h2 class="fs-title mb-0">Confirmation</h2>
    </div>
    @if (!blank($quotations))
    <form class="" action="{{ route('update_material_status') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="form-group mt-4">
            <label for="flexRadioDefault">Is selection Done?</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="material_option" id="flexRadioDefault1"
                        @if ($projects->material_selection == 1) checked @endif value="1">
                    <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="material_option" id="flexRadioDefault2"
                        value="0" @if ($projects->material_selection == 0) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2"> No </label>
                </div>
            </div>
        </div>
        <div id="selection-date-container" class="form-group mt-4">
            <label for="selection_date">Selection Date</label>
            <div class="d-flex gap-3">
                <div class="">
                    @php
                        $formattedDateTime = null;
                        if ($projects->selection_date) {
                            $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->selection_date));
                        }
                    @endphp
                    <input class="form-control" type="datetime-local" name="selection_date" id="selection_date" 
                        @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                </div>
            </div>
        </div>
        <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
    @endif
    @if($projects->type == 0)
        <form class="" action="{{route('store.selection')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projects->id }}">
            <div class="form-group mt-4">
                <label for="flexRadioDefault">Is Project is final?</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="project_final" id="flexRadioDefault11" value="1" @if($projects->status == 1) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault11"> Yes </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="project_final" id="flexRadioDefault21"
                            value="0" @if($projects->status == 0) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault21"> No </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
            <button type="submit" class="btn btn-primary convertProject @if($projects->status == 0) d-none @endif">Convert to Project</button>

        </form>
    @else
        <h3>Lead Converted to Project.</h3>
        <a href="{{route('view.project',$projects->id)}}" class="btn btn-primary">View Project</a>
    @endif
    <div class="d-flex align-items-center justify-content-end mt-3">
        <a href="{{ route('view.quotation', $projects->id)  }}" class="btn btn-primary me-3"><i
                data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
        {{-- <a href="{{ !empty($quotations->project_id) ? route('view.workshop', $projects->id) : '#' }}"
            class="{{ !empty($quotations->project_id) ? 'active_btn' : 'disabled' }} btn btn-primary">Next<i
                data-feather="arrow-right" class="ms-2 fw-bold"></i></a> --}}
    </div>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Reject Quotation</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{route('admin.reject.quotation')}}" method="post">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="addressone">Reject Quotation Reason *</label>
                    <textarea class="form-control" name="reason" id="reason"
                        placeholder="Enter Reason" required></textarea>
                    <span class="text-danger"></span>
                </div>
                <div class="form-group">
                    <label for="addressone">Quotation Final Cost *</label>
                    <input type="number" class="form-control" min="0" name="cost" id="cost"
                        placeholder="Enter cost" required>
                    <span class="text-danger"></span>
                </div>
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
@if(Session::has('success'))
    <script>
        Swal.fire('Rejected','Quotation Rejected Successfully!','success');
    </script>
@endif
<script>
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#flexRadioDefault1').is(':checked')) {
                $('#selection-date-container').show();
            } else {
                $('#selection-date-container').hide();
            }
        }

        // Initial check on page load
        toggleSelectionDate();

        // Bind change event
        $('input[name="material_option"]').change(function() {
            toggleSelectionDate();
        });
    });
</script>
<script>
    $(document).on('click','.finalizeQuotation',function(){
        var quotation_id = $(this).data('id');
        $.ajax({
            url: "{{ route('finalize.quotation', '') }}" + "/" + quotation_id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
            }
        });
    });
    $(document).on('click','.rejectQuotation',function(){
        var quotation_id = $(this).data('id');
        $('.quotation_file_id').val(quotation_id);
    });
    $(document).ready(function() {
        var quotationFilesExist = @json(!blank($quotationfiles));
        if (quotationFilesExist){
            $(".quotation_add_div").hide();
        } else {
            $(".quotation_add_div").show();
        }
    });
</script>
<script>
    var i = 100;
    $(".add_quotation_btn").click(function() {
        i++;
        var html = '';
        html += '<div class="quotationCard row">';
        html += '<div class="form-group col-10">';
        html += '<label for="formFile" class="form-label">Upload Quotation File <span class="text-danger">*</span></label>';
        html += '<input class="form-control" type="file" id="formFile" name="quotation['+i+'][quotations_file]">';
        html += '</div>';
        html += '<div class="form-group col-md-6">';
        html += '<label for="quotationCost'+i+'" class="form-label">Quotation Cost <span class="text-danger">*</span></label>';
        html += '<input class="form-control" type="number" id="quotationCost'+i+'" name="quotation['+i+'][quotation_cost]">';
        html += '</div>';
        html += '<div class="form-group col-md-6">';
        html += '<label for="quotationPrice'+i+'" class="form-label">Project Total Price <span class="text-danger">*</span></label>';
        html += '<input class="form-control" type="number" id="projectCost'+i+'" name="quotation['+i+'][project_cost]">';
        html += '</div>';
        html += '<div class="form-group">';
        html += '<label for="measurementdesc">Quotation Description </label>';
        html += '<textarea class="form-control" id="measurementdesc" rows="3" name="quotation['+i+'][description]"></textarea>';
        html += '</div>';
        html += '<div class="form-check">';
        html += '<input class="form-check-input" type="checkbox" id="quotationfinal'+i+'" name="quotation['+i+'][final]">';
        html += '<label for="quotationfinal'+i+'" class="form-check-label">Final Quotation?</label>';
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
             $('input[name="project_final"]').change(function() {
                var option = $(this).val();
                var project_id = $('.projectID').val();
                if ($(this).val() == 1) {
                    $('.convertProject').removeClass('d-none');
                    $('.convertProject').addClass('d-block');

                } else {
                    $('.convertProject').removeClass('d-block');
                    $('.convertProject').addClass('d-none');
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
