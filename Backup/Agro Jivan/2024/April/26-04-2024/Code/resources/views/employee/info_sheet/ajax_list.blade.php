<table id="example" class="table table-custom" style="width:100%">
    <thead>
        <tr>
            <th>Sr. no</th>
            <th>Title</th>
            <th>Description</th>
            <th>InfoSheet</th>
            <th>Status</th>
            <th>Created At</th>
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
