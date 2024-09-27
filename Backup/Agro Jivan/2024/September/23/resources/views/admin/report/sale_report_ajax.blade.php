<table class="table align-middle table-row-dashed fs-6 gy-5 report_table_border" id="kt_table_users">
    <thead class="report_table_border">
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px sr_no_column">Sr. no</th>
            <th class="min-w-100px">Product Name</th>
            <th class="min-w-100px">District Name</th>
            @if ($order_sub_district !== null)
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
        @forelse ($productVariantList as $key => $variant)
            @php
                $quantity = $variant->total_quantity;
                $amount = $variant->total_amount;
                $totalQuantity += $quantity;
                $totalAmount += $amount;
                $totalOrder += $variant->total_order_count;
            @endphp
            <tr>
                <td class="sr_no_column">{{ $productVariantList->firstItem() + $key }}</td>
                <td class="align-middle">
                    {{ $variant->sku_name }}<br />
                    @if ($variant->productDetail)
                        [{{ $variant->productDetail->sku_name }}]
                    @endif
                </td>
                <td>
                    @php
                        $districtNames = $variant->orderItems
                            ->pluck('orderDetail.districtDetail')
                            ->filter(function ($district) use ($order_district) {
                                if (isset($district) && $order_district !== null) {
                                    return $district->district == $order_district;
                                } else {
                                    return true;
                                }
                            })
                            ->pluck('district_name')
                            ->unique();
                    @endphp
                    @foreach ($districtNames as $districtName)
                        {{ $districtName }},
                        @if ($loop->iteration % 4 == 0)
                            <br>
                        @endif
                    @endforeach
                </td>
                @if ($order_sub_district !== null)
                    <td>
                        @php
                            $subDistrictNames = $variant->orderItems
                                ->pluck('orderDetail.subDistrictDetail')
                                ->filter(function ($subDistrict) use ($order_sub_district) {
                                    if (isset($subDistrict) && $order_sub_district !== null) {
                                        return $subDistrict->sub_district == $order_sub_district;
                                    }
                                })
                                ->pluck('sub_district_name')
                                ->unique();
                        @endphp
                        @foreach ($subDistrictNames as $subDistrictName)
                            {{ $subDistrictName }},
                            @if ($loop->iteration % 4 == 0)
                                <br>
                            @endif
                        @endforeach
                    </td>
                @endif
                <td class="fw-bold text-dark">
                    {{ $variant->total_order_count }}
                </td>
                <td class="fw-bold text-dark">
                    {{ $quantity }}
                </td>
                <td class="fw-bold text-dark">
                    ${{ number_format($amount, 2) }}
                </td>
                <td>
                    @php
                        $statusClasses = [
                            1 => ['warning', 'Pending Order'],
                            2 => ['success', 'Confirmed'],
                            3 => ['warning', 'On Delivery'],
                            4 => ['danger', 'Cancelled'],
                            5 => ['danger', 'Returned'],
                            6 => ['success', 'Delivered'],
                        ];
                        [$status, $text] = $statusClasses[$order_status];
                    @endphp
                    <span class="badge bg-{{ $status }}">{{ $text }}</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{isset($order_sub_district) ? 8 : 7}}" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
        <tr>
            <td colspan="{{isset($order_sub_district) ? 4 : 3}}" class="text-end fw-bold text-dark"><b>Total</b></td>
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
