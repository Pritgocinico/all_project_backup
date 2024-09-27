@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Add Agent</h4>
            <div class="ms-auto">
                <a href="javascript:void(0);" onclick="window.history.go(-1); return false;" class="btn btn-primary ustify-content-end float-right">
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
        <form action="{{ route('admin.add_agent_data') }}" method="POST" class="row g-3">
        @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Agent Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="Agent Code" value="{{old('name')}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="AgentName" class="form-label">Agent Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="agent_name" id="AgentName" placeholder="Agent Name" value="{{old('agent_name')}}">
                @if ($errors->has('agent_name'))
                    <span class="text-danger">{{ $errors->first('agent_name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Phone" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="User Email Address" value="{{old('email')}}">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="phone" id="Phone" placeholder="User Phone Number" value="{{old('phone')}}">
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="HeadQuarter" class="form-label">Headquarter <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="headquarter" id="HeadQuarter" placeholder="Head Quarter" value="{{old('headquarter')}}">
                @if ($errors->has('headquarter'))
                    <span class="text-danger">{{ $errors->first('headquarter') }}</span>
                @endif
            </div>
           <input type="hidden" name="role" value="3">
            <!--<div class="col-md-6">-->
            <!--    <label for="State" class="form-label">State <span class="text-danger">*</span></label>-->
            <!--    <select name="state" id="State" class="form-control select2">-->
            <!--        <option value="0">Select State</option>-->
            <!--        @foreach ($states as $item)-->
            <!--            <option value="{{$item->id}}">{{$item->name}}</option>-->
            <!--        @endforeach-->
            <!--    </select>-->
            <!--    @if ($errors->has('agent'))-->
            <!--        <span class="text-danger">{{ $errors->first('agent') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <!--<div class="col-md-6">-->
            <!--    <label for="City" class="form-label">City <span class="text-danger">*</span></label>-->
            <!--    <select name="city" id="City" class="form-control select2">-->

            <!--    </select>-->
            <!--    @if ($errors->has('agent'))-->
            <!--        <span class="text-danger">{{ $errors->first('agent') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <!--<div class="col-md-6">-->
            <!--    <label for="Locality" class="form-label">Locality <span class="text-danger">*</span></label>-->
            <!--    <input type="text" class="form-control" name="locality" id="Locality" placeholder="Locality" value="{{old('locality')}}">-->
            <!--    @if ($errors->has('locality'))-->
            <!--        <span class="text-danger">{{ $errors->first('locality') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <!--<div class="col-md-6">-->
            <!--    <label for="Floor" class="form-label">Floor No / Block No / Office No</label>-->
            <!--    <input type="text" class="form-control" name="floor_no" id="Floor" placeholder="Floor No" value="{{old('floor_no')}}">-->
            <!--    @if ($errors->has('floor_no'))-->
            <!--        <span class="text-danger">{{ $errors->first('floor_no') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <!--<div class="col-md-6">-->
            <!--    <label for="Address" class="form-label">Address <span class="text-danger">*</span></label>-->
            <!--    <input type="text" class="form-control" name="address" id="Address" placeholder="Address" value="{{old('address')}}">-->
            <!--    @if ($errors->has('address'))-->
            <!--        <span class="text-danger">{{ $errors->first('address') }}</span>-->
            <!--    @endif-->
            <!--</div>-->
            <div class="col-md-6">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder=" " >
                    <span class="input-group-btn"><button type="button" class="btn btn-primary btn-lg getNewPass" style="border-radius:unset !important;" onclick="showPassword()"><span class="bi bi-eye"></span></button></span>
                </div>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Status" class="form-label">Status <span class="text-danger">*</span></label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" checked>
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
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
        function showPassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
        $(document).on('change','#State',function(){
            var state = $(this).val();
            $.ajax({
                type: 'GET',
                url: '{{ route("get_state_cities") }}',
                data: {'id': state},
                success: function (data) {
                    $('#City').html(data)
                }
            });
        });
    </script>
@endsection
