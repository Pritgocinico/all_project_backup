<table class="table table-hover table-sm table-nowrap table-responsive mt-6 border" id="info_sheet_table">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Uploaded Infosheet</th>
            <th>Created By</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($infosheetList as $key=>$infosheet)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $infosheet->name }}</td>
                @php $subject = $infosheet->description @endphp
                @if (strlen($infosheet->description) > 30)
                    @php $subject = substr($infosheet->description, 0, 30) . '...'; @endphp
                @endif
                <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $infosheet->description }}">
                    {{ $subject }}</td>
                </td>
                <td>
                    @php
                        $text = 'Active';
                        $color = 'success';
                        if ($infosheet->status == 0) {
                            $color = 'danger';
                            $text = 'Inactive';
                        }
                    @endphp
                    <span class="badge bg-{{ $color }} w-120">{{ $text }}</span>
                </td>
                <td> <a href="{{ asset('storage/'.$infosheet->info_sheet) }}" download><img src="{{ url('assets/img/message/pdf.png') }}" width="40px"></a> </td>
                <td>{{ isset($infosheet->userDetail)?$infosheet->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($infosheet->created_at) }}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '6')->first()->status == 2)
                    <a href="{{ route('info_sheet.edit', $infosheet->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Info Sheet"><i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="javascript:void(0)" onclick="deleteInfoSheet({{$infosheet->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Info Sheet"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>