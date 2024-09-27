@extends('admin.layouts.app')

@section('content')
    <div class="gc_row px-md-4 px-2">
        <div class="card mt-md-3 mb-3">
            <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
                <div class="pe-4 fs-5">Add Business</div>
                <div class="ms-auto">
                    <a href="{{ route('admin.business') }}" class="btn gc_btn">Go Back</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('admin.add.business.data') }}" method="post" class="row g-3">
                            @csrf
                            <div class="col-md-11 form-floating mt-4">
                                <select name="client" class="form-control client_dropwdown" id="Client">
                                    <option value="0" {{ old('client') == 0 ? 'selected' : '' }}>Select Client...
                                    </option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="Client" class="form-label">Select Client *</label>
                                @if ($errors->has('client'))
                                    <span class="text-danger">{{ $errors->first('client') }}</span>
                                @endif
                            </div>
                            <div class="col-md-1 form-floating mt-4">
                                <button type="button" class="btn gc_btn" data-bs-toggle="modal"
                                    data-bs-target="#RequestReportModal"
                                    style="position: absolute; bottom: 10px;">+</button>
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <input type="text" class="form-control" name="name" id="Name"
                                    value="{{ old('name') }}" placeholder="" />
                                <label for="Name" class="form-label">Business Name *</label>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <input type="text" class="form-control" name="shortname" id="ShortName"
                                    value="{{ old('shortname') }}" placeholder="" />
                                <label for="ShortName" class="form-label">Business Shortname (for URL)*</label>
                                @if ($errors->has('shortname'))
                                    <span class="text-danger">{{ $errors->first('shortname') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <input type="text" class="form-control" name="place_id" id="PlaceID"
                                    value="{{ old('place_id') }}" placeholder="" />
                                <label for="PlaceID" class="form-label">Place ID *</label>
                                @if ($errors->has('place_id'))
                                    <span class="text-danger">{{ $errors->first('place_id') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <input type="text" class="form-control" name="api_key" id="ApiKey"
                                    value="{{ old('api_key') }}" placeholder="" />
                                <label for="ApiKey" class="form-label">Api Key *</label>
                                @if ($errors->has('api_key'))
                                    <span class="text-danger">{{ $errors->first('api_key') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4">
                                <select name="plan" class="form-control" id="plan">
                                    <option value="">Select Plan...</option>
                                    @foreach ($planList as $plan)
                                        <option value="{{ $plan->id }}"
                                            @if (old('plan') == $plan->id) {{ 'selected' }} @endif>
                                            {{ $plan->plan_title }}</option>
                                    @endforeach
                                </select>
                                <label for="plan" class="form-label">Select Plan *</label>
                                @if ($errors->has('plan'))
                                    <span class="text-danger">{{ $errors->first('plan') }}</span>
                                @endif
                            </div>
                            
                            <div class="col-md-12 form-floating mt-4">
                                <select name="payment_option" class="form-control" id="payment_option">
                                    <option value="">Select Payment Type...</option>
                                    <option value="cash" @if (old('payment_option') == 'cash') {{ 'selected' }} @endif>
                                        Cash</option>
                                    <option value="online" @if (old('payment_option') == 'online') {{ 'selected' }} @endif>
                                        Online</option>
                                </select>
                                <label for="plan" class="form-label">Payment Option *</label>
                                @if ($errors->has('payment_option'))
                                    <span class="text-danger">{{ $errors->first('payment_option') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12 form-floating mt-4 @if(old('payment_option' == "cash"))d-none @endif" id="transaction_id_div">
                                <input type="text" class="form-control" name="transaction_id" id="transaction_id"
                                    value="{{ old('transaction_id') }}" placeholder="" />
                                <label for="Name" class="form-label">Transaction Number *</label>
                                @if ($errors->has('transaction_id'))
                                    <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn gc_btn mt-3">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="form-label">Process of Adding API key in Place ID</label>
                        <iframe src="{{ url('/') }}/assets/Images/blank.pdf" class="col-d-12" width="700"
                            height="400" frameborder="0" style="width: 100%;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="RequestReportModal" tabindex="-1" aria-labelledby="RequestReportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RequestReportModalLabel">Add Client</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.add.client.data') }}" method="post" class="row g-3 add_client_form">
                        @csrf
                        <div class="col-md-6 form-floating mt-4">
                            <input type="text" class="form-control" name="name" id="Name"
                                value="{{ old('name') }}" placeholder="" autofocus />
                            <label for="Name" class="form-label">User Name (required)</label>
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="email" class="form-control" name="email" id="email"
                                value="{{ old('email') }}" placeholder="" />
                            <label for="email" class="form-label">User Email Address (required)</label>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input type="tel" class="form-control" name="phone" id="Phone"
                                value="{{ old('phone') }}" placeholder="" />
                            <label for="Phone" class="form-label">User Phone Number (required)</label>
                            @if ($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6 form-floating mt-4">
                            <input class="form-control" id="password" name="password" placeholder="" />
                            <label for="password" class="form-label">Password (required)</label>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <input type="hidden" name="business" value="1">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn gc_btn add_client_btn">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('.add_client_btn').on('click', function() {
            $.ajax({
                'method': "POST",
                url: "{{ route('admin.add.client.data') }}",
                data: $('.add_client_form').serialize(),
                success: function(data) {
                    var htmls = '<option value="' + data.data.id + '" selected>' + data.data.name +
                        '</option>';
                    $('.client_dropwdown').append(htmls);
                    toastr.success(data.message);
                    $('#RequestReportModal').modal('hide');
                },
                error: function(data) {
                    toastr.error(data.responseJSON.message)
                }
            })
        });
        $('#payment_option').on('change',function(e){
            var payment_option = $(this).val();
            $('#transaction_id_div').addClass('d-none');
            if(payment_option == 'online'){
                $('#transaction_id_div').removeClass('d-none');
            }
        })
    </script>
@endsection
