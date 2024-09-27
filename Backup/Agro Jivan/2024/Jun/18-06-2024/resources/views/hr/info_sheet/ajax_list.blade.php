<table id="example" class="table align-middle table-row-dashed fs-6 gy-5" style="width:100%">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th>Sr. no</th>
            <th class="min-w-125px">Title</th>
            <th class="min-w-125px">Description</th>
            <th class="min-w-125px">InfoSheet</th>
            <th class="min-w-125px">Status</th>
            <th class="min-w-125px">Created At</th>
            <th class="min-w-125px">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($infoSheetList as $key=>$info)
            <tr>
                <td>{{ $infoSheetList->firstItem() + $key }}</td>
                <td>{{ $info->title }}</td>
                <td>{{ $info->description }}</td>
                <td>
                    <a href="{{ url('/') }}/public/assets/upload/{{ $info->info_sheet }}" download>
                        <img src="{{ url('/') }}/public/assets/media/png_images/file.png" width="60px">
                    </a>
                </td>
                <td>
                    @php $text = 'Inactive'; $class = 'danger'; @endphp
                    @if ($info->status == 1)
                        @php $text = 'Active'; $class = 'success'; @endphp
                    @endif
                    <div class="badge badge-light-{{ $class }} fw-bold">
                        {{ $text }}</div>
                </td>
                <td>{{ Utility::convertDmyWith12HourFormat($info->created_at) }}</td>
                <td>
                    @if (Permission::checkPermission('info-sheet-edit'))
                    <a class="btn btn-icon btn-info w-30px h-30px me-3" href="{{ route('hr-info-sheet.edit', $info->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit Info Sheet" data-bs-original-title="Edit Info Sheet" aria-describedby="tooltip553274">
                        <i class="fa-solid fa-edit"></i>
                    </a>
                    @endif
                    @if (Permission::checkPermission('info-sheet-delete'))
                    <a class="btn btn-icon btn-danger w-30px h-30px me-3" href="#" onclick="deleteInfoSheet({{ $info->id }})" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete Info Sheet" data-bs-original-title="Delete Info Sheet">
                        <i class="fa-solid fa-trash"></i>
                    </a> 
                    @endif
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="5">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end">
    {{ $infoSheetList->links('pagination::bootstrap-5') }}
</div>
