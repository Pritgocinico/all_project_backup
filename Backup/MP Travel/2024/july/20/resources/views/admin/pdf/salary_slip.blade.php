<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .table-employee tr th {
            text-align: start !important;
            width: auto;
        }

        .table-employee tr td {
            margin-left: 20px;

        }
    </style>
</head>

<body style="width: 700px;">
    <div style="border: 1px solid black; padding: 15px;">
        <div style="clear:both;"></div>
        <div style="margin-bottom: 15px;">
            @php $image = asset('assets/img/login/mpfs - R.jpeg');
            if(isset($settings)){
                $image = asset('storage/' . $settings->logo);
            }
            @endphp
            <img src="{{ $image }}" style="float:left; margin-top:15px;" width="25%"
                alt="logo">
            <h2 style="float:right">Payslip for the month of {{ $salaryDetail->month }} {{ $salaryDetail->year }}</h2>
        </div>
        <div style="clear:both;"></div>
        <div style="margin-bottom: 10px;">
            <table style="width:100%">
                <tr>
                    <td style="width:20%"><b>Employee Name :</b></td>
                    <td>{{$employee->name }}</td>
                </tr>
                <tr>
                    <td style="width:20%"><b>Department Name:</b></td>
                    <td>
                        {{ isset($employee->departmentDetail) ? $employee->departmentDetail->name :""}}
                    </td>
                </tr>
                <tr>
                    <td style="width:20%"><b>Designation:</b></td>
                    <td>{{ isset($employee->designationDetail) ? $employee->designationDetail->name :""}}</td>
                </tr>
                <tr>
                    <td style="width:20%"><b>Salary Before Deduction:</b></td>
                    <td>{{ number_format($salaryDetail->payable_salary,2) }}</td>
                </tr>
            </table>
            <table class="table table-hover" border="2" style="border-collapse: collapse; margin: 20px 0px;">
                <tbody>
                    <tr>
                        <th>Deductions Type</th>
                        <th style="width: 15%; padding: 5px 24px;     ">Actual Amount</th>
                    </tr>
                    @php $totalDeductionAmount = 0; @endphp
                    @foreach ($deductionDetail as $deduction)
                        @php $totalDeductionAmount += $deduction->deduction_amount; @endphp
                        <tr>
                            <th>{{$deduction->deduction_type}}</th>
                            <td style="width: 15%; padding: 5px 24px;">{{number_format($deduction->deduction_amount,2)}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding: 5px 24px;     "><b>Total Payble Salary</b></td>
                        <td style="padding: 5px 24px;     ">{{ number_format($salaryDetail->payable_salary,2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     "><b>Total Deduction Amount</b></td>
                        <td style="padding: 5px 24px;     ">{{ number_format($totalDeductionAmount,2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     "><b>Total Salary Amount</b></td>
                        <td style="padding: 5px 24px;     ">
                            @php $totalAmount = $salaryDetail->payable_salary - $totalDeductionAmount; @endphp
                            {{ number_format($totalAmount,2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="display:flex; gap: 15px;">
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">CURRENCY :
                    <span style="margin:3px 0px">Rupiees</span>
                </p>
            </div>
            <div style="display:flex; gap: 15px;">
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">Applicant
                    trainee stipend : <span style="margin:3px 0px"> {{ number_format($totalAmount, 2) }}</span></p>

            </div>
            <div style="display:flex; gap: 15px;">
                @php
                    $amountInWords = ucwords(
                        (new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($totalAmount),
                    );
                @endphp
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase;  ">AMOUNT IN
                    WORDS : <span style="margin:3px 0px">{{ $amountInWords }}</span></p>

            </div>
        </div>
    </div>
</body>

</html>
