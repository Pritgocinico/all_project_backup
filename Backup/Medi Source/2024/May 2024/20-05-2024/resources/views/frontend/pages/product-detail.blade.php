@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section about-parent prdct-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center  mt-4 mb-0 justify-content-start pd-in-banner">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                       {{$product->productname}}
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                        class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page"><a href="{{ route('product') }}"
                                        class="text-decoration-none text-white">Products</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="#"
                                        class="text-decoration-none text-white">{{$product->productname}}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="prdct-dtl-sec inner-prdt-dtl-sec">
        <div class="container">
            <div class="row mx-2 slide-in-prdt">
                <div class="col-md-5 slide-div text-center">
                    <!-- <div class="inner-prdct-dtl py-5">  //old img parts
                        <img class="mb-0" src="./assets/images/med-btl.png" alt="product">
                        <div class="d-flex align-items-center justify-content-center parent-inner-small-btl gap-4">
                            <img class="mb-0 inner-small-btl" src="./assets/images/med-btl.png" alt="product">
                            <img class="mb-0 inner-small-btl" src="./assets/images/med-btl.png" alt="product">
                            <img class="mb-0 inner-small-btl" src="./assets/images/med-btl.png" alt="product">
                        </div>
                    </div> -->

                    <!-- ----------------------------------------------slider start-------------------------------------- -->

                    <div class="inner-prdct-dtl py-3  py-lg-5">
                        <div class="slider-for">
                            
                            <div class="slide-in">
                                <img class="mb-0 magnifier" src="{{ asset('storage/images/' . $product->single_image) }}"
                                    alt="product">
                            </div>
                            @forelse($product->images as $image)
                                <!-- Add your dynamic product images here -->
                                <div class="slide-in">
                                    <img class="mb-0 magnifier" src="{{ asset('storage/images/' . $image->path) }}"
                                        alt="product">
                                </div>
                            @empty
                            
                            @endforelse
                            
                        </div>
                    </div>
                    
                    <div class="slider-nav">
                        @if($product->image !== null)
                        <div class="align-items-center parent-inner-small-btl">
                            <img class="mb-0 inner-small-btl" src="{{ asset('storage/images/' . $product->single_image) }}"
                                alt="product">
                        </div>
                        @forelse($product->images as $image)
                            <div class="align-items-center parent-inner-small-btl">
                                <img class="mb-0 inner-small-btl" src="{{ asset('storage/images/' . $image->path) }}"
                                    alt="product">
                            </div>
                        @empty
                        @endforelse
                        @endif
                    </div>
<!-- ---------------------------------------------------------------------------------------------------------------------------------------------------- -->
                    <div class="col-md-12">
                    <div class="topical text-start">
                        <h3>PRESERVATIVE</h3>
                        <div class="d-flex  gap-2 align-items-center">
                            <h4>{{$product->preservative_free}}</h4>
                            
                        </div>
                    </div>
                    @auth('web')
                    <?php
                        $latestLot = \App\Models\Lot::latest()->first();
                        if(!blank($latestLot)){
                    ?>
                    <div class="product-insert text-start">
                        <h3>Available inventory BUD</h3>
                        <div class="d-flex gap-2">
                            <div class="dwn-btn">


                                @if ($latestLot && $latestLot->file)
                                    <a id="downloadPdfBtn"
                                        class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                        href="{{ asset('storage/lot-files/' . $latestLot->file) }}" download>
                                        <span class="inner-pdf-text-cart text-white"><i class="fa-solid fa-download me-2"></i>DOWNLOAD</span>
                                    </a>
                                @else
                                    <p>No files available for download</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    @endauth
                </div>

                    
                </div>
                <div class="col-md-7 in-all-prdt">
                    <div class="inner-order pb-3 pt-0 ps-lg-3">
                        @if(!blank($product->tags))
                        <div class="d-flex gap-2 in-option ">
                            <?php 
                                $tags = explode(',', $product->tags);
                            ?>
                            
                            @foreach($tags as $tag)
                                <p class="anticholinergic">{{$tag}}</p>    
                            @endforeach
                            
                            {{-- <p class="anticholinergic">Anticholinergic</p>
                            <p class="anticholinergic2">Steroid</p>
                            <p class="anticholinergic3">Antibiotic</p> --}}
                        </div>
                        @endif
                        
                        <div class="atropine-in">
                            <h4>{{ $product->productname }}</h4>
                            <div class="d-flex align-item-center inner-sku">
                            <p>SKU: {{ $product->sku }}</p>
                            <a href="#"><img class="mb-0 inner-small-btl"
                                    src="{{ url('/') }}/frontend/assets/images/copy-two-paper-sheets-interface-symbol.png"
                                    alt="product"></a>
                        </div>
                            <p>{{ $product->inactive_ingredients }}</p>
                            @php
                                $userId = auth()->user() ? auth()->user()->id : null;
                                $doctorPrice = $userId
                                    ? DB::table('doctor_prices')
                                        ->where('doctor_id', $userId)
                                        ->where('product_id', $product->id)
                                        ->first()
                                    : null;
                                    // dd($doctorPrice);
                            @endphp
                            <form action="{{route('add.to.cart',$product->id)}}" method="post">
                                @csrf
                            {{-- Check if the user is logged in --}}
                            @auth('web')
                                @if(!blank($product_packages))
                                @php $i = 1; @endphp
                                    @foreach($product_packages as $package)
                                        @if($i == 1)
                                            @if($product->stock != 0)

                                            <div class="d-flex justify-content-between inner-price ">
                                            <p class="price-tag-text">Price: <span id="price"> ${{isset($doctorPrice)?$doctorPrice->price:$package->vial_total}}</span></p>   
                                                <input type="hidden" class="hidden_price" name="price" value="{{isset($doctorPrice)?$doctorPrice->price:$package->vial_total}}">
                                                <h3 class="text-uppercase ">NOW AVAILABLE!</h3>
                                            </div>
                                            @endif
                                        @endif
                                        @php $i++; @endphp 
                                    @endforeach
                                @endif
                                
                                <!-- @if ($doctorPrice && $doctorPrice->price !== null)
                                    <p>Price: ${{ $doctorPrice->price }}</p>
                                    <input type="hidden" name="price" value="{{$doctorPrice->price}}">
                                @else
                                    <p>Price: ${{ $product->price }}</p>
                                    <input type="hidden" name="price" value="{{$product->price}}">
                                @endif -->

                                {{-- Check if $isDoctor is true --}}
                                @isset($isDoctor)
                                    @if ($isDoctor)
                                        <p>This information is visible to doctors only.</p>
                                    @endif
                                @endisset
                            @else
                            @endauth
                            @auth('web')
                            @if($product->stock > 0)
                            <div class="quantity-sec">
                                <p>Select Quantity</p>
                                @if(!blank($product_packages))
                                    @php $i = 1; @endphp
                                    <div class="inner-box row">
                                            @foreach($product_packages as $package)
                                                @php $doctorPrice = DB::table('doctor_prices')
                                                ->where('doctor_id', $userId)
                                                ->where('package_id', $package->id)
                                                ->first();
                                                 @endphp
                                                <div class="selectRadio">
                                                    <div class="radio  @if($i == 1) active @endif">
                                                        <label>
                                                        <input type="radio" class="package_price" name="package_price" value="{{$package->id}}" @if($i == 1) checked @endif />
                                                            <!-- {{$package->varient_name}} -->
                                                            <h6>{{$package->varient_name}}</h6>
                                                            <h6>${{isset($doctorPrice)?$doctorPrice->price :$package->vial_total}}</h6>  
                                                        
                                                        </label>
                                                    </div>
                                                </div>
                                                
                                                @php $i++; @endphp
                                            @endforeach
                                    </div>
                                    @endif
                                    @endauth
                                    @if($product->medical_necessity != null && $product->stock != 0)
                                    <div class="row mt-3">
                                        @php
                                            $medicalNecessities = explode('/', $product->medical_necessity);
                                        @endphp
                                        <p>Medical Necessity</p>
                                        <select name="medical_necessity" id="medical_necessity">
                                            <option value="">Select Medical Necessity</option>
                                            @foreach($medicalNecessities as $option)
                                                <option value="{{ $option }}" @if(old('medical_necessity') == $option) {{"selected"}} @endif>{{ $option }}</option>
                                            @endforeach
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    @endif
                                @endif
                                <div class="row mt-3 @if(old('medical_necessity') !== "other") d-none @endif" id="other_medical_necessity_div">
                                    <p>Other Medical Necessity</p>
                                    <input type="text" name="other_medical_necessity" value="{{old('other_medical_necessity')}}" class="form-control opacity-1 w-100" id="other_medical_necessity" placeholder="Other Medical Necessity">
                                </div>
                            </div>
                            <div class="d-flex   justify-content-between  inner-div-info flex-sm-row gap-3 flex-column align-items-center">
                            <!--<div class="product-insert">-->
                            <!--    <h3>Product Insert</h3>-->
                            <!--    <div class="d-flex gap-2">-->
                            <!--        <img class="mb-0 inner-small-btl" src="{{url('/')}}/public/frontend/assets/images/pdf-two.png" alt="product">-->
                            <!--        <div class="dwn-btn">-->
        
        
                            <!--                                                <a id="downloadPdfBtn" class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder" href="{{url('/')}}/public/storage/lot-files/2024030918124319.pdf" download="">-->
                            <!--                    <span class="inner-pdf-text-cart text-white">DOWNLOAD</span>-->
                            <!--                </a>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->
    
                    <div class=" in-increse">
                                {{-- <div class="plus-minus-inner d-flex gap-2">
                                    <button type="button" data-decrease>-</button>
                                    <input data-value type="text" name="qty" value="1"  />
                                    <button type="button" data-increase>+</button>
                                </div> --}}
                                <div class="dw-btn">
                                @if($product->stock > 0)
                                @auth('web')
                                        <input type="hidden" name="doctor_id" id="doctor_id" value="{{Auth::guard('web')->user()->id}}">
                                            <button class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                                type="submit">
                                                <span class="inner-pdf-text-cart  text-white">ADD TO CART</span>
                                            </button>
                                            @else
                                            <a class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                            href="{{route('logindoctor')}}">
                                            <span class="inner-pdf-text-cart  text-white">ADD TO CART</span>
                                        </a>
                                @endauth
                                @endif
                                </div>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif            
                            </div>
                        </form>
                        @if($product->stock <= 0)
                            <h3 class="text-danger">OUT OF STOCK!</h3>
                        @else
                            <h3 class="text-uppercase d-none ">NOW AVAILABLE!</h3>
                        @endif
                        </div>
                        <div class="available-in">
                            <div class="d-flex justify-content-between">
                                <p>Fill</p>
                                <h4>{{ $product->unit_size_type }}</h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Container
                                </p>
                                <h4>{{ $product->package_size }}
                                </h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Dispense Size
                                </p>
                                <h4>{{ $product->product_code }}
                                </h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>NDC</p>
                                <h4>{{ $product->ndc }}
                                </h4>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p>Storage</p>
                                <h4>{{ $product->storage }}
                                </h4>
                            </div>
                        </div>

                        <div class="col-md-12">
                        <div class="Product">
                            @if (!empty($accordionItems))
                                <h3>Product Information</h3>
                                <div class="accordion border-0 accordion-flush" id="accordionFlushExample">
                                    <ul>
                                    @foreach ($accordionItems as $item)
                                        @if($item['title'] !== "")
                                            <li>
                                                <div class="product-information-list">
                                                    <h5>{{ $item['title'] }}</h5>
                                                    <p>{{ $item['content'] }}</p>
                                                </div>
                                            </li>
                                        @else
                                            <div class="product-information-list">
                                                <p>{{ $item['content'] }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            
            <!-- <div class="row my-4 mt-0 my-sm-3 g-3 mx-1">
                <div class="col-md-6 ">
                    <div class="topical">
                        <h3>TOPICAL SOLUTION</h3>
                        <div class="d-flex  gap-2 align-items-center">
                            <h4>PRESERVATIVE-FREE</h4>
                            <img class="mb-0 inner-small-btl"
                                src="{{ url('/') }}/frontend/assets/images/conservative.png" alt="product">
                        </div>
                    </div>
                    <?php
                        $latestLot = \App\Models\Lot::latest()->first();
                        if(!blank($latestLot)){
                    ?>
                    <div class="product-insert">
                        <h3>Product Insert</h3>
                        <div class="d-flex gap-2">
                            <div class="dwn-btn">


                                @if ($latestLot && $latestLot->file)
                                    <a id="downloadPdfBtn"
                                        class="text-decoration-none cart-btn clr-black d-flex gap-1 align-items-center parent-cartorder"
                                        href="{{ asset('storage/lot-files/' . $latestLot->file) }}" download>
                                        <span class="inner-pdf-text-cart text-white"><i class="fa-solid fa-download me-2"></i>DOWNLOAD</span>
                                    </a>
                                @else
                                    <p>No files available for download</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-md-6">
                    <div class="Product">
                        @if (!empty($accordionItems))
                            <h3>Product Information</h3>
                            <div class="accordion border-0 accordion-flush" id="accordionFlushExample">
                                @foreach ($accordionItems as $item)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $item['target'] }}"
                                                aria-expanded="false" aria-controls="{{ $item['target'] }}">
                                                {{ $item['title'] }}
                                            </button>
                                        </h2>
                                        <div id="{{ $item['target'] }}" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                {{ $item['content'] }}
                                            </div>
                                        </div>  
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div> -->
            </div>
        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="{{ url('/') }}/frontend/assets/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.slider-for').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.slider-nav'
            });
            $('.slider-nav').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '.slider-for',
                dots: false,
                centerMode: true,
                focusOnSelect: true
            });
        });
        let $containers = $('.radio');
        $containers.find(':radio').on('change', e => {
        $containers.removeClass('active'); // remove from all containers
        $(e.target).closest('.radio').addClass('active'); // add class to current
        });
        $(document).on('change','.package_price',function(){
            var id = $(this).val();
            var doctor_id = $('#doctor_id').val();
            $.ajax({
                // url: '{{ route('getvariantPriceFront', '') }}' + '/' + id,
                url: '{{ route('getvariantPriceFront') }}',
                data: { id: id,
                        doctor_id: doctor_id,
                    },
                // url: '/product-package-price/'+id,
                method: 'GET',
                success: function(data) {
                    $('#price').html(data);
                    $('.hidden_price').val(data);
                } 
            })
        })
        $('#medical_necessity').on('change',function(e){
            var medical = $(this).val();
            $('#other_medical_necessity_div').addClass('d-none')
            if(medical == "other"){
                $('#other_medical_necessity_div').removeClass('d-none')
            }
        })
    </script>

@endsection
