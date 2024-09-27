@php $totalRevenue = 0; @endphp
<table class="table align-middle table-row-dashed fs-6 gy-5 report_table_border" id="kt_table_users">
    <thead class="report_table_border">
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px sr_no_column">Sr. no</th>
            <th class="min-w-100px">Product Name</th>
            <th class="min-w-100px">Category</th>
            <th class="min-w-100px">Total Quantity</th>
            <th class="min-w-100px">Total Amount</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold report_table_border">
        @forelse ($productList as $key=>$product)
            <tr>
                <td class="sr_no_column">{{ $productList->firstItem() + $key }}</td>
                <td><a href="{{route('product.show',isset($product->getProductDetail)?$product->getProductDetail->id: "-")}}" class="pre-agro-emp">{{ isset($product->getProductDetail)?$product->getProductDetail->product_name: "-"}}[{{$product->sku_name}}]</a></td>
                <td>
                    {{ isset($product->getProductDetail)?isset($product->getProductDetail->categoryDetail)?$product->getProductDetail->categoryDetail->name:"": "-"}}
                </td>
                <td class="fw-bold text-dark">
                    @php $totalQuantity = 0;$totalAmount = 0; @endphp
                    @foreach ($product->orderItems as $item)
                        @php $totalQuantity = $totalQuantity+$item->total_quantity;
                            $totalAmount = $totalAmount+$item->total_amount; 
                            $totalRevenue += $item->total_amount; 
                        @endphp
                    @endforeach
                    <b>{{$totalQuantity}}</b>
                </td>
                <td class="fw-bold text-dark">
                    &#x20B9; {{number_format($totalAmount,2)}}</b>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Records Not Found.</td>
               
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $productList->links('pagination::bootstrap-5') }}
</div>
<h4>Total Amount:- &#x20B9; {{number_format($totalRevenue,2)}}</h4>
