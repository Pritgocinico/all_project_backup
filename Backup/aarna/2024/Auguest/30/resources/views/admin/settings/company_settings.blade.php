@extends('admin.layouts.setting')

@section('tabs')
<div class="row">
    <div class="col-md-12">
        @if(Session::has('alert'))
            <p class="alert
            {{ Session::get('alert-class', 'alert-danger') }}">{{Session::get('alert') }}</p>
        @endif
        <form action="{{ route('save_company_settings') }}" method="POST" class="row g-3" enctype="multipart/form-data">
           @csrf
            <div class="col-12">
                <label for="companyName" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="companyName" name="company_name" placeholder=" " value="{{$settings->company_name}}">
                @if ($errors->has('company_name'))
                    <span class="text-danger">{{ $errors->first('company_name') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="Address" class="form-label">Address</label>
                <input type="text" class="form-control" id="Address" name="address" placeholder=" " value="{{$settings->address}}">
                @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="City" class="form-label">City</label>
                <input type="text" class="form-control" id="City" name="city" value="{{$settings->city}}">
                @if ($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="State" class="form-label">State</label>
                <input type="text" class="form-control" id="State" name="state" value="{{$settings->state}}">
                @if ($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
            </div>

            <div class="col-12">
                <label for="PostalCode" class="form-label">Postal Code</label>
                <input type="text" class="form-control" id="PostalCode" name="postal_code" value="{{$settings->postal_code}}">
                @if ($errors->has('postal_code'))
                    <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{$settings->phone}}">
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="col-12">
                <label for="gst_number" class="form-label">GST Number</label>
                <input type="text" class="form-control" id="gst_number" name="gst_number" value="{{$settings->gst_number}}">
                @if ($errors->has('gst_number'))
                    <span class="text-danger">{{ $errors->first('gst_number') }}</span>
                @endif
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('script')
<script>
    $('.select2-country1').select2({
        placeholder: 'Select'
    });
</script>
@endsection
