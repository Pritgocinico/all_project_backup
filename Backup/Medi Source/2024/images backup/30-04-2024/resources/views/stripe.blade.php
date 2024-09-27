<!DOCTYPE html>
<html>

<head>
    <title>Medisource - Payment</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<style>
    .stripe-image {
        height: 135px;
        width: 230px;
        border-radius: 10px;
        margin: inherit;
        margin-bottom: 35px;
    }
</style>

<body>

    <div class="container">
        <div class="row"><br />
            <div class="col-md-6 col-md-offset-3">
                <img class="stripe-image" src="{{ url('/') }}/frontend/assets/images/stripe.gif">
                <div class="panel panel-default credit-card-box">
                    <div class="panel-heading display-table">
                        <h3 class="panel-title">Payment Details</h3>
                    </div>
                    <div class="panel-body">

                        @if (Session::has('success'))
                            <div class="alert alert-success text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">X</a>
                                <p>{{ Session::get('success') }}</p>
                            </div>
                        @endif

                        <form role="form" action="{{ route('stripe.post') }}" method="post"
                            class="require-validation" data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
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
                                        <option value="">New</option>
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
                                <div class='col-xs-12 form-group card required'>
                                    <label class='control-label'>Card Number</label> <input autocomplete='off'
                                        class='form-control card-number' size='20' type='text'>
                                </div>
                            </div>

                            <div class='form-row row'>
                                <div class='col-xs-12 col-md-4 form-group cvc required'>
                                    <label class='control-label'>CVC</label> <input autocomplete='off'
                                        class='form-control card-cvc' placeholder='ex. 311' size='4'
                                        type='text'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>Expiration Month</label> <input
                                        class='form-control card-expiry-month' placeholder='MM' size='2'
                                        type='text'>
                                </div>
                                <div class='col-xs-12 col-md-4 form-group expiration required'>
                                    <label class='control-label'>Expiration Year</label> <input
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

                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now
                                        ${{ $order->total }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    $(function() {
        getCardDetail("{{Auth()->user()->card_id}}")
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
                var token = response['id'];
                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // Send the payment data to the server using AJAX
                $.ajax({
                    type: 'POST',
                    url: '{{ route('transaction.store') }}',
                    data: {
                        id: {{ $order->id }},
                        stripeToken: token,
                        amount: {{ $order->total }},
                        status: 'success',
                        card_name: $('#card_name').val(),
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val()
                    },
                    success: function(data) {
                        console.log('Payment stored successfully:', data);
                        $form.get(0).submit();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error storing payment:', error);
                        // Handle the error as needed
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
            url: "{{route('get-card-detail')}}",
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
</script>

</html>
