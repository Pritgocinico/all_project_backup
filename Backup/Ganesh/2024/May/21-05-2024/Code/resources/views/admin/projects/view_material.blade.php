@extends('admin.layouts.viewproject')

@section('pages')
<div class="fieldset">
    <h2 class="fs-title">Purchase</h2>
    <hr>
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
                        $formattedDateTime = date('Y-m-d\TH:i'); // Default to the current date and time
                        if ($projects->selection_date) {
                            $formattedDateTime = date('Y-m-d\TH:i', strtotime($projects->selection_date));
                        }
                    @endphp
                    <input class="form-control" type="datetime-local" name="selection_date" id="selection_date" 
                        value="{{ $formattedDateTime }}">
                </div>

            </div>
        </div>
        <input type="hidden" name="project_id" class="projectID" value="{{ $projects->id }}">
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
    @endif
    <hr>
    <h4 class="mt-3">Purchase Files</h4>
    <form class="" action="{{ route('store.project.materials') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="add_material_div mt-3">
            @if(!blank($purchases))
                <?php $m = 0; ?>
                @foreach($purchases as $file)
                    <?php $m++; ?>
                    <?php $image = URL::asset("public/purchases/".$file->purchase); ?>
                    <div class="d-flex align-items-center">
                        <div class="me-2">
                            <img src="{{url('/')}}/assets/media/image/docs.png" alt="" class="measurementfiles">
                        </div>
                        <a href="{{$image}}" download>{{$file->purchase}}
                            <p class="mb-0 text-danger"><small>{{date('d-m-Y H:i:s',strtotime($file->created_at))}}</small></p>
                        </a>
                        <div class="ms-auto">
                            <a href="{{$image}}" class="btn btn-primary" download><i class="fa fa-download"></i></a>
                            <a href="javascript:void(0);" data-id="{{$file->id}}" class="btn btn-danger delete_file"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                    <hr>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-primary add_material_btn" id="addSelectionBtn">Add Purchase File</button>
        <hr>
        <input type="hidden" name="project_id" value="{{$projects->id}}">
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </form>
    <hr>
    <h4 class="my-3">Project Cost Calculation</h4>
    <h6>Margin : &#8377; <strong class="text-danger margin">{{$projects->project_cost - $projects->quotation_cost}}</strong></h6>
    <form class="" action="{{ route('store.project.cost') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="row mt-3">
            <div class="form-group col-md-6">
                <label for="quotation_cost">Purchase Cost</label>
                <input type="number" class="form-control" name="quotation_cost" id="quotation_cost"
                    placeholder="Purchase Cost" min="0" step="0.01" value="{{$projects->quotation_cost}}">
                @if ($errors->has('quotation_cost'))
                    <span class="text-danger">{{ $errors->first('quotation_cost') }}</span>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label for="project_cost">Selling Cost</label>
                <input type="number" class="form-control" step="0.01" name="project_cost" id="project_cost"
                    placeholder="Selling Cost" min="0" value="{{$projects->project_cost}}">
                @if ($errors->has('project_cost'))
                    <span class="text-danger">{{ $errors->first('project_cost') }}</span>
                @endif
            </div>
        </div>
        <input type="hidden" name="project_id" value="{{$projects->id}}">
        <button type="submit" class="btn btn-primary mt-2">Save</button>
    </form>
    <div class="d-flex align-items-center justify-content-end mt-3">
        <a href="{{ route('view.quotation', $projects->id)  }}" class="btn btn-primary me-3"><i data-feather="arrow-left"
                class="me-2 fw-bold"></i>Previous</a>
        <a class="{{ !blank($purchases) ? 'active_btn' : 'disabled' }} btn btn-primary"
            href="{{ !blank($purchases) ? route('view.workshop', $projects->id) : '#' }}">Next<i
                data-feather="arrow-right" class="ms-2 fw-bold"></i></a>
    </div>
</div>
@endsection
@section('script')
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
    var i = 100;
    $(".add_material_btn").click(function() {
        i++;
        var html = '';
        html += '<div class="row quotation align-items-end mb-2">';
        html += '<div class="col-md-7">';
        html += '<label for="purchase_pdf" class="form-label">Upload Purchase File <span class="text-danger">*</span></label>';
        html += '<input class="form-control" type="file" id="purchase" name="purchase[]" required>';
        html += '</div>';
        html += '<div class="col-md-5">';
        html += '<button type="button" class="btn btn-primary quo-delete-btn">Delete</button>';
        html += '</div>';
        $(".add_material_div").append(html);
    });
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
    // $(document).ready(function() {
    //     $('input[name="material_option"]').change(function() {
    //         var option = $(this).val();
    //         var project_id = $('.projectID').val();
    //         $.ajax({
    //             url: "{{ route('update_material_status', '') }}" + "/" + project_id,
    //             type: 'POST',
    //             data: {
    //                 '_token': "{{ csrf_token() }}",
    //                 "option": option
    //             },
    //             dataType: 'json',
    //             success: function(data) {
    //                 Swal.fire({
    //                     title: 'Updated!',
    //                     text: "Material Selection status has been Updated.",
    //                     icon: 'success',
    //                     showCancelButton: false,
    //                     confirmButtonColor: '#3085d6',
    //                     cancelButtonColor: '#d33',
    //                     confirmButtonText: 'Ok'
    //                 }).then((result) => {
    //                     if (result.isConfirmed) {
    //                         location.reload();
    //                     }
    //                 });
    //             }
    //         });
    //         if ($(this).val() == 1) {
    //             $('#addSelectionBtn').show();
    //         } else {
    //             $('#addSelectionBtn').hide();
    //         }
    //     });
    // });
    $(document).on('keyup','#quotation_cost, #project_cost',function(){
        var q_cost = $('#quotation_cost').val();
        var p_cost = $('#project_cost').val();
        if(q_cost == ''){
            q_cost = 0;
        }
        if(p_cost == ''){
            p_cost = 0;
        }
        var margin = parseFloat(p_cost).toFixed(2)-parseFloat(q_cost).toFixed(2);
        $('.margin').html(parseFloat(margin).toFixed(2));
    });

     // delete file
     $(".delete_file").click(function(){
            var purchase_id = $(this).attr('data-id');
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
                            url: "{{ route('delete.purchase', '') }}" + "/" + purchase_id,
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
@endsection
