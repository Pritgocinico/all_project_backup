@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3 p-3 d-flex">
            <div class="card-body">
                <ul class="nav nav-pills gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#pills-profile-overview" class="nav-link active" id="pills-profile-overview-tab"
                            data-bs-toggle="pill" data-bs-target="#pills-profile-overview" type="button" role="tab"
                            aria-controls="pills-profile-overview" aria-selected="true">General Setting</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#pills-campaings" class="nav-link" id="pills-campaings-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-campaings" type="button" role="tab" aria-controls="pills-campaings"
                            aria-selected="false">Plan Detail</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-profile-overview" role="tabpanel"
                        aria-labelledby="pills-profile-overview-tab">
                        <form action="{{ route('save_general_settings') }}" method="POST" class="row g-3"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="logo" class="form-label">Logo <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="logo" name="logo">
                                @if ($errors->has('logo'))
                                    <span class="text-danger">{{ $errors->first('logo') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 mt-5">
                                @php $logoUrl = asset($settings->logo); @endphp
                                <input type="hidden" name="uploaded_logo" value="{{ $settings->logo }}">
                                <img id="preview-image-before-upload" src="{{ $logoUrl }}" alt=""
                                    style="max-height: 100px; width:100px;">
                            </div>
                            <div class="col-md-6">

                                <label for="favicon" class="form-label">Favicon <span class="text-danger">*</span></label>
                                <input class="form-control" type="file" id="favicon" name="favicon">
                                @if ($errors->has('favicon'))
                                    <span class="text-danger">{{ $errors->first('favicon') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 mt-5">
                                @php $faUrl = asset($settings->favicon); @endphp
                                <input type="hidden" name="uploded_favicon" value="{{ $settings->favicon }}">
                                <img id="preview-image-before-upload-favicon" src="{{ $faUrl }}" alt=""
                                    style="max-height: 50px;">
                            </div>
                            <div class="col-12">
                                <label for="SiteName" class="form-label">Company Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="site_name" id="SiteName"
                                    placeholder="Company Name" value="{{ $settings->site_name }}">
                                @if ($errors->has('site_name'))
                                    <span class="text-danger">{{ $errors->first('site_name') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <label for="SiteUrl" class="form-label">Site Url <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="site_url" id="SiteUrl"
                                    placeholder="Company Name" value="{{ $settings->site_url }}">
                                @if ($errors->has('site_url'))
                                    <span class="text-danger">{{ $errors->first('site_url') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="pills-campaings" role="tabpanel"
                        aria-labelledby="pills-campaings-tab">
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Add Plan
                                </button>
                            </div>
                        </div>
                        <table id="example" class="table rwd-table mb-0">
                            <thead>
                                <tr>
                                    <th>Plan ID</th>
                                    <th>Plan Title</th>
                                    <th>Period Type</th>
                                    <th>Price</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!blank($planDetail))
                                    @foreach ($planDetail as $plan)
                                        <tr>
                                            <td>{{ $plan->plan_id }}</td>
                                            <td>{{ $plan->plan_title }}</td>
                                            <td>{{ $plan->plan_period_type }}</td>
                                            <td>{{ $plan->price }}</td>
                                            <td class="text-end">
                                                <a href="javascript:void(0)"
                                                    onclick="editPlan('{{ $plan->id }}')"><img
                                                        src="{{ url('/') }}/assets/Images/edit.png" alt=""
                                                        class="ed_btn me-2"></a>
                                                <a href="javascript:void(0);" data-id="{{ $plan->id }}"
                                                    class="delete-btn"><img
                                                        src="{{ url('/') }}/assets/Images/delete.png" alt=""
                                                        class="ed_btn"></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade modal-lg" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('store.plan') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-floating">
                                <input type="text" name="plan_title" id="plan_title" placeholder="Plan Title"
                                    class="form-control" required>
                                <label for="plan_title">Plan Title</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <select class="form-select" name="plan_period_type" required>
                                    <option value="">Select Type</option>
                                    <option value="years">Years</option>
                                    <option value="months">Months</option>
                                    <option value="Days">Days</option>
                                </select>
                                <label for="plan_title">Plan Period Type</label>
                            </div>
                            <div class="col-md-6 form-floating mt-3">
                                <input type="number" name="price" id="price" placeholder="Plan Price"
                                    class="form-control" required>
                                <label for="plan_title">Plan Price</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-lg" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Plan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update.plan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_id" id="edit_plan_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-floating">
                                <input type="text" name="plan_title" id="edit_plan_title" placeholder="Plan Title"
                                    class="form-control" required>
                                <label for="plan_title">Plan Title</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <select class="form-select" name="plan_period_type" required id="edit_plan_period_type">
                                    <option value="">Select Type</option>
                                    <option value="years">Years</option>
                                    <option value="months">Months</option>
                                    <option value="Days">Days</option>
                                </select>
                                <label for="plan_title">Plan Period Type</label>
                            </div>
                            <div class="col-md-6 form-floating mt-3">
                                <input type="number" name="price" id="edit_price" placeholder="Plan Price"
                                    class="form-control" required>
                                <label for="plan_title">Price</label>
                            </div>
                            <div class="col-md-6 form-floating">
                                <div class="col-md-6">
                                    <label for="Status" class="">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="status" id="status" />
                                        <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function editPlan(id) {
            $.ajax({
                'method': 'get',
                'url': "{{ route('edit.plan') }}",
                'data': {
                    'plan_id': id,
                },
                success: function(data) {
                    $('#edit_plan_id').val(data.id);
                    $('#edit_plan_title').val(data.plan_title);
                    $('#edit_price').val(data.price);
                    $('#edit_plan_period_type').val(data.plan_period_type);
                    if (data.status == "1") {
                        $('#status').attr("checked", 'true');
                    }
                    $('#editModal').modal('show');
                },
            })
        }
        $(document).on('click', '.delete-btn', function() {
            var plan_id = $(this).attr('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure delete this plan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete.plan','') }}" + "/" + plan_id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Plan has been deleted.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    toastr.success(data.message);
                                    top.location.href =
                                        "{{ route('setting.page') }}";
                                }
                            });
                        },error:function (error){
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>
@endsection
