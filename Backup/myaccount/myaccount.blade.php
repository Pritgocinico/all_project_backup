@extends('frontend.layouts.app')

@section('content')
    <section class="banner-section about-parent prdct-parent position-relative  py-sm-5 py-1">
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
    <section>
        <div class="container">
            <div class="inner-ac">
                <div class="tab-btn pe-xl-5 pe-lg-3">
                    <ul class="pro-tab">
                        <a href="{{ route('orders') }}">
                            <div class="d-flex align-items-center  justify-content-between active">
                                <p class="mb-0">Profile</p>
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </div>
                        </a>
                        <a href="{{ route('orders') }}" class="active">
                            <div class="d-flex align-items-center  justify-content-between ">
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
                    <div>
                        <section class="checkout-form">
                            <div class="container">
                                <form action="{{ route('update.profile') }}" method="post">
                                    @csrf
                                    <div class="">
                                        <div class="rgs-w mt-0">
                                            <label for="organization_name" class="form-label">Organization Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="organization_name" class="form-control py-2"
                                                id="organization_name" value="{{ $user->organization_name }}">
                                            @if ($errors->has('organization_name'))
                                                <span class="text-danger">{{ $errors->first('organization_name') }}</span>
                                            @endif
                                        </div>
                                        <div class="d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="first_name" class="form-label">First Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="first_name" class="form-control py-2"
                                                    value="{{ $user->first_name }}" id="first_name">
                                                @if ($errors->has('first_name'))
                                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                                @endif
                                            </div>
                                            <div class="rgs-w">
                                                <label for="last_name" class="form-label">Last Name <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="last_name" class="form-control py-2"
                                                    value="{{ $user->last_name }}" id="last_name">
                                                @if ($errors->has('last_name'))
                                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="npi" class="form-label">NPI</label>
                                                <input type="text" name="npi" class="form-control py-2"
                                                    value="{{ $user->npi }}" id="npi">
                                                @if ($errors->has('npi'))
                                                    <span class="text-danger">{{ $errors->first('npi') }}</span>
                                                @endif
                                            </div>
                                            <div class="rgs-w">
                                                <label for="business_license_number" class="form-label">Business License
                                                    Number</label>
                                                <input type="text" name="business_license_number"
                                                    value="{{ $user->business_license_number }}" class="form-control py-2"
                                                    id="business_license_number">
                                                @if ($errors->has('business_license_number'))
                                                    <span class="text-danger">{{ $errors->first('business_license_number') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="prescriber_state_license_number" class="form-label">Prescriber
                                                    State License Number</label>
                                                <input type="text" name="prescriber_state_license_number"
                                                    value="{{ $user->prescriber_state_license_number }}"
                                                    class="form-control py-2" id="prescriber_state_license_number">
                                                @if ($errors->has('prescriber_state_license_number'))
                                                    <span class="text-danger">{{ $errors->first('prescriber_state_license_number') }}</span>
                                                @endif
                                            </div>
                                            <div class="rgs-w">
                                                <label for="dea_number" class="form-label">DEA Number</label>
                                                <input type="text" name="dea_number" class="form-control py-2"
                                                    value="{{ $user->dea_number }}" id="dea_number">
                                                @if ($errors->has('dea_number'))
                                                    <span class="text-danger">{{ $errors->first('dea_number') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="rgs-w">
                                            <label for="speciality" class="form-label">Speciality <span class="text-danger">*</span></label>
                                            <select name="speciality" class="form-control py-2" id="speciality" required="">
                                                <option value="Ophthalmology" {{ $user->speciality == 'Ophthalmology' ? 'selected' : '' }}>Ophthalmology</option>
                                                <option value="Optometry" {{ $user->speciality == 'Optometry' ? 'selected' : '' }}>Optometry</option>
                                                <option value="Retina" {{ $user->speciality == 'Retina' ? 'selected' : '' }}>Retina</option>
                                                <option value="Anesthesia" {{ $user->speciality == 'Anesthesia' ? 'selected' : '' }}>Anesthesia</option>
                                                <option value="Derm/Aesthetics" {{ $user->speciality == 'Derm/Aesthetics' ? 'selected' : '' }}>Derm/Aesthetics</option>
                                                <option value="Dentist" {{ $user->speciality == 'Dentist' ? 'selected' : '' }}>Dentist</option>
                                                <option value="Integrative/Other" {{ $user->speciality == 'Integrative/Other' ? 'selected' : '' }}>Integrative/Other</option>
                                                <option value="Vet" {{ $user->speciality == 'Vet' ? 'selected' : '' }}>Vet</option>
                                            </select>
                                            @if ($errors->has('speciality'))
                                                <span class="text-danger">{{ $errors->first('speciality') }}</span>
                                            @endif
                                        </div>
                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="phone" class="form-label">Phone <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control py-2"
                                                    id="phone" value="{{ $user->phone }}">
                                                @if ($errors->has('phone'))
                                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                                @endif
                                            </div>
                                            <div class="rgs-w">
                                                <label for="practice_address_street" class="form-label">Street <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="practice_address_street"
                                                    value="{{ $user->practice_address_street }}"
                                                    class="form-control py-2" id="practice_address_street">
                                                @if ($errors->has('practice_address_street'))
                                                    <span class="text-danger">{{ $errors->first('practice_address_street') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="state" class="form-label">State <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select select2" id="state" name="state">
                                                    <option value="" disabled="" selected="">Select State
                                                    </option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state['name'] }}"
                                                            @if ($user->state == $state['name']) selected @endif>
                                                            {{ $state['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('state'))
                                                    <span class="text-danger">{{ $errors->first('state') }}</span>
                                                @endif
                                            </div>
                                            <div class="rgs-w">
                                                <label for="city" class="form-label">City <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select select2-city" id="city" name="city">
                                                    <option value="" disabled="" selected="">Select City
                                                    </option>
                                                   
                                                </select>
                                                @if ($errors->has('city'))
                                                    <span class="text-danger">{{ $errors->first('city') }}</span>
                                                @endif
                                            </div>
                                            
                                        </div>
                                        <div class=" d-flex gap-2 name-f">
                                            <div class="rgs-w">
                                                <label for="zip_code" class="form-label">Zip Code <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="zip_code" class="form-control py-2"
                                                    value="{{ $user->zip_code }}" id="zip_code">
                                            </div>
                                            <div class="rgs-w">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control py-2" readonly
                                                    id="email" value="{{ $user->email }}">
                                                @if ($errors->has('email'))
                                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-control-btn text-start">
                                            <button type="submit"
                                                class="header-btn profile-btn bg-dark border-0 mt-lg-0 mt-lg-4 mb-lg-0 mb-4 text-white py-2 px-4">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <form action="{{ route('change.password') }}" method="post">
                                    @csrf
                                    <h4 class="text-start mt-2 mt-lg-5">Change Password</h4>
                                    <div class="rgs-w">
                                        <label for="old_password" class="form-label">Current password <span class="text-danger">*</span></label>
                                        <input type="password" name="old_password" class="form-control py-2" id="old_password" required>
                                    </div>
                                    <div class="rgs-w">
                                        <label for="new_password" class="form-label">New password <span class="text-danger">*</span></label>
                                        <input type="password" name="new_password" class="form-control py-2" id="new_password" required>
                                    </div>
                                    <div class="rgs-w">
                                        <label for="confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="confirm_password" class="form-control py-2" id="confirm_password" required>
                                    </div>
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    <div class="form-control-btn mt-3 text-start submit-btns">
                                        <button type="submit" class="header-btn profile-btn bg-dark border-0 mt-lg-0 mt-lg-3 mb-lg-0 mb-4 text-white py-2 px-4">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            getCityList("{{Auth()->user()->state}}");
            $(document).on('change','#state',function(){
                var state = $(this).val();
                getCityList(state);
            });
        });
        function getCityList(state){
            $.ajax({
                url : "{{route('cityByState', '')}}"+"/"+state,
                type : 'GET',
                dataType:'json',
                success : function(data) {
                    $('#city').html(data);
                }
            });
        }
    </script>
@endsection
