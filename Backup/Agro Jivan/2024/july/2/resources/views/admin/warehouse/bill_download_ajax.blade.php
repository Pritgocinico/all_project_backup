<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-100px">Batch ID</th>
            <th class="min-w-125px">Sub District Name</th>
            <th class="min-w-125px">Driver Name</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-100px">Created AT</th>
            <th class="min-w-100px">Action</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @forelse ($batchList as $key=>$batch)
            <tr>
                <td>{{ $batchList->firstItem() + $key }}</td>
                <td class="align-middle">
                    {!! DNS1D::getBarcodeHTML($batch->batch_id, 'C128', 0.8, 22) !!}
                    <a href="{{ route('batch-view', $batch->id) }}" class="pre-agro-emp">{{ $batch->batch_id }}</a>
                </td>
                <td>
                    @php $subDistrictIdArray = [];
                        $count = 0;
                    @endphp
                    @foreach ($batch->batchItemDetail as $key => $item)
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
                            @php array_push($subDistrictIdArray,$item->orderDetail->sub_district) @endphp
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
                            $text = 'Deliverd';
                            $class = 'success';
                        @endphp
                    @endif
                    <span class="badge bg-{{ $class }}">{{ $text }}</span>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($batch->created_at) }}</td>
                <td>
                    <a class="btn btn-icon btn-info w-30px h-30px me-3"
                        href="{{ route('generate-batch-invoice-pdf', $batch->id) }}" data-bs-toggle="tooltip"
                        data-bs-placement="top" title="Generate Invoice">
                        <i class="fas fa-file-invoice"></i></a>
                </td>
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
