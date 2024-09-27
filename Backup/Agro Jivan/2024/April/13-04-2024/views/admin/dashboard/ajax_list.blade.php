<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px">Batch ID</th>
            <th class="max-w-100px">Sub District Name</th>
            <th class="min-w-100px">Driver Name</th>
            <th class="min-w-100px">Car Number</th>
            <th class="min-w-100px">Return Order</th>
            <th class="min-w-100px">Pending Order</th>
            <th class="min-w-100px">Return Order</th>
            <th class="min-w-100px">Delivered Order</th>
            <th class="min-w-100px">Status</th>
            <th class="min-w-100px">Created At</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($batchList as $key=>$batch)
            <tr>
                <td class="align-middle pre-agro-emp">
                     {!! DNS1D::getBarcodeHTML($batch->batch_id, 'C128', 0.8, 22) !!}
                    @php $route = route('batch-view', $batch->id) @endphp
                    @if(Auth()->user() !== null && Auth()->user()->role_id == 1)
                        @php $route = route('admin-batch-view', $batch->id) @endphp
                    @endif
                    <a href="{{ $route }}" class="pre-agro-emp">{{ $batch->batch_id }}</a>
                </td>
                <td>
                    @foreach ($batch->batchItemDetail as $key=>$item)
                        @if (isset($item->orderDetail))
                             @if (isset($item->orderDetail->subDistrictDetail))
                                {{ $item->orderDetail->subDistrictDetail->sub_district_name }},
                                @if (($key + 1) % 4 === 0) 
                                    <br/>
                                @endif
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>{{ isset($batch->driverDetail) ? $batch->driverDetail->name : '' }}</td>
                <td>{{ $batch->car_no !== null ? $batch->car_no : '-' }}</td>
                <td>{{count($batch->batchItemDetail)}}</td>
                <td>{{$batch->pending_orders}}</td>
                <td>{{$batch->return_orders}}</td>
                <td>{{$batch->complete_orders}}</td>
                <td>
                    <span class="badge bg-primary">On Delivery</span>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($batch->created_at) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $batchList->links('pagination::bootstrap-5') }}
</div>
