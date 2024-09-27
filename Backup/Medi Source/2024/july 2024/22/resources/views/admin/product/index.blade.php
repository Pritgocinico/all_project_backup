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
                        <h2 class="mb-0">Products</h2>
                        @if(PermissionHelper::checkUserPermission('Product Add/Edit/Delete'))
                        <a href="{{ route('admin.product.create') }}" class="btn btn-primary ms-auto ">Add Product</a>
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
                                <th>SKU</th>
                                <th>Product Name</th>
                                <th>Unit Size/Type</th>
                                <th>Package Size</th>
                                <th>Product Code</th>
                                <th>NDC</th>
                                <th>Storage</th>

                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!blank($products))
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>
                                            @if(PermissionHelper::checkUserPermission('Product List/View'))
                                                <a href="{{ route('admin.product-details.show', $product->slug) }}">{{ $product->sku }}</a>
                                            @else
                                                {{$product->sku}}
                                            @endif

                                        </td>
                                        <td>{{ $product->productname }}</td>
                                        <td>{{ $product->unit_size_type }}</td>
                                        <td>{{ $product->package_size }}</td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->ndc }}</td>
                                        <td>{{ $product->storage }}</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if(PermissionHelper::checkUserPermission('Product List/View'))
                                            <a href="{{ route('admin.product-details.show', $product->slug) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            @if(PermissionHelper::checkUserPermission('Product Add/Edit/Delete'))
                                            <a href="{{ route('admin.product-details.edit', $product->slug) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.product-details.destroy', $product->id) }}"
                                                method="post" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm delete-product-btn">
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
        $('#productTable').DataTable();

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
<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>
@endsection
