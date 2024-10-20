@extends('admin.layouts.app')

@section('content')
    <div id="pdf_element">

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
                        <input type="hidden" id="project_id" value="{{$projects->type == 1 ?$projects->project_generated_id : $projects->lead_no}}">
                        <input type="hidden" id="customer_name"value="{{$projects->customer->name}}">
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
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                    class="measurementfiles">
                                            </div>
                                            <p class="mb-0 text-danger">
                                                <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                            </p>

                                            <hr>
                                        </div>
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
                                    Yes
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
                                                <h5 class="">Quotation
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
                                                            <div class="d-flex align-items-center mt-3">
                                                                <div class="me-2">
                                                                    <img src="{{ url('/') }}/assets/media/image/docs.png"
                                                                        alt="" class="measurementfiles">
                                                                </div>
                                                                <p class="mb-0 text-danger">
                                                                    <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                                                </p>
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
                            <p class="mb-0"><strong>Selling Cost : </strong>{{ $projects->project_cost }}</p>
                            <p class="mb-0"><strong>Purchase Cost : </strong>{{ $projects->quotation_cost }}</p>
                            <p><strong>Project Margin : </strong>{{ $projects->project_cost - $projects->quotation_cost }}
                            </p>
                        </div>
                        <hr>
                        <div class="col-12">
                            <h5 class="mb-0">Purchase Files</h5>
                        </div>
                        <div class="col-12 ">
                            <div class="mt-3">
                                @foreach ($purchases as $file)
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <img src="{{ url('/') }}/assets/media/image/docs.png" alt=""
                                                class="measurementfiles">
                                        </div>
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </div>
                                    <hr>
                                @endforeach
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
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <img src="{{ url('/') }}/assets/media/image/docs.png"
                                                        alt="" class="measurementfiles">
                                                </div>
                                                <p class="mb-0 text-danger">
                                                    <small>{{ date('d-m-Y H:i:s', strtotime($sitephoto->created_at)) }}</small>
                                                </p>
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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
                                        <span>Done </span>
                                        <p class="mb-0 text-danger">
                                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                                        </p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                        <div>
                            <p class="mb-0 ms-auto"><strong>Is Cutting Done ? : </strong>
                                @if ($projects->cutting_selection == 1)
                                    Yes
                                @else
                                    No
                                @endif
                            </p>
                        </div>
                        <p class="mb-0 ms-auto"><strong>Is Shutter Joinery Done ? : </strong>
                            @if ($projects->shutter_selection == 1)
                                Yes
                            @else
                                No
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Is Glass Measurement Done ? : </strong>
                            @if ($projects->glass_measurement == 1)
                                Yes
                            @else
                                No
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Glass Received ? : </strong>
                            @if ($projects->glass_receive == 1)
                                Yes
                            @else
                                No
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Is shutter ready with glass fitting ? : </strong>
                            @if ($projects->shutter_ready == 1)
                                Yes
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
                                        <span>Done </span>

                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                        <div>
                            <p class="mb-0 ms-auto"><strong>Is Cutting Done ? : </strong>
                                @if ($projects->cutting_selection == 1)
                                    Yes
                                @else
                                    No
                                @endif
                                @if (isset($projects->cutting_date))
                                    <p class="mb-0 text-black">Cutting At:-
                                        <small>{{ date('d-m-Y H:i:s', strtotime($projects->cutting_date)) }}</small>
                                    </p>
                                @endif
                            </p>
                        </div>
                        <p class="mb-0 ms-auto"><strong>Is Shutter Joinery Done ? : </strong>
                            @if ($projects->shutter_selection == 1)
                                Yes
                            @else
                                No
                            @endif
                            @if (isset($projects->shutter_date))
                                <p class="mb-0 text-black">Shutter Joinery At:-
                                    <small>{{ date('d-m-Y H:i:s', strtotime($projects->shutter_date)) }}</small>
                                </p>
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Is Glass Measurement Done ? : </strong>
                            @if ($projects->glass_measurement == 1)
                                Yes
                            @else
                                No
                            @endif
                            @if (isset($projects->glass_date))
                                <p class="mb-0 text-black">Glass Measurement At:-
                                    <small>{{ date('d-m-Y H:i:s', strtotime($projects->glass_date)) }}</small>
                                </p>
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Glass Received ? : </strong>
                            @if ($projects->glass_receive == 1)
                                Yes
                            @else
                                No
                            @endif
                            @if (isset($projects->glass_receive_date))
                                <p class="mb-0 text-black">Glass Received At:-
                                    <small>{{ date('d-m-Y H:i:s', strtotime($projects->glass_receive_date)) }}</small>
                                </p>
                            @endif
                        </p>
                        <p class="mb-0 ms-auto"><strong>Is shutter ready with glass fitting ? : </strong>
                            @if ($projects->shutter_ready == 1)
                                Yes
                            @else
                                No
                            @endif
                            @if (isset($projects->shutter_ready_date))
                                <p class="mb-0 text-black">Glass fitting At:-
                                    <small>{{ date('d-m-Y H:i:s', strtotime($projects->shutter_ready_date)) }}</small>
                                </p>
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
                                        <img src="{{ url('/') }}/public/assets/media/image/image.png" alt=""
                                            id="featured_image_preview">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function(e) {
             var name = $('#customer_name').val();
             var id = $('#project_id').val();

             var fileName = id +" - " +name+".pdf";
            const element = document.getElementById('pdf_element');
            html2pdf().set({filename:fileName}).from(element).save();
            setTimeout(function() {
                window.location.href = "{{ route('projects') }}";
            }, 100);
        })
    </script>
@endsection
