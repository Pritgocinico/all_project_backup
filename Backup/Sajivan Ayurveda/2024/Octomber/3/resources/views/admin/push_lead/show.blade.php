@extends('admin.partials.header', ['active' => 'lead'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar main-table bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h1 class="ls-tight">{{ $lead->lead_id }}</h1>
                        <a href="{{ route('leads.edit', $lead->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit lead" class="btn btn-light">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                </div>
            </div>

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
                    <div class="col-md-2"><label class="form-label mb-0">Remarks</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->remarks ?? '-' }}
                    </div>
                </div>

                <div class="row align-items-center g-3 mt-6">
                    <div class="col-md-2"><label class="form-label mb-0">Lead Date</label></div>
                    <div class="col-md-4 col-xl-4">
                        {{ $lead->lead_date ?? '-' }}
                    </div>
                    <div class="col-md-2"><label class="form-label mb-0">Product</label></div>
                    <div class="col-md-4 col-xl-4">
                        @if(!blank($lead->productDetail))
                            @foreach($lead->productDetail as $product)
                                {{ $product->leadProduct->name }} <br/>
                            @endforeach
                        @else
                            {{'-'}}
                        @endif
                    </div>
                </div>
            @endif
        </main>
    </div>
@endsection
@section('script')
    <script>
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
    </script>
@endsection
