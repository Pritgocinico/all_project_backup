@extends('quotation.layouts.viewproject')

@section('pages')
    <div class="fieldset">
        <div class="d-flex">
            <button type="button" class="btn btn-primary btn-add ms-auto">Add Question</button>
        </div>
        <h2 class="fs-title">Workshop</h2>
        <h3 class="fs-subtitle">Enter your Workshop details</h3>
        <hr>
        <div>
            <form class="" action="{{ route('quotation_storeworkshopquestion') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="workshop_show_hide" style="display: none">
                    <div class="form-group">
                        <label for="workshop_question">Question</label>
                        <textarea class="form-control" id="workshop_question" rows="5" name="workshop_question"></textarea>
                        @if ($errors->has('workshop_question'))
                            <span class="text-danger">{{ $errors->first('workshop_question') }}</span>
                        @endif
                    </div>
                    <input type="hidden" name="project_id" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </div>
            </form>
        </div>
        <div class="mt-3">
            <form class="" action="{{ route('quotation_storeworkshop') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $projects->id }}">
                <table class="table">
                    <tr>
                        <th>Question</th>
                        <th>Yes/No</th>
                        <th><input type="checkbox" id="checkAll"></th>
                    </tr>
                    {{-- @foreach ($workshop_questions as $w_item)
                        <tr>
                            <th>{{$w_item->workshop_question}}</th>
                            <input type="hidden" name="chk{{$w_item->id}}" value="off"> <!-- Hidden input for unchecked checkboxes -->
                            <td><input type="checkbox" name="chk{{$w_item->id}}" @if ($w_item->chk == "on") checked @endif></td>
                        </tr>
                    @endforeach --}}
                        @if(count($workshop_doneTasks) != 0)
                            @foreach ($workshop_doneTasks as $d_item)
                            <tr>
                                <th>{{$d_item->wquestion->workshop_question}}</th>
                                <input type="hidden" name="chk{{$d_item->question_id}}" value="off"> <!-- Hidden input for unchecked checkboxes -->
                                <td><input type="checkbox" name="chk{{$d_item->question_id}}" @if ($d_item->chk == "on") checked @endif></td>
                                <td></td>
                            </tr>
                            @endforeach
                        @else
                            @foreach ($workshop_questions as $w_item)
                                <tr>
                                    <th>{{$w_item->workshop_question}}</th>
                                    <input type="hidden" name="chk{{$w_item->id}}" value="off"> <!-- Hidden input for unchecked checkboxes -->
                                    <td><input type="checkbox" name="chk{{$w_item->id}}"></td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    {{-- <tr>
                        <th>કટિંગ પહેલા નું pvc રેપિંગ</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>કાતરા કાચ ફિટ થયા પહેલા અને કાચ ફિટ થયા પછી ચેક કરવા</th>
                        <td><input type="checkbox" name="chk2" checked=""></td>
                    </tr>
                    <tr>
                       <th>કાતરા માં કલર કર્યો કે નહિ</th>
                        <td><input type="checkbox" name="chk3" checked=""></td>
                    </tr>
                    <tr>
                        <th>કાચ હલે છે કે નહિ</th>
                        <td><input type="checkbox" name="chk4" checked=""></td>
                    </tr>
                    <tr>
                        <th>કટિંગ પહેલા નું pvc રેપિંગ</th>
                        <td><input type="checkbox" name="chk5" checked=""></td>
                    </tr>
                    <tr>
                        <th>કાતરા ફાઇલિંગ અને કલર ચેક કરવા</th>
                        <td><input type="checkbox" name="chk6" checked=""></td>
                    </tr>
                    <tr>
                        <th>16*45 માં કાય ફિટ થયા પછી સિલિકોન ચેક કરવી</th>
                        <td><input type="checkbox" name="chk7" checked=""></td>
                    </tr>
                    <tr>
                        <th>પિવોટ કલર સેમ છે કે નહિ ચેક કરવા</th>
                        <td><input type="checkbox" name="chk8" checked=""></td>
                    </tr>
                    <tr>
                        <th>મિજાગરા સીધા છે કે નહિ ચેક કરવા</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>કાચ માં જતા પહેલા માપ ચેક કરવા</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>ડીસ્પેચ વખતે માલ ડેમેજ ચેક કરવો</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>1 mm બોર્ડર માં ગ્લાસ માં ગેપ ચેક કરવી 1 mm</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>1 mm બોર્ડર માં ગ્લાસ ની ધાર માં સિલિકોન ચેક કરવી 1 mm</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>1 mm બોર્ડર માં ગ્લાસ ની ધાર ચેક કરવી 1 mm</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr>
                    <tr>
                        <th>16*45 માં ટોપ બોટમ ની કલર ચેક કરવો</th>
                        <td><input type="checkbox" name="chk1" checked=""></td>
                    </tr> --}}
                </table>
                <?php $i=0; ?>
                <!--@if(!blank($workshops)) -->
                <!--        @foreach ($workshops as $work)-->
                <!--        <?php $i++; ?>-->
                <!--                <div class="row quotation align-items-end mb-2">-->
                <!--                    <div class="col-md-3">-->
                <!--                        <input type="hidden" name="workshop[{{$i}}][id]" value="{{$work->id}}">-->
                <!--                        <label for="mesurement" class="form-label">Measurement <span class="text-danger">*</span></label>-->
                <!--                        <input class="form-control" type="text" id="mesurement" name="workshop[{{$i}}][measurement]"  value="{{$work->measurement}}" required>-->
                <!--                    </div>-->
                <!--                    <div class="col-md-7">-->
                <!--                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>-->
                <!--                        <input class="form-control" type="text" id="description" name="workshop[{{$i}}][description]" value="{{$work->description}}" required>-->
                <!--                    </div>-->
                <!--                    <div class="col-md-1">-->
                <!--                        <a href="javascript:void(0);" data-id="{{ $work->id }}" class="btn btn-primary delect_btn">Delete</a>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!--        @endforeach-->
                <!--    @else{{old('description')}}-->
                <!--@endif-->
                <div data-repeater-item class="workshop_div">
                </div>

                <!--<button type="button" class="btn btn-primary mb-2 mt-4 add_workshop" data-repeater-create>-->
                <!--    <i class="ti-plus font-size-10 mr-2"></i> Add Workshop Tasks-->
                <!--</button>-->
                <hr>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>

            <div class="form-group mt-4">
                <form class="" action="{{ route('quotation_updateCutting') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Cutting  </h4>
                        <span class="ms-2">(Workshop 5.1)</span>
                    </div>
                    <label for="flexRadioDefault">Is Cutting Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cutting_option" id="flexRadioDefault1"
                                @if ($projects->cutting_selection == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cutting_option" id="flexRadioDefault2"
                                value="0" @if ($projects->cutting_selection == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Cutting Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = null;
                                    if ($projects->cutting_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->cutting_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="cutting_date" id="cutting_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('quotation_updateShutter') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Shutter Joinery  </h4>
                        <span class="ms-2">(Workshop 5.2)</span>
                    </div>
                    <label for="flexRadioDefault">Is Shutter Joinery Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_joinery" id="flexRadioDefault1"
                                @if ($projects->shutter_selection == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_joinery" id="flexRadioDefault2"
                                value="0" @if ($projects->shutter_selection == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Shutter Joinery Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = null;
                                    if ($projects->shutter_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->shutter_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="shutter_date" id="shutter_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('quotation_updateGlassmeasure') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Glass Measurement  </h4>
                        <span class="ms-2">(Workshop 5.3)</span>
                    </div>
                    <label for="flexRadioDefault">Is Glass Measurement Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_measurement" id="flexRadioDefault1"
                                @if ($projects->glass_measurement == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_measurement" id="flexRadioDefault2"
                                value="0" @if ($projects->glass_measurement == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Glass Measurement Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = null;
                                    if ($projects->glass_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->glass_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="glass_date" id="glass_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('quotation_updateGlassReceive') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Glass Received?  </h4>
                        <span class="ms-2">(Workshop 5.4)</span>
                    </div>
                    <label for="flexRadioDefault">Is Glass received at workshop?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_receive" id="flexRadioDefault1"
                                @if ($projects->glass_receive == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_receive" id="flexRadioDefault2"
                                value="0" @if ($projects->glass_receive == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Glass Received Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = null;
                                    if ($projects->glass_receive_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->glass_receive_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="glass_receive_date" id="glass_receive_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('quotation_updateShutterReady') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Fitting  </h4>
                        <span class="ms-2">(Workshop 5.5)</span>
                    </div>
                    <label for="flexRadioDefault">Is shutter ready with glass fitting?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_ready" id="flexRadioDefault1"
                                @if ($projects->shutter_ready == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_ready" id="flexRadioDefault2"
                                value="0" @if ($projects->shutter_ready == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Shutter Ready Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = null;
                                    if ($projects->shutter_ready_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->shutter_ready_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="shutter_ready_date" id="shutter_ready_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <form class="" action="{{ route('quotation_storeInvoiceStatus') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Invoice Status  </h4>
                        <span class="ms-2">(Workshop 5.6)</span>
                    </div>
                    <label for="flexRadioDefault">Is Invoice generated ?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="invoice_status" id="invoice_yes_radio"
                                @if ($projects->invoice_status == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="invoice_status" id="flexRadioDefault2"
                                value="0" @if ($projects->invoice_status == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div class="form-group" id="invoice_files_container" style="display: none;">
                        <label for="projectdesc">Upload Invoice Files</label>
                        <input class="form-control" type="file" id="files" name="invoicefiles[]" multiple />
                        <div class="me-2" id="loader" style="display:none">
                            <img src="{{ url('/') }}/public/ZKZg.gif" alt="" class="measurementfiles">
                        </div>
                    </div>
                    <label for="flexRadioDefault">Date</label>
                    <div class="d-flex gap-3">
                        <div class="">
                            @php
                                $formattedDateTime = null;
                                if ($projects->invoice_date) {
                                    $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->invoice_date));
                                }
                            @endphp
                            <input class="form-control" type="datetime-local" name="invoice_date" id="invoice_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{$projects->id}}">
                <button type="submit" class="btn btn-primary mt-2">Save</button>
            </form>
            <hr>

            <form class="" action="{{ route('quotation_storeMaterialStatus') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Material Delivered  </h4>
                        <span class="ms-2">(Workshop 5.7)</span>
                    </div>
                    <label for="flexRadioDefault">Is Material delivered?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="material_delivered" id="flexRadioDefault1"
                                @if ($projects->material_delivered == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="material_delivered" id="flexRadioDefault2"
                                value="0" @if ($projects->material_delivered == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Delivered By</label>
                    <div class="d-flex gap-3">
                        <div class="">
                            <input class="form-control" type="text" name="delivered_by" id="delivered_by" @if ($projects->delivered_by) value="{{$projects->delivered_by}}" @endif>
                        </div>
                    </div>
                    <label for="flexRadioDefault">Delivered Date</label>
                    <div class="d-flex gap-3">
                        <div class="">
                            @php
                                $formattedDateTime = null;
                                if ($projects->deliver_date) {
                                    $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->deliver_date));
                                }
                            @endphp
                            <input class="form-control" type="datetime-local" name="deliver_date" id="deliver_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{$projects->id}}">
                <button type="submit" class="btn btn-primary mt-2">Save</button>
            </form>

            <div class="d-flex align-items-center justify-content-end mt-3">
                @if($projects->status == 1)
                    <a href="{{ route('quotation_view.material', $projects->id)  }}" class="btn btn-primary me-3"><i data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
                @else
                    <a href="{{ route('quotation_view.quotation', $projects->id)  }}" class="btn btn-primary me-3"><i data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
                @endif
                <a href="{{ route('quotation_view.fitting', $projects->id)}}" class="btn btn-primary">Next<i data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(".btn-add").click(function() {
        $(".workshop_show_hide").toggle();
    });

    $(document).ready(function () {
        if ($('#invoice_yes_radio').is(':checked')) {
            $('#invoice_files_container').show();
        } else {
            $('#invoice_files_container').hide();
        }

        $('#invoice_yes_radio').change(function () {
            if ($(this).is(':checked')) {
                $('#invoice_files_container').show();
            } else {
                $('#invoice_files_container').hide();
            }
        });
    });
</script>
    <script>
        var i = 100;
        $(".add_workshop").click(function() {
            i++;
            var html = '';
            html += '<div class="row quotation align-items-end mb-2">';
            html += '<div class="col-md-3">';
            html +=
                '<label for="mesurement" class="form-label">Measurement <span class="text-danger">*</span></label>';
            html += '<input class="form-control" type="text" id="mesurement" name="workshop['+i+'][measurement]" required>';
            html += '</div>';
            html += '<div class="col-md-7">';
            html +='<label for="description" class="form-label">Description <span class="text-danger">*</span></label>';
            html += '<input class="form-control" type="text" id="description" name="workshop['+i+'][description]" required>';
            html += '</div>';
            html += '<div class="col-md-1">';
            html += '<button type="button" class="btn btn-primary quo-delete-btn">Delete</button>';
            html += '</div>';
            html += '</div>';
            $(".workshop_div").append(html);
        });
    </script>
    <script>
        $(document).on("click", ".quo-delete-btn", function() {
            $(this).parent().parent().remove();
        });

        // cutting
    $(document).ready(function() {
        $('input[name="cutting_option"]').change(function() {
            var selection = $(this).val();
            var project_id = $('.projectID').val();
            $.ajax({
                url: "{{ route('quotation_updateCutting', '') }}" + "/" + project_id,
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    "selection": selection
                },
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: "Cutting status has been Updated.",
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
        });
    });

    // shutter joinery
    $(document).ready(function() {
        $('input[name="shutter_joinery"]').change(function() {
            var selection = $(this).val();
            var project_id = $('.projectID').val();
            $.ajax({
                url: "{{ route('quotation_updateShutter', '') }}" + "/" + project_id,
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    "selection": selection
                },
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: "Shutter Joinery status has been Updated.",
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
        });
    });

    // Glass Measurement
    $(document).ready(function() {
        $('input[name="glass_measurement"]').change(function() {
            var selection = $(this).val();
            var project_id = $('.projectID').val();
            $.ajax({
                url: "{{ route('quotation_updateGlassmeasure', '') }}" + "/" + project_id,
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    "selection": selection
                },
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: "Glass Measurement status has been Updated.",
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
        });
    });

    // Glass Receive
    $(document).ready(function() {
        $('input[name="glass_receive"]').change(function() {
            var selection = $(this).val();
            var project_id = $('.projectID').val();
            $.ajax({
                url: "{{ route('quotation_updateGlassReceive', '') }}" + "/" + project_id,
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    "selection": selection
                },
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: "Glass Received status has been Updated.",
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
        });
    });

    // Shutter Ready
    $(document).ready(function() {
        $('input[name="shutter_ready"]').change(function() {
            var selection = $(this).val();
            var project_id = $('.projectID').val();
            $.ajax({
                url: "{{ route('quotation_updateShutterReady', '') }}" + "/" + project_id,
                type: 'POST',
                data: {
                    '_token': "{{ csrf_token() }}",
                    "selection": selection
                },
                dataType: 'json',
                success: function(data) {
                    Swal.fire({
                        title: 'Updated!',
                        text: "Shutter Fitting status has been Updated.",
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
                            url: "{{ route('quotation_delete.workshop', '') }}" + "/" +
                                employee_id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: "Task has been deleted.",
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
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="chk"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = this.checked;
            }, this);
        });
    
        document.getElementById('workshopForm').addEventListener('submit', function() {
            // Remove the hidden inputs for unchecked checkboxes before submitting
            var hiddenInputs = document.querySelectorAll('input[type="hidden"][name^="chk"]');
            hiddenInputs.forEach(function(hiddenInput) {
                hiddenInput.remove();
            });
        });
    </script>
@endsection
