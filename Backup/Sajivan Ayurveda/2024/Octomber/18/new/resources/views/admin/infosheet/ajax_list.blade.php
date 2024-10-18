<table class="table table-hover table-sm table-nowrap table-scrolling table-responsive mt-6 border" id="info_sheet_table">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Description</th>
            <th>Uploaded Incentive</th>
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
                    @if(collect($accesses)->where('menu_id', '6')->where('view',1)->first())
                        <a href="{{ asset('storage/'.$infosheet->info_sheet) }}" download><img src="{{ url('assets/img/message/pdf.png') }}" width="40px"></a> 
                    @endif
                </td>
                <td>{{ isset($infosheet->userDetail)?$infosheet->userDetail->name :"-" }}</td>
                <td>{{ Utility::convertDmyAMPMFormat($infosheet->created_at) }}</td>
                <td class="text-end">
                <div class="icon-td">
                    @if (collect($accesses)->where('menu_id', '6')->where('edit',1)->first())
                    <a href="{{ route('incentive.edit', $infosheet->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Incentive"><i class="fa-solid fa-pen-to-square"></i></a>
                    @endif
                    @if (collect($accesses)->where('menu_id', '6')->where('delete',1)->first())
                    <a href="javascript:void(0)" onclick="deleteInfoSheet({{$infosheet->id}})" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Incentive"><i class="fa-solid fa-trash-can"></i></a>
                    @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>