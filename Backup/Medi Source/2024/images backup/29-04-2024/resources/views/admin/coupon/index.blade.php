<!-- resources/views/admin/lot/index.blade.php -->

@extends('admin.layouts.app')

@section('content')

<div class="content-page">
    <div class="content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="mb-0">All Coupon</h3>
                    <a href="{{ route('coupon.create') }}" class="btn btn-primary ms-auto">Add Coupon</a>
                </div>
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table" id="lotTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Coupon Code</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($couponList as $coupon)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $coupon->coupon_code }}</td>
                                <td>{{isset($coupon->productDetail)?$coupon->productDetail->sku:""}}</td>
                                <td>{{ $coupon->quantity }}</td>
                                <td>
                                    @if($coupon->coupon_type == "dollar")
                                        {{"$" . $coupon->discount_amount}}
                                    @else
                                        {{$coupon->discount_percentage ."%" }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('coupon.edit', $coupon->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-lot-id="{{ $coupon->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Form for delete action -->
                                    <form action="{{ route('coupon.destroy', $coupon->id) }}" method="POST" style="display: none;" data-lot-id="{{ $coupon->id }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert library directly -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize DataTables
        $('#lotTable').DataTable();
        // Listen for click on delete button
        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                const lotId = this.getAttribute('data-lot-id');
                // Show SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085D6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form if the user confirms
                        document.querySelector('form[data-lot-id="' + lotId + '"]').submit();
                    }
                });
            });
        });
    });
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

@endsection
