@extends('admin.layouts.app')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">Add Order</h2>
                        <a href="{{ route('admin.orders') }}" class="btn btn-secondary ms-auto">Go Back</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{route('store')}}" method="post">
                        @csrf
                        <div class="checkout-inner">
                            <section class="checkout-form">
                                <div class="container">
                                    <h3 class="mb-3">Your Order</h3>
                                    <div class="mb-3" id="">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Product</label>
                                                <select name="product" class="form-control select2" id="product">
                                                    <option>Select Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product['id'] }}"
                                                            @if (old('product') == $product['productname']) selected @endif>
                                                            {{ $product['productname'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Product Package</label>
                                                <select name="product_package" class="form-control select2" id="product_package">
                                                    <option value="">Select Product Package</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Product Price</label>
                                                <input type="number" class="form-control" name="product_price" id="product_price" placeholder="Price" readonly>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Qty</label>
                                                <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Quantity" readonly>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Total</label>
                                                <input type="number" class="form-control" name="total" id="total" placeholder="Total" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="mb-3">Contact Information</h3>
                                    <div class="mb-3" id="">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" placeholder="Email">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Phone</label>
                                                <input type="number" class="form-control" name="phone" placeholder="Phone">
                                            </div>
                                        </div>
                                    </div>

                                    <h3>Shipping Address</h3>
                                    <div class="mb-3" id="">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Address</label>
                                                <textarea name="address" class="form-control" id="address" placeholder="Address"></textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Apartment, suite, etc.</label>
                                                <textarea name="address1" class="form-control" id="address1" placeholder="Apartment, suite, etc."></textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" placeholder="City">
                                            </div>
                                            <div class="col-6">
                                                <label for="state" class="form-label">State</label>
                                                <select name="state" class="form-control select2" id="state">
                                                    <option value="">Select State</option>
                                                @foreach (NumberFormat::stateListDropdown() as $state)
                                                    <option value="{{$state['code']}}" @if(old('state') == $state['name']) {{'selected'}} @endif>{{$state['name']}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="Zip Code">
                                            </div>
                                            <div class="col-6 mt-4">
                                                <input type="checkbox" name="checkout-checkbox" id="checkout-checkbox">
                                                <label for="checkout-checkbox" class="form-label">Save this information for next time</label>
                                            </div>
                                        </div>
                                    </div>

                                    <h3>Billing Address</h3>
                                    <div class="mb-3" id="">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Address</label>
                                                <textarea name="billing_address" class="form-control" id="billing_address" placeholder="Address"></textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Apartment, suite, etc.</label>
                                                <textarea name="billing_address1" class="form-control" id="billing_address1" placeholder="Shipping Address"></textarea>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">City</label>
                                                <input type="text" class="form-control" id="billing_city" name="billing_city" placeholder="Last Name">
                                            </div>
                                            <div class="col-6">
                                                <label for="state" class="form-label">State</label>
                                                <select name="billing_state" class="form-control select2" id="billing_state">
                                                    <option value="">Select State</option>
                                                    @foreach (NumberFormat::stateListDropdown() as $state)
                                                    <option value="{{$state['code']}}" @if(old('state') == $state['name']) {{'selected'}} @endif>{{$state['name']}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">ZIP Code</label>
                                                <input type="text" class="form-control" id="billing_zip_code" name="billing_zip_code" placeholder="Zip Code">
                                            </div>
                                            <div class="col-6 mt-4">
                                                <input type="checkbox" name="address-checkbox" id="address-checkbox">
                                                <label for="checkout-checkbox" class="form-label">Billing Address Same as Shipping Address</label>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Place Order</button>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function(){
            $(document).on('change','#state',function(){
                var state = $(this).val();
                $.ajax({
                    url : "{{route('cityByState', '')}}"+"/"+state,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        $('#city').html(data);
                    }
                });
            });

            
            $('#address-checkbox').change(function() {
                var address = $('#address').val();
                var address1 = $('#address1').val();
                var city = $('#city').val();
                var state = $('#state').val();
                var zip_code = $('#zip_code').val();
                if(this.checked) {
                    $('#billing_address').val(address);
                    $('#billing_address1').val(address1);
                    $('#billing_city').val(city);
                    $('#billing_state').val(state);
                    $('#billing_zip_code').val(zip_code);
                }else{
                    $('#billing_address').val('');
                    $('#billing_address1').val('');
                    $('#billing_city').val('');
                    $('#billing_state').val('');
                    $('#billing_zip_code').val('');
                }   
            });

            $('#product').change(function(){
                var product_id = $(this).val();
                $.ajax({
                    url : "{{route('productPackage', '')}}"+"/"+product_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        var productPackage = $('#product_package');
                        productPackage.empty();
                        productPackage.append('<option value="" selected disabled> Select Product Package </option>');
                        
                        var product_packages = data;
                        for (var i = 0; i < product_packages.length; ++i) {
                                var product_package = product_packages[i];
                                var option = '<option value="' + product_package.id + '">' + product_package.varient_name + '</option>';
                                productPackage.append(option);
                            }
                    } 
                })
            });

            $('#product_package').change(function(){
                var package_id = $(this).val();
                $.ajax({
                    url : "{{route('packagePrice', '')}}"+"/"+package_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        $('#product_price').val(data.vial_price);
                        $('#quantity').val(data.vial_quantity);
                        $('#total').val(data.vial_total);
                    } 
                })
            });
        });
    </script>
@endsection
