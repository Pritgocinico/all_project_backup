<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Jost:ital,wght@0,100..900;1,100..900&family=Space+Grotesk:wght@300..700&display=swap"
        rel="stylesheet">
    <title>Salary Slip</title>
    <style>
        body {
            font-family: "DM Sans", sans-serif;
        }

        table,
        tr,
        td,
        th {
            border-collapse: collapse;
            border: 1px solid black;
        }

        table {
            width: 100% !important;
        }

        th,
        td {
            text-align: start;
            vertical-align: baseline;
            padding-left: 5px;
            height: 50px;
        }
    </style>
</head>

<body style="width: auto;">
    <table>
        <tr>
            <th colspan="8" align="center" style="text-align: center; height: 0px;">
                Salary Register for the Month of :{{ ucfirst($salaryDetail->month) }}, {{ $salaryDetail->year }}
            </th>
        </tr>
        <tr>
            <th colspan="6">
                Company Name: MP WORLD TRAVEL
            </th>
            <td colspan="2">
                Date: {{ date('d/m/Y') }}
            </td>
        </tr>
        <tr>
            <th style="max-width: 10%;">
                Emp.Code
            </th>
            <td colspan="2" style="max-width: 20%;">
                {{ $employee->user_code }}
            </td>
            <th style="max-width: 10%;">
                Emp.Name
            </th>
            <td colspan="2" style="max-width: 40%;">
                {{ $employee->name }}
            </td>
            <td colspan="2" style="max-width: 20%;">
                Dept:- {{ $departmentDetail->name ?? '-' }}
            </td>
        </tr>
        <tr>
            <th style="max-width: 10%;">
                Designation
            </th>
            <td colspan="3" style="max-width: 10%;">
                {{ $designationDetail->name ?? '-' }}
            </td>
            <td colspan="4" style="max-width: 10%;">
                <b>Shift Detail:- </b>
                @isset($shiftDetail)
                    {{ $shiftDetail->shift_name . '( ' . Utility::convertHIAFormat($shiftDetail->shift_start_time) . ' - ' . Utility::convertHIAFormat($shiftDetail->shift_end_time) . ' )' }}
                @endisset
            </td>
        </tr>
        <tr>
            <th colspan="2" style="text-align: center;">Attendance Detail</th>
            <th colspan="2">Amt. Payable</th>
            <th colspan="2">Other Deduction</th>
            <th colspan="2">PF & ESI Deduction</th>
        </tr>
        <tr>
            <th style="max-width: 10%;">Present</th>
            <td style="width: 10%;">{{ $salaryDetail->present_days }}</td>
            <th style="max-width: 10%;">BASIC</th>
            <td style="width: 10%;">{{ $employee->basic_amount ?? 0 }}</td>
            <th style="max-width: 10%;">Other</th>
            <td style="width: 10%;">0</td>
            <th style="max-width: 10%;">PF</th>
            <td style="width: 10%;">0</td>
        </tr>
        <tr>
            <th style="max-width: 10%;">Absent</th>
            <td style="width: 10%;">{{ $salaryDetail->absent_day }}</td>
            <th style="max-width: 10%;">HRA</th>
            <td style="width: 10%;">{{ $employee->hra_amount ?? 0 }}</td>
            <th style="max-width: 10%;"></th>
            <td style="width: 10%;"></td>
            <th style="max-width: 10%;">Esi</th>
            <td style="width: 10%;">0</td>
        </tr>
        <tr>
            <th>Holiday</th>
            <td>{{ $salaryDetail->holiday }}</td>
            <th>ALLOW</th>
            <td>{{ $employee->allowance_amount ?? 0 }}</td>
            <th></th>
            <td></td>
            <th></th>
            <td></td>
        </tr>
        <tr>
            <th>Leave</th>
            <td>{{ $salaryDetail->leave }}</td>
            <th>PETROL</th>
            <td>{{ $employee->allowance_amount ?? 0 }}</td>
            <th></th>
            <td></td>
            <th></th>
            <td></td>
        </tr>
        <tr>
            <th>Week Off</th>
            <td>{{ $salaryDetail->week_off }}</td>
            <td style="1px solid black"></td>
            <td style="1px solid black"></td>
            <td style="1px solid black"></td>
            <td style="1px solid black"></td>
            <td style="1px solid black"></td>
            <td style="1px solid black"></td>
        </tr>
        @php $totalDeduction  = 0; @endphp
        @forelse ($deductionDetail as $key => $deduct)
            <tr>
                @if ($key == 0)
                    <th style="1px solid black">Paid Days</th>
                    <td style="1px solid black">{{ $salaryDetail->present_days + $salaryDetail->week_off }}</td>
                @else
                    <th style="1px solid black"></th>
                    <td style="1px solid black"></td>
                @endif
                <th style="1px solid black"></th>
                <th style="1px solid black"></th>
                <th style="1px solid black">{{ $deduct->deduction_type }}</th>
                <td style="1px solid black">{{ $deduct->deduction_amount ?? 0 }}</td>
                <th style="1px solid black"></th>
                <td style="1px solid black"></td>
                @php $totalDeduction += $deduct->deduction_amount ??0; @endphp
            </tr>
        @empty
            <tr>
                <th style="1px solid black">Paid Days</th>
                <td style="1px solid black">{{ $salaryDetail->paid_day }}</td>
                <th style="1px solid black"></th>
                <th style="1px solid black"></th>
                <th style="1px solid black"></th>
                <td style="1px solid black"></td>
                <th style="1px solid black"></th>
                <td style="1px solid black"></td>
            </tr>
        @endforelse
        <tr>
            <th>Work,Ot Hours</th>
            <td>{{ $salaryDetail->total_over_time }}</td>
            <th>OT Amount</th>
            <td>{{ $salaryDetail->over_time_amount }}</td>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: end;">Salary Amount</th>
            <td>{{ $salaryDetail->per_day_salary + $salaryDetail->over_time_amount }}</td>
            <th colspan="2" style="text-align: end;">Total Deduction</th>
            <td colspan="2" style="text-align: end;">{{ $totalDeduction }}</td>
        </tr>
        <tr>
            @php
                $netSalary = ($salaryDetail->per_day_salary + $salaryDetail->over_time_amount) - $totalDeduction;
                $amountInWords = ucwords((new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($netSalary));
            @endphp
            <td colspan="4">Total Earning In Words:- ({{ $amountInWords }})</td>
            <th colspan="2" style="text-align: end;">Total Payble Salary</th>
            <td colspan="2" style="text-align: end;">{{ $netSalary }}</td>
        </tr>
    </table>
</body>

</html>
