<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Designation PDF</title>
    <style>
        .table {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
    <img alt="Logo" src="{{ $mainLogoUrl }}"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Designation List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Designation Name</th>
            <th class="table">Department Name</th>
            <th class="table">Status</th>
            <th class="table">Created By</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($designationList as $key=>$designation)
                <tr>
                    <td class="table">{{ $key +1  }}</td>
                    <td class="table">{{$designation->name}}</td>
                    <td class="table">{{ isset($designation->departmentDetail) ? $designation->departmentDetail->name : '' }}</td>
                    <td class="table">
                        @php
                            $text = 'Active';
                            $color = 'success';
                            if ($designation->status == 0) {
                                $color = 'danger';
                                $text = 'Inactive';
                            }
                            @endphp
                    <span class="badge bg-{{ $color }} w-120">{{ $text }}</span></td>
                    <td class="table">{{ isset($designation->userDetail)?$designation->userDetail->name :"-" }}</td>
                    <td class="table">{{Utility::convertDmyAMPMFormat($designation->created_at)}}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-center" colspan="5">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
