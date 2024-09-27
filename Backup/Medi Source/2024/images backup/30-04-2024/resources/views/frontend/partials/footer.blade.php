<footer class="pt-0 pt-sm-5">
    <div class="container">
        <div class="row align-items-start py-5 parent-main-footer">
            <div class="col-md-3 text-start">
                <img class="img-fluid main-logo" src="{{ url('/') }}/frontend/assets/images/main-logo-b.png"
                    alt="logo">
                <div class="d-flex justify-content-start mt-3 mb-3 gap-3 align-items-center">
                    <a href="https://www.linkedin.com/company/medisourcerx-company/" target="_blank">
                        <img class="img-fluid" src="{{ url('/') }}/frontend/assets/images/linkedin.png" alt="linkedin">
                    </a>    
                </div>
            </div>
            <div class="col-md-4 text-center">
                <!--<p class="text-white text-start fw-normal">503B PRODUCTS</p>-->
                <p class="text-white text-start fw-normal"><a
                        href="#" class="text-white">Our Future Product Pipeline</a></p>
                <p class="text-white text-start fw-normal"><a
                        href="#"
                        class="text-white">VITAMIN C</a></p>
                {{-- <p class="text-white text-start fw-normal"><a
                        href="https://replete-software.com/projects/medisource/product/b12-5mgml"
                        class="text-white">B12 5mg/ml</a></p> --}}
                        <p class="text-white text-start fw-normal"><a
                            href="#"
                            class="text-white">MIC IV</a></p>
                <!-- <p class="text-white text-start fw-normal"><a
                        href="https://replete-software.com/projects/medisource/product/mi"
                        class="text-white">M.I.(L-Methionine 25mg/ml, Inositol 50mg/ml)</a></p> -->
                {{-- <p class="text-white text-start fw-normal"><a
                        href="https://replete-software.com/projects/medisource/product/b-complex"
                        class="text-white">B-Complex</a></p> --}}
                        <div class="footer-notice">
                        <p class="text-light text-start"> NOTES : Please call MedisourceRx for more information on when these future Offerings will be made available for purchase.</p>
                        </div>
            </div>
            <div class="col-md-2 mx-auto text-center">
                <p class="text-white text-start fw-normal footer-title">QUICK LINKS</p>
                <ul class="nav parent-footer-li flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('events') }}">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('product') }}">Our Products</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('surgery') }}">Surgery/EMS</a>
                    </li> -->
                </ul>
            </div>
            <div class="col-md-2 text-center">
                <p class="text-white text-start fw-normal footer-title">POLICY</p>
                <ul class="nav parent-footer-li flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('terms') }}">Terms & Condition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('privacy.policy') }}">Privacy Policies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('cookie.policy') }}">Cookie
                            Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white text-start px-0" href="{{ route('help') }}">Support Help</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row text-center cpy-right py-4">
            <div class="col-md-12 cpy-right">

                <p class="text-white">Copyright© {{ date('Y') }} MEDISOURCERX - All Rights Reserved.</p>
                <p class="text-white">Design & Developed by <a class="text-call-mail-color" href="https://www.repletesoftware.com/" target="_blank">Replete Software Solution</a></p>
            </div>
        </div>
    </div>
</footer>





</body>

<!-- Include jQuery, SweetAlert2, and Font Awesome -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->



<script src="{{ url('/') }}/frontend/assets/jquery.min.js"></script>
<script src="{{ url('/') }}/frontend/assets/bootstrap.bundle.min.js"></script>
<script src="{{ url('/') }}/frontend/assets/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script> -->

<!-- slick slider script -->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js"></script> --}}
<script src="{{ url('/') }}/frontend/assets/slick.min.js"></script>
<script src="{{ url('/') }}/frontend/assets/select2.min.js"></script>
<script src="{{ url('/') }}/frontend/assets/owl.carousel.js"></script>
<script src="{{ url('/') }}/frontend/assets/owl.carousel.min.js"></script>

@if (session('success'))
    <script>
        toastr.success('{{ session("success") }}');
    </script>
@endif
@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif
<script>
    $(".select2").select2({
        placeholder: "Select State",
        allowClear: true
    });
    $(".select2-city").select2({
        placeholder: "Select City",
        allowClear: true
    });
</script>

@yield('script')
<script>
    $('.banner').slick({
        dots: false,
        infinite: true,
        speed: 600,
        arrows: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay:true,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                dots: true
            }
        }]
    });
</script>
<script>
    
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        fade: true,
        asNavFor: '.slider-nav'
    });
    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        focusOnSelect: true,
        responsive: [{
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                    asNavFor: '.slider-for',
                }
            }, {
                breakpoint: 990,
                settings: {
                    slidesToShow: 4,
                    asNavFor: '.slider-for',
                }
            },
            {
                breakpoint: 584,
                settings: {
                    slidesToShow: 4,
                    asNavFor: '.slider-for',
                }
            }, {
                breakpoint: 425,
                settings: {
                    slidesToShow: 3,
                    asNavFor: '.slider-for',
                }
            },
        ]
    });
</script>

<script>
    $('.home-product-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
</script>
<script>
    $('.event-page-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 2
            }
        }
    });
</script>

<script>
    $('.page').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        customPaging: function(slider, i) {
            var thumb = $(slider.$slides[i]).data();
            return '<a class="dot">' + i + '</a>';
        },
        // nextArrow: '<i class="fa fa-arrow-right btn-unique"></i>',
        // prevArrow: '<i class="fa fa-arrow-left"></i>',
        responsive: [{
                breakpoint: 1024,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
</script>
<!-- increment decrement -->
<script>
    $(function() {
        $('[data-decrease]').click(decrease);
        $('[data-increase]').click(increase);
        $('[data-value]').change(valueChange);
    });

    function decrease() {
        var value = $(this).parent().find('[data-value]').val();
        if (value > 1) {
            value--;
            $(this).parent().find('[data-value]').val(value);
        }
    }

    function increase() {
        var value = $(this).parent().find('[data-value]').val();
        if (value < 100) {
            value++;
            $(this).parent().find('[data-value]').val(value);
        }
    }

    function valueChange() {
        var value = $(this).val();
        if (value == undefined || isNaN(value) == true || value <= 0) {
            $(this).val(1);
        } else if (value >= 101) {
            $(this).val(100);
        }
    }
</script>

<!-- form-validation script -->
<script>
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<!-- drop down ------------------------ -->
<script>
    const langArray = [];

    $('.select option').each(function() {
        const img = $(this).attr("data-thumbnail");
        const text = this.innerText;
        const value = $(this).val();
        const item = `<li><img src="${img}" alt="" value="${value}"/><span>${text}</span></li>`;
        langArray.push(item);
    })

    $('#a').html(langArray);

    //Set the button value to the first el of the array
    $('.btn-select').html(langArray[0]).attr('value', 'en');

    //change button stuff on click
    $('#a li').click(function() {
        const img = $(this).find('img').attr("src");
        const value = $(this).find('img').attr('value');
        const text = this.innerText;
        const item = `<li><img src="${img}" alt="" value="${value}"/><span>${text}</span></li>`;
        $('.btn-select').html(item).attr('value', value);
        $(".b").toggle();
    });

    $(".btn-select").click(function() {
        $(".b").toggle();
    });
</script>

<script>
    jQuery(document).ready(function() {
        if(localStorage.getItem('noShowEducation') != 'shown'){
            jQuery("#cookie-div").delay(1000).fadeIn();
        }
        jQuery('#accept-btn').click(function(e){
            jQuery("#cookie-div").fadeOut(); 
            localStorage.setItem('noShowEducation','shown')
        });
        jQuery("#close-btn").click(function(){
            jQuery("#cookie-div").hide();
        });
});
    
</script>

<script>
  
    $('#home-product-image-slider').owlCarousel({
	    loop:true,
        autoplay:true,
	    margin:10,
	    nav:false,
	    responsive:{
	        0:{
	            items:1
	        },
	        600:{
	            items:1
	        },
	        1000:{
	            items:1
	        }
	    }
	})
</script>

</html>
