<table class="table table-hover table-striped table-sm table-nowrap table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Status</th>
            <th>Uploaded Infosheet</th>
            <th>Created At</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($infosheetList as $key=>$infosheet)
            <tr>
                <td>{{ $infosheetList->firstItem() + $key }}</td>
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
                <td>{{Utility::convertDmyAMPMFormat($infosheet->created_at)}}</td>
                <td class="text-end">
                    @if (collect($accesses)->where('menu_id', '8')->first()->status == 2)
                        <div class="dropdown"><a class="text-bg-dark rounded-circle" href="#"
                                role="button" data-bs-toggle="dropdown" aria-haspopup="false"
                                aria-expanded="false"><button type="button"
                                    class="btn btn-sm btn-square btn-neutral w-rem-6 h-rem-6"><i
                                        class="bi bi-three-dots"></i></button></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('info_sheet.edit', $infosheet->id) }}"><i
                                        class="bi bi-pencil me-3"></i>Edit Infosheet</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)"
                                    onclick="deleteInfoSheet({{ $infosheet->id }})"><i
                                        class="bi bi-trash me-3"></i>Delete Infosheet </a>
                            </div>
                        </div>
                    @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Data Available.</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-end me-2 mt-2">
    {{ $infosheetList->links() }}
</div>