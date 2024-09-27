<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-100px">Batch ID</th>
            <th class="min-w-125px">Sub District Name</th>
            <th class="min-w-125px">Driver Name</th>
            <th class="min-w-125px">Car Number</th>
            <th class="min-w-125px">Total Order</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-100px">Created AT</th>
            @if (Auth()->user() !== null && Auth()->user()->role_id == 8)
                <th class="min-w-100px">Action</th>
            @endif
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
                            @endif
                            @if (($key + 1) % 4 === 0) 
                                <br/>
                            @endif
                        @endif
                    @endforeach
                </td>
                <td>{{ isset($batch->driverDetail) ? $batch->driverDetail->name : '' }}</td>
                <td>{{ $batch->car_no !== null ? $batch->car_no : '-' }}</td>
                <td>{{count($batch->batchItemDetail)}}</td>
                <td>
                    @php
                        $text = 'On Delivery';
                        $class = 'primary';
                    @endphp

                    @if ($batch->status == '2')
                        @php
                            $text = 'Deliverd';
                            $class = 'success';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $class }}">{{ $text }}</span>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($batch->created_at) }}</td>
                @if (Auth()->user() !== null && Auth()->user()->role_id == 8)
                    <td>
                        <a class="btn btn-icon btn-primary w-30px h-30px me-3" href="#" onclick="viewBacthDetail({{ $batch->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="Edit Employee"
                            data-bs-original-title="View Batch Item"><i class="fas fa-eye"></i></a>
                        <a class="btn btn-icon btn-info w-30px h-30px me-3" href="#"
                            onclick="updateBatchStatus({{ $batch->id }})" data-bs-toggle="tooltip"
                            data-bs-placement="top" aria-label="Edit Employee"
                            data-bs-original-title="Mark As Delivered" aria-describedby="tooltip553274">
                            <i class="fas fa-shipping-fast"></i>
                        </a>
                    </td>
                @endif
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
