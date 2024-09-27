<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px">Batch ID</th>
            <th class="min-w-125px">Village Name</th>
            <th class="min-w-125px">Driver Name</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-100px">Created AT</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($batchList as $key=>$batch)
            <tr>
                <td class="align-middle">
                    <a href="{{ route('batch-view', $batch->id) }}" class="pre-agro-emp">{{ $batch->batch_id }}</a>
                    {!! DNS1D::getBarcodeHTML($batch->batch_id, 'C128', 0.8, 22) !!}
                </td>
                <td>
                    @foreach ($batch->batchItemDetail as $item)
                        @if (isset($item->orderDetail))
                            @if (isset($item->orderDetail->villageDetail))
                                {{ $item->orderDetail->villageDetail->village_name }},
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>{{ isset($batch->driverDetail) ? $batch->driverDetail->name : '' }}</td>
                <td>
                    @php
                        $text = 'On Delivery';
                        $class = 'primary';
                    @endphp

                    @if ($batch->status == '2')
                        @php
                            $text = 'Delivered';
                            $class = 'success';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $class }}">{{ $text }}</span>
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
