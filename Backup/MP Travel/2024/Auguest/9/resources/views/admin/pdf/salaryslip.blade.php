<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Salary Slip PDF</title>
    <style>
        .table {
            border: 1px solid black;
            border-collapse: collapse;
        }
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Jost:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300..700&display=swap');

.cursive {
    font-family: Cambria, Georgia, serif;
    font-style: italic;
}
    </style>
</head>

<body>
    <div style="text-align: center">
    <img alt="Logo" src="https://trustedstaging.com/mpgroup-crm/storage/logo/Dlp1T9XBXCGtcB79Q0f92ybOv5X4lqcrU219iJLn.png"
                    class="theme-light-show" style="width: 150px;" />
                </div>
    <h5 style="text-align: center">Salary Slip List</h5>

    <table class="table">
        <thead>
            <th class="table">#</th>
            <th class="table">Employee Name</th>
            <th class="table">Month</th>
            <th class="table">Year</th>
            <th class="table">Working Days</th>
            <th class="table">Present Days</th>
            <th class="table">Payable Salary</th>
            <th class="table">Leave</th>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse ($salarySlipList as $key=>$salary)
                <tr>
                    <td class="table">{{ $i }}</td>
                    <td class="table">{{ $salary->employeeDetail->name }}</td>
                    <td class="table">{{ $salary->month }}</td>
                    <td class="table">{{ $salary->year }}</td>
                    <td class="table">{{ $salary->total_working_days }}</td>
                    <td class="table">{{ $salary->present_days }}</td>
                    <td class="table">{{ $salary->payable_salary }}</td>
                    <td class="table">{{ $salary->leave }}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @empty
                <tr>
                    <td style="text-center" colspan="8">No Data Available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
