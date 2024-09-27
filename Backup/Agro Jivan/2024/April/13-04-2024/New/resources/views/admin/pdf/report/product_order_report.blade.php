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
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show"
            style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Order List</h5>

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0 table">
                <th class="min-w-125px table">Product Name</th>
                <th class="min-w-125px table">Category</th>
                <th class="min-w-125px table">Total Quantity</th>
                <th class="min-w-100px table">Total Amount</th>
            </tr>
        </thead>
        @php $totalRevenue =0 @endphp
        <tbody class="text-gray-600">
            @forelse ($productList as $product)
                <tr>
                    <td class="table">{{ isset($product->getProductDetail) ? $product->getProductDetail->product_name : '-' }}[{{ $product->sku_name }}]</td>
                    <td class="table">
                        {{ isset($product->getProductDetail) ? (isset($product->getProductDetail->categoryDetail) ? $product->getProductDetail->categoryDetail->name : '') : '-' }}
                    </td>
                    <td class="table">
                        @php
                            $totalQuantity = 0;
                            $totalAmount = 0;
                        @endphp
                        @foreach ($product->orderItems as $item)
                            @php $totalQuantity = $totalQuantity + $item->total_quantity;
                                $totalAmount = $totalAmount + $item->total_amount;
                                $totalRevenue += $item->total_amount;
                            @endphp
                        @endforeach
                        {{ $totalQuantity }}
                    </td>
                    <td class="table">
                         {{ number_format($totalAmount, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Records Not Found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <h4> Total Revenue:- {{number_format($totalRevenue,2)}}</h4>
</body>

</html>
