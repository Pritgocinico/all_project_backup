@extends('layouts.main_layout')
@section('section')
    <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar  pt-6 pb-2 ">
                <div id="kt_app_toolbar_container" class="container-fluid d-flex align-items-stretch ">
                    <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                            <h1
                                class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                                System Engineer Create
                            </h1>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">
                                        Home </a>
                                </li>
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ route('employees.index') }}" class="text-muted text-hover-primary">
                                        System Engineer List </a>
                                </li>

                            </ul>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-lg-3">

                            <a href="{{ route('employees.index') }}" class="btn btn-flex btn-primary h-40px fs-7 fw-bold">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="kt_app_content" class="app-content  flex-column-fluid">
                <div id="kt_app_content_container" class="app-container  container-fluid">
                    <form class="form" action="{{ route('systemengineer.store') }}" method="post" enctype="multipart/form-data" onsubmit="return systemengineerValidate()">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Name</label>
                                <input type="text" class="form-control mb-3 mb-lg-0"
                                    placeholder="Enter Name" name="name" value="{{ old('name') }}" id="name">
                                <span class="text-danger"
                                    id="name_error">{{ $errors->getBag('default')->first('name') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-semibold mb-2">Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                    value="{{ old('email') }}" id="email">
                                <span class="text-danger"
                                    id="email_error">{{ $errors->getBag('default')->first('email') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Phone Number</label>
                                <input type="number" class="form-control" placeholder="Enter Phone Number"
                                    name="phone_number" value="{{ old('phone_number') }}" id="phone_number">
                                <span class="text-danger"
                                    id="phone_number_error">{{ $errors->getBag('default')->first('phone_number') }}</span>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-semibold mb-2">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password"
                                    value="{{ old('password') }}" id="password">
                                <span class="text-danger"
                                    id="password_error">{{ $errors->getBag('default')->first('password') }}</span>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="Status" class="required fs-6 fw-semibold mb-2">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="status"
                                        id="flexSwitchCheckChecked" checked="">
                                    <label class="form-check-label" for="flexSwitchCheckChecked"></label>
                                </div>
                            </div>
                        </div>

                        <input type="number" class="form-control mb-3 mb-lg-0"
                                     name="role" value="3"  hidden>
                        
                        <div class="mt-2">
                            <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('page')
    <script src="{{ asset('public\assets\js\custom\admin\systemengineer.js') }}?{{time()}}"></script>
@endsection
