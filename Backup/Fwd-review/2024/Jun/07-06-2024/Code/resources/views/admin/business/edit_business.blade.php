@extends('admin.layouts.app')

@section('content')
<div class="gc_row px-md-4 px-2">
    <div class="card mt-md-3 mb-3">
        <div class="card-body d-flex align-items-center p-lg-3 p-2 staff_header">
            <div class="pe-4 fs-5">Edit Business</div>
            <div class="ms-auto">
                <a href="{{route('admin.business')}}" class="btn gc_btn">Go Back</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    @php $route = route('admin.update.business'); @endphp
                    @if(Auth()->user() !== null && Auth()->user()->role == 2)
                    @php $route = route('client.update.business'); @endphp
                    @endif
                    <form action="{{$route}}" method="post" class="row g-3">
                        @csrf
                        <div class="col-md-12 form-floating mt-4">
                            <select name="client" class="form-control" id="Client">
                                <option value="0">Select Client...</option>
                                @foreach ($clients as $client)
                                    <option value="{{$client->id}}" @if($client->id == $business->client_id) selected @endif>{{$client->name}}</option>
                                @endforeach
                            </select>
                            <label for="Client" class="form-label">Select Client *</label>
                            @if ($errors->has('client'))
                                <span class="text-danger">{{ $errors->first('client') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="name" id="Name" value="{{$business->name}}" placeholder="" />
                            <label for="Name" class="form-label">Business Name *</label>
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="shortname" id="ShortName" value="{{$business->shortname}}" placeholder="" />
                            <label for="ShortName" class="form-label">Business Shortname (for URL)*</label>
                            @if ($errors->has('shortname'))
                                <span class="text-danger">{{ $errors->first('shortname') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="place_id" id="PlaceID" value="{{$business->place_id}}" placeholder="" />
                            <label for="PlaceID" class="form-label">Place ID *</label>
                            @if ($errors->has('place_id'))
                                <span class="text-danger">{{ $errors->first('place_id') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12 form-floating mt-4">
                            <input type="text" class="form-control" name="api_key" id="ApiKey" value="{{$business->api_key}}" placeholder="" />
                            <label for="ApiKey" class="form-label">Api Key *</label>
                            @if ($errors->has('api_key'))
                                <span class="text-danger">{{ $errors->first('api_key') }}</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="id" value="{{$business->id}}">
                            <button type="submit" class="btn gc_btn mt-3">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <label for="" class="form-label">Process of Adding API key in Place ID</label>
                    <iframe src="{{url('/')}}/assets/Images/blank.pdf" class="col-d-12" width="700" height="400" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
