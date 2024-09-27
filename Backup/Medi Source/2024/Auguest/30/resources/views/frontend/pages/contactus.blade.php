@extends('frontend.layouts.app')


@section('content')
    <section class="banner-section  about-parent prdct-parent position-relative py-sm-5 py-1">
        <div class="container">
            <div class="row align-items-center py-5 mt-4 mb-0 justify-content-start ">
                <!-- <img class="img-fluid banner-img" src="./assets/images/banner-ing.png" alt="about-img"> -->
                <div class="col-md-8">
                    <h1 class="text-white text-start">
                        Contact Us
                    </h1>
                    <div class="parent-banner-btn text-start mt-4 mb-2">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                        class="text-decoration-none text-white">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a href=""
                                        class="text-decoration-none text-white">Contact Us</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section>

        <div class="container">
            <div class="contact-in">

                <div class="text-center">
                    <h4>Contact Us Inquiery</h4>
                    <p>(Please only submit 1 query; submitting multiple will not change its queue position)
                    </p>
                    <!-- <a class="text-center text-call-mail-color" href="{{ route('faq') }}">Do you have a question? Check out
                        our FAQ webpage!</a> -->
                    <!-- <a class="text-center text-call-mail-color" href="{{route('faq')}}">Do you have a question? Check out our FAQ webpage!</a> -->
                </div>
                <div class="form-inner ">
                    <div class="f-info">
                        <form class="needs-validation" novalidate action="{{ route('contactus.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="f-in">
                                <div class="rdo-btn">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="user_type" id="inlineRadio1"
                                            value="Doctor/Provider">
                                        <label class="form-check-label" for="inlineRadio1">Doctor/Provider</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="user_type" id="inlineRadio2"
                                            value="Patient">
                                        <label class="form-check-label" for="inlineRadio2">Patient</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="user_type" id="inlineRadio3"
                                            value="Media/PR/Government Relations">
                                        <label class="form-check-label" for="inlineRadio3">Media/PR/Government
                                            Relations</label>
                                    </div>
                                    <div class="invalid-feedback">
                                        @error('user_type')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="f-in">
                                <div class="name-w">
                                    <label for="validationCustom01" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="validationCustom01" name="first_name"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('first_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="name-w">
                                    <label for="validationCustom02" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="validationCustom02" name="last_name"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('last_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="f-in">
                                <div class="name-w">
                                    <label for="validationCustom01" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="validationCustom01" name="email"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="name-w">
                                    <label for="validationCustom02" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="validationCustom02" name="phone"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('phone')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="f-in">
                                <div class="name-w">
                                    <label for="validationCustom01" class="form-label">Clinic Name</label>
                                    <input type="text" class="form-control" id="validationCustom01" name="clinic_name"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('clinic_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="name-w">
                                    <label for="validationCustom02" class="form-label">Website</label>
                                    <input type="text" class="form-control" id="validationCustom02" name="website"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('website')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="f-in">
                                <div class="name-w">
                                    <label for="validationCustom01" class="form-label">Number Of Physicians</label>
                                    <input type="number" class="form-control" id="validationCustom01"
                                        name="number_of_physicians" required>
                                    <div class="invalid-feedback">
                                        @error('number_of_physicians')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="name-w">
                                    <label for="validationCustom02" class="form-label">Number of Locations</label>
                                    <input type="number" class="form-control" id="validationCustom02"
                                        name="number_of_locations" required>
                                    <div class="invalid-feedback">
                                        @error('number_of_locations')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="f-in">
                                <div class="name-w">
                                    <label for="validationCustom01" class="form-label">License Number</label>
                                    <input type="number" class="form-control" id="validationCustom01"
                                        name="license_number" required>
                                    <div class="invalid-feedback">
                                        @error('license_number')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                                <div class="name-w">
                                    <label for="validationCustom02" class="form-label">DEA Number</label>
                                    <input type="number" class="form-control" id="validationCustom02" name="dea_number"
                                        required>
                                    <div class="invalid-feedback">
                                        @error('dea_number')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="f-in">
                                <div>
                                    <label for="">Products Interested In</label>
                                    <div class="inner-checkbox-div">
                                        <div class="f-check">
                                            @foreach ($productList as $index => $product)
                                                @if ($index < 4)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="products_services_interested[]"
                                                            value="{{ $product->id }}">
                                                        <label class="form-check-label">{{ $product->sku }}</label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="f-check">
                                            @foreach ($productList as $index => $product)
                                                @if (($index + 1) % 4 == 0 || $index == count($productList) - 1)
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="products_services_interested[]"
                                                            value="{{ $product->id }}">
                                                        <label class="form-check-label">{{ $product->sku }}</label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="f-in">
                                <div class="des-inner">
                                    <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" name="description" required rows="3"></textarea>
                                    <div class="invalid-feedback">
                                        @error('description')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>





                            <div class="f-in mt-4">


                                <button class="btn btn-primary submit-btns" type="button" onclick="submitForm()">Submit
                                    form</button>
                            </div>



                        </form>
                    </div>
                    <div class="map-videos">
                        <div>
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6630.222967206999!2d-118.0659482!3d33.809436!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80dd2e6203a2d30d%3A0x30880629b8b44e0!2s10525%20Humbolt%20St%2C%20Los%20Alamitos%2C%20CA%2090720!5e0!3m2!1sen!2sus!4v1713012084016!5m2!1sen!2sus"
                                width="450" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>


                            <!-- <iframe width="450" height="345" src="https://www.youtube.com/embed/-CBhi1JcFnM?si=bhbtIwAcQZR3Cub4">
                                        </iframe> -->
                        </div>
                    </div>

                </div>

            </div>
        </div>
        </div>
    </section>
    <script>
        function submitForm() {
            // Serialize the form data
            var formData = new FormData($('form')[0]);

            // Submit the form using AJAX
            $.ajax({
                url: $('form').attr('action'),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    // If the submission was successful, show a success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your form has been submitted successfully.',
                        icon: 'success',
                    });
                    // You can also redirect or perform other actions here if needed
                },
                error: function(error) {
                    console.log(error);
                    // If there's an error with the form submission, show an error message
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error submitting the form. Please try again later.',
                        icon: 'error',
                    });
                }
            });
        }
    </script>
@endsection
