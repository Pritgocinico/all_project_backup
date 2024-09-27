@extends('admin.layouts.app')

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Logs</a> 
            </li>
        </ol>
    </nav>
</div>
{{-- <div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="d-none d-md-flex">All Logs</div>
            <div class="d-md-flex gap-4 align-items-center">
                <form class="mb-3 mb-md-0">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <select class="form-select classic order-table">
                                <option hidden>Sort by</option>
                                <option value="desc">Desc</option>
                                <option value="asc">Asc</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select class="form-select classic" id="maxRows">
                                <option value="25" selected="selected">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="200">200</option>
                                <option value="500">500</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="ms-auto d-flex">
                <form action="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control src d-none" id="search-table" placeholder="Search">
                                <span class="search-btn mt-2 ms-2" type="button">
                                    <i class="bi bi-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
<div class="houmanity-card">
    <div class="card-body table-responsive">
        <table id="example" class="table table-custom rwd-table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Log</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @if (count($logs) > 0)
                    <?php $i = 1; ?>
                    @foreach($logs as $log) 
                        <tr>
                            <td data-th="#">{{$i++}}</td>
                            <td data-th="User">{{$log->user_id }}</td>
                            <td data-th="Module">{{$log->module}}</td>
                            <td data-th="Log">{{$log->log}}</td>
                            <td data-th="Created At">{{date('d-m-Y H:i:s',strtotime($log->created_at))}}</td>
                        </tr>
                    @endforeach
                @else
                    <!--<tr class="text-center">-->
                    <!--    <th colspan="5">Logs Not Found.</th>-->
                    <!--</tr>-->
                @endif
                
            </tbody>
        </table>
    </div>
</div>
<!--<div class='pagination-container'>-->
<!--    <nav class="mt-4" aria-label="Page navigation example">-->
<!--    <ul class="pagination justify-content-center">-->
<!--    </ul>-->
<!--    </nav>-->
<!--</div>-->
@endsection