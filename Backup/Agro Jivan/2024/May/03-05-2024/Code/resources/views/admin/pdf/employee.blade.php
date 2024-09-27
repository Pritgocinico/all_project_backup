<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employee PDF</title>
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
    <h5 style="text-align: center">Employee List</h5>

    <table class="table">
        <thead>
            <th class="table">Name</th>
            <th class="table">Email</th>
            <th class="table">Phone Number</th>
            <th class="table">Role</th>
            <th class="table">Status</th>
            <th class="table">Created At</th>
        </thead>
        <tbody>
            @forelse ($employeeList as $employee)
                <tr>
                    <td class="table">{{$employee->name}}</td>
                    <td class="table">{{$employee->email}}</td>
                    <td class="table">{{$employee->phone_number}}</td>
                    <td class="table">{{isset($employee->roleDetail)?$employee->roleDetail->name:""}}</td>
                    <td class="table">@if($employee->status == 1){{"Active"}} @else {{ "Inactive" }}@endif</td>
                    <td class="table">{{ Utility::convertDmyWith12HourFormat($employee->created_at) }}</td>
                </tr>
            @empty
                <tr>
                    <td style="text-center" colspan="6">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
