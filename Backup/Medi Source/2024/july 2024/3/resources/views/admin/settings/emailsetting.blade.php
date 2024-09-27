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

                    <div class="tab-pane fade show active" id="email">
                        <form method="post" action="{{ route('admin.settings.email.save') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $emailSetting->email) }}">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    value="{{ old('password', $emailSetting->password) }}">
                            </div>


                            <button type="submit" class="btn btn-primary">Save Email Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection