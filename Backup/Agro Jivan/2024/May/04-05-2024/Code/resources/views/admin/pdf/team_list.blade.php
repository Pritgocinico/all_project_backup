<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave PDF</title>
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
        <img alt="Logo" src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" class="theme-light-show"
            style="width: 150px;" />
    </div>
    <h5 style="text-align: center">Team List</h5>

    <table class="table" id="kt_table_users">
        <thead>
            <tr class="table">
                <th class="table">Team Id</th>
                <th class="table">Manager Name</th>
                <th class="table">Team Size</th>
                <th class="table">Created AT</th>
            </tr>
        </thead>
        <tbody class="text-gray-600">
            @forelse ($teamList as $key=>$team)
                <tr>
                    <td class="table">{{ $team->team_id }}</td>
                    <td class="table">{{ isset($team->managerDetail) ? $team->managerDetail->name : '-' }}</td>
                    <td class="table">{{ isset($team->teamMember) ? count($team->teamMember) : '0' }}</td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($team->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
