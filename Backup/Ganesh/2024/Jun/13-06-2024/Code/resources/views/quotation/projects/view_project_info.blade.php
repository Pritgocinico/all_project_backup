<div class="card w-100">
    <div class="card-body pb-0">
        <div class="row w-100 view_project">
            <h4 class="pb-2">
                @if ($projects->type == 1)
                    Project Details
                @else
                    Lead Details
                @endif
            </h4>
            <hr>
            <h3>
                @if ($projects->type == 1)
                    {{ $projects->project_generated_id }} -
                @else
                    {{ $projects->lead_no }} -
                @endif
                <span>
                    @if ($projects->customer->name)
                        {{ $projects->customer->name }}
                    @endif
                </span>
            </h3>
            <div class="col-md-6 mt-3">
                <div class="view_project_details border_first">
                    <h6>Phone Number</h6>
                    <p>
                        {{ $projects->phone_number }}
                    </p>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="view_project_details border_first">
                    <h6>Email Address</h6>
                    <p>
                        {{ $projects->email }}
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="view_project_details border_first">
                    <h6>Reference Name</h6>
                    <p>
                        {{ $projects->reference_name }}
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="view_project_details border_first">
                    <h6>Reference Number</h6>
                    <p>
                        {{ $projects->reference_phone }}
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="view_project_details">
                    <h6>Estimated Measurement Date</h6>
                    <p>
                        @if ($projects->measurement_date != '')
                            {{ date('d/m/Y', strtotime($projects->measurement_date)) }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-12">
                <div class="view_project_details border_first">
                    <h6>Address</h6>
                    <p>
                        {{ $projects->address }}
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="view_project_details">
                    <h6>Project Description</h6>
                    <p>
                        @if ($projects->description != '')
                            {{ $projects->description }}
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="view_project_details">
                    <h6>Totla Issue Work</h6>
                    <p>
                        {{$projects->total_issue_work}}
                    </p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="view_project_details">
                    <h6>Totla Additional Work</h6>
                    <p>
                        {{$projects->total_additional_work}}
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@if (!blank($measurements))
    <div class="card">
        <div class="card-body pb-0">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Measurement Details </h4>
                </div>
                <hr>
                @if (!blank($measurementfiles))
                    <div class="col-12">
                        <h5 class="mb-0">Measurement Files</h5>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($measurementfiles as $file)
                                <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $file->measurement }} {{$file->add_work == 1 ? " - Additional" : ""}}
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (!blank($measurementphotos))
                    <div class="col-12">
                        <h5 class="mb-0">Measurement Site Photos</h5>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($measurementphotos as $sfile)
                                <?php $image = URL::asset('public/sitephoto/' . $sfile->site_photo); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $sfile->site_photo }} {{ $sfile->add_work == 1? " - Additional" : ""}}
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($sfile->created_at)) }}</small>
                                        </p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
@if (!blank($quotations))
    <div class="card">
        <div class="card-body">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Quotation Details </h4>
                    <span class="ms-2">({{ date('d/m/Y H:i:s', strtotime($quotations->created_at)) }})</span>
                    <p class="mb-0 ms-auto"><strong>Selction Done ? : </strong>
                        @if ($projects->material_selection == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->selection_date)) }})</span>
                        @else
                            No
                        @endif
                    </p>
                </div>
                <hr>
                <div class="col-md-12">
                    @if (!blank($quotations))
                        @if (!blank($quotationfiles))
                            @foreach ($quotationfiles as $quo)
                                <div class="align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="">{{ $quo->add_work == 1 ?"Additional" : "" }} Quotation
                                            @if ($quo->final == 1)
                                                <span class="text-success">( Final Quotation )</span>
                                            @endif
                                        </h5>
                                        <div class="ms-auto">
                                            {{ date('d/m/Y H:i:s', strtotime($quo->created_at)) }}
                                        </div>
                                    </div>
                                    <div class="ms-auto">
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
                                        <div class="card bg-light card-body mt-3 rounded">
                                            {{-- <p class="mb-0"><strong>Quotation Cost: </strong>{{$quo->cost}}</p>
                                            <p class="mb-0"><strong>Project Cost: </strong>{{$quo->project_cost}}</p>
                                            <p><strong>Margin: </strong>{{(int)$quo->project_cost-(int)$quo->cost}}</p> --}}
                                            <h5>Uploaded Files</h5>
                                            @php
                                                $quotation_uploads = DB::table('quotation_uploads')
                                                    ->where('quotation_file_id', $quo->id)
                                                    ->where('deleted_at', null)
                                                    ->get();
                                            @endphp
                                            @foreach ($quotation_uploads as $file)
                                                @if ($file->quotation_file_id == $quo->id)
                                                    <?php $image = URL::asset('public/quotationfile/' . $file->file); ?>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <div class="me-2">
                                                            <img src="{{ url('/') }}/assets/media/image/docs.png"
                                                                alt="" class="measurementfiles">
                                                        </div>
                                                        <a href="{{ $image }}" download>{{ $file->file_name }}
                                                            <p class="mb-0 text-danger">
                                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                                            </p>
                                                        </a>
                                                        <div class="ms-auto">
                                                            <a href="{{ $image }}" class="btn btn-primary"
                                                                download><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <!--<div class="text-center px-3 w-15">-->
                                                    <!--    <a href="{{ $image }}" download><img src="{{ url('/') }}/assets/media/image/docs.png" alt="" class="measurementfiles"></a>-->
                                                    <!--    <p>{{ $file->file_name }}</p>-->
                                                    <!--</div>-->
                                                @endif
                                            @endforeach
                                            <!--</div>-->
                                            <h5>Description</h5>
                                            <p>{{ $quo->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@if (!blank($purchases))
    <div class="card">
        <div class="card-body">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Purchase Details </h4>
                </div>
                <hr>
                <h5>Project Margin Calculation</h5>
                <div>
                    <p class="mb-0"><strong>Selling Cost : &#8377; </strong>{{ $projects->project_cost }}</p>
                    <p class="mb-0"><strong>Purchase Cost : &#8377; </strong>{{ $projects->quotation_cost }}</p>
                    <p class="mb-0"><strong>Transport Cost : &#8377; </strong>{{ $projects->transport_cost }}</p>
                    <p class="mb-0"><strong>Laber Cost : &#8377; </strong>{{ $projects->laber_cost }}</p>
                    <p><strong>Project Margin : &#8377; </strong>{{ $projects->margin_cost }}</p>
                </div>
                <hr>
                @if (isset($addProject))
                    <h5>Additional Project Margin Calculation</h5>
                    <div>
                        <p class="mb-0"><strong>Selling Cost : &#8377; </strong>{{ $addProject->project_cost }}</p>
                        <p class="mb-0"><strong>Purchase Cost : &#8377; </strong>{{ $addProject->quotation_cost }}
                        </p>
                        <p class="mb-0"><strong>Transport Cost : &#8377; </strong>{{ $addProject->transport_cost }}
                        </p>
                        <p class="mb-0"><strong>Laber Cost : &#8377; </strong>{{ $addProject->laber_cost }}
                        </p>
                        <p><strong>Project Margin : &#8377; </strong>{{ $addProject->margin_cost }}</p>
                    </div>
                @endif
                <div class="col-12">
                    <h5 class="mb-0">Purchase Files</h5>
                </div>
                <div class="col-12 ">
                    <div class="mt-3">
                        @foreach ($purchases as $file)
                            <?php $image = URL::asset('public/purchases/' . $file->purchase); ?>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                        class="measurementfiles">
                                </div>
                                <a href="{{ $image }}" download>{{ $file->purchase }} {{ $file->add_work == 1 ? " - Additional" : "" }}
                                    <p class="mb-0 text-danger">
                                        <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                    </p>
                                </a>
                                <div class="ms-auto">
                                    <a href="{{ $image }}" class="btn btn-primary" download><i
                                            class="fa fa-download"></i></a>
                                </div>
                            </div>
                            <hr>
                            <!-- <a href="{{ url('/') }}/public/measurementfile/{{ $file->measurement }}" download class="measurementFile">
                            <img src="{{ url('/') }}/public/assets/media/image/docs.png" alt=""
                                id="featured_image_preview">
                            <p>{{ $file->measurement }}</p>
                        </a> -->
                        @endforeach
                        <!-- </div> -->
                    </div>
                </div>

            </div>
        </div>
    </div>
@endif
@if (!blank($sitephotos))
    <div class="card">
        <div class="card-body">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Site Photos </h4>
                </div>
                <hr>
                <div class="site_photos_table_outer">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Files</th>
                                <th scope="col">Uploaded By</th>
                                <th scope="col">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sitephotos as $sitephoto)
                                <tr>
                                    <td>
                                        <?php $image = URL::asset('public/sitephoto/' . $sitephoto->site_photo); ?>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="{{ url('/') }}/assets/media/image/docs.png"
                                                    alt="" class="measurementfiles">
                                            </div>
                                            <a href="{{ $image }}" download>{{ $sitephoto->site_photo }}
                                                <p class="mb-0 text-danger">
                                                    <small>{{ date('d-m-Y H:i:s', strtotime($sitephoto->created_at)) }}</small>
                                                </p>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $users = DB::table('users')
                                                ->where('id', $sitephoto->created_by)
                                                ->where('deleted_at', null)
                                                ->get();
                                        @endphp
                                        @foreach ($users as $user)
                                            {{ $user->name }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
@if ($projects->type == 1)
    @if (!blank($workshop_doneTasks))
        <div class="card">
            <div class="card-body">
                <div class="row view_project">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Workshop Details </h4>
                    </div>
                    <hr>
                    @foreach ($workshop_doneTasks as $d_item)
                        @if ($d_item->chk == 'on')
                            <div class="form-group col-12">
                                <span class="fw-bold fs-6 ">{{ $d_item->wquestion->workshop_question }} - </span>
                                <strong><span class="text-success">Done </span></strong>
                            </div>
                        @endif
                    @endforeach
                </div>
                <hr>
                @foreach ($workshop_doneTasks as $d_item)
                    @if ($d_item->chk == 'off')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->workshop_question }} - </span>
                            <strong><span class="text-danger">Pending </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
                <div>
                    <p class="mb-0 ms-auto"><strong>Is Cutting Done ? : </strong>
                        @if ($projects->cutting_selection == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->cutting_date)) }})</span>
                        @else
                            No
                        @endif
                    </p>
                </div>
                <p class="mb-0 ms-auto"><strong>Is Shutter Joinery Done ? : </strong>
                    @if ($projects->shutter_selection == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Measurement Done ? : </strong>
                    @if ($projects->glass_measurement == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Received ? : </strong>
                    @if ($projects->glass_receive == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_receive_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is shutter ready with glass fitting ? : </strong>
                    @if ($projects->shutter_ready == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_ready_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is material delivered ? : </strong>
                    @if ($projects->material_delivered == 1)
                        Yes - delevered by: {{ $projects->delivered_by }} <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->deliver_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <hr>
                @if (!blank($invoiceFiles))
                    <div class="d-flex align-items-center pb-3">
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
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="row view_project">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Workshop Details </h4>
                    </div>
                    <hr>
                    @foreach ($workshop_questions as $w_item)
                        @if ($w_item->chk == 'on')
                            <div class="form-group col-12">
                                <span class="fw-bold fs-6 ">{{ $w_item->workshop_question }} - </span>
                                <strong><span class="text-success">Done </span></strong>
                            </div>
                        @endif
                    @endforeach
                </div>
                <hr>
                @foreach ($workshop_questions as $w_item)
                    @if ($w_item->chk == 'off')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $w_item->workshop_question }} - </span>
                            <strong><span class="text-danger">Pending </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
                <div>
                    <p class="mb-0 ms-auto"><strong>Is Cutting Done ? : </strong>
                        @if ($projects->cutting_selection == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->cutting_date)) }})</span>
                        @else
                            No
                        @endif
                    </p>
                </div>
                <p class="mb-0 ms-auto"><strong>Is Shutter Joinery Done ? : </strong>
                    @if ($projects->shutter_selection == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Measurement Done ? : </strong>
                    @if ($projects->glass_measurement == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Received ? : </strong>
                    @if ($projects->glass_receive == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_receive_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is shutter ready with glass fitting ? : </strong>
                    @if ($projects->shutter_ready == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_ready_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is material delivered ? : </strong>
                    @if ($projects->material_delivered == 1)
                        Yes - delevered by: {{ $projects->delivered_by }} <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->deliver_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <hr>
                @if (!blank($invoiceFiles))
                    <div class="d-flex align-items-center pb-3">
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
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if (isset($addProject))
        <div class="card">
            <div class="card-body">
                <div class="row view_project">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Additional Workshop Details </h4>
                    </div>
                </div>
                <hr>
                @foreach ($workshop_doneTasks as $d_item)
                    @if ($d_item->chk == 'off')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->workshop_question }} - </span>
                            <strong><span class="text-danger">Pending </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
                <div>
                    <p class="mb-0 ms-auto"><strong>Is Cutting Done ? : </strong>
                        @if ($addProject->cutting_selection == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->cutting_date)) }})</span>
                        @else
                            No
                        @endif
                    </p>
                </div>
                <p class="mb-0 ms-auto"><strong>Is Shutter Joinery Done ? : </strong>
                    @if ($addProject->shutter_selection == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->shutter_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Measurement Done ? : </strong>
                    @if ($addProject->glass_measurement == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->glass_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is Glass Received ? : </strong>
                    @if ($addProject->glass_receive == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->glass_receive_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is shutter ready with glass fitting ? : </strong>
                    @if ($addProject->shutter_ready == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->shutter_ready_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <p class="mb-0 ms-auto"><strong>Is material delivered ? : </strong>
                    @if ($addProject->material_delivered == 1)
                        Yes - delevered by: {{ $projects->delivered_by }} <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($addProject->deliver_date)) }})</span>
                    @else
                        No
                    @endif
                </p>
                <hr>
                @if (!blank($invoiceFiles))
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Invoice Files </h4>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($invoiceFiles as $file)
                                <?php $image = URL::asset('public/invoicefiles/' . $file->invoice); ?>
                                {{ $file->add_work == 1 ? 'Additional Work' : '' }}
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $file->invoice }}
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </a>
                                    <div class="ms-auto">
                                        <a href="{{ $image }}" class="btn btn-primary" download><i
                                                class="fa fa-download"></i></a>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endif
@if (!blank($projects->transit_date))
    <div class="card">
        <div class="card-body">
            <div class="row view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Delivery Details </h4> <span
                        class="ms-2">({{ $projects->transit_date }})</span>
                </div>
                <div class="form-group col-12">
                    <p class="mb-0">{{ $projects->transit_desc }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
@if (!blank($projects->fitting_date))
    <div class="card">
        <div class="card-body">
            <div class="row view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Fitting Details </h4> <span
                        class="ms-2">({{ $projects->fitting_date }})</span>
                </div>
                <div class="form-group col-12">
                    <p class="mb-0">{{ $projects->fitting_desc }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
@if (!blank($projects->fitting_complete_date))
    <div class="card">
        <div class="card-body">
            <div class="row view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Fitting Complete Details </h4> <span
                        class="ms-2">({{ $projects->fitting_complete_date }})</span>
                </div>
                <div class="form-group col-12">
                    <p class="mb-0">{{ $projects->fitting_complete_desc }}</p>
                </div>
                <div class="form-group col-12">
                    @if (!blank($projects->id))
                        <div class="d-flex align-items-center measurement_f">
                            @foreach ($fittings as $file)
                                <a href="{{ url('/') }}/public/fittingfile/{{ $file->Fitting_image }}"
                                    download class="d-flex align-items-center">
                                    <img src="{{ url('/') }}/public/assets/media/image/image.png"
                                        alt="" id="featured_image_preview">
                                    <span>{{ $file->Fitting_image }}</span><i class="fa fa-download ms-3"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
