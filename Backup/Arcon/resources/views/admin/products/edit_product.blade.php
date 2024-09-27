@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Product</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.products') }}" class="btn btn-primary ustify-content-end float-right">
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
        <form action="{{ route('admin.edit.product') }}" method="POST" class="row g-3" enctype="multipart/form-data">
        @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="Product Name" value="{{$product->name}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="SKU" class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sku" id="SKU" placeholder="Product SKU" value="{{$product->sku}}">
                @if ($errors->has('sku'))
                    <span class="text-danger">{{ $errors->first('sku') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="BrandName" class="form-label">Brand Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="brand_name" id="BrandName" placeholder="Brand Name" value="{{$product->brand_name}}">
                @if ($errors->has('brand_name'))
                    <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Category" class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category" class="form-control" id="">
                    <option value="0">Select Category...</option>
                    @if (count($parent_categories)>0)
                        @foreach ($parent_categories as $item)
                            <?php 
                            $childs = DB::table('categories')->where('parent',$item->id)->get();
                            ?>
                            @if (count($childs)>0)
                                <optgroup label="{{$item->name}}">
                                    @foreach ($childs as $child)
                                        <option value="{{$child->id}}" @if ($child->id == $product->category)
                                            selected
                                        @endif>{{$child->name}}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{$item->id}}" @if ($product->category == $item->id)
                                    selected
                                @endif>{{$item->name}}</option> 
                            @endif
                        @endforeach
                        
                    @endif
                </select>
                @if ($errors->has('category'))
                    <span class="text-danger">{{ $errors->first('category') }}</span>
                @endif
            </div>
            <div class="col-md-10">
                <label for="Image" class="form-label">Image </label>
                <input type="file" class="form-control" name="image" id="Image">
                @if ($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
            </div>
            <div class="col-md-2">
                <img src="{{url('/')}}/public/products/{{$product->image}}" id="product_image" class="product_preview" height="80px" alt="">
                <input type="hidden" name="hidden_image" id="product_hidden_image" value="{{$product->image}}">
                @if(!blank($product->image))
                    <a href="javascript:void(0);" class="text-danger delete-image my-auto ms-3">Delete</a>
                @endif
            </div>
            <div class="col-md-12">
                <label for="Slider" class="form-label">Slider Images </label>
                <div class="input-group hdtuto control-group lst increment" >
                    <input type="file" name="slider[]" class="myfrm form-control" id="slider">
                    <div class="input-group-btn"> 
                      <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                    </div>
                  </div>
                  <div class="clone hide">
                    <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                      <input type="file" name="slider[]" class="myfrm form-control" id="slider">
                      <div class="input-group-btn"> 
                        <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                      </div>
                    </div>
                  </div>
                
                {{-- <input type="file" class="form-control" name="slider[]" accept=".png, .jpg, .jpeg" multiple id="slider"> --}}
            </div>
            <div class="col-md-12">
                @if(!blank($images))
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col" class="text-center">Image</th>
                        <th scope="col" class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td class="text-center"><img src="{{url('/')}}/public/products/{{$item->image}}" width="80px" alt=""></td>
                                <td class="text-center">
                                    <a href="{{route('delete.product.image',$item->id)}}" class="delete-btn btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                    
                @endif
            </div>
            <div class="col-md-6">
                <label for="CGST" class="form-label">CGST</label>
                <input type="number" class="form-control" name="cgst" id="CGST" placeholder="CGST (%)" value="{{$product->cgst}}">
                @if ($errors->has('cgst'))
                    <span class="text-danger">{{ $errors->first('cgst') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="SGST" class="form-label">SGST</label>
                <input type="number" class="form-control" name="sgst" id="SGST" placeholder="SGST (%)" value="{{$product->sgst}}">
                @if ($errors->has('sgst'))
                    <span class="text-danger">{{ $errors->first('sgst') }}</span>
                @endif
            </div>
            <hr>
            <h6>Product Variants</h6>
            <div class="variants">
                <div class="row">
                    <div class="col-md-2">
                        <label for="">Packing</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Quantity</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Case Qty (Kg / Ltr)</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Price</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Total Price</label>
                    </div>
                    <!--<div class="col-md-2">-->
                    <!--    <label for="">Stock</label>-->
                    <!--</div>-->
                    <div class="col-md-1">
                        <label for="">Action</label>
                    </div>
                </div>
                <div class="variant">
                    <?php $i = 100; ?>
                    @if (count($variants) > 0)
                        @foreach ($variants as $item)
                        <?php $i++; ?>
                            <div class="row mt-2 var-{{$item->id}}">
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="v_sku[]" placeholder="Variant SKU" value="{{$item->sku}}">
                                </div>
                                <input type="hidden" class="form-control" name="variant_id[]" placeholder="Variant SKU" value="{{$item->id}}">
                                <div class="col-md-2">
                                    <input type="text" class="form-control qty qty{{$i}}" data-id="{{$i}}" name="qty[]" placeholder="Variant Quantity" value="{{$item->quantity}}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control" name="capacity[]" placeholder="Variant Capacity" value="{{$item->capacity}}">
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control price price{{$i}}" data-id="{{$i}}" name="price[]" placeholder="Variant Price" value="{{$item->price}}">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control total_price total_price{{$i}}" data-id="{{$i}}" name="actual_price[]" placeholder="Variant Price" value="{{$item->actual_price}}">
                                </div>
                                <!--<div class="col-md-2">-->
                                <!--    <input type="number" class="form-control" name="stock[]" placeholder="Variant Stock" value="{{$item->stock}}">-->
                                <!--</div>-->
                                <div class="col-md-2">
                                    <input type="checkbox" class="radio" name="selected[{{$loop->index}}]" data-id="{{$i}}" onClick=onlyOne(this) id="selected-{{$i}}" @if($item->selected == 1) checked @endif ><label class="ms-2" for="selected-{{$i}}">Set Default</label>
                                    <a href="javascript:void(0);" class="delete-variant" data-id="{{$item->id}}"><img src="{{url("/")}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div>
                <button type="button" name="add" class="btn btn-primary mt-3 add_variant">Add Variant</button>
            </div>
            <hr class="mt-5">
            <div class="col-md-12">
                <label for="Description" class="form-label">Description </label>
                <textarea class="form-control" name="description" rows="4" id="Description" placeholder="Product Description" >{{$product->description}}</textarea>
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" @if ($product->status==1)
                    checked
                    @endif >
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
            </div>
            <div class="col-12">
                <input type="hidden" name="id" value="{{$product->id}}">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script>
       
        $(document).ready(function() {
            
            $(document).on('click','.delete-image',function(){
                $('#product_image').attr('src',''); 
                $('#product_hidden_image').val(''); 
                $('.delete-image').remove();
            });
            $(".btn-success").click(function(){ 
                var lsthmtl = $(".clone").html();
                $(".increment").after(lsthmtl);
            });
            $("body").on("click",".btn-danger",function(){ 
                $(this).parents(".hdtuto").remove();
            });
        });
        var i = 1000;
        $(document).on('click','.add_variant',function(){
            i = i+1;
            $('.variants').removeClass('d-none');
            var html = '';
            html += '<div class="row mt-2 var1-'+i+'">';
            html += '<div class="col-md-2">';
            html += '<input type="text" class="form-control" name="v_sku[]" placeholder="Variant SKU">';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<input type="text" class="form-control qty qty'+i+'" data-id="'+i+'" name="qty[]" placeholder="Variant Quantity">';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<input type="text" class="form-control" name="capacity[]" placeholder="Variant Capacity">';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<input type="number" class="form-control price'+i+'" data-id="'+i+'" name="price[]" placeholder="Variant Price">';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<input type="text" class="form-control total_price'+i+'" data-id="'+i+'" name="actual_price[]" placeholder="Variant Rate">';
            html += '</div>';
            // html += '<div class="col-md-2">';
            // html += '<input type="number" class="form-control" name="stock[]" value = "0" placeholder="Variant Stock">';
            // html += '</div>';
            html += '<div class="col-md-2 d-flex align-items-center">';
            html += '<input type="checkbox" class="radio" onClick=onlyOne(this) name="selected['+i+']" id="selected-'+i+'"><label class="ms-2" for="selected-'+i+'">Set Default</label>';
            html += '<a href="javascript:void(0);" class="delete-variant1 ms-2" data-id="'+i+'"><img src="{{url("/")}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>';
            html += '</div>';
            html += '</div>';
            $('.variant').append(html);
        });
        $(document).on('click','.delete-variant1',function(){
            var var_id = $(this).data('id');
            $('.var1-'+var_id).remove();
            const boxes = document.querySelectorAll(".radio")
            for (const box of boxes) {
                box.disabled = false;
            }
        });
        function onlyOne(checkbox) {
            const boxes = document.querySelectorAll(".radio")
            for (const box of boxes) {
                if (checkbox.checked && (box !== checkbox)) {
                    box.disabled = true;
                    box.checked = false;
                } else {
                    box.disabled = false;
                }
            }
        }
        $(document).on('click','.delete-variant',function(){
            var var_id = $(this).data('id');
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
                        url : "{{route('delete.product_variant', '')}}"+"/"+var_id,
                        type : 'GET',
                        dataType:'json',
                        success : function(data) {  
                            Swal.fire({
                                title: 'Deleted!',
                                text: "Product Variant has been deleted.",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                                }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.var-'+var_id).remove();
                                    const boxes = document.querySelectorAll(".radio")
                                    for (const box of boxes) {
                                        box.disabled = false;
                                    }
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
        $(document).on('keyup','.qty',function(){
            var id = $(this).data('id');
            var qty = $(this).val(); 
            var price = $('.price'+id).val();
            if(price == ''){
                price = 0;
            }
            if(qty == ''){
                qty = 0;
            }
            var total = $('.total_price'+id).val();
            $('.total_price'+id).val(parseFloat(qty)*parseFloat(price));
        });
        $(document).on('keyup','.price',function(){
            var id = $(this).data('id');
            var price = $(this).val(); 
            var qty = $('.qty'+id).val();
            if(price == ''){
                price = 0;
            }
            if(qty == ''){
                qty = 0;
            }
            var total = $('.total_price'+id).val();
            $('.total_price'+id).val(parseFloat(qty)*parseFloat(price));
        });
    </script>
@endsection