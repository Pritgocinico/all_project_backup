@extends('frontend.layouts.app')

@section('content')
<section class="banner-section about-parent position-relative about-sec py-sm-5 py-1">
    <div class="container">
        <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start">
            <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
            <div class="col-md-12">
                <h1 class="text-white text-center">THANK YOU</h1>
            </div>
        </div>
    </div>
</section>

<section class="parent-order-details-form">
    <div class="container">
        <div class="row">
            <div class="parent-box-for-thank-you">
                <div class="parent-text-for-title">
                    <h2>Your order has been received</h2>
                </div>    
            </div>
</div>    
            <div class="row main-for-inner-child">
                <div class="col-lg-3 col-md-6 my-3">
                    <div class="parent-box-for-inner-child">
                        <h5>Order Number</h5>
                        <span>938</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 my-3">
                    <div class="parent-box-for-inner-child">
                        <h5>Date</h5>
                        <span>September 1, 2020</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 my-3">
                    <div class="parent-box-for-inner-child">
                        <h5>Total</h5>
                        <span>$110.00</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 my-3">
                    <div class="parent-box-for-inner-child">
                        <h5>Payment Method</h5>
                        <span>Cash on delivery</span>
                    </div>
                </div>   
            </div>     
            <div class="parent-text-for-title order-detail-text">
                <h2>Order Details</h2>
            </div>
            <table class="table-for-placed-thankyou-detail">
                <tr>
                    <th class="text-start text-uppercase">Product</th>
                    <th class="text-end text-uppercase">Total</th>
                </tr>
                <tr>
                    <td class="text-start">Glutathione 200mg/ml x <span>2</span> </td>
                    <td class="text-end">$100</td>
                </tr>
                <tr>
                    <td class="text-start">M.I. x <span>1</span> </td>
                    <td class="text-end">$50</td>
                </tr>
                <tr>
                    <td class="text-start">Subtotal</td>
                    <td class="text-end">$150</td>
                </tr>
                <tr>
                    <td class="text-start">FedEx</td>
                    <td class="text-end">FedEx Shipping Rate</td>
                </tr>
                <tr>
                    <td class="text-start">Payment method</td>
                    <td class="text-end">Cash on delivery</td>
                </tr> 
                <tr>
                    <td class="text-start fw-bold">TOTAL</td>
                    <td class="text-end">$150</td>
                </tr>   
            </table>
        </div>
    </div>
</section>
@endsection
