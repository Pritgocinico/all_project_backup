@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        Checkout
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                        class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href=""
                                        class="text-decoration-none text-white">Checkout</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @php
                            $total = 0;
                            $vailQuantity = 0;
                        @endphp
    <main>
        <div class="container">
            @if (!blank($cart_items))
                <form action="{{ route('place.order') }}" id="order-form" method="post">
                    @csrf
                    <div class="checkout-inner">
                        <section class="checkout-form">
                            <div class="container">
                                <h5>Contact information</h5>
                                <div class="rgs-w">
                                    <label for="email" class="form-label">Coupon Code <span
                                            class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-9">
                                            <input type="text" name="coupon_code" class="form-control py-2"
                                                id="coupon_code" value="">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="button"
                                                class="update header-btn border-0 mt-lg-0 mb-lg-0 mb-4 mt-sm-0 text-white"
                                                id="cuppon_detail" value="Apply">
                                        </div>
                                    </div>

                                </div>
                                <div class=" d-flex gap-sm-2 name-f">
                                    <div class="rgs-w">
                                        <label for="email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control py-2" id="email"
                                            value="{{ Auth::user()->email }}" required="">
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="rgs-w">
                                        <label for="phone" class="form-label">Phone <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control py-2" id="phone"
                                            value="{{ Auth::user()->phone }}" required="">
                                        @if ($errors->has('phone'))
                                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <h5>Shipping Address</h5>
                                <div class=" d-flex gap-sm-2 name-f">
                                    <div class="rgs-w">
                                        <label for="first_name" class="form-label">First Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control py-2"
                                            value="{{ Auth::user()->first_name }}" id="first_name">
                                        @if ($errors->has('first_name'))
                                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>
                                    <div class="rgs-w">
                                        <label for="last_name" class="form-label">Last Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control py-2"
                                            value="{{ Auth::user()->last_name }}" id="last_name">
                                        @if ($errors->has('last_name'))
                                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="rgs-w">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control py-2"
                                        value="{{ Auth::user()->practice_address_street }}" id="address">
                                    @if ($errors->has('address'))
                                        <span class="text-danger">{{ $errors->first('address') }}</span>
                                    @endif
                                </div>
                                <div class="rgs-w">
                                    <label for="last_name" class="form-label">Apartment, suite, etc.</label>
                                    <input type="text" name="address1" class="form-control py-2"
                                        value="{{ old('address1') }}" id="address1">
                                    @if ($errors->has('address1'))
                                        <span class="text-danger">{{ $errors->first('address1') }}</span>
                                    @endif
                                </div>
                                {{-- {{dd(Auth()->user()->city)}} --}}
                                <div class=" d-flex gap-sm-2 name-f">
                                    <div class="rgs-w select-f">
                                        <label for="state" class="form-label">State <span
                                                class="text-danger">*</span></label>
                                        {{-- <i class="fas fa-angle-down	"></i> --}}
                                        <select name="state" class="form-control py-2 select2" id="state">
                                            @foreach ($states as $state)
                                                <option value="{{ $state['name'] }}"
                                                    @if (Auth()->user()->state == $state['name']) selected @endif>
                                                    {{ $state['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('state'))
                                            <span class="text-danger">{{ $errors->first('state') }}</span>
                                        @endif
                                    </div>
                                    <div class="rgs-w select-f">
                                        <label for="city" class="form-label">City <span
                                                class="text-danger">*</span></label>
                                        <!-- <i class="fas fa-angle-down	"></i>  -->
                                        <select name="city" class="form-control py-2 select2-city" id="city">
                                            <option value="0">Select City...</option>
                                            <option value="city1">City1</option>
                                        </select>
                                        <!-- <input type="text" name="city" id="city" class="form-control py-2"> -->
                                        @if ($errors->has('city'))
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group gap-sm-2 name-f">
                                    {{-- <div class="rgs-w  select-f">
                                            <label for="speciality" class="form-label">Country <span
                                                    class="text-danger">*</span></label>
                                            <i class="fas fa-angle-down	"></i>
                                            <select name="speciality" class="form-control py-2" id="speciality" required="">
                                                <!-- Add options for the dropdown -->
                                                <option value="Ophthalmology">Adrar</option>
                                                <option value="Optometry">Algiers</option>
                                                <option value="Retina">Retina</option>
                                                <option value="Anesthesia">Annanba</option>
                                                <option value="Derm/Aesthetics">Derm/Aesthetics</option>
                                                <option value="Dentist">Bechar</option>
                                                <option value="Integrative/Other">Integrative/Other</option>
                                                <option value="Vet">Vet</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div> --}}
                                    <div class="rgs-w">
                                        <label for="zip_code" class="form-label">Zip Code <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="zip_code" class="form-control py-2 zip_codes"
                                            pattern="\d{5}" maxlength="5" value="{{ Auth::user()->zip_code }}"
                                            id="zip_code" required>
                                        @if ($errors->has('zip_code'))
                                            <span class="text-danger">{{ $errors->first('zip_code') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="checkbox-control mt-3">
                                    <input type="checkbox" name="address_checkbox" id="address-checkbox" checked>
                                    <label for="checkout-checkbox">Billing Address Same as Shipping Address</label>
                                </div>

                                <div class="container mt-4" id="billingAddress">
                                    <h5>Billing Address</h5>
                                    <div class="rgs-w">
                                        <label for="address" class="form-label">Address <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="billing_address" class="form-control py-2"
                                            value="{{ Auth::user()->address }}" id="billing_address">
                                        @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('billing_address') }}</span>
                                        @endif
                                    </div>
                                    <div class="rgs-w">
                                        <label for="last_name" class="form-label">Apartment, suite, etc.</label>
                                        <input type="text" name="billing_address1" class="form-control py-2"
                                            value="{{ old('address1') }}" id="billing_address1">
                                        @if ($errors->has('address1'))
                                            <span class="text-danger">{{ $errors->first('billing_address1') }}</span>
                                        @endif
                                    </div>
                                    <div class=" d-flex gap-sm-2 name-f">
                                        <div class="rgs-w select-f">
                                            <label for="city" class="form-label">City <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="billing_city" id="billing_city"
                                                class="form-control py-2">
                                            @if ($errors->has('city'))
                                                <span class="text-danger">{{ $errors->first('billing_city') }}</span>
                                            @endif
                                        </div>
                                        <div class="rgs-w select-f">
                                            <label for="state" class="form-label">State <span
                                                    class="text-danger">*</span></label>
                                            {{-- <i class="fas fa-angle-down	"></i> --}}
                                            <select name="billing_state" class="form-control py-2 select2"
                                                id="billing_state">
                                                @foreach ($states as $state)
                                                    <option value="{{ $state['name'] }}"
                                                        @if (old('state') == $state['name']) selected @endif>
                                                        {{ $state['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('state'))
                                                <span class="text-danger">{{ $errors->first('billing_state') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group gap-sm-2 name-f">
                                        <div class="rgs-w">
                                            <label for="zip_code" class="form-label">Zip Code <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="billing_zip_code" class="form-control py-2"
                                                value="{{ Auth::user()->zip_code }}" id="billing_zip_code">
                                            @if ($errors->has('zip_code'))
                                                <span class="text-danger">{{ $errors->first('billing_zip_code') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="city_code" id="city_code" value="">

                                <div class="checkbox-control mt-3">
                                    <input type="checkbox" name="checkout-checkbox" id="checkout-checkbox">
                                    <label for="checkout-checkbox">Save this information for next time</label>
                                </div>

                                <div class="form-control-btn">
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <button type="submit"
                                        class="update d-none header-btn border-0 mt-lg-0 mt-lg-4 mb-lg-0 mb-4 mt-sm-0 mt-2 text-white py-2 px-4">
                                        Place Order
                                    </button>
                                </div>
                            </div>
                        </section>
                        
                        <section class="checkout-details">
                            <div class="container">
                                <div class="checkout-details-inner">
                                    <h5>Your Order</h5>
                                    <hr>
                                    <div class="checkout-lists">

                                        @foreach ($cart_items as $item)
                                            @php
                                                $product = DB::table('products')
                                                    ->where('id', $item->product_id)
                                                    ->first();
                                                $total = $total + $item->total;
                                                $vailDetail = DB::table('product_packages')
                                                    ->where('varient_name', $item->package_name)
                                                    ->first();
                                                $vailQuantity = $vailQuantity + $vailDetail->vial_quantity;
                                            @endphp
                                            @if (!blank($product))
                                                <input type="hidden" id="product_id_{{ $item->product_id }}"
                                                    value="{{ $item->total }}" />
                                                <div class="card card-inner-checkout">
                                                    <div class="card-image"><img
                                                            src="{{ asset('storage/images/' . $product->single_image) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="card-details">
                                                        <div class="card-name">{{ $product->productname }}</div>
                                                        @if ($item->medical_necessity)
                                                            <div class="card-name">Medical Necessity:
                                                                {{ $item->medical_necessity }}</div>
                                                        @endif
                                                        {{-- <div class="card-info">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                                                                Voluptatem,
                                                                suscipit aliquam! Expedita corporis maiores optio.</div> --}}
                                                        <div class="card-price">${{ $item->price }} *
                                                            {{ $item->quantity }} =<span
                                                                class="text-decoration-none">${{ $item->total }}</span>
                                                        </div>
                                                        <div class="card-details d-none d-inline"
                                                            id="discount_div_{{ $item->product_id }}"
                                                            style="font-size: 14px;">Discount Price:- <span
                                                                id="discount_price_{{ $item->product_id }}">$0</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <input type="hidden" id="total_product_amount" value="{{ $total ?? 0 }}">
                                    </div>

                                    <div class="checkout-outer position-relative">
                                        <div id="product_detail_total_div" style="opacity: 0.5">
                                            <div class="checkout-total">
                                                <p>Subtotal</p>
                                                <p id="sub_total">${{ number_format($total, 2) }}</p>
                                            </div>

                                            <div class="checkout-shipping">
                                                <p>Shipping</p>
                                                <p class="shipping_charge">$0.00</p>
                                                <input type="hidden" class="shipping_charge" name="shipping"
                                                    value="0" id="shipping_charge">
                                            </div>
                                            <div class="checkout-total">
                                                <p>Total</p>
                                                <input type="hidden" class="grand_total" name="total"
                                                    id="grand_total" value="{{ $total }}">
                                                <p class="grand_total">${{ $total }}</p>
                                            </div>
                                        </div>
                                        <div class="loader">
                                            <img src="{{ url('/') }}/frontend/assets/images/loader.gif">
                                        </div>
                                    </div>
                </form>
        </div>
        <div class="container payment-container mt-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table">
                    <h4 class="panel-title">Payment Details</h4>
                </div>
                <hr>
                <div class="panel-body">
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    @if (Session::has('error'))
                        <div class="alert alert-success text-center">
                            <p>{{ Session::get('error') }}</p>
                        </div>
                    @endif
                    <div class="alert alert-danger text-center d-none" id="stripe_error_div">
                        <p id="stripe_error_message" class="mb-0"></p>
                    </div>
                    <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation"
                        data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
                        @csrf
                        <div class="form-row row">
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Saved Cards</label>
                                <select name="card_id" id="card_id" class="form-control">
                                    @foreach ($cardDetail as $card)
                                        <option value="{{ $card->id }}"
                                            @if (Auth()->user()->card_id == $card->id) {{ 'selected' }} @endif>
                                            {{ ucfirst($card->card_name) }}</option>
                                    @endforeach
                                    <option value="" @if (Auth()->user()->card_id == null) {{ 'selected' }} @endif>
                                        Process With New Card</option>
                                </select>
                            </div>
                        </div>
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> <input class='form-control'
                                    size='4' type='text' name="card_name" id="card_name">
                            </div>
                        </div>
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Card Number</label> <input autocomplete='off'
                                    class='form-control card-number' size='20' type='text'>
                            </div>
                        </div>
                        <div class='form-row row form-inner-menu'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                    class='form-control card-cvc' placeholder='ex. 311' size='4' type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Exp. Month</label> <input
                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Exp. Year</label> <input
                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                    type='text'>
                            </div>
                        </div>
                        <div class='form-row row'>
                            <div class='col-md-12 error form-group hide'>
                                <div class='alert-danger alert'>Please correct the errors and try
                                    again.</div>
                            </div>
                        </div>
                        <hr>
                        <div class="row d-flex newc-sub justify-content-between mt-3">
                            <div class="col-xs-6 col-md-8">
                                <button class="submit-button btn-lg border-0 pay-btn" id="pay_place_order_button"
                                    type="submit">Pay&Place Order</button>
                            </div>
                            <div class="col-xs-6 col-md-4 newc-can">
                                <a href="{{route('cart')}}"
                                    class="btn update newc-btn header-btn border-0 mt-lg-0 mb-lg-0 mb-4 mt-sm-0 text-white">
                                    Cancel </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        </div>
        </section>
        </div>
        <!-- </form> -->
    @else
        <div class="container">
            <h3>You don't have items in your cart.</h3>
        </div>
        @endif
        </div>
    </main>
@endsection
@section('script')
    <script>
        var rate = 0;
        $(document).ready(function() {
            getCityList("{{ Auth()->user()->state }}");
            $(document).on('change', '#state', function() {
                var state = $(this).val();
                getCityList(state);
            });

            function getCityList(state) {
                $.ajax({
                    url: "{{ route('cityByState', '') }}" + "/" + state,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#city').html(data);
                    }
                });
            }
            handleZipCodes();
            $(document).on('input', '.zip_codes', function() {
                handleZipCodes();
            });


            if ($('#address-checkbox').is(':checked')) {
                $('#billingAddress').hide();
            }


            $('#address-checkbox').change(function() {

                if ($(this).is(':checked')) {
                    $('#billingAddress').hide();
                } else {
                    $('#billingAddress').show();
                }
            });
        });

        function handleZipCodes() {
            var address = $('#address').val();
            var address1 = $('#address1').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var zip = $('#zip_code').val();

            if (zip.length === 5) {
                $('.loader').removeClass('d-none');
                $('#product_detail_total_div').css('opacity', '0.5')
                $('#checkout_form_button').css('opacity', '0.5')
                document.getElementById("pay_place_order_button").innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                $.ajax({
                    url: "{{ route('fedex-rate') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        address: address,
                        address1: address1,
                        city: city,
                        state: state,
                        zip: zip,
                        vial: "{{ $vailQuantity }}",
                    },
                    success: function(data) {
                        $('.shipping_charge').text('$' + data[0]['totalNetChargeWithDutiesAndTaxes'].toFixed(
                            2));
                        $('#shipping_charge').val(data[0]['totalNetChargeWithDutiesAndTaxes'].toFixed(2))
                        var totalAmount = $('#total_product_amount').val();
                        rate = data[0].totalNetChargeWithDutiesAndTaxes;
                        var total = parseInt(totalAmount) + data[0].totalNetChargeWithDutiesAndTaxes;
                        $('.grand_total').text('$' + total.toFixed(2));
                        $('.pay_grand_total').text('Pay $' + total.toFixed(2) + ' & Place Order');
                        $('#grand_total').val(total.toFixed(2));
                        $('.loader').addClass('d-none');
                        document.getElementById("pay_place_order_button").innerHTML = 'Pay & Place Order';
                        $('#product_detail_total_div').css('opacity', '1')
                        $('#checkout_form_button').css('opacity', '1')
                    }
                });
            }
        }
    </script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        $(function() {
            getCardDetail("{{ Auth()->user()->card_id }}")
            var $form = $(".require-validation");

            $('form.require-validation').bind('submit', function(e) {
                var $form = $(".require-validation"),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                        'input[type=text]', 'input[type=file]',
                        'textarea'
                    ].join(', '),
                    $inputs = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid = true;
                $errorMessage.addClass('hide');

                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                    var $input = $(el);
                    if ($input.val() === '') {
                        $input.parent().addClass('has-error');
                        $errorMessage.removeClass('hide');
                        e.preventDefault();
                    }
                });

                if (!$form.data('cc-on-file')) {
                    e.preventDefault();
                    Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                    Stripe.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    }, stripeResponseHandler);
                }

            });

            function stripeResponseHandler(status, response) {
                if (response.error) {
                    $('.error')
                        .removeClass('hide')
                        .find('.alert')
                        .text(response.error.message);
                } else {
                    $('#stripe_error_div').addClass('d-none');
                    var token = response['id'];
                    $form.find('input[type=text]').empty();
                    $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    document.getElementById("pay_place_order_button").innerHTML =
                        '<i class="fa fa-spinner fa-spin"></i>';
                    // Send the payment data to the server using AJAX
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('transaction.store') }}',
                        data: {
                            id: 1,
                            stripeToken: token,
                            couponCode: $('#coupon_code').val(),
                            status: 'success',
                            charge: $('#shipping_charge').val(),
                            card_id: $('#card_id').val(),
                            card_name: $('#card_name').val(),
                            number: $('.card-number').val(),
                            cvc: $('.card-cvc').val(),
                            exp_month: $('.card-expiry-month').val(),
                            exp_year: $('.card-expiry-year').val()
                        },
                        success: function(data) {
                            console.log('Payment stored successfully:', data);
                            $('#order-form').submit();
                        },
                        error: function(xhr, status, error) {
                            $('#stripe_error_message').text(xhr.responseJSON.message);
                            $('#stripe_error_div').removeClass('d-none');
                            document.getElementById("pay_place_order_button").innerHTML =
                                'Pay & Place Order';
                        }
                    });
                }
            }

        });
        $(document).on('change', '#card_id', function() {
            var id = $(this).val();
            getCardDetail(id)
        });

        function getCardDetail(cardId) {
            $.ajax({
                method: 'get',
                url: "{{ route('get-card-detail') }}",
                data: {
                    id: cardId,
                },
                success: function(res) {
                    $('#card_name').val(res.card_name),
                        $('.card-number').val(res.card_number),
                        $('.card-cvc').val(res.cvv_number),
                        $('.card-expiry-month').val(res.expire_month),
                        $('.card-expiry-year').val(res.expire_year)
                }
            })
        }
        $('#cuppon_detail').on('click', function(e) {
            var code = $('#coupon_code').val();
            $.ajax({
                method: 'get',
                url: "{{ route('coupon-detail') }}",
                data: {
                    code: code,
                },
                success: function(res) {
                    if (res.length == undefined) {
                        var amount = $('#product_id_' + res.product_id).val();
                        if (amount !== undefined) {
                            var total = $('#total_product_amount').val();
                            var discount = 0;
                            var subTotal = 0;
                            if (res.coupon_type == "percentage") {
                                disount = (parseInt(amount) * res.discount_percentage) / 100;
                                subTotal = parseInt(total) - disount;
                                var detail = parseInt(total) - (parseInt(total) - disount)
                                console.log(detail);
                                $('#discount_price_' + res.product_id).text("$" + detail.toFixed(2));
                                $('#discount_div_' + res.product_id).removeClass('d-none');
                            }
                            if (res.coupon_type == "dollar") {
                                discount = res.discount_amount;
                                subTotal = parseInt(total) - discount;
                                $('#discount_price_' + res.product_id).text("$" + discount);
                            }
                            $('#total_product_amount').val(subTotal.toFixed(2));
                            $('#sub_total').text("$" + subTotal);
                            var totalCharge = rate + subTotal;
                            $('#grand_total').val(totalCharge);
                            $('.grand_total').text("$" + totalCharge.toFixed(2));
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            })
        });
    </script>
@endsection
