@extends('quotation.layouts.app')

@section('content')
<div class="card mb-3 p-2">
    <div class="card-body">
        <div class="d-md-flex gap-4 align-items-center">
            <h4 class="mb-0">Settings</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-lg-3 col-xl-3 col-xxl-3">
        <div class="card py-2">
            <div class="card-body">
                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="{{ route('quotation_general.setting') }}" class="nav-link p-2 mb-1 @if(\Request::route()->getName() == 'quotation_general.setting' || \Request::route()->getName() == 'quotation_settings') active @endif" id="v-pills-home-tab" aria-selected="true">General Settings</a>
                    <a href="{{ route('quotation_company.setting') }}" class="nav-link p-2 mb-1 @if(\Request::route()->getName() == 'quotation_company.setting') active @endif" id="v-pills-profile-tab" aria-selected="false">Company Information</a>
                    <a href="{{ route('quotation_email.setting') }}" class="nav-link p-2 mb-1 @if(\Request::route()->getName() == 'quotation_email.setting') active @endif" id="v-pills-messages-tab" aria-selected="false">Email Settings</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9 col-lg-9 col-xl-9 col-xxl-9">
        <div class="card p-2">
            <div class="card-body">
                <div class="row w-100 p-3">
                    @yield('tabs')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
