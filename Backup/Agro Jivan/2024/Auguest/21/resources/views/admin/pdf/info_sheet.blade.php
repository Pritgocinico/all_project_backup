<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Info Sheet PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        type="text/css" />
    <style>
        .table {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
    <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Info Sheet List</h5>

    <table id="example" class="table" style="width:100%">
        <thead>
            <tr>
                <th class="table">Title</th>
                <th class="table">Description</th>
                <th class="table">InfoSheet</th>
                <th class="table">Status</th>
                <th class="table">Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($infoSheetList as $info)
                <tr>
                    <td class="table">{{ $info->title }}</td>
                    <td class="table">{{ $info->description }}</td>
                    <td class="table">
                        <a href="{{ url('/') }}/public/assets/media/{{ $info->info_sheet }}" download>
                            <img src="{{ url('/') }}/public/assets/media/png_images/file.png" width="60px">
                        </a>
                    </td>
                    <td class="table">
                        @php $text = 'Inactive'; $class = 'danger'; @endphp
                        @if ($info->status == 1)
                            @php $text = 'Active'; $class = 'success'; @endphp
                        @endif
                        <div class="badge badge-light-{{ $class }} fw-bold">
                            {{ $text }}</div>
                    </td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($info->created_at) }}</td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="5">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
