@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Add Order</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.orders') }}" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="houmanity-card p-3">
    <div class="">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="card">
                    <div class="card-body m-3">
                        @if(Session::has('alert'))
                            <p class="alert
                            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
                        @endif
                        <form action="{{ route('admin.add_order_data') }}" method="POST" class="row g-3" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6">
                                <label for="Users" class="form-label">Dealers <span class="text-danger">*</span></label>
                                    <select name="user_id" id="Users" class="form-control select2">
                                        <option value="0">Select Dealer...</option>
                                        @if(!blank($users))
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                {{-- <input type="text" class="form-control" name="state" id="State" placeholder="State" value="{{old('state')}}"> --}}
                                @if ($errors->has('state'))
                                    <span class="text-danger">{{ $errors->first('state') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Agents" class="form-label">Agent <span class="text-danger">*</span></label>
                                    <select name="agent_id" id="Agents" class="form-control select2">
                                        <option value="0">Select Agent...</option>
                                        @if(!blank($agents))
                                            @foreach($agents as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                {{-- <input type="text" class="form-control" name="state" id="State" placeholder="State" value="{{old('state')}}"> --}}
                                @if ($errors->has('agent_id'))
                                    <span class="text-danger">{{ $errors->first('agent_id') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="phone_no" id="Phone" placeholder="Phone Number" value="{{old('phone_no')}}">
                                @if ($errors->has('phone_no'))
                                    <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="CustomerName" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" id="CustomerName" placeholder="Customer Name" value="{{old('customer_name')}}">
                                @if ($errors->has('customer_name'))
                                    <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="Email" placeholder="Email" value="{{old('email')}}">
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Floor" class="form-label">Floor No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="floor_no" id="Floor" placeholder="Floor No" value="{{old('floor_no')}}">
                                @if ($errors->has('floor_no'))
                                    <span class="text-danger">{{ $errors->first('floor_no') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Address" class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address" id="Address" placeholder="Address" value="{{old('address')}}">
                                @if ($errors->has('address'))
                                    <span class="text-danger">{{ $errors->first('address') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Locality" class="form-label">Locality <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="locality" id="Locality" placeholder="Locality" value="{{old('locality')}}">
                                @if ($errors->has('locality'))
                                    <span class="text-danger">{{ $errors->first('locality') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="City" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="city" id="City" placeholder="City" value="{{old('city')}}">
                                @if ($errors->has('city'))
                                    <span class="text-danger">{{ $errors->first('city') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="State" class="form-label">State <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="state" id="Floor" placeholder="State" value="{{old('state')}}">
                                @if ($errors->has('state'))
                                    <span class="text-danger">{{ $errors->first('state') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Country" class="form-label">Country<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="country" id="Country" placeholder="Country" value="{{old('country')}}">
                                @if ($errors->has('country'))
                                    <span class="text-danger">{{ $errors->first('country') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="Transport" class="form-label">Transport<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="transport" id="Transport" placeholder="Transport" value="{{old('transport')}}">
                                @if ($errors->has('transport'))
                                    <span class="text-danger">{{ $errors->first('transport') }}</span>
                                @endif
                            </div>
                            <hr>
                            <h6>Products</h6>
                            <?php $id = 0; ?>
                                <div class="products_data">
                                    @if (old('category'))
                                        <?php $cnt = count(old('category')); ?>
                                        @for ($i = 0; $i < $cnt; $i++)
                                            <div class="bg-light p-3 mt-2 product-{{$i}}">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="">Category</label>
                                                        <select name="category[]" id="" class="form-control cat cat-{{$i}}" data-id="{{$i}}" required>
                                                            <option value="">Select Category...</option>
                                                            @if (count($parent_categories)>0)
                                                                @foreach ($parent_categories as $item1)
                                                                    <?php
                                                                    $childs = DB::table('categories')->where('parent',old('category.'.$i))->get();
                                                                    ?>
                                                                    @if (count($childs)>0)
                                                                        <optgroup label="{{$item1->name}}">
                                                                            @foreach ($childs as $child)
                                                                                <option value="{{$child->id}}" @if ($child->id == old('category.'.$i))
                                                                                    selected
                                                                                @endif>{{$child->name}}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @else
                                                                        <option value="{{$item1->id}}" @if (old('category.'.$i) == $item1->id)
                                                                            selected
                                                                        @endif>{{$item1->name}}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="Product">Product</label>
                                                        <select name="products[]" class="form-control products products-{{$i}}" data-id ="{{$i}}" id="" required>
                                                            <option value="">Select Product...</option>
                                                                <?php
                                                                $products = DB::table('products')->where('category',old('category.'.$i))->get();
                                                                ?>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}" @if ($product->id == old('products.'.$i))
                                                                        selected
                                                                    @endif>[{{$product->sku}}] {{$product->name}}</option>
                                                                @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <label for="">Variant</label>
                                                        <select name="variant[]" class="form-control variants-{{$i}} variant" data-id="{{$i}}" id="" required>
                                                            <option value="">Select Product Variant...</option>
                                                            <?php
                                                                $variants = DB::table('product_variants')->where('product_id',old('products.'.$i))->get();
                                                                ?>
                                                                @foreach($variants as $variant)
                                                                    <option value="{{$variant->id}}" @if ($variant->id == old('variant.'.$i))
                                                                        selected
                                                                    @endif>[{{$variant->sku}}] {{$variant->capacity}}</option>
                                                                @endforeach
                                                        </select>
                                                        <p class="text-danger">
                                                            <?php $pr = DB::table('product_variants')->where('id',old('variant.'.$i))->first();?>
                                                            <p class="text-danger fs-12">( Stock : <span class="stock-{{$i}} fs-12">@if(!blank($pr)){{$pr->stock}}@else 0 @endif</span> )</p>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Price</label>
                                                        <input type="hidden" name="pr_price[]" class="form-control pr-price-{{$i}}" value="{{old('price.'.$i)}}">
                                                        <input type="hidden" id="item_amt-{{$i}}" value="{{old('price.'.$i)}}">
                                                        <input type="text" name="price[]" class="form-control price-{{$i}}" value="{{old('price.'.$i)}}" disabled>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="">Quantity</label>
                                                        <input type="number" name="quantity[]" min="0" value="{{old('quantity.'.$i)}}" class="form-control qty qty-{{$i}}" data-id="{{$i}}">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <label for="Stock">Stock</label>
                                                        <?php $pr = DB::table('product_variants')->where('id',old('variant.'.$i))->first();?>
                                                        <input type="number" name="stock[]" class="form-control stock-{{$i}}" value="@if(!blank($pr)){{$pr->stock}}@else 0 @endif" disabled>
                                                    </div>
                                                    <div class="col-md-2 m-auto text-end">
                                                        <input type="hidden" name="product_total[]" class="pr-total-{{$i}}" value="{{old('quantity.'.$i)*old('price.'.$i)}}">
                                                        <h6 class="mb-0">Total : <span class="text-danger"> &#x20B9; <span class="total total-{{$i}}">{{old('quantity.'.$i)*old('price.'.$i)}}</span></span></h6>
                                                    </div>
                                                </div>
                                                <div class="row text-danger">
                                                    @if(!blank($pr))
                                                        <?php $stock = $pr->stock; ?>
                                                    @else
                                                        <?php $stock = 0; ?>
                                                    @endif
                                                    @if($stock == 0)
                                                        * Stock is not available for this product.
                                                    @endif
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                                <input type="hidden" name="product_data" class="product_data" value="{{old('product_data')}}" >
                                @if ($errors->has('product_data'))
                                    <span class="text-danger">Please Select Order Products.</span>
                                @endif
                                <div class="row justify-content-end mt-3">
                                    <div class="col-md-4">
                                        <h5 class="text-end">Total : <span class="text-danger fs-24"> &#x20B9; <span class="g-total fs-24">@if(old('amount')) {{old('amount')}} @else 0 @endif</span></span></h5>
                                    </div>
                                </div>
                            <div>
                                <button type="button" name="add" class="btn btn-primary mt-3 add_product">Add Product</button>
                            </div>
                            <hr class="mt-5">
                            <div class="col-md-12">
                                <label for="Remarks" class="form-label">Remarks </label>
                                <textarea class="form-control" name="remarks" rows="3" id="Remarks" placeholder="Remarks" >{{old('remarks')}}</textarea>
                                @if ($errors->has('remarks'))
                                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                                @endif
                            </div>
                            <div class="col-12 mt-3">

                                <input type="hidden" name="amount" class="grand_total" value="@if(old('amount')) {{old('amount')}} @else 0 @endif">
                                <button type="submit" class="btn btn-primary add_order">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).on('click','.add_order',function(){
            $('#loader').removeClass('hidden');
        });
        $(document).on('change','#flexSwitchCheckChecked',function(){
            if($(this).prop('checked') == true){
                $('.lead_datetime').removeClass('d-none');
            }else{
                $('.lead_datetime').addClass('d-none');
            }
        })
        $(document).on('change','#flexSwitchCheck',function(){
            if($(this).prop('checked') == true){
                $('.divert_order').removeClass('d-none');
            }else{
                $('.divert_order').addClass('d-none');
            }
        });


        var i = 0;
        $(document).on('change','.cat',function(){
            var id = $(this).data('id');
            var cat = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_cat_products") }}',
                data: {'id': cat},
                success: function (data) {
                    $('.products-'+id).html('');
                    $('.products-'+id).append(data);
                    $('.price-'+id).val(0);
                    $('.pr-price-'+id).val(0);
                    $('.stock-'+id).val('');
                    $('.total-'+id).html(0);
                    $('.pr-total-'+id).val('0')
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','.products',function(){
            var id = $(this).data('id');
            var product = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_product_variants") }}',
                data: {'id': product},
                success: function (data) {
                    $('.variants-'+id).html('');
                    $('.variants-'+id).append(data);
                    $('.price-'+id).val(0);
                    $('.pr-price-'+id).val(0);
                    $('.stock-'+id).val('');
                    $('.stock-'+id).html('');
                    $('.product-stock-'+id).html('');
                    $('.total-'+id).html(0);
                    $('.pr-total-'+id).val('0')
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','.variant',function(){
            var id = $(this).data('id');
            var variant = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_variant") }}',
                data: {'id': variant},
                success: function (data) {
                    var qty = $('.qty-'+id).val();
                    $('.price-'+id).val(data.price);
                    $('.pr-price-'+id).val(data.price);
                    $('.stock-'+id).val(data.stock);
                    $('.stock-'+id).html(data.stock);
                    if(data.stock == 0){
                        $('.product-stock').html('* Stock is not available for this product.')
                    }
                    $('.total-'+id).html(data.price*qty);
                    $('.pr-total-'+id).val(data.price*qty);
                    var gtotal = $('.g-total').html();
                    var amt = $('#item_amt-'+id).val();
                    var ttl = parseInt(gtotal)+parseInt(data.price)-parseInt(amt);
                    $('.g-total').html(ttl);
                    $('.grand_total').val(ttl);
                    $('#item_amt-'+id).val(data.price);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('change','.qty',function(){
            var id = $(this).data('id');
            var qty = $(this).val();
            var gtotal = $('.g-total').html();
            var amt =  $('.total-'+id).html();
            var amount = $('#item_amt-'+id).val()*qty;
            var ttl = parseInt(gtotal)+parseInt(amount)-parseInt(amt);
            $('.pr-total-'+id).val($('#item_amt-'+id).val()*qty);
            $('.total-'+id).html(amount);
            $('.g-total').html(ttl);
            $('.grand_total').val(ttl);
        });
        $(document).on('click','.add_product',function(){
            i = i+1;
            var ctn = i;
            $('.product_data').val(i);
            // $('.variants').removeClass('d-none');
            var html = '';
            html += '<div class="bg-light p-3 mt-2 product-'+i+'">';
            html += '<div class="row">';
            html += '<div class="col-md-4">';
            html += '<label for="">Category</label>';
            html += '<select name="category[]" id="" class="form-control cat cat-'+i+'" data-id="'+i+'" required>';
            html += '<option value="">Select Category...</option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<label for="Product">Product</label>';
            html += '<select name="products[]" class="form-control products products-'+i+'" data-id ="'+i+'" id="" required>';
            html += '<option value="">Select Product...</option>';
            html += '</select>';
            html += '</div>';
            html += '<div class="col-md-2 text-end">';
            html += '<a href="javascript:void(0);" class="ms-2 delete-btn delete-'+i+'" data-id="'+i+'"><img src="{{url('/')}}/public/assets/admin/images/delete.png" width="20px" class="me-2"></a>';
            html += '</div>';
            html += '</div>';
            html += '<div class="row mt-3">';
            html += '<div class="col-md-4">';
            html += '<label for="">Variant</label>';
            html += '<select name="variant[]" class="form-control variants-'+i+' variant" data-id="'+i+'" id="" required>';
            html += '<option value="">Select Product Variant...</option>';
            html += '</select>';
            // html += '<p class="text-danger fs-12">( Stock : <span class="stock-'+i+' fs-12" >0</span> )</p>';
            html += '</div>';
            html += '<div class="col-md-3">';
            html += '<label for="">Price</label>';
            html += '<input type="hidden" id="item_amt-'+i+'" value="0">';
            html += '<input type="hidden" name="pr_price[]" value="" class="form-control pr-price-'+i+'">';
            html += '<input type="text" name="price[]" value="" class="form-control price-'+i+'">';
            html += '</div>';
            html += '<div class="col-md-3">';
            html += '<label for="">Quantity</label>';
            html += '<input type="number" name="quantity[]" min="0" value="1" class="form-control qty-'+i+' qty" data-id="'+i+'">';
            html += '</div>';
            // html += '<div class="col-md-2">';
            // html += '<label for="Stock">Stock</label>';
            // html += '<input type="number" name="stock[]" class="form-control stock-'+i+'" disabled>';
            // html += '</div>';
            html += '<div class="col-md-2 m-auto text-end">';
            html += '<input type="hidden" name="product_total[]" class="pr-total-'+i+'" value="">';
            html += '<h6 class="mb-0">Total : <br><span class="text-danger"> &#x20B9; <span class="total total-'+i+'">0</span></span></h6>';
            html += '</div>';
            html += '</div>';
            html += '<div class="row product-stock-'+i+'"></div>';
            html += '</div>';
            $('.products_data').append(html);
            $.ajax({
                type: 'GET',
                url: '{{ route("get_categories") }}',
                success: function (data) {
                    $('.cat-'+i).append(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
        $(document).on('click','.delete-variant',function(){
            var var_id = $(this).data('id');
            $('.var-'+var_id).remove();
        });
        $('#Image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => {
                $('.product_preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
        $(document).on('click','.delete-btn',function(){
            // alert($(this).data('id'));
            var data = $(this).data('id');
            var price = $('.pr-total-'+data).val();
            var gtl = $('.g-total').html();
            $('.g-total').html(parseInt(gtl)-parseInt(price));
            var grand = $('.grand_total').val();
            $('.grand_total').val(parseInt(grand)-parseInt(price));
            // alert(price);
            $('.product-'+data).remove();
        });
    </script>
     <script>

    </script>
@endsection
