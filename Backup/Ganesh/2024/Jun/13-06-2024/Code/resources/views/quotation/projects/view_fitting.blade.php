@extends('quotation.layouts.viewproject')

@section('pages')
<div class="fieldset">
    <div class="d-flex">
        <button type="button" class="btn btn-primary btn-add ms-auto">Add Question</button>
    </div>
    <h2 class="fs-title">Fitting</h2>
    <h3 class="fs-subtitle">Enter your Fitting details</h3>
    <hr>
    <div>
        <form class="" action="{{ route('quotation_storefittingquestion') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="fitting_show_hide" style="display: none">
                <div class="form-group">
                    <label for="fitting_question">Question</label>
                    <textarea class="form-control" id="fitting_question" rows="5" name="fitting_question"></textarea>
                    @if ($errors->has('fitting_question'))
                        <span class="text-danger">{{ $errors->first('fitting_question') }}</span>
                    @endif
                </div>
                <input type="hidden" name="project_id" value="{{ $projects->id }}">
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </div>
        </form>
    </div>
    <div class="mt-3">
        <form class="fitting_form" action="{{ route('quotation_storefitting') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projects->id }}">
         <table class="table">
                        <tr>
                            <th>Question</th>
                            <th>Yes/No</th>
                        </tr>
                        @if(count($fitting_doneTasks) != 0)
                            @foreach ($fitting_doneTasks as $d_item)
                            <tr>
                                <th>{{$d_item->question->fitting_question}}</th>
                                <input type="hidden" name="chk{{$d_item->question_id}}" value="off"> <!-- Hidden input for unchecked checkboxes -->
                                <td><input type="checkbox" name="chk{{$d_item->question_id}}" @if ($d_item->chk == "on") checked @endif></td>
                            </tr>
                            @endforeach
                        @else
                            @foreach ($fitting_questions as $w_item)
                                <tr>
                                    <th>{{$w_item->fitting_question}}</th>
                                    <input type="hidden" name="chk{{$w_item->id}}" value="off"> <!-- Hidden input for unchecked checkboxes -->
                                    <td><input type="checkbox" name="chk{{$w_item->id}}"></td>
                                </tr>
                            @endforeach
                        @endif
                    </table>
            <button type="submit" class="btn btn-primary mt-2 submit_btn" data-id="{{ $projects->id }}">Submit</button>
          
    
    
        </form>
    </div>
    <div>
        <form class="" action="{{route('quotation_projectDone')}}" enctype="multipart/form-data" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $projects->id }}">
            <div class="form-group mt-4">
                <label for="flexRadioDefault">Is Project Done?</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="project_done" id="flexRadioDefault11" value="1" @if($projects->status == 2) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault11"> Yes </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="project_done" id="flexRadioDefault21"
                            value="0" @if($projects->status == 0 || $projects->status == 1) checked @endif>
                        <label class="form-check-label" for="flexRadioDefault21"> No </label>
                    </div>
                </div>
            </div>
            <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
            <button type="submit" class="btn btn-primary convertProject @if($projects->status == 2) d-none @endif">Complete Project</button>
        </form>
    </div>
    
    <div class="d-flex align-items-center justify-content-end mt-3">
        <a href="{{ route('quotation_view.workshop', $projects->id)  }}" class="btn btn-primary"><i data-feather="arrow-left"
                class="me-2 fw-bold"></i>Previous</a>
    </div>
</div>

@endsection
@section('script')
<script>
    $(".btn-add").click(function() {
        $(".fitting_show_hide").toggle();
    });
</script>
<script>
$(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
        $("#files").on("change", function(e) {
            var files = e.target.files,
                filesLength = files.length;
            for (var i = 0; i < filesLength; i++) {
                var f = files[i]
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                    var file = e.target;
                    $("<span class=\"pip\">" +
                        "<img class=\"imageThumb\" src=\"" + e.target.result +
                        "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"remove btn btn-primary\">Remove</span>" +
                        "</span>").insertAfter("#files");
                    $(".remove").click(function() {
                        $(this).parent(".pip").remove();
                    });
                });
                fileReader.readAsDataURL(f);
            }
            console.log(files);
        });
    } else {
        alert("Your browser doesn't support to File API")
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var statusSelect = document.getElementById("status");
    var deliveryDateField = document.getElementById("deliveryDateField");
    var fittingDateField = document.getElementById("fittingDateField");
    statusSelect.addEventListener("change", function() {
        deliveryDateField.style.display = statusSelect.value == "2" ? "block" : "none";
        fittingDateField.style.display = statusSelect.value == "3" ? "block" : "none";
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var statusSelect = document.getElementById("status");
    var fittingCompleteModal = new bootstrap.Modal(document.getElementById('fittingCompleteModal'));
    var fittingCompleteDateField = document.getElementById("completedate");
    var submitBtn = document.querySelector('.submit_btn');
    submitBtn.addEventListener('click', function(event) {
        event.preventDefault();
        if (statusSelect.value == "4") {
            fittingCompleteModal.show();
        }
    });
});
</script>
<script>
// $(document).on('click', '.submit_btn', function() {
//     var statusError = document.getElementById('status_error');
//     var deliveryDateError = document.getElementById('delivery_date_error');
//     var fittingDateError = document.getElementById('fitting_date_error');
//     var id = $(this).data('id');
//     var statusSelect = $("#status");
//     var i = 0;
//     statusError.innerHTML = "";
//     deliveryDateError.innerHTML = "";
//     fittingDateError.innerHTML = "";
//     if (statusSelect.val() == 0) {
//         i++;
//         statusError.innerHTML = "Status must be selected!";
//         statusError.style.color = "Red";
//     } else if (statusSelect.val() == 2) {
//         var deliveryDateField = document.getElementById("deliveryDateField");
//         var deliveryDateInput = document.getElementsByName("deliverydate")[0];
//         if (deliveryDateInput.value === "") {
//             i++;
//             deliveryDateError.innerHTML = "Delivery Date must be filled!";
//             deliveryDateError.style.color = "Red";
//         } else {
//             document.getElementsByClassName("fitting_form")[0].submit();
//         }
//     } else if (statusSelect.val() == 3) {
//         var fittingDateField = document.getElementById("fittingDateField");
//         var fittingDateInput = document.getElementsByName("fittingdate")[0];
//         if (fittingDateInput.value === "") {
//             i++;
//             fittingDateError.innerHTML = "Fitting Date must be filled!";
//             fittingDateError.style.color = "Red";
//         } else {
//             document.getElementsByClassName("fitting_form")[0].submit();
//         }
//     }
// });
// $(document).on('click', '.modal_submit', function() {
//     var compeleteDateError = document.getElementById('complete_date_error');
//     // var fittingDateField = document.getElementById("fittingDateField");
//     var completeDateInput = document.getElementsByName("completedate")[0];
//     var id = $(this).data('id');
//     var i = 0;
//     compeleteDateError.innerHTML = "";

//     if (completeDateInput.value === "") {
//         i++;
//         compeleteDateError.innerHTML = "Complete Date must be filled!";
//         compeleteDateError.style.color = "Red";
//     } else {
//         document.getElementsByClassName("model_fitting_form")[0].submit();
//     }
// });
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.delect_btn', function() {
        var fitting_id = $(this).attr('data-id');
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
                    url: "{{ route('quotation_delete.fittingimage', '') }}" + "/" +
                        fitting_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Image has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('.fitting_id_' + fitting_id).remove();
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