@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('setting') }}" method="post"
                        enctype="multipart/form-data" onsubmit="return employeeValidate()">
                        @csrf
                        <input type="hidden" id="id" name="id" value="{{isset($setting)?$setting->id:''}}">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Login Time</label>
                                <input type="time" class="form-control" placeholder="Enter Email" name="login_time"
                                    value="{{isset($setting)?$setting->login_time:''}}" id="login_time">
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Buffer Time</label>
                                <input type="time" class="form-control" placeholder="Enter Phone Number"
                                    name="buffer_time" value="{{isset($setting)?$setting->buffer_time:''}}" id="buffer_time">
                            </div>
                        </div>
                        <div class="modal-footer flex-start mt-2">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
