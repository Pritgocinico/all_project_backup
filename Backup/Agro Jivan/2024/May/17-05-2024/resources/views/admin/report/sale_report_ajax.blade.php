<table class="table align-middle table-row-dashed fs-6 gy-5 report_table_border" id="kt_table_users">
    <thead class="report_table_border">
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px sr_no_column">Sr. no</th>
            <th class="min-w-100px">Product Name</th>
            <th class="min-w-100px">District Name</th>
            @if($order_sub_district !== null)
                <th class="min-w-125px">Sub District Name</th>
            @endif
            <th class="min-w-125px">Order</th>
            <th class="min-w-125px">Quantity</th>
            <th class="min-w-125px">Amount</th>
            <th class="min-w-100px">Status</th>
        </tr>
    </thead> 
    <tbody class="text-gray-600 fw-semibold report_table_border">
        @php
            $totalQuantity = 0;
            $totalAmount = 0;
            $totalOrder = 0;
            @endphp
        @forelse ($productVariantList as $key=>$variant)
            <tr>
                <td class="sr_no_column">{{ $productVariantList->firstItem() + $key }}</td>
                <td class="align-middle">
                    {{ $variant->sku_name }}<br /> {{ '[' . $variant->productDetail->sku_name . ']' }}
                </td>
                <td>
                    @php
                        $districtIdArray = [];
                        $count = 0;
                        $order = 0;
                    @endphp
                    @foreach ($variant->orderItems as $key => $item)
                        @if (isset($item->orderDetail))
                            @if (isset($item->orderDetail->districtDetail))
                                @if (!in_array($item->orderDetail->district, $districtIdArray))
                                    {{ $item->orderDetail->districtDetail->district_name }},
                                    @php $count++; @endphp
                                    @if ($count == 4)
                                        <br>
                                        @php
                                            $count = 0;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                            @php 
                                array_push($districtIdArray,$item->orderDetail->district); 
                                
                            @endphp
                        @endif
                    @endforeach
                </td>
                @if($order_sub_district !== null)
                <td>
                    @php
                        $subDistrictIdArray = [];
                        $count = 0;
                    @endphp
                    @foreach ($variant->orderItems as $key => $item)
                        @if (isset($item->orderDetail))
                            @if (isset($item->orderDetail->subDistrictDetail))
                                @if (!in_array($item->orderDetail->sub_district, $subDistrictIdArray))
                                    {{ $item->orderDetail->subDistrictDetail->sub_district_name }},
                                    @php $count++; @endphp
                                    @if ($count == 4)
                                        <br>
                                        @php
                                            $count = 0;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                            @php 
                                array_push($subDistrictIdArray,$item->orderDetail->sub_district); 
                            @endphp
                        @endif
                    @endforeach
                </td>
                @endif
                <td class="fw-bold text-dark">
                    @php 
                        $totalOrder = $totalOrder + count($variant->orderItems);
                    @endphp
                    {{count($variant->orderItems)}}
                </td>
                <td class="fw-bold text-dark">
                    @php $quantity = 0;$amount = 0;@endphp
                    @foreach ($variant->orderItems as $key => $item)
                        @php $quantity = $quantity + $item->quantity;@endphp
                        @php $totalQuantity = $totalQuantity + $item->quantity;@endphp
                        @php $amount = $amount + $item->amount;@endphp
                        @php $totalAmount = $totalAmount + $item->amount;@endphp
                    @endforeach
                    {{ $quantity }}
                </td>

                <td class="fw-bold text-dark">
                    ${{ number_format($amount, 2) }}
                </td>

                <td>
                    @if ($order_status == 1)
                        @php
                            $status = 'warning';
                            $text = 'Pending Order';
                        @endphp
                    @endif
                    @if ($order_status == 2)
                        @php
                            $status = 'success';
                            $text = 'Confirmed';
                        @endphp
                    @endif
                    @if ($order_status == 3)
                        @php
                            $status = 'warning';
                            $text = 'On Delivery';
                        @endphp
                    @endif
                    @if ($order_status == 4)
                        @php
                            $status = 'danger';
                            $text = 'Cancelled';
                        @endphp
                    @endif
                    @if ($order_status == 5)
                        @php
                            $status = 'danger';
                            $text = 'Returned';
                        @endphp
                    @endif
                    @if ($order_status == 6)
                        @php
                            $status = 'success';
                            $text = 'Delivered';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="3" class="text-end fw-bold text-dark"><b>Total</b></td>
            <td class="fw-bold text-dark">{{ $totalOrder }}</td>
            <td class="fw-bold text-dark">{{ $totalQuantity }}</td>
            <td class="fw-bold text-dark">${{ number_format($totalAmount, 2) }}</td>
        </tr>
    </tbody>
</table>
<div class="d-flex justify-content-end">
    @if ($productVariantList !== [])
        {{ $productVariantList->links('pagination::bootstrap-5') }}
    @endif
</div>
