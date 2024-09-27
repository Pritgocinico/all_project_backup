@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings') ? 'active' : '' }}"
                            href="{{ route('admin.settings') }}">
                            <i class="fas fa-cogs"></i> General Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings/companysetting') ? 'active' : '' }}"
                            href="{{ route('admin.settings.companysetting') }}">
                            <i class="fas fa-building"></i> Company Information
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/settings/email') ? 'active' : '' }}"
                            href="{{ route('admin.settings.email') }}">
                            <i class="fas fa-envelope"></i> Email Settings
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="company">

                        <!-- Form Start -->
                        <form method="post" action="{{ route('admin.settings.company.save') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address"
                                    rows="3">{{ old('address', $setting->address) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <select class="form-select" id="city" name="city">
                                    @foreach($cities as $city)
                                    <option value="{{ $city['name'] }}" @if(old('city', $setting->city) ==
                                        $city['name']) selected @endif>{{ $city['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <select class="form-select" id="state" name="state">
                                    @foreach($states as $state)
                                    <option value="{{ $state['name'] }}" @if(old('state', $setting->state) ==
                                        $state['name']) selected @endif>{{ $state['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code"
                                    value="{{ old('postal_code', $setting->postal_code) }}">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone', $setting->phone) }}">
                            </div>

                            {{-- <div class="mb-3">
                                <label for="gst_number" class="form-label">GST Number</label>
                                <input type="text" class="form-control" id="gst_number" name="gst_number"
                                    value="{{ old('gst_number', $setting->gst_number) }}">
                            </div> --}}

                            <button type="submit" class="btn btn-primary">Save Company Information</button>
                        </form>
                        <!-- Form End -->

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
