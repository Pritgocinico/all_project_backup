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
                <a href="javascript:void(0);">Inquiries</a>
            </li>
        </ol>
    </nav>
</div>
<div class="houmanity-card">
    <div class="card-body card-head">
        <div class="d-md-flex gap-4 align-items-center bg-white p-3">
            <div class="d-none d-md-flex">All Inquiry</div>
            <div class="d-md-flex gap-4 align-items-center">
                <form class="mb-3 mb-md-0">
                    <div class="row g-3">
                        <div class="col-6 col-sm-6 col-md-7">
                            <select class="form-select classic order-table">
                                <option hidden>Sort by</option>
                                <option value="desc">Desc</option>
                                <option value="asc">Asc</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-5">
                            <select class="form-select classic" id="maxRows">
                                <option value="10" selected="selected">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="ms-auto d-flex arcon-user-inner-search-outer">
                <form class="arcon-user-inner-search" action="">
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
        <table id="" class="table table-custom rwd-table" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Message</th>
                    <th>Created At</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @if (!blank($inquiries))
                    @foreach ($inquiries as $inquiry)
                        <tr>
                            <td data-th="Name"><a href="javascript:void(0);" class="text-primary">{{$inquiry->name}}</a></td>
                            <td data-th="Phone Number">
                                {{$inquiry->phone}}
                            </td>
                            <td data-th="Product Name">
                                {{$inquiry->product_name}}
                            </td>
                            <td data-th="Quantity">
                                {{$inquiry->quantity}}
                            </td>
                            <td data-th="Message">
                               {{$inquiry->message}}
                            </td>
                            <td data-th="Created At">{{ date($setting->date_format,strtotime($inquiry->created_at)) }}</td>
                            <td data-th="Action" class="text-md-end">
                                <div class="d-flex float-end">
                                    <a href="javascript:void(0);" data-id="{{ $inquiry->id }}" class="ms-2 delete-btn"><img src="{{url('/')}}/assets/admin/images/delete.png" width="20px" class="me-2"></a>
                                   
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan=7>Inquiries Not Found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class='pagination-container'>
    <nav class="mt-4" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
    </ul>
    </nav>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $(document).on('click','.delete-btn',function(){
            var user_id = $(this).attr('data-id');
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url : "{{route('delete.inquiry', '')}}"+"/"+user_id,
                    type : 'GET',
                    dataType:'json',
                    success : function(data) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: "Inquiry has been deleted.",
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                top.location.href="{{ route('admin.inquiries') }}";
                            }
                        });
                    }
                });
            }
            });
        });
    });
</script>
@endsection
