<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .table-employee tr th{
    text-align: start !important;
    width:125px;
    
}
.table-employee tr td{
    margin-left:20px;

}
    </style>
</head>

<body style="width: 700px;">
    <div style="border: 1px solid black; padding: 15px;">
        <div style="clear:both;"></div>
        <div style="margin-bottom: 15px;">
            <img src="https://portal.agrojivan.com/public/assets/media/svg/AgroJivanLogoDash.png" style="float:left; margin-top:15px;" width="25%" alt="logo">
            <h2 style="float:right" >Payslip for the month of {{ $data['salaryDetail']->month }} {{ date('Y') }}</h2>
        </div>
        <div style="clear:both;"></div>
        <div style="margin-bottom: 10px;">
            


            <table style="width:100%">
          
            
          <tr>
              <td style="width:20%"><b>Employee Code :</b></td>
              <td>{{ $data['employee']->employee_code }}</td>
          </tr>
          <tr>
              <td style="width:20%"><b>Employee Name :</b></td>
              <td>{{ $data['employee']->name }}</td>
          </tr>
          <tr>
              <td style="width:20%"><b>Department :</b></td>
              <td>@foreach ($data['employee']->departmentDetail as $department)
                       {{ $department->departmentNameDetail->department_name }},
                          @endforeach</td>
          </tr>
          <tr>
              <td style="width:20%"><b>Date Of Joining:</b></td>
              <td>{{ $data['employee']->join_date }}</td>
          </tr>

  
 
      </table>
            <table class="table table-hover" border="2" style="border-collapse: collapse; margin: 20px 0px;">
                <tbody>
                    <tr>
                        <th style="width: 20%; padding: 5px 24px;   ">Earnings</th>
                        <th style="width: 15%; padding: 5px 24px;    ">Standard</th>
                        <th style="width: 25%; padding: 5px 24px;     ">Actual Amount</th>
                        <th style="width: 25%; padding: 5px 24px;     ">Deductions</th>
                        <th style="width: 15%; padding: 5px 24px;     ">Actual Amount</th>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     ">Basic pay</td>
                        <td style="padding: 5px 24px;    "></td>
                        <td style="padding: 5px 24px;    ">{{ $data['basicPay'] }}</td>
                        <td style="padding: 5px 24px;    ">PF</td>
                        <td style="padding: 5px 24px;    ">{{ $data['pfCount'] }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     ">HRA</td>
                        <td style="padding: 5px 24px;     "></td>
                        <td style="padding: 5px 24px;    ">{{ $data['hraCalculate'] }}</td>
                        <td style="padding: 5px 24px;    ">Professional Tax</td>
                        <td style="padding: 5px 24px;    ">{{ $data['salaryDetail']->pt }}</td>
                    <tr>
                        <td style="padding: 5px 24px;     ">Medical Allowance</td>
                        <td style="padding: 5px 24px;     "></td>
                        <td style="padding: 5px 24px;    ">{{ $data['medicalAllowance'] }}</td>
                        <td style="padding: 5px 24px;    "></td>
                        <td style="padding: 5px 24px;    "></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     ">Conveyance Allowance</td>
                        <td style="padding: 5px 24px;     "></td>
                        <td style="padding: 5px 24px;    ">{{ $data['conveyanceAllowance'] }}</td>
                        <td style="padding: 5px 24px;    "></td>
                        <td style="padding: 5px 24px;    "></td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 24px;     "><b>Total Earnings</b></td>
                        <td style="padding: 5px 24px;     "></td>
                        <td style="padding: 5px 24px;    ">{{ number_format($data['salaryDetail']->payable_salary, 2) }}
                        </td>
                        <td style="padding: 5px 24px;    "><b>Total Deduction</b></td>
                        <td style="padding: 5px 24px;    ">
                            {{ number_format($data['pfCount'] + $data['salaryDetail']->pt, 2) }}</td>
                    </tr>

                </tbody>
            </table>
            <div style="display:flex; gap: 15px;">
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">CURRENCY :
                    <span style="margin:3px 0px">Rupiees</span>
                </p>

            </div>
            <div style="display:flex; gap: 15px;">
                @php $salaryAmount = $data['salaryDetail']->payable_salary-($data['pfCount'] + $data['salaryDetail']->pt); @endphp
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">Applicant
                    trainee stipend : <span style="margin:3px 0px"> {{ number_format($salaryAmount, 2) }}</span></p>

            </div>
            <div style="display:flex; gap: 15px;">
                @php
                    $amountInWords = ucwords(
                        (new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($salaryAmount),
                    );
                @endphp
                <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase;  ">AMOUNT IN
                    WORDS : <span style="margin:3px 0px">{{ $amountInWords }}</span></p>

            </div>

        </div>
</body>

</html>
