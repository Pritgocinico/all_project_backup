@extends('purchase.layouts.app')

@section('content')
    <div class="page-header d-md-flex justify-content-between">
        <h3>Welcome back, {{ Auth::user()->name }}</h3>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <a href="{{ route('purchase.customers') }}">
                            <div class="card-body">
                                <h6 class="card-title">Total Customer</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <div class="avatar">
                                            <span class="avatar-title bg-primary-bright text-primary rounded-pill">
                                                <i class="ti-ruler-pencil"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="font-weight-bold ml-1 font-size-30 ml-3">{{ count($totalCustomer) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <a href="{{ route('purchase_projects') }}">
                            <div class="card-body">
                                <h6 class="card-title">Total Projects</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <div class="avatar">
                                            <span class="avatar-title bg-warning-bright text-warning rounded-pill">
                                                <i class="ti-dashboard"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="font-weight-bold ml-1 font-size-30 ml-3">{{ count($projects) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <a href="{{ route('purchase_task-management') }}">
                            <div class="card-body">
                                <h6 class="card-title">Total Task</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div>
                                        <div class="avatar">
                                            <span class="avatar-title bg-info-bright text-info rounded-pill">
                                                <i class="ti-map"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="font-weight-bold ml-1 font-size-30 ml-3">{{ count($completedTasks) }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection