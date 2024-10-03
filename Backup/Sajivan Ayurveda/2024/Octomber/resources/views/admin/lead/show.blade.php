@extends('admin.partials.header', ['active' => 'lead'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col">
                        <h1 class="ls-tight">{{ $lead->lead_id }}</h1>
                    </div>
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                    {{-- <div class="col text-end">
                        <a href="{{ route('follow-up.index') }}" class="btn btn-dark btn-sm">Add Follow Up</a>
                        @if ($lead->lead_status == 1)
                            <button class="btn btn-warning text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Pending Lead
                            </button>
                        @elseif($lead->lead_status == 2)
                            <button class="btn btn-success text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                In Process Lead
                            </button>
                        @elseif($lead->lead_status == 3)
                            <button class="btn btn-info text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Complete Lead
                            </button>
                        @elseif($lead->lead_status == 4)
                            <button class="btn btn-danger text-white btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Cancel Lead
                            </button>
                        @endif
                        <ul class="dropdown-menu task-status">
                            <li><a class="dropdown-item status @if ($lead->lead_status == 1) d-none @endif"
                                    href="javascript:void(0)" data-status="1" onclick="changeStatusLead(1)">Pending
                                    Lead</a></li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 2) d-none @endif"
                                    href="javascript:void(0)" data-status="2">In Process Lead</a>
                            </li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 3) d-none @endif"
                                    href="javascript:void(0)" data-status="3">Complete Lead</a></li>
                            <li><a class="dropdown-item status lead_status_dropdown @if ($lead->lead_status == 4) d-none @endif"
                                    href="javascript:void(0)" data-status="4">Cancel
                                    Lead</a></li>
                        </ul>
                    </div> --}}
                </div>
            </div>

            <ul class="nav nav-tabs nav-tabs-flush gap-8 overflow-x border-0 mt-4" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="user-detail-tab" data-bs-toggle="tab" href="#user-detail" role="tab"
                       aria-controls="user-detail" aria-selected="true">Lead Detail</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="attendance-list-tab" data-bs-toggle="tab" href="#attendance-list" role="tab"
                       aria-controls="attendance-list" aria-selected="false">Lead Remarks</a>
                </li>
            </ul>

            <div class="tab-content clearfix">
                <!-- Tab 1 Content -->
                <div id="user-detail" class="tab-pane fade show active" role="tabpanel" aria-labelledby="user-detail-tab">
                    @if (isset($lead->customerDetail))
                        <hr class="my-6">
                        <h4>Customer Detail</h4>
                        <div id="customer_detail_div">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->name ?? "-" }}
                                </div>
                
                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->mobile_number ?? "-" }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_age ?? "-" }}
                                </div>
                
                                <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_height ?? "-" }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_weight ?? "-" }}
                                </div>
                
                                <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                <div class="col-md-4 col-xl-4">
                                    @if($customer->wa_exist == 1) {{ "YES" }} @else {{"NO"}} @endif
                                </div>
                            </div>
                            
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->custDisease->name ?? "-" }}
                                </div>
                                @if(!blank($custAltnumbers))
                                    <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        @foreach($custAltnumbers as $numbers)
                                            {{ $numbers->cust_alt_num }} <br/>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{'-'}}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Address</label></div>
                                <div class="col-md-4 col-xl-4">
                                    @if(!blank($custAddresses))
                                        @foreach ($custAddresses as $Custaddress)
                                        <strong> @if($Custaddress->add_type == "office_add"){{ 'Office Address' }} @elseif ($Custaddress->add_type == "shop_add") {{ 'Shop Address' }} @else {{ 'Home Address' }} @endif </strong>,<br />
                                            {{ $Custaddress->address }},
                                            {{ $Custaddress->village }},<br />
                                            {{ $Custaddress->office_name }}, {{ $Custaddress->dist_city }}, {{ $Custaddress->dist_state }}, {{ $Custaddress->pin_code }}<br />
                                        @endforeach
                                    @else
                                        {{'-'}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-6">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Lead Source</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->lead_source ?? "-" }}
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Problem Duration</label></div>
                            <div class="col-md-4 col-xl-4 text-uppercase">
                                {{ $lead->problem_duration ?? "-" }}
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">For Whom</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->for_whom ?? "-" }}
                            </div>
                            
                            <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
                            <div class="col-md-4 col-xl-4">
                                @foreach($lead->productDetail as $product)
                                    {{ $product->leadProduct->name }} <br/>
                                @endforeach
                            </div>
                        </div>
        
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->lead_date ?? "-" }}
                            </div>
                        </div>
                    @endif
                </div>
            
                <!-- Tab 2 Content -->
                <div id="attendance-list" class="tab-pane fade" role="tabpanel" aria-labelledby="attendance-list-tab">
                    <div class="row align-items-start g-3 mt-6">
                        <!-- First Card (Comments) -->
                        <div class="col-md-8">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5>
                                        <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button"
                                           aria-expanded="false" aria-controls="collapseExample">
                                            Remarks
                                        </a>
                                    </h5>
                                    <div class="container mt-5">
                                        <!-- Add content here if needed -->
                                    </div>
                                    <div class="col-md-12 mt-5">
                                        <div class="mb-3">
                                            <input type="hidden" name="lead_id" id="lead_id" value="{{$lead->id}}">
                                            <div class="label mb-2">Title</div>
                                            <select name="title" id="title" class ="form-control">
                                                <option value="">Select Title</option>
                                                <option value="No Problem">No Problem</option>
                                                <option value="CB">CB</option>
                                                <option value="Follow UP">Follow UP</option>
                                                <option value="Sale Done">Sale Done</option>
                                                <option value="Already Sale Done">Already Sale Done</option>
                                                <option value="Resale Done">Resale Done</option>
                                                <option value="No Need">No Need</option>
                                                <option value="Not Intrested">Not Intrested</option>
                                                <option value="Cancel Sale">Cancel Sale</option>
                                                <option value="Call Cut by Customer">Call Cut by Customer</option>
                                                <option value="Other Disease">Other Disease</option>
                                                <option value="Pending Sale">Pending Sale</option>
                                                <option value="On Call">On Call</option>
                                                <option value="Incoming Stop">Incoming Stop</option>
                                                <option value="Switch Off">Switch Off</option>
                                                <option value="Auto Cut">Auto Cut</option>
                                                <option value="Not Connected">Not Connected</option>
                                                <option value="Busy">Busy</option>
                                                <option value="Ringing">Ringing</option>
                                                <option value="F2F Comming">F2F Comming</option>
                                                <option value="Do Not Call">Do Not Call</option>
                                                <option value="Hold Sale">Hold Sale</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <span class="error-title text-danger"></span>
                                            <div class="label mb-2">Remark</div>
                                            <textarea name="remark" class="lead-remark form-control" id="remark" rows="2"></textarea>
                                            <span class="error-remark text-danger"></span>
                                        </div>
                                        <a href="javascript:void(0);" class="btn-comment btn btn-primary btn-icon mt-3 fs-12"
                                           id="btn-comment" style="float:right;">
                                            Add Remark
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Second Card (Lead Follow Up Detail) -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header" style="background-color: #5D8E47">
                                    <div class="card-title text-white">
                                        Lead Remark Detail
                                    </div>
                                </div>
                    
                                <div class="cus-card-body" style="background-color: #dfdfdf;">
                                    <div class="timeline">
                                        <!-- Add timeline content here -->
                                        @foreach ($leadRemarks as $remark)
                                            <div>
                                                <i class="fa-regular fa-message bg-dark text-white"></i>
                                                <div class="timeline-item">
                                                    <span class="time">
                                                        <i class="fas fa-clock"></i>
                                                        {{ Utility::convertDmyAMPMFormat($remark->cretaed_at) }}
                                                    </span>
                                                    <h3 class="timeline-header">
                                                        {{ $remark->title }} - {{ $remark->remark }}
                                                    </h3>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </main>
    </div>
@endsection
@section('script')
    <script>
        $('#title').select2();
        $('.lead_status_dropdown').on('click', function(e) {
            e.preventDefault();
            var status = $(this).data('status');
            $('#lead_status').val(status);
            var headerModalText = "In Process Lead Remark";
            if (status == 3) {
                var headerModalText = "Complete Lead Remark";
            }
            if (status == 4) {
                var headerModalText = "Cancel Lead Remark";
            }
            $('#depositLiquidityModalLabel').html(headerModalText)
            if (status != 1) {
                $('#depositLiquidityModal').modal('show');
            }
        })

        function changeStatusLead(id) {
            var status = $('#lead_status').val();
            if (id !== undefined) {
                status = id;
            }
            var remark = $('#status_remark').val();
            var statusName = "Pending";
            if (status == 2) {
                var statusName = "In Process";
            }
            if (status == 3) {
                var statusName = "Complete";
            }
            if (status == 4) {
                var statusName = "Cancel";
            }
            Swal.fire({
                title: 'Are you sure?',
                text: "Are you sure you want to " + statusName + " this ticket?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#submitBtn').prop('disabled', true);
                    $('#submitBtn').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                    );
                    $.ajax({
                        url: "{{ route('lead.status') }}",
                        data: {
                            lead_id: "{{ $lead->id }}",
                            status: status,
                            remarks: remark
                        },
                        success: function(data) {

                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('textarea[name="remark"]').val('');
                                    $('#submitBtn').prop('disabled', false);
                                    $('#submitBtn').html('Submit');
                                    $('#depositLiquidityModal').modal('hide');
                                    location.reload();
                                }
                            });
                        },
                        error: function(error) {
                            $('#submitBtn').prop('disabled', false);
                            $('#submitBtn').html('Submit');
                            Swal.fire({
                                title: 'error!',
                                text: error.responseJSON.message,
                                icon: 'error',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            })
                        }
                    })
                }
            })
        }

        $(document).on('click', '.btn-comment', function() {
            var title = $('#title').val();
            var remark = $('.lead-remark').val();
            var lead_id = $('#lead_id').val();

            // Clear previous error messages
            $('.error-title').html("");
            $('.error-remark').html("");

            // Validation checks
            if (title == "") {
                $('.error-title').html("Please Select Title.");
            } else if (title == "other" && remark == "") {
                $('.error-remark').html("Please Enter Remark.");
            } else {
                // If validation passes, proceed with the AJAX request
                $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('lead-remark') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include the CSRF token
                    },
                    data: {
                        title: title, 
                        remark: remark,
                        lead_id: lead_id
                    },
                    success: function(data) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Clear the remark field and reset the button
                                $('textarea[name="remark"]').val('');
                                $('#submitBtn').prop('disabled', false);
                                $('#submitBtn').html('Submit');
                                $('#depositLiquidityModal').modal('hide');
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        console.log(error);
                        Swal.fire({
                            title: 'Error!',
                            text: error.responseJSON.message,
                            icon: 'error',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            }
        });


    </script>
@endsection
