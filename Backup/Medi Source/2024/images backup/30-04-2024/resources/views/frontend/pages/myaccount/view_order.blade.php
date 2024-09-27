@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section prdct-parent about-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        My Account
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="section-for-view-order-parent">
        <div class="container">
            <div class="inner-ac">
                <div class="tab-btn pe-xl-5 pe-lg-3">
                    <ul class="pro-tab">
                        <a href="{{ route('myaccount') }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0">Profile</p>
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                        </a>
                        <a href="{{ route('orders') }}">
                            <div class="d-flex align-items-center  justify-content-between active">
                                <p class="mb-0">Orders</p>
                                <i class="fas fa-shopping-basket"></i>
                            </div>
                        </a>
                        <a href="{{ route('card-detail') }}" class="active">
                            <div class="d-flex align-items-center  justify-content-between ">
                                <p class="mb-0">Card Detail</p>
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                            </div>
                        </a>
                    </ul>
                </div>
                <div class="page-content tab-content">
                    <section class="order-section-reorder-cancel">
                        <div class="">
                            <table class="table-resp">
                                <div class="d-flex align-items-center justify-content-between mb-4 inner-section-reorder-cancel">
                                    <h4 class="pb-0 mb-0">Order details</h4>
                                    <div class="d-flex align-items-start gap-3">
                                    <div class="form-control-btn">
                                        <input type="hidden" name="user_id" value="2">
                                        <a href="{{route('re.order',['id'=> $order->id])}}">
                                            <button type="submit" class="update bg-custom-blue header-btn border-0 mt-0 text-white  ">
                                            <i class="fa-solid fa-repeat me-2"></i>Re-Order
                                            </button>
                                         </a>
                                    </div>
                                    <div class="form-control-btn">
                                    <input type="hidden" name="user_id" value="2">
                                        <a href="{{ route('cancel.order', ['id' => $order->id]) }}">
                                            <button type="submit" class="update header-btn border-0 mt-0 text-white    ">
                                            Cancel Order
                                            </button>
                                         </a>
                                    </div>
                                    </div>
                                </div>
                                
                                @php
                                    if($order->status == 0){
                                        $status = 'Processing';
                                    }elseif($order->status == 1){
                                        $status = 'On Delivery';
                                    }elseif($order->status == 2){
                                        $status = 'Delivered';
                                    }else{
                                        $status = 'Canceled';
                                    }
                                @endphp
                                <p>Order<b> #{{$order->order_id}}</b> was placed on <b>{{date('F j,Y',strtotime($order->created_at))}}</b>And is currently <b>{{$status}}</b>
                                </p>
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!blank($order_items))
                                        @foreach ($order_items as $item)
                                            <tr>
                                                <td>{{$item->product_name}} ({{$item->package_name}})</td>
                                                <td>${{$item->total}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr style="border-top:2px solid lightgray;">
                                        <td><b>Cart Subtotal</b></td>
                                        <td>${{$order->total-$order->shipping_charge}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Shipping</b></td>
                                        <td>${{$order->shipping_charge}} (Fedex)</td>
                                    </tr>
                                    <tr>
                                        <td><b>Order Total</b></td>
                                        <td>${{$order->total}}</td>
                                    </tr>
                                    @if($order->tracking_number !== null)
                                    <tr>
                                        <td><b>Order Tracking Number</b></td>
                                        <td>{{$order->tracking_number}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <h4 class="mt-5 pb-3">Customer details</h4>
                            <div class="d-flex gap-3 cust-d">
                                <h5>
                                    Name :
                                </h5>
                                <p>{{$order->first_name .' '. $order->last_name}}</p>
                            </div>
                            <div class="d-flex gap-3 cust-d">
                                <h5>
                                    Email :
                                </h5>
                                <p>{{$order->email}}</p>
                            </div>
                            <div class="d-flex gap-3 cust-d">
                                <h5>
                                    Phone :
                                </h5>
                                <p>{{$order->phone}}</p>
                            </div>
                            <div class="d-flex gap-3 cust-d">
                                <h5>
                                    Shipping Address :
                                </h5>
                                <div>
                                    <p>{{$order->address}}</p>
                                    @if(!blank($order->address1))
                                        <p>{{$order->address1}}</p>
                                    @endif
                                    <p>{{$order->city.' '.$order->state}}</p>
                                    <p>{{$order->zip_code}}</p>
                                </div>
                            </div>
                            <div class="d-flex gap-3 cust-d">
                                <h5>
                                    Billing Address :
                                </h5>
                                <div>
                                    <p>{{$order->billing_address}}</p>
                                    @if(!blank($order->billing_address1))
                                        <p>{{$order->billing_address1}}</p>
                                    @endif
                                    <p>{{$order->billing_city.' '.$order->billing_state}}</p>
                                    <p>{{$order->billing_zip_code}}</p>
                                </div>
                            </div>
                        </div>
                    </section>
                    {{-- <div>
                        <!--start view order detail section---------------------------------- -->
                        <!--end view order detail section---------------------------------- -->
                        <section class="checkout-form">
                            <div class="container">
                                <form action="#!" method="get">
                                    <div class="">
                                        <div class="rgs-w mt-0">
                                            <label for="organization_name" class="form-label">Organization Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="organization_name" class="form-control py-2"
                                                id="organization_name" value="" required="">
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="first_name" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="first_name" class="form-control py-2"
                                                    value="" id="first_name" required="">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>


                                            <div class="rgs-w">
                                                <label for="last_name" class="form-label">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="last_name" class="form-control py-2"
                                                    value="" id="last_name" required="">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>
                                        </div>



                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="npi" class="form-label">NPI</label>
                                                <input type="text" name="npi" class="form-control py-2"
                                                    value="" id="npi">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>

                                            <div class="rgs-w">
                                                <label for="business_license_number" class="form-label">Business License
                                                    Number</label>
                                                <input type="text" name="business_license_number" value=""
                                                    class="form-control py-2" id="business_license_number">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" d-flex gap-2 name-f">

                                            <div class="rgs-w">
                                                <label for="prescriber_state_license_number" class="form-label">Prescriber
                                                    State
                                                    License
                                                    Number</label>
                                                <input type="text" name="prescriber_state_license_number" value=""
                                                    class="form-control py-2" id="prescriber_state_license_number">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>

                                            <div class="rgs-w">
                                                <label for="dea_number" class="form-label">DEA Number</label>
                                                <input type="text" name="dea_number" class="form-control py-2"
                                                    value="" id="dea_number">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>
                                        </div>


                                        <div class="rgs-w">
                                            <label for="speciality" class="form-label">Speciality <span
                                                    class="text-danger">*</span></label>
                                            <select name="speciality" class="form-control py-2" id="speciality"
                                                required="">
                                                <!-- Add options for the dropdown -->
                                                <option value="Ophthalmology">Ophthalmology</option>
                                                <option value="Optometry">Optometry</option>
                                                <option value="Retina">Retina</option>
                                                <option value="Anesthesia">Anesthesia</option>
                                                <option value="Derm/Aesthetics">Derm/Aesthetics</option>
                                                <option value="Dentist">Dentist</option>
                                                <option value="Integrative/Other">Integrative/Other</option>
                                                <option value="Vet">Vet</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>
                                        <div class=" d-flex gap-2 name-f">


                                            <div class="rgs-w">
                                                <label for="phone" class="form-label">Phone <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control py-2"
                                                    id="phone" value="" required="">
                                                <div class="invalid-feedback" id="phone-error">
                                                    This field is required
                                                </div>
                                            </div>


                                            <div class="rgs-w">
                                                <label for="practice_address_street" class="form-label">Street <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="practice_address_street" value=""
                                                    class="form-control py-2" id="practice_address_street"
                                                    required="">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>

                                        </div>
                                        <div class=" d-flex gap-2 name-f">

                                            <div class="rgs-w">
                                                <label for="city" class="form-label">City <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" id="city" name="city"
                                                    required="">
                                                    <option value="" disabled="" selected="">Select City
                                                    </option>
                                                    <option value="Bamboo Flat">
                                                        Bamboo Flat</option>
                                                    <option value="Nicobar">
                                                        Nicobar</option>
                                                    <option value="Port Blair">
                                                        Port Blair</option>
                                                    <option value="South Andaman">
                                                        South Andaman</option>
                                                    <option value="Addanki">
                                                        Addanki</option>
                                                    <option value="Adoni">
                                                        Adoni</option>
                                                    <option value="Akasahebpet">
                                                        Akasahebpet</option>
                                                    <option value="Akividu">
                                                        Akividu</option>
                                                    <option value="Akkarampalle">
                                                        Akkarampalle</option>
                                                    <option value="Amalapuram">
                                                        Amalapuram</option>
                                                    <option value="Amudalavalasa">
                                                        Amudalavalasa</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a city.
                                                </div>
                                            </div>

                                            <div class="rgs-w">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" id="state" name="state"
                                                    required="">
                                                    <option value="" disabled="" selected="">Select State
                                                    </option>
                                                    <option value="Andaman and Nicobar Islands">
                                                        Andaman and Nicobar Islands</option>
                                                    <option value="Andhra Pradesh">
                                                        Andhra Pradesh</option>
                                                    <option value="Arunachal Pradesh">
                                                        Arunachal Pradesh</option>
                                                    <option value="Assam">
                                                        Assam</option>
                                                    <option value="Bihar">
                                                        Bihar</option>
                                                    <option value="Chandigarh">
                                                        Chandigarh</option>
                                                    <option value="Chhattisgarh">
                                                        Chhattisgarh</option>

                                                </select>
                                                <div class="invalid-feedback">
                                                    Please select a state.
                                                </div>
                                            </div>
                                        </div>

                                        <div class=" d-flex gap-2 name-f">


                                            <div class="rgs-w">
                                                <label for="zip_code" class="form-label">Zip Code <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="zip_code" class="form-control py-2"
                                                    value="" id="zip_code" required="">
                                                <div class="invalid-feedback">
                                                    This field is required
                                                </div>
                                            </div>

                                            <div class="rgs-w">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control py-2"
                                                    id="email" value="" required="">
                                                <div class="invalid-feedback" id="email-error">
                                                    This field is required
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-control-btn mt-4 text-start ps-2">
                                            <a href=""
                                                class="header-btn profile-btn bg-dark border-0 mt-lg-0 mt-lg-4 mb-lg-0 mb-4 text-white py-2 px-4">
                                                Save Changes
                                            </a>
                                        </div>



                                        <h5 class="text-start my-4">Password change</h5>


                                        <div class="rgs-w">
                                            <label for="password" class="form-label">Current password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control py-2"
                                                id="password" required="">
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>
                                        <div class="rgs-w">
                                            <label for="password" class="form-label"> New password
                                                <span class="text-danger">*</span></label>
                                            <input type="password" name="password" class="form-control py-2"
                                                id="password" required="">
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>
                                        <div class="rgs-w">
                                            <label for="confirm_password" class="form-label">Confirm Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="confirm_password" class="form-control py-2"
                                                id="confirm_password" required="">
                                            <div class="invalid-feedback">
                                                This field is required
                                            </div>
                                        </div>



                                        <div class="form-control-btn mt-5 text-start ps-2">
                                            <a href=""
                                                class="header-btn profile-btn bg-dark border-0 mt-lg-0 mt-lg-4 mb-lg-0 mb-4 text-white py-2 px-4">
                                                Save Changes
                                            </a>
                                        </div>


                        </section>


                    </div> --}}
                    <!-- ------------------------------%%%%%------- -->
                </div>
    </section>
@endsection
