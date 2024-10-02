<div class="card w-100">
    <div class="card-body pb-0">
        <div class="row w-100 view_project">
            <h4 class="pb-2">
                @if ($projects->type == 1)
                    @if ($projects->sub_project_id !== null)
                        Additional Project Details
                        @php
                            $lastProjectDetail = \App\Models\Project::where('id', $projects->sub_project_id)
                                ->latest()
                                ->first();
                        @endphp
                        <br>
                        <h6>Main project ID: {{ $lastProjectDetail->project_generated_id }}</h6>
                    @else
                        Project Details
                    @endif
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
            <div class="col-12">
                <div class="view_project_details border_first">
                    <h6>Total Issue Work</h6>
                    <p>
                        {{ $projects->total_issue_work }}
                    </p>
                </div>
            </div>
            <div class="col-12">
                <div class="view_project_details border_first">
                    <h6>Total Additional Work</h6>
                    <p>
                        {{ $projects->total_additional_work }}
                    </p>
                </div>
            </div>
            @if ($projects->sub_project_id !== null)
                @php
                    $subID = DB::table('projects')
                        ->where('id', $projects->sub_project_id)
                        ->first();
                @endphp
                <div class="col-12">
                    <div class="view_project_details border_first">
                        <h6>Main Project ID</h6>
                        <p>
                            {{ $subID->project_generated_id }}
                        </p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@if (!blank($measurements))
    <div class="card">
        <div class="card-body pb-0">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Measurement Details </h4>
                    <p class="mb-0 ms-auto"><strong>Selection Done ? : </strong>
                        @if ($projects->material_selection == 1)
                            Yes<span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->selection_date)) }})</span>
                        @else
                            No
                        @endif

                    </p>
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
                                    <a href="{{ $image }}" download>{{ $file->measurement }} <span
                                            class="text-success">{{ $file->add_work == 1 ? ' - Additional' : ' ' }}</span>
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
                                    <a href="{{ $image }}" download>{{ $sfile->site_photo }}
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

                @if (!blank($sumeasurementfiles))
                    <div class="col-12">
                        <h5 class="mb-0">Sub Project Measurement Files</h5>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($sumeasurementfiles as $file)
                                <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $file->measurement }} <span
                                            class="text-success">{{ $file->add_work == 1 ? ' - Additional' : ' ' }}</span>
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


                @if (!blank($sumeasurementphotos))
                    <div class="col-12">
                        <h5 class="mb-0">Sub Project Measurement Site Photos</h5>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($sumeasurementphotos as $sfile)
                                <?php $image = URL::asset('public/sitephoto/' . $sfile->site_photo); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                            class="measurementfiles">
                                    </div>
                                    <a href="{{ $image }}" download>{{ $sfile->site_photo }}
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
                </div>
                <hr>
                <div class="col-md-12">
                    @if (!blank($quotations))
                        @if (!blank($quotationfiles))
                            @foreach ($quotationfiles as $quo)
                                <div class="align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="">
                                            <spam class="text-success">{{ $quo->add_work == 1 ? 'Additional ' : ' ' }}
                                            </spam>Quotation
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
                                                @endif
                                            @endforeach
                                            <h5>Description</h5>
                                            <p>{{ $quo->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    @endif

                    @if (!blank($subquotations))
                        @if (!blank($subquotationfiles))
                            @foreach ($subquotationfiles as $quo)
                                <div class="align-items-center">
                                    <div class="d-flex align-items-center">
                                        <h5 class="">
                                            <spam class="text-success">{{ $quo->add_work == 1 ? 'Additional ' : ' ' }}
                                            </spam>Sub Quotation
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
                                                @endif
                                            @endforeach
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
                    <p class="mb-0"><strong>Labor Cost : &#8377; </strong>{{ $projects->laber_cost }}</p>
                    <p><strong>Project Margin : &#8377;
                        </strong>{{ $projects->margin_cost }}</p>
                </div>
                <hr>
                <div class="col-12">
                    <h5 class="mb-0">Purchase Files (4.1)</h5>
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
                                <a href="{{ $image }}" download>{{ $file->purchase }} <span
                                        class="text-success">{{ $file->add_work == 1 ? '- Additional' : '' }}</span>
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
                <div>
                    <div class="col-12">
                        <h5 class="mb-0">Material Received (4.2)</h5>
                    </div>
                    <span class="fw-bold fs-6 ">Is Material Received ? : </span>
                    @if ($projects->material_received_selection == 1)
                        Yes
                        Material Received Date:- <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->material_received_date)) }})</span>
                        Material Received By:- <span
                            class="mb-0 text-danger">{{ $projects->material_received_by }}</span>
                    @else
                        No
                    @endif

                    </p>
                </div>
            </div>
        </div>
    </div>
@endif

@if (!blank($subPurchases))
    <div class="card">
        <div class="card-body">
            <div class="row w-100 view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Sub Purchase Details </h4>
                </div>
                @foreach ($subProjectData as $subProject)
                    <hr>
                    <h5>Sub Project Margin Calculation</h5>
                    <div>
                        <p class="mb-0"><strong>Selling Cost : &#8377; </strong>{{ $subProject->project_cost }}</p>
                        <p class="mb-0"><strong>Purchase Cost : &#8377; </strong>{{ $subProject->quotation_cost }}
                        </p>
                        <p class="mb-0"><strong>Transport Cost : &#8377; </strong>{{ $subProject->transport_cost }}
                        </p>
                        <p class="mb-0"><strong>Labor Cost : &#8377; </strong>{{ $subProject->laber_cost }}</p>
                        <p><strong>Project Margin : &#8377;
                            </strong>{{ $subProject->margin_cost }}</p>
                    </div>
                @endforeach
                <hr>
                <div class="col-12">
                    <h5 class="mb-0">Sub Purchase Files (4.1)</h5>
                </div>
                <div class="col-12 ">
                    <div class="mt-3">
                        @foreach ($subPurchases as $subPurchase)
                            <?php $image = URL::asset('public/purchases/' . $subPurchase->purchase); ?>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                        class="measurementfiles">
                                </div>
                                <a href="{{ $image }}" download>{{ $subPurchase->purchase }} <span
                                        class="text-success">{{ $subPurchase->add_work == 1 ? '- Additional' : '' }}</span>
                                    <p class="mb-0 text-danger">
                                        <small>{{ date('d-m-Y H:i:s', strtotime($subPurchase->created_at)) }}</small>
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
                <div>
                    <div class="col-12">
                        <h5 class="mb-0">Material Received (4.2)</h5>
                    </div>
                    <span class="fw-bold fs-6 ">Is Material Received ? : </span>
                    @foreach ($subProjectData as $subProject)
                        @if ($subProject->material_received_selection == 1)
                            Yes
                            Material Received Date:- <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->material_received_date)) }})</span>
                            Material Received By:- <span
                                class="mb-0 text-danger">{{ $subProject->material_received_by }}</span>
                        @else
                            No
                        @endif
                    @endforeach
                    </p>
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
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->question }} - </span>
                            <strong><span class="text-success">Done </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
            </div>
            <div>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Workshop(5.1)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is Cutting Done ? : </span>
                @if ($projects->cutting_selection == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->cutting_date)) }})</span>
                @else
                    No
                @endif

                </p>
            </div>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.2)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is Shutter Joinery Done ? : </span>
            @if ($projects->shutter_selection == 1)
                Yes <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_date)) }})</span>
            @else
                No
            @endif

            </p>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.3)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is Glass Measurement Done ? : </span>
            @if ($projects->glass_measurement == 1)
                Yes <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_date)) }})</span>
            @else
                No
            @endif
            <div class="card bg-light card-body mt-3 rounded">
                <h5>Uploaded Files</h5>
                @if (isset($projects->glassMeasurementFile))
                    @foreach ($projects->glassMeasurementFile as $measurement)
                        <?php $image = URL::asset('public/glassmeasurementfiles/' . $measurement->file); ?>
                        <div class="d-flex align-items-center mt-3">
                            <div class="me-2">
                                <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                    class="measurementfiles">
                            </div>
                            <a href="{{ $image }}" download>{{ $measurement->file }}
                                <p class="mb-0 text-danger">
                                    <small>{{ date('d-m-Y H:i:s', strtotime($measurement->created_at)) }}</small>
                                </p>
                            </a>
                            <div class="ms-auto">
                                <a href="{{ $image }}" class="btn btn-primary" download><i
                                        class="fa fa-download"></i></a>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @endif
            </div>
            </p>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.4)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is Glass Received ? : </span>
            @if ($projects->glass_receive == 1)
                Yes <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->glass_receive_date)) }})</span>
            @else
                No
            @endif

            </p>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.5)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is shutter ready with glass fitting ? : </span>
            @if ($projects->shutter_ready == 1)
                Yes <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->shutter_ready_date)) }})</span>
            @else
                No
            @endif

            </p>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.6)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is Invoice generated ? : </span>
            @if ($projects->invoice_status == 1)
                Yes <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->invoice_date)) }})</span>
            @else
                No
            @endif

            </p>
            <div class="col-12">
                <h5 class="mb-0 text-danger">Workshop(5.7)</h5>
            </div>
            <span class="fw-bold fs-6 ">Is material delivered ? : </span>
            @if ($projects->material_delivered == 1)
                Yes - delevered by: {{ $projects->delivered_by }} <span
                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($projects->deliver_date)) }})</span>
            @elseif($projects->material_delivered == 2)
                Partially Delivered
                <h6>Partial Deliveries By</h6>
                @foreach ($partialDeliverDatas as $deliveryby)
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <span class="fw-bold fs-6 ">{{ $deliveryby->partial_deliver_by }} </span> <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($deliveryby->partial_deliver_date)) }})</span>
                        </div>
                    </div>
                    <hr>
                @endforeach
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
@if (count($subProjectData) > 0)
    <div class="card">
        <div class="card-body">
            <div class="row view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">Sub Project Workshop Details </h4>
                </div>
                <hr>
                @foreach ($workshop_doneTasks as $d_item)
                    @if ($d_item->chk == 'on')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->question }} - </span>
                            <strong><span class="text-success">Done </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
                @foreach ($workshop_doneTasks as $d_item)
                    @if ($d_item->chk == 'off')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->question }} - </span>
                            <strong><span class="text-danger">Pending </span></strong>
                        </div>
                    @endif
                @endforeach
                <hr>
            </div>
            @foreach ($subProjectData as $subProject)
                <div>
                    <div class="col-12">
                        <h5 class="mb-0 text-danger">Sub Workshop(5.1)</h5>
                    </div>
                    <span class="fw-bold fs-6 ">Is Cutting Done ? : </span>
                    @if ($subProject->cutting_selection == 1)
                        Yes <span
                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->cutting_date)) }})</span>
                    @else
                        No
                    @endif

                    </p>
                </div>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.2)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is Shutter Joinery Done ? : </span>
                @if ($subProject->shutter_selection == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->shutter_date)) }})</span>
                @else
                    No
                @endif

                </p>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.3)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is Glass Measurement Done ? : </span>
                @if ($subProject->glass_measurement == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->glass_date)) }})</span>
                @else
                    No
                @endif

                </p>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.4)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is Glass Received ? : </span>
                @if ($subProject->glass_receive == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->glass_receive_date)) }})</span>
                @else
                    No
                @endif

                </p>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.5)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is shutter ready with glass fitting ? : </span>
                @if ($subProject->shutter_ready == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->shutter_ready_date)) }})</span>
                @else
                    No
                @endif

                </p>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.6)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is Invoice generated ? : </span>
                @if ($subProject->invoice_status == 1)
                    Yes <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->invoice_date)) }})</span>
                @else
                    No
                @endif

                </p>
                <div class="col-12">
                    <h5 class="mb-0 text-danger">Sub Workshop(5.7)</h5>
                </div>
                <span class="fw-bold fs-6 ">Is material delivered ? : </span>
                @if ($subProject->material_delivered == 1)
                    Yes - delevered by: {{ $subProject->delivered_by }} <span
                        class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($subProject->deliver_date)) }})</span>
                @elseif($subProject->material_delivered == 2)
                    Partially Delivered
                    <h6>Partial Deliveries By</h6>
                    @foreach ($subPartialDeliverDatas as $deliveryby)
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="fw-bold fs-6 ">{{ $deliveryby->partial_deliver_by }} </span> <span
                                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($deliveryby->partial_deliver_date)) }})</span>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                @else
                    No
                @endif

                </p>
                <hr>
                @if (!blank($invoiceFiles))
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Sub Invoice Files </h4>
                    </div>
                    <div class="col-12 ">
                        <div class="mt-3">
                            @foreach ($subInvoiceFiles as $file)
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
            @endforeach

        </div>
    </div>
@endif

@if ($projects->type == 1)
    @if (!blank($fitting_doneTasks))
        <div class="card">
            <div class="card-body">
                <div class="row view_project">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Site Installation</h4>
                    </div>
                    <hr>
                    @foreach ($fitting_doneTasks as $d_item)
                        @if ($d_item->chk == 'on')
                            <div class="form-group col-12">
                                <span class="fw-bold fs-6 ">{{ $d_item->question->question }} - </span>
                                <strong><span class="text-success">Done </span></strong>
                            </div>
                        @endif
                    @endforeach
                    <hr>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="row view_project">
                    <div class="d-flex align-items-center pb-3">
                        <h4 class="mb-0">Site Installation</h4>
                    </div>
                    <hr>
                    @foreach ($fitting_questions as $w_item)
                        @if ($w_item->chk == 'on')
                            <div class="form-group col-12">
                                <span class="fw-bold fs-6 ">{{ $w_item->question }} - </span>
                                <strong><span class="text-success">Done </span></strong>
                            </div>
                        @endif
                    @endforeach
                    <hr>
                </div>
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
@foreach ($issueProject as $key => $issue)
    <div class="card">
        <div class="card-body">
            <div class="row view_project">
                <div class="d-flex align-items-center pb-3">
                    <h4 class="mb-0">{{ $key + 1 . ')' }} Issue In Work Details </h4>
                </div>
                @foreach ($issue->workshopIssueTask as $d_item)
                    @if ($d_item->chk == 'on')
                        <div class="form-group col-12">
                            <span class="fw-bold fs-6 ">{{ $d_item->wquestion->question }} - </span>
                            <strong><span class="text-success">Done </span></strong>
                        </div>
                    @endif
                @endforeach
                @if (isset($issue->margin_cost))
                    <div class="form-group col-12">
                        <hr>
                        <h5>Project Margin Calculation</h5>
                        <div>
                            <p class="mb-0"><strong>Selling Cost : &#8377;
                                </strong>{{ $issue->project_cost }}
                            </p>
                            <p class="mb-0"><strong>Purchase Cost : &#8377;
                                </strong>{{ $issue->quotation_cost }}
                            </p>
                            <p class="mb-0"><strong>Transport Cost : &#8377;
                                </strong>{{ $issue->transport_cost }}
                            </p>
                            <p class="mb-0"><strong>Labor Cost : &#8377; </strong>{{ $issue->laber_cost }}
                            </p>
                            <p><strong>Project Margin : &#8377;
                                </strong>{{ $issue->project_cost - $issue->quotation_cost }}</p>
                        </div>
                    </div>
                @endif

                <div class="form-group col-12">
                    <hr>
                    <h5>Workshop Details</h5>
                    <div>
                        <div>
                            <div class="col-12">
                                <h5 class="mb-0 text-danger">Workshop(5.1)</h5>
                            </div>
                            <span class="fw-bold fs-6 ">Is Cutting Done ? : </span>
                            @if ($issue->cutting_selection == 1)
                                Yes <span
                                    class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->cutting_date)) }})</span>
                            @else
                                No
                            @endif

                            </p>
                        </div>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.2)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is Shutter Joinery Done ? : </span>
                        @if ($issue->shutter_selection == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->shutter_date)) }})</span>
                        @else
                            No
                        @endif

                        </p>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.3)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is Glass Measurement Done ? : </span>
                        @if ($issue->glass_measurement == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->glass_date)) }})</span>
                        @else
                            No
                        @endif

                        </p>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.4)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is Glass Received ? : </span>
                        @if ($issue->glass_receive == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->glass_receive_date)) }})</span>
                        @else
                            No
                        @endif

                        </p>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.5)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is shutter ready with glass fitting ? : </span>
                        @if ($issue->shutter_ready == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->shutter_ready_date)) }})</span>
                        @else
                            No
                        @endif

                        </p>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.6)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is Invoice generated ? : </span>
                        @if ($issue->invoice_status == 1)
                            Yes <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->invoice_date)) }})</span>
                        @else
                            No
                        @endif

                        </p>
                        <div class="col-12">
                            <h5 class="mb-0 text-danger">Workshop(5.7)</h5>
                        </div>
                        <span class="fw-bold fs-6 ">Is material delivered ? : </span>
                        @if ($issue->material_delivered == 1)
                            Yes - delevered by: {{ $issue->delivered_by }} <span
                                class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($issue->deliver_date)) }})</span>
                        @elseif($issue->material_delivered == 2)
                            Partially Delivered
                            <h6>Partial Deliveries By</h6>
                            @foreach ($partialDeliverDatas as $deliveryby)
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="fw-bold fs-6 ">{{ $deliveryby->partial_deliver_by }} </span>
                                        <span
                                            class="mb-0 text-danger">({{ date('d/m/Y - H:i:s', strtotime($deliveryby->partial_deliver_date)) }})</span>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
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
                                                <img src="{{ url('/') }}/assets/media/image/docs.png"
                                                    alt="" class="measurementfiles">
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
                        <div class="d-flex align-items-center pb-3">
                            <h4 class="mb-0">Site Installation</h4>
                        </div>
                        @foreach ($issue->fittingIssueTask as $d_item)
                            @if ($d_item->chk == 'on')
                                <div class="form-group col-12">
                                    <span class="fw-bold fs-6 ">{{ $d_item->question->question }} - </span>
                                    <strong><span class="text-success">Done </span></strong>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
