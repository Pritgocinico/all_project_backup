@extends('admin.layouts.viewproject')

@section('pages')
    <div class="fieldset">
        <h2 class="fs-title">Project Complete</h2>
        <div>
        </div>
        <div class="mt-3">
            <div class="col-md-6 mt-3">
                <div class="view_project_details border_first">
                    <h6>Project Completed Date</h6>
                    <p>
                        {{ date('d/m/Y h:i:s A', strtotime($projects->end_date)) }}
                    </p>
                </div>
            </div>

            <div class="col-md-6 mt-3">
                <div class="view_project_details border_first">
                    <h6>Project Profit</h6>
                    <p>
                        &#8377; {{ $projects->margin_cost }}
                    </p>
                </div>
            </div>
        </div>
        @if (isset($addProject))
            <h2 class="fs-title">Additional Project Complete</h2>
            <div>
            </div>
            @foreach ($addProject as $add)
                <div class="mt-3">
                    <div class="col-md-6 mt-3">
                        <div class="view_project_details border_first">
                            <h6>Project Completed Date</h6>
                            <p>
                                @if (isset($add->end_date))
                                    {{ date('d/m/Y h:i:s A', strtotime($add->end_date)) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3">
                        <div class="view_project_details border_first">
                            <h6>Project Profit</h6>
                            <p>
                                &#8377; {{ $add->margin_cost }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('resume.work') }}" id="resume_work_form">
                    <div class="page-title d-flex justify-content-start gap-1 me-3">
                        <input type="hidden" name="resume_work_option" id="status" value="2">
                        <input type="hidden" name="project_id" value="{{ $projects->id }}">
                        <div
                            class="page-heading d-flex flex-column justify-content-start text-gray-900 fw-bold fs-3 m-0 ms-3">
                            <button class="btn btn-primary text-dark dropdown-item" href="#" data-value="2" type="submit">
                                Issue In Work
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-end mt-3">
                    <a href="{{ route('view.fitting', $projects->id) }}" class="btn btn-primary"><i
                            data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
                </div>
            </div>
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
                            url: "{{ route('delete.fittingimage', '') }}" + "/" +
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
