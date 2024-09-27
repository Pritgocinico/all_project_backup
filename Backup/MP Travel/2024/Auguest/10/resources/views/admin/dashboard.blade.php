@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
    <div
        class="flex-fill overflow-y-lg-auto scrollbar bg-body rounded-top-4 rounded-top-start-lg-4 rounded-top-end-lg-0 border-top border-lg shadow-2">
        <main class="container-fluid px-3 py-5 p-lg-6 p-xxl-8">
            <div class="mb-6 mb-xl-10">
                <div class="row g-3 align-items-center mb-5">
                    <div class="col">
                        <h1 class="ls-tight">Dashboard</h1>
                    </div>
                </div>
                <div class="vstack gap-3 gap-xl-6">
                    <div class="row row-cols-xl-4 g-3 g-xl-6">
                        <div class="col">
                            <a href="{{ route('user.index') }}">
                                <div class="card bg-style1">
                                    <div class="p-7">
                                        <h6 class="text-limit text-white mb-3">Total User</h6><span
                                            class="d-block h3 fw-bold text-white">{{ $userCount }}</span>

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('role.index') }}">
                                <div class="card bg-style2">
                                    <div class="p-7">
                                        <h6 class="text-limit text-white mb-3">Total Role</h6><span
                                            class="d-block h3 text-white fw-bold">{{ $roleCount }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('department.index') }}">
                                <div class="card bg-style3">
                                    <div class="p-7">
                                        <h6 class="text-limit text-white mb-3">Total Department</h6>
                                        <span class="d-block h3 text-white fw-bold">{{ $departmentCount }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('designation.index') }}">
                            <div class="card bg-style4">
                                <div class="p-7">
                                    <h6 class="text-limit text-white mb-3">Total Designation</h6>
                                    <span class="d-block h3 text-white fw-bold">{{ $designationCount }}</span>
                                </div>
                            </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('leads.index') }}">
                            <div class="card bg-style5">
                                <div class="p-7">
                                    <h6 class="text-limit text-white mb-3">Total Lead</h6>
                                    <span class="d-block h3 text-white fw-bold">{{ $leadCount }}</span>
                                </div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
