@extends('admin.partials.header', ['accesses' => $accesses, 'active' => 'dashboard'])
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <div class="row row-cols-xl-4 g-3 g-xl-6 dashboard-cards">
                        <div class="col">
                            <a href="{{ route('user.index') }}">
                                <div class="card bg-style1">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3">{{ $userCount }}</span>
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        
                                        <h6 class="text-limit  mb-3">Total Users</h6>
                                        

                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('role.index') }}">
                                <div class="card bg-style2">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                            <span class="d-block h3">{{ $roleCount }}</span>
                                             <i class="fas fa-business-time	"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Roles</h6>
                                      
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('department.index') }}">
                                <div class="card bg-style3">
                                    <div class="p-7 dashboard-inner-card">
                                        <div>
                                        <span class="d-block h3">{{ $departmentCount }}</span>
                                        <i class="fas fa-building"></i>
                                        </div>
                                        <h6 class="text-limit  mb-3">Total Departments</h6>
                                        
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ route('designation.index') }}">
                            <div class="card bg-style4">
                                <div class="p-7 dashboard-inner-card">
                                    <div>
                                    <span class="d-block h3">{{ $designationCount }}</span>
                                    <i class="fa-solid fa-user-group"></i>
                                    </div>
                                    <h6 class="text-limit  mb-3">Total Designations</h6>
                                    
                                </div>
                            </div>
                            </a>
                        </div>
                        <!-- <div class="col">
                            <a href="{{ route('leads.index') }}">
                            <div class="card bg-style5">
                                <div class="p-7">
                                    <div>
                                    <span class="d-block h3">{{ $leadCount }}</span>
                                    </div>
                                    <h6 class="text-limit  mb-3">Total Lead</h6>
                                    
                                </div>
                            </div>
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
