<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee PDF</title>
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
    <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Product List</h5>

    <table class="table">
        <thead>
            <th class="table">Product Name</th>
            <th class="table">SKU</th>
            <th class="table">Category Name</th>
            <th class="table">Status</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($productList as $product)
                <tr>
                    <td class="table">{{$product->product_name}}</td>
                    <td class="table">{{$product->sku_name}}</td>
                    <td class="table">{{isset($product->categoryDetail)?$product->categoryDetail->name:""}}</td>
                    <td class="table">@if($product->status == 1){{"Active"}} @else {{ "Inactive" }}@endif</td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($product->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-center" colspan="6">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
