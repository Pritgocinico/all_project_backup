@extends('admin.layouts.app')

@section('content')
<div class="card mb-2 p-3">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Edit Dealer</h4>
            <div class="ms-auto">
                <a href="{{ route('admin.users') }}" class="btn btn-primary ustify-content-end float-right">
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
        <form action="{{ route('admin.edit.user') }}" method="POST" class="row g-3">
        @csrf
            <div class="col-md-6">
                <label for="Name" class="form-label">Firm Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="Name" placeholder="User Name" value="{{$user->name}}">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" name="phone" id="Phone" placeholder="User phone" value="{{$user->phone}}">
                @if ($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="User Email Address" value="{{$user->email}}">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Agent" class="form-label">Agent <span class="text-danger">*</span></label>
                <select name="agent" id="" class="form-control">
                    <option value="0">Select Agent</option>
                    @foreach ($agents as $item)
                        <option value="{{$item->id}}" @if ($item->id == $user->agent)
                            selected
                        @endif>{{$item->name}}</option>
                    @endforeach
                </select>
                @if ($errors->has('agent'))
                    <span class="text-danger">{{ $errors->first('agent') }}</span>
                @endif
            </div>
            <input type="hidden" name="role" value="2">
            <div class="col-md-6">
                <label for="GST" class="form-label">Gst Number</label>
                <input type="text" class="form-control" name="gst_number" id="GST" placeholder="GST Number" value="{{$user->gst_number}}">
                @if ($errors->has('gst_number'))
                    <span class="text-danger">{{ $errors->first('gst_number') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Transport" class="form-label">Transport <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="transport" id="Transport" placeholder="Transport Name" value="{{$user->transport}}">
                @if ($errors->has('transport'))
                    <span class="text-danger">{{ $errors->first('transport') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Category" class="form-label">Category <span class="text-danger">*</span></label>
                <select class="form-control select2" name="categories[]" id="Category" placeholder="Select Categories" multiple >
                    @if (count($parent_categories)>0)
                        @foreach ($parent_categories as $item)
                            <?php 
                                $childs = DB::table('categories')->where('parent',$item->id)->get();
                            ?>
                            @if (count($childs)>0)
                                <optgroup label="{{$item->name}}">
                                    @foreach ($childs as $child)
                                        <option value="{{$child->id}}" @if($dealer_categories) @foreach($dealer_categories as $d_category) @if($d_category->category_id == $child->id) selected @endif @endforeach @endif>{{$child->name}}</option>
                                    @endforeach
                                </optgroup>
                            @else
                                <option value="{{$item->id}}" @if($dealer_categories) @foreach($dealer_categories as $d_category) @if($d_category->category_id == $item->id) selected @endif @endforeach @endif>{{$item->name}}</option> 
                            @endif
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('categories'))
                    <span class="text-danger">{{ $errors->first('categories') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="FloorNo" class="form-label">Floor No / Block No / Office No</label>
                <input type="text" class="form-control" name="floor_no" id="FloorNo" placeholder="Floor No." value="{{$user->floor_no}}">
                @if ($errors->has('floor_no'))
                    <span class="text-danger">{{ $errors->first('floor_no') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Address" class="form-label">Address</label>
                <input type="text" class="form-control" name="address" id="Address" placeholder="Address" value="{{$user->address}}">
                @if ($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="Locality" class="form-label">Locality</label>
                <input type="text" class="form-control" name="locality" id="Locality" placeholder="Locality" value="{{$user->locality}}">
                @if ($errors->has('locality'))
                    <span class="text-danger">{{ $errors->first('locality') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="State" class="form-label">State </label>
                <select class="form-control" id="State" name="state">
                    <option value="0">Select State...</option>
                    @if(isset($states) && !blank($states))
                        @foreach($states as $state)
                            <option value="{{$state->name}}" @if($state->name == strtoupper($user->state)) selected @endif>{{$state->name}}</option>
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="City" class="form-label">City </label>
                <select class="form-control" id="City" name="city">
                    <option value="0">Select City...</option>
                    @if(isset($cities) && !blank($cities))
                        @foreach($cities as $city)
                            <option value="{{$state->name}}" @if($city->city == ucfirst($user->city)) selected @endif>{{$city->city}}</option>
                        @endforeach
                    @endif
                </select>
                @if ($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password <span class="text-danger">(Leave blank if don't want to change)</span></label>
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
                    <input class="form-check-input" type="checkbox" name="status" id="flexSwitchCheckChecked" @if($user->status==1) checked @endif>
                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                </div>
            </div>
            <div class="col-12">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
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
            console.log(state);
            $.ajax({
                type: 'GET',
                url: '{{ route("get_cities",'') }}'+'/'+state,
                success: function (data) {
                    $('#City').html(data);
                },
                error: function (data) {
                    // console.log(data);
                }
            });
        });
    </script>
@endsection
