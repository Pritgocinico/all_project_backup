@extends('frontend.layouts.app')

@section('content')

<div class="container">
    <div class="outer-thankyou-section outer-thankyou-section-top">
        <div>
            <div class="logo">
                <div class="logo-inner">
                     <i class="fa-solid fa-check"></i>
                </div>
            </div>
            <div>
                <h2><strong>Payment Success</strong></h2>
            </div>
            <div>
                <h4>THANK YOU</h4>
                <h5>Your Order Has Been Confirmed !</h5>
            </div>
            <div class="detail">
                <p>We've sent your free report to your inbox so it's easy to access. You can find more information on our
                    website and social pages.</p>
            </div>
            <a href="{{ route('orders') }}" class="btn btn-primary view-order-btn mt-2 mt-sm-4 px-4 py-2">View Orders</a>
        </div>
    </div>
</div>


@endsection
