@extends('admin.partials.header', ['active' => 'lead'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="row g-3">
                <div class="col-md-8">
                    @if (isset($lead->customerDetail))
                        <h4>Customer Detail</h4>
                        <div id="customer_detail_div">
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->name ?? '-' }}
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->mobile_number ?? '-' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Age</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_age ?? '-' }}
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">Height</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_height ?? '-' }}
                                </div>
                            </div>
                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Weight</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->cust_weight ?? '-' }}
                                </div>

                                <div class="col-md-2"><label class="form-label mb-0">WhatsApp Exist?</label></div>
                                <div class="col-md-4 col-xl-4">
                                    @if ($customer->wa_exist == 1)
                                        {{ 'YES' }}
                                    @else
                                        {{ 'NO' }}
                                    @endif
                                </div>
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2"><label class="form-label mb-0">Disease</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ $customer->custDisease->name ?? '-' }}
                                </div>
                                @if (!blank($custAltnumbers))
                                    <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        @foreach ($custAltnumbers as $numbers)
                                            {{ $numbers->cust_alt_num }} <br />
                                        @endforeach
                                    </div>
                                @else
                                    <div class="col-md-2"><label class="form-label mb-0">Alternate Number</label></div>
                                    <div class="col-md-4 col-xl-4">
                                        {{ '-' }}
                                    </div>
                                @endif
                            </div>

                            <div class="row align-items-center g-3 mt-6">
                                <div class="col-md-2 row">
                                    <div class="col-md-12"><label class="form-label mb-0">Address</label></div>
                                </div>
                                <div class=" col-md-10 row">
                                    @forelse ($custAddresses as $Custaddress)
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <strong>
                                                            @if ($Custaddress->add_type == 'office_add')
                                                                <i class="fa fa-building" aria-hidden="true"></i>
                                                                <span>{{ 'Office Address' }}</span>
                                                            @elseif ($Custaddress->add_type == 'shop_add')
                                                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                                <span>{{ 'Shop Address' }}</span>
                                                            @else
                                                                <i class="fa fa-home" aria-hidden="true"></i>
                                                                <span>{{ 'Home Address' }}</span>
                                                            @endif
                                                        </strong>
                                                    </h5>
                                                    <p class="card-text">
                                                        {{ $Custaddress->address }},<br />
                                                        {{ $Custaddress->village }},<br />
                                                        {{ $Custaddress->office_name }},<br />
                                                        {{ $Custaddress->dist_city }}, {{ $Custaddress->dist_state }},
                                                        {{ $Custaddress->pin_code }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    @empty
                                        {{ '-' }}
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <hr class="my-6">
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">Lead Source</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->lead_source ?? '-' }}
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Problem Duration</label></div>
                            <div class="col-md-4 col-xl-4 text-uppercase">
                                {{ $lead->problem_duration ?? '-' }}
                            </div>
                        </div>
                        <div class="row align-items-center g-3 mt-6">
                            <div class="col-md-2"><label class="form-label mb-0">For Whom</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->for_whom ?? '-' }}
                            </div>
                            <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                            <div class="col-md-4 col-xl-4">
                                {{ $lead->lead_date ?? '-' }}
                            </div>
                        </div>

                        <div class="row align-items-center g-3 mt-6">
                        @foreach ($lead->productDetail as $product)
                                <div class="col-md-2"><label class="form-label mb-0">Product Name:-</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ isset($product->leadProduct) ? $product->leadProduct->name : "-" }}
                                </div>
                                <div class="col-md-2"><label class="form-label mb-0">Product Name:-</label></div>
                                <div class="col-md-4 col-xl-4">
                                    {{ isset($product->leadProduct) ? $product->leadProduct->name : "-" }}
                                </div>
                            <hr />
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-header" style="background-color: #5D8E47">
                            <div class="card-title text-white">
                                Lead Remark Detail
                            </div>
                        </div>

                        <div class="cus-card-body" style="background-color: #dfdfdf;">
                            <div class="timeline">
                                @foreach ($leadRemarks as $remark)
                                    <div>
                                        <i class="fa-regular fa-message bg-dark text-white"></i>
                                        <div class="timeline-item">
                                            <div class="timeline-header-outer">
                                                <h3 class="timeline-header">
                                                    {{ $remark->title }}
                                                    {{ $remark->remark ? ' - ' . $remark->remark : '' }}
                                                </h3>
                                                <div class="time d-flex">
                                                    <div class="time-tooltip">
                                                        @php $name = ""; @endphp
                                                        @if (isset($remark->userDetail))
                                                            @php $name = "<div class='tooltip-title'>Name:- ".$remark->userDetail->name . "</div>"; @endphp
                                                            @php $nameData = $remark->userDetail->name; @endphp
                                                            @if (isset($remark->userDetail->departmentDetail))
                                                                @php $name .= "<div class='tooltip-department'> Department:- ".$remark->userDetail->departmentDetail->name . "</div>"; @endphp
                                                            @endif
                                                        @endif
                                                        @if ($name != '')
                                                            <p class="text-dark" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" data-bs-html="true"
                                                                title="{{ $name }}">
                                                                {{ $nameData }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="time_date">
                                                        <i class="fas fa-clock"></i>
                                                        <span>{{ Utility::convertDmyAMPMFormat($remark->cretaed_at) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include the CSRF token
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
