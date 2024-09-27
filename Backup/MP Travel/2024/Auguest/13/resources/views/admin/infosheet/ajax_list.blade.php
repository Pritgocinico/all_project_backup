<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="info_sheet_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Status</th>
            <th>Uploaded Infosheet</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($infosheetList as $key=>$infosheet)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $infosheet->name }}</td>
                <td>
                    @php
                        $text = 'Active';
                        $color = 'success';
                        if ($infosheet->status == 0) {
                            $color = 'danger';
                            $text = 'Inactive';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $text }}</span>
                </td>
                <td> <a href="{{ asset('storage/'.$infosheet->info_sheet) }}" download><img src="{{ url('assets/img/user/file.png') }}" width="60px"></a> </td>
                <td>{{ isset($infosheet->userDetail)?$infosheet->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($infosheet->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '8')->first()->status == 2)
                    <a href="{{ route('info_sheet.edit', $infosheet->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Info Sheet"><i
                        class="bi bi-pencil-square"></i></a>
                    <a href="javascript:void(0)" onclick="deleteInfoSheet({{$infosheet->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Info Sheet"><i
                        class="bi bi-trash"></i></a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>