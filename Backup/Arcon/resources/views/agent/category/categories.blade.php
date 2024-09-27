@extends('agent.layouts.app')

@section('content')
<div class="mb-4 px-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('agent.dashboard') }}">
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                <a href="">
                    Products
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="javascript:void(0);">Categories</a>
            </li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="houmanity-card">
            <div class="card-body card-head">
                <div class="d-md-flex gap-4 align-items-center bg-white p-3">
                    <div class="d-none d-md-flex">All Categories</div>
                    <div class="d-md-flex gap-4 align-items-center">
                        <form class="mb-3 mb-md-0">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <select class="form-select classic order-table">
                                        <option hidden>Sort by</option>
                                        <option value="desc">Desc</option>
                                        <option value="asc">Asc</option>
                                    </select>
                                </div>
                                <!-- <div class="col-md-5">
                                    <select class="form-select classic" id="maxRows">
                                        <option value="10" selected="selected">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>
                                </div> -->
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
        </div>
        <div class="houmanity-card">
            <div class="card-body table-responsive">
                <table id="" class="table table-custom" style="width:100%">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($parent_categories) > 0)
                            @foreach ($parent_categories as $category)
                                <tr>
                                    <td class="align-middle"><a class="text-primary" href="">{{$category->name }}</a></td>
                                    <td class="align-middle">
                                        @if (!blank($category->image))
                                          <img src="{{url('/')}}/public/categories/{{$category->image}}" width="50px" alt="">
                                        @endif
                                    </td>
                                </tr>
                                @foreach ($categories as $item)
                                    @if($category->id == $item->parent)
                                    <tr>
                                        <td class="align-middle"><a href="" class="text-primary"><span class="ps-3">- {{$item->name }}</span></a></td>
                                        <td class="align-middle">
                                            @if (!blank($item->image))
                                              <img src="{{url('/')}}/public/categories/{{$item->image}}" width="50px" alt="">
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="2">Categories not found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
