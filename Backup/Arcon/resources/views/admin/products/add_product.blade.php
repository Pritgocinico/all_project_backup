@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Add Product</h4>
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
        <form action="{{ route('admin.add_product_data') }}" method="POST" class="row g-3" enctype="multipart/form-data">
            @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="Product Name" value="{{old('name')}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="SKU" class="form-label">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="sku" id="SKU" placeholder="Product SKU" value="{{old('sku')}}">
                @if ($errors->has('sku'))
                    <span class="text-danger">{{ $errors->first('sku') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="BrandName" class="form-label">Brand Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="brand_name" id="BrandName" placeholder="Brand Name" value="{{old('brand_name')}}">
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
                                        <option value="{{$child->id}}">{{$child->name}}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{$item->id}}">{{$item->name}}</option> 
                            @endif
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('category'))
                    <span class="text-danger">{{ $errors->first('category') }}</span>
                @endif
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-10">
                        <label for="Image" class="form-label">Image </label>
                        <input type="file" class="form-control" name="image" id="Image">
                        @if ($errors->has('image'))
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <img src="{{url('/')}}/public/assets/photo.png" class="product_preview" height="80px" alt="">
                        <input type="hidden" name="hidden_image" value="photo.png">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <label for="Slider" class="form-label">Slider Images </label>
                <div class="input-group hdtuto control-group lst increment" >
                    <input type="file" name="slider[]" class="myfrm form-control" accept=".png, .jpg, .jpeg .webp .gif" id="slider">
                    <div class="input-group-btn"> 
                      <button class="btn btn-success" type="button"><i class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                    </div>
                  </div>
                  <div class="clone hide">
                    <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                      <input type="file" name="slider[]" class="myfrm form-control" accept=".png, .jpg, .jpeg" id="slider">
                      <div class="input-group-btn"> 
                        <button class="btn btn-danger" type="button"><i class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                      </div>
                    </div>
                  </div>
                
                {{-- <input type="file" class="form-control" name="slider[]" accept=".png, .jpg, .jpeg" multiple id="slider"> --}}
            </div>
            <div class="col-md-12">
                <div class="gallery" id="gallery"></div>
            </div>
            <div class="col-md-6">
                <label for="CGST" class="form-label">CGST</label>
                <input type="number" class="form-control" name="cgst" id="CGST" placeholder="CGST (%)" value="{{old('cgst')}}">
                @if ($errors->has('cgst'))
                    <span class="text-danger">{{ $errors->first('cgst') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="SGST" class="form-label">SGST</label>
                <input type="number" class="form-control" name="sgst" id="SGST" placeholder="SGST (%)" value="{{old('sgst')}}">
                @if ($errors->has('sgst'))
                    <span class="text-danger">{{ $errors->first('sgst') }}</span>
                @endif
            </div>
            <hr>
            <h6>Product Variants</h6>
            <div class="variants d-none">
                <div class="row">
                    <div class="col-md-2">
                        <label for="">Packing</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Qty</label>
                    </div>
                    <div class="col-md-2">
                        <label for="">Case Qty (Kg / Ltr))</label>
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
                    {{-- <div class="row mt-2">
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="sku[]" placeholder="Variant SKU">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="capacity[]" placeholder="Variant Capacity">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="price[]" placeholder="Variant Price">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" name="stock[]" placeholder="Variant Stock">
                        </div>
                        <div class="col-md-2">
    
                        </div>
                    </div> --}}
                </div>
               
            </div>
            <div>
                <button type="button" name="add" class="btn btn-primary mt-3 add_variant">Add Variant</button>
            </div>
            
            {{-- <div class="col-md-6">
                <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" checked>
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
            </div> --}}
            <hr class="mt-5">
            <div class="col-md-12">
                <label for="Description" class="form-label">Description </label>
                <textarea class="form-control" name="description" rows="3" id="Description" placeholder="Product Description" >{{old('description')}}</textarea>
                @if ($errors->has('description'))
                    <span class="text-danger">{{ $errors->first('description') }}</span>
                @endif
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".btn-success").click(function(){ 
                var lsthmtl = $(".clone").html();
                $(".increment").after(lsthmtl);
            });
            $("body").on("click",".btn-danger",function(){ 
                $(this).parents(".hdtuto").remove();
            });
        });
        var i = 0;
        $(document).on('click','.add_variant',function(){
            i = i+1;
            $('.variants').removeClass('d-none');
            var html = '';
            html += '<div class="row mt-2 var-'+i+'">';
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
            html += '<input type="number" class="form-control price price'+i+'" data-id="'+i+'" name="price[]" placeholder="Variant Price">';
            html += '</div>';
            html += '<div class="col-md-2">';
            html += '<input type="text" class="form-control total_price total_price'+i+'" data-id="'+i+'" name="actual_price[]" placeholder="Variant Rate">';
            html += '</div>';
            // html += '<div class="col-md-2">';
            // html += '<input type="number" class="form-control" name="stock[]" value = "0" placeholder="Variant Stock">';
            // html += '</div>';
            html += '<div class="col-md-2 d-flex align-items-center">';
            html += '<input type="checkbox" class="radio" onClick=onlyOne(this) name="selected['+i+']" id="selected-'+i+'"><label class="ms-2" for="selected-'+i+'">Set Default</label>';
            html += '<a href="javascript:void(0);" class="delete-variant ms-2" data-id="'+i+'"><img src="{{url("/")}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>';
            html += '</div>';
            html += '</div>';
            $('.variant').append(html);
        });
        $(document).on('click','.delete-variant',function(){
            var var_id = $(this).data('id');
            $('.var-'+var_id).remove();
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
        $('#Image').change(function(){
            let reader = new FileReader();
            reader.onload = (e) => { 
                $('.product_preview').attr('src', e.target.result); 
            }
            reader.readAsDataURL(this.files[0]);
        });
        $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).attr('width','150px').attr('class','m-2').appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#slider').on('change', function() {
        imagesPreview(this, 'div.gallery');
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
});
    </script>
@endsection