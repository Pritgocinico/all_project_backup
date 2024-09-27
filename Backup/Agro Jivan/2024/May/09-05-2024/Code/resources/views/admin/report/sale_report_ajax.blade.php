<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
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
    <tbody class="text-gray-600 fw-semibold">
        @php
            $totalQuantity = 0;
            $totalAmount = 0;
            $totalOrder = 0;
            @endphp
        @forelse ($productVariantList as $key=>$variant)
            <tr>
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
                                $order = $order +1;
                                $totalOrder = $totalOrder +1;
                            @endphp
                        @endif
                    @endforeach
                </td>
                @if($order_sub_district !== null)
                <td>
                    @php
                        $subDistrictIdArray = [];
                        $count = 0;
                        $order = 0;
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
                <td>
                    {{$order}}
                </td>
                <td>
                    @php $quantity = 0;$amount = 0;@endphp
                    @foreach ($variant->orderItems as $key => $item)
                        @php $quantity = $quantity + $item->quantity;@endphp
                        @php $totalQuantity = $totalQuantity + $item->quantity;@endphp
                        @php $amount = $amount + $item->amount;@endphp
                        @php $totalAmount = $totalAmount + $item->amount;@endphp
                    @endforeach
                    {{ $quantity }}
                </td>

                <td>
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
            <td colspan="2" class="text-end"><b>Total</b></td>
            <td>{{ $totalOrder }}</td>
            <td>{{ $totalQuantity }}</td>
            <td>${{ number_format($totalAmount, 2) }}</td>
        </tr>
    </tbody>
</table>
<div class="d-flex justify-content-end">
    @if ($productVariantList !== [])
        {{ $productVariantList->links('pagination::bootstrap-5') }}
    @endif
</div>
