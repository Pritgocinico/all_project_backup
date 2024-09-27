@extends('admin.layouts.viewproject')

@section('pages')
    <div class="fieldset">
        <div class="d-flex">
            <button type="button" class="btn btn-primary btn-add ms-auto">Add Question</button>
        </div>
        <h2 class="fs-title">Workshop</h2>
        <h3 class="fs-subtitle">Enter your Workshop details</h3>
        <div>
            <form class="" action="{{ route('storeworkshopquestion') }}" enctype="multipart/form-data" method="POST">
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
            <form class="" action="{{ route('storeworkshop') }}" enctype="multipart/form-data" method="POST">
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
                                    <td><input type="checkbox" name="chk{{$w_item->id}}" @if ($w_item->chk == "on") checked @endif></td>
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
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
            <div class="form-group mt-4">
                <form class="" action="{{ route('updateCutting') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Cutting  </h4>
                        <span class="ms-2">(Workshop 5.1)</span>
                    </div>
                    <label for="flexRadioDefault">Is Cutting Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cutting_option" id="cutting_option_radio1"
                                @if ($projects->cutting_selection == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cutting_option" id="cutting_option_radio2"
                                value="0" @if ($projects->cutting_selection == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div id="cutting-date-container" class="form-group mt-4">
                        <label for="cutting_date">Cutting Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->cutting_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->cutting_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="cutting_date" id="cutting_date" 
                                    @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('updateShutter') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Shutter Joinery  </h4>
                        <span class="ms-2">(Workshop 5.2)</span>
                    </div>
                    <label for="flexRadioDefault">Is Shutter Joinery Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_joinery" id="shutter_joinery_radio1"
                                @if ($projects->shutter_selection == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_joinery" id="shutter_joinery_radio2"
                                value="0" @if ($projects->shutter_selection == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div id="shutter-date-container" class="form-group mt-4">
                        <label for="shutter_date">Shutter Joinery Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->shutter_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->shutter_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="shutter_date" id="shutter_date" 
                                    @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('updateGlassmeasure') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Glass Measurement  </h4>
                        <span class="ms-2">(Workshop 5.3)</span>
                    </div>
                    <label for="flexRadioDefault">Is Glass Measurement Done?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_measurement" id="glass_measurement_radio1"
                                @if ($projects->glass_measurement == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_measurement" id="glass_measurement_radio2"
                                value="0" @if ($projects->glass_measurement == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div id="glass-date-container" class="form-group mt-4">
                        <label for="glass_date">Glass Measurement Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->glass_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->glass_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="glass_date" id="glass_date" 
                                    @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('updateGlassReceive') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Glass Received?  </h4>
                        <span class="ms-2">(Workshop 5.4)</span>
                    </div>
                    <label for="flexRadioDefault">Is Glass received at workshop?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_receive" id="glass_receive_radio1"
                                @if ($projects->glass_receive == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="glass_receive" id="glass_receive_radio2"
                                value="0" @if ($projects->glass_receive == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div id="glass-receive-date-container" class="form-group mt-4">
                        <label for="glass_receive_date">Glass Received Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->glass_receive_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->glass_receive_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="glass_receive_date" id="glass_receive_date" 
                                    @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <div class="form-group mt-4">
                <form class="" action="{{ route('updateShutterReady') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Fitting  </h4>
                        <span class="ms-2">(Workshop 5.5)</span>
                    </div>
                    <label for="flexRadioDefault">Is shutter ready with glass fitting?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_ready" id="shutter_ready_radio1"
                                @if ($projects->shutter_ready == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shutter_ready" id="shutter_ready_radio2"
                                value="0" @if ($projects->shutter_ready == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2"> No </label>
                        </div>
                    </div>
                    <div id="shutter-ready-date-container" class="form-group mt-4">
                        <label for="shutter_ready_date">Glass Received Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->shutter_ready_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->shutter_ready_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="shutter_ready_date" id="shutter_ready_date" 
                                    @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <hr>

            <form class="" action="{{ route('storeInvoiceStatus') }}" enctype="multipart/form-data" method="POST">
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
                    <div id="invoice-date-container" class="form-group">
                        <label for="flexRadioDefault">Date</label>
                        <div class="d-flex gap-3">
                            <div class="">
                                @php
                                    $formattedDateTime = date('Y-m-d\TH:i');
                                    if ($projects->invoice_date) {
                                        $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->invoice_date));
                                    }
                                @endphp
                                <input class="form-control" type="datetime-local" name="invoice_date" id="invoice_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{$projects->id}}">
                <button type="submit" class="btn btn-primary mt-2">Save</button>
            </form>
                @if(!blank($invoiceFiles))
                    <div class="d-flex align-items-center pb-3 mt-3">
                        <h4 class="mb-0">Invoice Files </h4>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($invoiceFiles as $file)
                                <?php $image = URL::asset('public/invoicefiles/' . $file->invoice); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $file->invoice }}
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small></p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                        <a href="javascript:void(0);" data-id="{{$file->id}}" class="btn btn-danger delete_file"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif
            <hr>

            <form action="{{ route('storeMaterialStatus') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="row mt-3">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Material Delivered</h4>
                        <span class="ms-2">(Workshop 5.7)</span>
                    </div>
                    <label for="flexRadioDefault">Is Material delivered?</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="material_delivered" id="material_partial_radio" @if ($projects->material_delivered == 2) checked @endif value="2">
                            <label class="form-check-label" for="flexRadioDefault1">Partially delivered</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="material_delivered" id="material_yes_radio" @if ($projects->material_delivered == 1) checked @endif value="1">
                            <label class="form-check-label" for="flexRadioDefault1">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="material_delivered" id="material_no_radio" value="0" @if ($projects->material_delivered == 0) checked @endif>
                            <label class="form-check-label" for="flexRadioDefault2">No</label>
                        </div>
                    </div>
                    <div id="yes_deliver_detail">
                        <div class="form-group col-md-6">
                        <label for="flexRadioDefault">Delivered By</label>
                            <input class="form-control" type="text" name="delivered_by" id="delivered_by" @if ($projects->delivered_by) value="{{ $projects->delivered_by }}" @endif>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="flexRadioDefault">Driver Number</label>
                               <input type="text" class="form-control" name="driver_numbers" placeholder="Driver Number" pattern="\d{10}" maxlength="10"  @if ($projects->driver_number) value="{{ $projects->driver_number }}" @endif title="Driver Number must be a 10-digit number" required>
                        </div>
                        <label for="flexRadioDefault">Delivered Date</label>
                        <div class="d-flex gap-3">
                            @php
                                $formattedDateTime = date('Y-m-d\TH:i');
                                if ($projects->deliver_date) {
                                    $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->deliver_date));
                                }
                            @endphp
                            <input class="form-control" type="datetime-local" name="deliver_date" id="deliver_date" @if ($formattedDateTime) value="{{ $formattedDateTime }}" @endif>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary add-partial ms-auto">Add Details</button>
                    <div>
                        <div class="partial_detail form-row row" style="display: none">
                            <div class="form-group col-md-6">
                                <label>Delivered By </label>
                                <input type="text" class="form-control" name="partial_deliver_by" placeholder="Driver Name" id="partial_deliver_by">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Driver Number</label>
                                <input type="text" class="form-control" name="driver_number" placeholder="Driver Number" pattern="\d{10}" maxlength="10" title="Driver Number must be a 10-digit number">
                            </div>

                            <div class="form-group col-md-6">
                                <label>Deliver Date</label>
                                @php
                                    $currentDateTime = date('Y-m-d\TH:i');
                                @endphp
                                <input class="form-control" type="datetime-local" name="partial_deliver_date" id="partial_deliver_date" value="{{ $currentDateTime }}">
                            </div>

                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_id" value="{{ $projects->id }}">
                <button type="submit" class="btn btn-primary mt-2">Save</button>
            </form>

            @if(!blank($partialDeliverDatas))
                <div class="card bg-light card-body mt-3">
                    <h5>Partial Deliveries By</h5>
                    @foreach($partialDeliverDatas as $deliveryby)
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                           <span class="fs-6 ">Driver Name : </span> <span class="fw-bold fs-6 ">  {{ $deliveryby->partial_deliver_by }} </span> 
                            <br><span class="fs-6 ">Driver Number : </span><span class="fw-bold fs-6 ">{{ $deliveryby->driver_number }} </span> 
                            <br><span class="fs-6 ">Delivery Date : </span><span class="mb-0 text-danger"> {{ date('d/m/Y - H:i:s',strtotime($deliveryby->partial_deliver_date)) }}</span>
                        </div>
                        
                        <div class="ms-auto">
                            <a href="javascript:void(0);" data-id="{{$deliveryby->id}}" class="btn btn-danger delete_partial"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                </div>
            @endif

            <div class="d-flex align-items-center justify-content-end mt-3">
                @if($projects->status == 1)
                    <a href="{{ route('view.material', $projects->id)  }}" class="btn btn-primary me-3"><i data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
                @else
                    <a href="{{ route('view.quotation', $projects->id)  }}" class="btn btn-primary me-3"><i data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
                @endif
                <a href="{{ route('view.fitting', $projects->id)}}" class="btn btn-primary">Next<i data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(".btn-add").click(function() {
        $(".workshop_show_hide").toggle();
    });

    $(".add-partial").click(function() {
            $(".partial_detail").toggle();
        });

    // Material deliver
    $(document).ready(function () {
        function toggleSelectionDate() {
            if ($('#material_yes_radio').is(':checked')) {
                $('#yes_deliver_detail').show();
                $('.add-partial').hide();
                $('.partial_detail').hide();
            } else if($('#material_no_radio').is(':checked')){
                $('#yes_deliver_detail').hide();
                $('.add-partial').hide();
                $('.partial_detail').hide();
            } else{
                $('.add-partial').show();
                $('#yes_deliver_detail').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="material_delivered"]').change(function() {
            toggleSelectionDate();
        });
    });

    // Delete Partial Deliveries
    $(".delete_partial").click(function(){
        var partial_id = $(this).attr('data-id');
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
                        url: "{{ route('delete.partial', '') }}" + "/" + partial_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Partial Delivery detail deleted.",
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

    // delete invoice file
    $(".delete_file").click(function(){
        var invoicefile_id = $(this).attr('data-id');
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
                        url: "{{ route('delete.invoice', '') }}" + "/" + invoicefile_id,
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

    // invoice
    $(document).ready(function () {
        function toggleSelectionDate() {
            if ($('#invoice_yes_radio').is(':checked')) {
                $('#invoice_files_container').show();
                $('#invoice-date-container').show();
            } else {
                $('#invoice_files_container').hide();
                $('#invoice-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="invoice_status"]').change(function() {
            toggleSelectionDate();
        });
    });

    // cutting
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#cutting_option_radio1').is(':checked')) {
                $('#cutting-date-container').show();
            } else {
                $('#cutting-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="cutting_option"]').change(function() {
            toggleSelectionDate();
        });
    });

    // shutter joinary
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#shutter_joinery_radio1').is(':checked')) {
                $('#shutter-date-container').show();
            } else {
                $('#shutter-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="shutter_joinery"]').change(function() {
            toggleSelectionDate();
        });
    });

    // Glass measurement
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#glass_measurement_radio1').is(':checked')) {
                $('#glass-date-container').show();
            } else {
                $('#glass-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="glass_measurement"]').change(function() {
            toggleSelectionDate();
        });
    });

    // Glass receive
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#glass_receive_radio1').is(':checked')) {
                $('#glass-receive-date-container').show();
            } else {
                $('#glass-receive-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="glass_receive"]').change(function() {
            toggleSelectionDate();
        });
    });

    // Shutter ready
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#shutter_ready_radio1').is(':checked')) {
                $('#shutter-ready-date-container').show();
            } else {
                $('#shutter-ready-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="shutter_ready"]').change(function() {
            toggleSelectionDate();
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
                            url: "{{ route('delete.workshop', '') }}" + "/" +
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
