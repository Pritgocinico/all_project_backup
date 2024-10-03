<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show"
            style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Stock List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="table">
                <th class="table">Product Name</th>
                <th class="table">Category Name</th>
                <th class="table">Product Variant / Stock</th>
                <th class="table">Total Stock</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($productList as $key=>$product)
                <tr>
                    <td class="table">{{ $product->product_name }}</td>
                    <td class="table">{{ isset($product->categoryDetail->categoryDetail) ? $product->categoryDetail->categoryDetail->name." - " : '' }}</b>  {{ isset($product->categoryDetail) ? $product->categoryDetail->name : '' }}</td>
                    <td class="table">
                        @php $totalStock = 0;@endphp
                    @foreach ($product->productVariantDetail as $variant)
                        {{ $variant->sku_name }} - {{ $variant->stock }}<br />
                        @php $totalStock += $variant->stock;@endphp
                    @endforeach
                    </td>
                    <td class="table">{{ $totalStock }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
