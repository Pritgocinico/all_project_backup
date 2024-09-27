<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category PDF</title>
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
    <h5 style="text-align: center">{{ $data['batch_id'] }}</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th class="table">Product</th>
                <th class="table">Total Order</th>
                <th class="table">Quantity</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 ">
            @php $product = "" @endphp
            @php $quantity = 0; @endphp
            @foreach ($data as $item)
                <tr>
                    @if ($item !== $data['batch_id'])
                        @php $quantity = $item['quantity'] + $item['quantity']; @endphp
                        @if ($item['product_id'] !== $product)
                            <td class="table">{{ $item['product_name'] }}</td>
                            <td class="table">{{ $item['total_order'] }}</td>
                            <td class="table">{{ $quantity }}</td>
                        @endif
                        @php $product = $item['product_id'] @endphp
                    @endif
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
