@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">{{Utility::getUserRoleName(Auth()->user()->role_id)}} Dashboard</h1>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection