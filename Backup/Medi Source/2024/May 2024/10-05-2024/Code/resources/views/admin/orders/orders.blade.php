@extends('admin.layouts.app')

@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
@endsection
@section('content')
    <div class="content-page">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">Orders</h2>
                        @if(PermissionHelper::checkUserPermission('Order Add/Edit/Delete'))
                        <a href="{{ route('create_order') }}" class="btn btn-primary ms-auto ">Add Order</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table" id="productTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Fedex Shipping Label</th>
                                <th>Order Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!blank($orders))
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            @if(PermissionHelper::checkUserPermission('Order List/View'))
                                                <a href="{{ route('admin.view.order', $order->id) }}">{{$order->order_id }}</a>
                                            @else
                                                {{$order->order_id}}
                                            @endif
                                        </td>
                                        <td>{{ $order->first_name . ' ' . $order->last_name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>$ {{ $order->total }}</td>
                                        <td>
                                            @if(PermissionHelper::checkUserPermission('Order Print Label'))
                                            <a class="btn btn-icon btn-dark btn-sm" target="_blank" href="{{ route('generate-label', $order->id) }}">
                                                Print Label &nbsp;<i class="fa-solid fa-tag"></i>
                                            </a>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if(PermissionHelper::checkUserPermission('Order Package Slip'))
                                            <a class="btn btn-icon btn-info btn-sm"
                                                    href="{{ route('generate-PackageSlip', $order->id) }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Generate Package Slip">
                                                    <i class="fa-solid fa-boxes-packing"></i></a>
                                                    @endif
                                                    @if(PermissionHelper::checkUserPermission('Order Generate Invoice'))
                                                <a class="btn btn-icon btn-info btn-sm"
                                                    href="{{ route('generate-invoice', $order->id) }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Generate Invoice">
                                                    <i class="fas fa-file-invoice"></i></a>
                                                    @endif
                                                    @if(PermissionHelper::checkUserPermission('Order List/View'))
                                                    <a href="{{ route('admin.view.order', $order->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                    @if(PermissionHelper::checkUserPermission('Order Add/Edit/Delete'))
                                                <a href="{{ route('edit', $order->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('destroy', $order->id) }}" method="post"
                                                    style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm delete-product-btn">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable({
                responsive: true,
            });
            $('[data-bs-toggle="tooltip"]').tooltip();

            // SweetAlert for delete confirmation
            $('.delete-product-btn').click(function() {
                var form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If confirmed, trigger the form submission
                        form.submit();
                    }
                });
            });
        });
    </script>   
@endsection
