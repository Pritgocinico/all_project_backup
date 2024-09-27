@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Order</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.orders') }}" class="btn btn-primary ustify-content-end float-right">
                    Go Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="card p-3">
    <div class="card-body">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('admin.edit.order') }}" method="POST" class="row g-3" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Customer Name" value="{{$order->customer_name}}">
                @if ($errors->has('customer_name'))
                    <span class="text-danger">{{ $errors->first('customer_name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="phone_no" id="Phone" placeholder="Phone Number" value="{{$order->phone_no}}">
                @if ($errors->has('phone_no'))
                    <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                @endif
            </div>
            <div class="col-md-12">
                <label for="Address" class="form-label">Address <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="address" id="Address" placeholder="Address" value="{{$order->address}}">
                @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
            </div>
           
            <hr>
            <h6>Products</h6>
            <?php $id = 100; $amt = 0;?>
            <div class="products_data">
                @foreach($items as $item)
                    <?php $id++; 
                    $amt = $amt+$item->total;
                    ?>
                    <div class="bg-light p-3 mt-2 product-{{$id}}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">Category</label>
                                <select name="category[]" id="" class="form-control cat cat-{{$id}}" data-id="{{$id}}" required>
                                    <option value="">Select Category...</option>
                                    @if (count($parent_categories)>0)
                                        @foreach ($parent_categories as $item1)
                                            <?php 
                                            $childs = DB::table('categories')->where('parent',$item1->id)->get();
                                            ?>
                                            @if (count($childs)>0)
                                                <optgroup label="{{$item1->name}}">
                                                    @foreach ($childs as $child)
                                                        <option value="{{$child->id}}" @if ($child->id == $item->category_id)
                                                            selected
                                                        @endif>{{$child->name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @else
                                                <option value="{{$item1->id}}" @if ($item->category_id == $item1->id)
                                                    selected
                                                @endif>{{$item1->name}}</option> 
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="Product">Product</label>
                                <select name="products[]" class="form-control products products-{{$id}}" data-id ="{{$id}}" id="" required>
                                    <option value="">Select Product...</option>
                                        <?php 
                                        $products = DB::table('products')->where('category',$item->category_id)->get();
                                        ?>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" @if ($product->id == $item->product_id)
                                                selected
                                            @endif>[{{$product->sku}}] {{$product->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="javascript:void(0);" class="ms-2 delete-btn delete-variant delete-{{$id}}" data-id="{{$item->id}}"><img src="{{url('/')}}/public/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="">Variant</label>
                                <select name="variant[]" class="form-control variants-{{$id}} variant" data-id="{{$id}}" id="" required>
                                    <option value="">Select Product Variant...</option>
                                    <?php 
                                        $variants = DB::table('product_variants')->where('product_id',$item->product_id)->get();
                                        ?>
                                        @foreach($variants as $variant)
                                            <option value="{{$variant->id}}" @if ($variant->id == $item->variant_id)
                                                selected
                                            @endif>[{{$variant->capacity}}] {{$variant->capacity}}</option>
                                        @endforeach
                                </select>
                                <p class="text-danger">
                                    <?php $pr = DB::table('product_variants')->where('id',$item->variant_id)->first();?>
                                    <p class="text-danger fs-12">( Stock : <span class="stock-{{$id}} fs-12">@if(!blank($pr)){{$pr->stock}} @else 0 @endif</span> )</p>
                                </p>
                            </div>
                            <div class="col-md-2">
                                <label for="">Price</label>
                                <input type="hidden" name="pr_price[]" class="form-control pr-price-{{$id}}" value="{{$item->price}}">
                                <input type="hidden" id="item_amt-{{$id}}" value="{{$item->price}}">
                                <input type="text" name="price[]" class="form-control price-{{$id}}" value="{{$item->price}}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label for="">Quantity</label>
                                <input type="number" name="quantity[]" min="0" value="{{$item->quantity}}" class="form-control qty qty-{{$id}}" data-id="{{$id}}">
                            </div>
                            <div class="col-md-2">
                                <label for="Stock">Stock</label>
                                <?php $pr = DB::table('product_variants')->where('id',$item->variant_id)->first();?>
                                <input type="number" name="stock[]" class="form-control stock-{{$id}}" value="@if(!blank($pr)){{$pr->stock}}@else 0 @endif" disabled>
                            </div>
                            <div class="col-md-2 m-auto text-end">
                                <input type="hidden" name="product_total[]" class="pr-total-{{$id}}" value="{{$item->quantity*$item->price}}">
                                <h6 class="mb-0">Total : <span class="text-danger"> &#x20B9; <span class="total total-{{$id}}">{{$item->quantity*$item->price}}</span></span></h6>
                            </div>
                        </div>
                        <div class="row text-danger">
                            @if(!blank($pr))
                                <?php $stock = $pr->stock; ?>
                            @else 
                                <?php $stock = 0; ?>
                            @endif
                            @if($stock == 0)
                                *Stock is not available for this product.
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-end mt-3">
                <div class="col-md-3">
                    <h5 class="text-end">Total : <span class="text-danger fs-24"> &#x20B9; <span class="g-total fs-24">@if(!blank($order->amount)){{$order->amount}}@else 0 @endif</span></span></h5>
                </div>
            </div>
            <div>
                <button type="button" name="add" class="btn btn-primary mt-3 add_product">Add Product</button>
            </div>
          
            <div class="col-12 mt-3">
                <input type="hidden" name="amount" class="grand_total" value="{{$amt}}">
                <input type="hidden" name="order_id" value="{{$order->id}}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script>
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
                    $('.stock-'+id).html('');
                    $('.product-stock-'+id).html('');
                    $('.total-'+id).html('');
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
                    if(data.stock == 0){
                        $('.product-stock-'+id).html('');
                    }
                    $('.total-'+id).html('');
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
                        $('.product-stock-'+id).html('* Stock is not available for this product.')
                    }
                    $('.total-'+id).html(data.price*qty);
                    $('.pr-total-'+id).val(data.price*qty);
                    var gtotal = $('.g-total').html();
                    var amt = $('#item_amt-'+id).val();
                    var ttl = parseInt(gtotal)+parseInt(data.price)-parseInt(amt);
                    $('.g-total').html(ttl);
                    $('.grand_total').val(ttl);
                    $('#item_amt-'+id).val(parseInt(data.price));
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
        // $(document).on('click','.delete-variant',function(){
        //     var var_id = $(this).data('id');
        //     $('.var-'+var_id).remove();
        // });
         $(document).on('click','.delete-variant',function(){
            var id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.variant', '')}}"+"/"+id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {  
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Variant has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                
                                $('.g-total').html(data);
                                $('.grand_total').val(data);
                                location.reload();
                                // top.location.href="{{ route('admin.users') }}";
                            }
                        });
                    }
                });
            }
            });
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
@endsection