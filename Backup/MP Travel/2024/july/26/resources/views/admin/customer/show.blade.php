@extends('admin.partials.header', ['active' => 'customer'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('storage/' . $customer->profile_image) }}" class="img-fluid rounded-top-start-4" style="height: 100px !important" alt="...">
                    </div>
                    <div class="col">
                        <h1 class="ls-tight">{{ $customer->name }}</h1>
                    </div>
                </div>
                
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Name</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->name }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Email</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->email }}
                </div>
            </div>
            <div class="row align-items-center g-3 mt-6">
                <div class="col-md-2"><label class="form-label mb-0">Phone Number</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ $customer->mobile_number }}
                </div>
                <div class="col-md-2"><label class="form-label mb-0">Role</label></div>
                <div class="col-md-4 col-xl-4">
                    {{ isset($customer->roleDetail) ? $customer->roleDetail->name : '-' }}
                </div>
            </div>
        </main>
    </div>
@endsection
