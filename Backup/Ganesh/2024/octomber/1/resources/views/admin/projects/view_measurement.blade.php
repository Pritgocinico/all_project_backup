@extends('admin.layouts.viewproject')

@section('pages')
<div class="fieldset">
    <div class="d-flex">
        <h2 class="fs-title">Measurement</h2>
        <button type="button" class="btn btn-primary btn-add ms-auto">Add {{ $projects->add_work ? 'Additional' : '' }}
            Measurement</button>
    </div>
    <hr>
    <form class="" action="{{ route('storemeasurement') }}" enctype="multipart/form-data" method="POST" id="addForm">
        @csrf
        <div class="measurement_show_hide" style="display: none">
            <div class="form-group">
                <label for="projectdesc">Upload Measurement Files<span class="text-danger">*</span></label>
                <input class="form-control" type="file" id="add_measurementfile" name="measurementfile[]" multiple/>
                <span id="measurement_file_error" class="text-danger"></span>
                <div class="me-2" id="loader" style="display:none">
                    <img src="{{ url('/') }}/public/ZKZg.gif" alt="" class="measurementfiles">
                </div>
            </div>

            <div class="form-group">
                <label for="projectdesc">Upload Site photos</label>
                <input class="form-control" type="file" id="sitephotos" name="sitephotos[]" multiple />
                <div class="me-2" id="site_loader" style="display:none">
                    <img src="{{ url('/') }}/public/ZKZg.gif" alt="" class="measurementfiles">
                </div>
            </div>

            <div class="form-group">
                <label for="measurementdesc">Measurement Description</label>
                <textarea class="form-control" id="measurementdesc" rows="5" name="description">
@if (!blank($measurements))
@if (!empty($measurements->description))
{{ $measurements->description }}
@endif @else{{ old('description') }}
@endif
</textarea>
                @if ($errors->has('description'))
                <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <input type="hidden" name="project_id" value="{{ $projects->id }}">
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </div>
    </form>
    @if (!blank($measurements))
    <?php $i = 0; ?>
    @foreach ($measurements as $measure)
    <?php $i++; ?>
    <div class="measurement-detail my-3">
        <div class="d-flex">
            <div>
                <h3 class="mb-3">
                    @if (count($measurements) > 1)
                    @if ($i == 1)
                    New Measurement
                    @else
                    Old Measurement - {{ $i - 1 }}
                    @endif
                    @endif
                </h3>
            </div>
            <div class="ms-auto">
                <a class="btn btn-primary" data-bs-toggle="collapse" href="#collapseExample{{ $i }}" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Edit Measurement
                </a>
            </div>
        </div>
        <div class="collapse" id="collapseExample{{ $i }}">
            <div class="card card-body">
                <form class="" action="{{ route('edit.measurement', $measure->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <h3 class="fs-subtitle">Enter your measurement details</h3>
                    <input type="hidden" name="project_id" value="{{ $projects->id }}">
                    @if (!blank($measurements))
                    <div class="measurement_f">
                        <div class=" measurement_div" name="{{ $measurementId ?? '' }}">
                            <input class="form-control" type="file" id="files" name="measurementfileupdated[]" multiple/>
                            <div class="d-flex gap-4">
                                @foreach ($measurementfiles->groupBy('measurement_id') as $measurementId => $files)
                                @foreach ($files as $file)
                                @if ($file->measurement_id == $measure->id)
                                <div class="">
                                    <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                                    <img src="{{ $image }}" alt="" class="measurementfiles">
                                    <p class="mb-1">{{ $file->measurement }}</p>
                                    <a href="javascript:void(0);" data-id="{{ $file->id }}" class="btn btn-primary delect_btn mb-4">Remove</a>
                                </div>
                                @endif
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="measurement_f">
                        <div class=" measurement_div" name="{{ $measurementId ?? '' }}">
                            <input class="form-control" type="file" id="sitephotos" name="sitephotos[]" multiple />
                            <div class="d-flex gap-4">
                                @foreach ($measurementphotos->groupBy('measurement_id') as $measurementId => $sfiles)
                                @foreach ($sfiles as $sfile)
                                @if ($sfile->measurement_id == $measure->id)
                                <div class="">
                                    <?php $image = URL::asset('public/measurementfile/' . $sfile->site_photo); ?>
                                    <img src="{{ $image }}" alt="" class="measurementfiles">
                                    <p class="mb-1">{{ $sfile->site_photo }}</p>
                                    <a href="javascript:void(0);" data-id="{{ $sfile->id }}" class="btn btn-primary delect_btn mb-4">Remove</a>
                                </div>
                                @endif
                                @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="measurementdesc">Measurement Description </label>
                        <textarea class="form-control" id="measurementdesc" rows="5" name="description">{{ $measure->description }} </textarea>
                        @if ($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                    @endif
                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                </form>
            </div>
        </div>
        <div class="card bg-light card-body">
            @if (!blank($measurementfiles))
            <h5>Measurement Files</h5>
            <div class="my-3">
                @foreach ($measurementfiles->groupBy('measurement_id') as $measurementId => $files)
                @foreach ($files as $file)
                @if ($file->measurement_id == $measure->id)
                <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt="" class="measurementfiles">
                    </div>
                    <a href="{{ $image }}" download>{{ $file->measurement }}
                        <p class="mb-0 text-danger">
                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                        </p>
                    </a>
                    <div class="ms-auto">
                        <a href="{{ $image }}" class="btn btn-primary" download><i class="fa fa-download"></i></a>

                        <a href="javascript:void(0);" data-id="{{ $file->id }}" class="btn btn-danger delete_file" download><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <hr>
                @endif
                @endforeach
                @endforeach
            </div>
            @endif
            @if (!blank($measurementphotos))
            <h5>Site Photos</h5>
            <div class="my-3">
                @if (!blank($measurementphotos))
                @foreach ($measurementphotos->groupBy('measurement_id') as $measurementId => $sfiles)
                @foreach ($sfiles as $sfile)
                @if ($sfile->measurement_id == $measure->id)
                <?php $image = URL::asset('public/sitephoto/' . $sfile->site_photo); ?>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt="" class="measurementfiles">
                    </div>
                    <a href="{{ $image }}" download>{{ $sfile->site_photo }}
                        <p class="mb-0 text-danger">
                            <small>{{ date('d-m-Y H:i:s', strtotime($sfile->created_at)) }}</small>
                        </p>
                    </a>
                    <div class="ms-auto">
                        <a href="{{ $image }}" class="btn btn-primary" download><i class="fa fa-download"></i></a>

                        <a href="javascript:void(0);" data-id="{{ $sfile->id }}" class="btn btn-danger delete_sfile" download><i class="fa fa-trash"></i></a>
                    </div>
                </div>
                <hr>
                @endif
                @endforeach
                @endforeach
                @endif
            </div>
            @endif
            <h5>Measurement Description</h5>
            <p>
                @if (!blank($measurements) && !blank($measure->description))
                {{ $measure->description }}
                @else
                -
                @endif
            </p>
        </div>
    </div>

    <hr>
    @endforeach
    @endif
    @if (!blank($submeasurements))
    <?php $i = 0; ?>
    @foreach ($submeasurements as $measure)
    <?php $i++; ?>
    <div class="measurement-detail my-3">
        <div class="d-flex">
            <div>
                <h3 class="mb-3">
                    Old Measurement
                </h3>
            </div>
        </div>
        <div class="card bg-light card-body">
            @if (!blank($sumeasurementfiles))
            <h5>Sub Measurement Files</h5>
            <div class="my-3">
                @foreach ($sumeasurementfiles->groupBy('measurement_id') as $measurementId => $files)
                @foreach ($files as $file)
                @if ($file->measurement_id == $measure->id)
                <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt="" class="measurementfiles">
                    </div>
                    <a href="{{ $image }}" download>{{ $file->measurement }}
                        <p class="mb-0 text-danger">
                            <small>{{ date('d-m-Y H:i:s', strtotime($file->created_at)) }}</small>
                        </p>
                    </a>
                    <div class="ms-auto">
                        <a href="{{ $image }}" class="btn btn-primary" download><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <hr>
                @endif
                @endforeach
                @endforeach
            </div>
            @endif
            @if (!blank($sumeasurementphotos))
            <h5>Site Photos</h5>
            <div class="my-3">
                @if (!blank($sumeasurementphotos))
                @foreach ($sumeasurementphotos->groupBy('measurement_id') as $measurementId => $sfiles)
                @foreach ($sfiles as $sfile)
                @if ($sfile->measurement_id == $measure->id)
                <?php $image = URL::asset('public/sitephoto/' . $sfile->site_photo); ?>
                <div class="d-flex align-items-center">
                    <div class="me-2">
                        <img src="{{ url('/') }}/assets/media/image/docs.png" alt="" class="measurementfiles">
                    </div>
                    <a href="{{ $image }}" download>{{ $sfile->site_photo }}
                        <p class="mb-0 text-danger">
                            <small>{{ date('d-m-Y H:i:s', strtotime($sfile->created_at)) }}</small>
                        </p>
                    </a>
                    <div class="ms-auto">
                        <a href="{{ $image }}" class="btn btn-primary" download><i class="fa fa-download"></i></a>
                    </div>
                </div>
                <hr>
                @endif
                @endforeach
                @endforeach
                @endif
            </div>
            @endif
            <h5>Measurement Description</h5>
            <p>
                @if (!blank($measurements) && !blank($measure->description))
                {{ $measure->description }}
                @else
                -
                @endif
            </p>
        </div>
    </div>
    <div class="collapse" id="collapseExample{{ $i }}">
        <div class="card card-body">
            <form class="" action="{{ route('edit.measurement', $measure->id) }}" enctype="multipart/form-data" method="POST">
                @csrf
                <h3 class="fs-subtitle">Enter your measurement details</h3>
                <input type="hidden" name="project_id" value="{{ $projects->id }}">
                @if (!blank($measurements))
                <div class="measurement_f">
                    <div class=" measurement_div" name="{{ $measurementId ?? '' }}">
                        <input class="form-control" type="file" id="files" name="measurementfileupdated[]" multiple />
                        <div class="d-flex gap-4">
                            @foreach ($measurementfiles->groupBy('measurement_id') as $measurementId => $files)
                            @foreach ($files as $file)
                            @if ($file->measurement_id == $measure->id)
                            <div class="">
                                <?php $image = URL::asset('public/measurementfile/' . $file->measurement); ?>
                                <img src="{{ $image }}" alt="" class="measurementfiles">
                                <p class="mb-1">{{ $file->measurement }}</p>
                                <a href="javascript:void(0);" data-id="{{ $file->id }}" class="btn btn-primary delect_btn mb-4">Remove</a>
                            </div>
                            @endif
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="measurement_f">
                    <div class=" measurement_div" name="{{ $measurementId ?? '' }}">
                        <input class="form-control" type="file" id="sitephotos" name="sitephotos[]" multiple />
                        <div class="d-flex gap-4">
                            @foreach ($measurementphotos->groupBy('measurement_id') as $measurementId => $sfiles)
                            @foreach ($sfiles as $sfile)
                            @if ($sfile->measurement_id == $measure->id)
                            <div class="">
                                <?php $image = URL::asset('public/measurementfile/' . $sfile->site_photo); ?>
                                <img src="{{ $image }}" alt="" class="measurementfiles">
                                <p class="mb-1">{{ $sfile->site_photo }}</p>
                                <a href="javascript:void(0);" data-id="{{ $sfile->id }}" class="btn btn-primary delect_btn mb-4">Remove</a>
                            </div>
                            @endif
                            @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="measurementdesc">Measurement Description </label>
                    <textarea class="form-control" id="measurementdesc" rows="5" name="description">{{ $measure->description }} </textarea>
                    @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
                @endif
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
    <hr>
    @endforeach
    @endif
    @if (!blank($measurements))
    <form class="" action="{{ route('update_material_status') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="form-group mt-4">
            <label for="flexRadioDefault">Is selection Done?</label>
            <div class="d-flex gap-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="material_option" id="flexRadioDefault1" @if ($projects->material_selection == 1) checked @endif value="1">
                    <label class="form-check-label" for="flexRadioDefault1"> Yes </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="material_option" id="flexRadioDefault2" value="0" @if ($projects->material_selection == 0) checked @endif>
                    <label class="form-check-label" for="flexRadioDefault2"> No </label>
                </div>
            </div>
        </div>
        <div id="selection-date-container" class="form-group mt-4">
            <label for="selection_date">Selection Date</label>
            <div class="d-flex gap-3">
                <div class="">
                    @php
                    $formattedDateTime = date('Y-m-d\TH:i'); // Default to the current date and time
                    if ($projects->selection_date) {
                    $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->selection_date));
                    }
                    @endphp
                    <input class="form-control" type="datetime-local" name="selection_date" id="selection_date" value="{{ $formattedDateTime }}">
                </div>

            </div>
        </div>
        <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
    @endif
    <hr>
    <!-- <form class="" action="{{ route('store.project.materials') }}" enctype="multipart/form-data" method="POST">
                @csrf -->
    <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
    <!-- <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form> -->
    <input type="hidden" name="material_count" value="{{ count($materials) }}">
    <div class="d-flex align-items-center justify-content-end mt-3">
        <a href="{{ route('view.project', $projects->id) }}" class="btn btn-primary me-3"><i data-feather="arrow-left" class="me-2 fw-bold"></i>Previous</a>
        <a class="{{ !blank($measurements) ? 'active_btn' : 'disabled' }} btn btn-primary" href="{{ !blank($measurements) ? route('view.quotation', $projects->id) : '#' }}">Next<i data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#addForm').on('submit',function(e){
            var file = $('#add_measurementfile').val();
            $('#measurement_file_error').html('');
            if(file == ""){
                $('#measurement_file_error').html('Please Select File.');
                e.preventDefault();
            }
        })
        if (window.File && window.FileList && window.FileReader) {
            $("#files").on("change", function(e) {
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
                            "</span>").insertAfter("#files");
                        $(".remove").click(function() {
                            $(this).parent(".pip").remove();
                            $("#files").val(null);
                        });
                    });
                    fileReader.readAsDataURL(f);
                }
                console.log(files);
                $('#loader').hide();
            });

            $("#sitephotos").on("change", function(e) {
                $('#site_loader').show();
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
                            "</span>").insertAfter("#sitephotos");
                        $(".remove").click(function() {
                            $(this).parent(".pip").remove();
                            $("#sitephotos").val(null);
                        });
                    });
                    fileReader.readAsDataURL(f);
                }
                console.log(files);
                $('#site_loader').hide();
            });
        } else {
            alert("Your browser doesn't support to File API")
        }
    });
</script>
<script>
    $(document).ready(function() {
        $('#purchase_user').select2();
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
                        url: "{{ route('delete.measurement', '') }}" + "/" +
                            employee_id,
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

    // delete file
    $(".delete_file").click(function() {
        var task_id = $(this).attr('data-id');
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
                    url: "{{ route('delete.measurement', '') }}" + "/" + task_id,
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

    $(".delete_sfile").click(function() {
        var task_id = $(this).attr('data-id');
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
                    url: "{{ route('delete.measurement_pic', '') }}" + "/" + task_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Photo has been deleted.",
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
    $(".btn-add").click(function() {
        $(".measurement_show_hide").toggle();
    });
</script>
<script>
    var i = 100;
    $(".add_material_btn").click(function() {
        i++;
        var html = '';
        html += '<div class="row quotation align-items-end mb-2">';
        html += '<div class="col-md-3">';
        html += '<label for="">Select material</label>';
        html += '<select class="form-control" id="exampleFormControlSelect1" name="material[' + i +
            '][material_selection]" >';
        html += '<option value="0">Select option</option>';
        html += '<option value="1">Hardware</option>';
        html += '<option value="2">Profile Shutter</option>';
        html += '<option value="3">Glass</option>';
        html += '</select>';
        html += '</div>';
        html += '<div class="col-md-7">';
        html +=
            '<label for="material_description" class="form-label">Description</label>';
        html += '<input class="form-control" type="text" id="description" name="material[' + i +
            '][material_description]" >';
        html += '</div>';
        html += '<div class="col-md-1">';
        html += '<button type="button" class="btn btn-primary quo-delete-btn">Delete</button>';
        html += '</div>';
        html += '</div>';
        $(".add_material_div").append(html);
    });
</script>
<script>
    $(document).on("click", ".quo-delete-btn", function() {
        $(this).parent().parent().remove();
    });
    $(document).on('click', '.delete-material-btn', function() {
        var material_id = $(this).attr('data-id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this Project!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete.material', '') }}" + "/" + material_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "material has been deleted.",
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
    $(document).ready(function() {
        var cnt = $('.material_count').val();
        if (cnt > 0) {
            $('#addSelectionBtn').hide();
        } else {
            $('#addSelectionBtn').show();
        }
    });
    $(document).ready(function() {
        function toggleSelectionDate() {
            if ($('#flexRadioDefault1').is(':checked')) {
                $('#selection-date-container').show();
            } else {
                $('#selection-date-container').hide();
            }
        }

        toggleSelectionDate();

        $('input[name="material_option"]').change(function() {
            toggleSelectionDate();
        });
    });
</script>
@endsection