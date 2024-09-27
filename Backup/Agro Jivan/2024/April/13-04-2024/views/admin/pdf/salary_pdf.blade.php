<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
{{-- {{dd($data)}} --}}
<body>
    <div style="border: 1px solid black; padding: 15px; width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <b><img src="{{ asset('public/assets/media/svg/AgroJivanLogoDash.png') }}" width="25%" alt="logo" style="margin: 10px; ;"></b>
            <h2>Payslip for the month of {{$data['salaryDetail']->month}} {{date('Y')}}</h2>
        </div>
        <div style="margin-bottom: 10px;">
            <table class="table table-striped" border="0" style="border-collapse: collapse;">
                <div style="display: flex; justify-content: space-between;">
                    <div style="width: 50%;">
                        <div style="display:flex; gap: 15px;">
                            <p style="margin:3px 0px; width:125px   ;  font-weight: bold; ">Employee Code :</p>
                            <p style="margin:3px 0px ">{{$data['employee']->employee_code}}</p>
                        </div>
                        <div style="display:flex; gap: 15px;">
                            <p style="margin:3px 0px ; width:125px  ;  font-weight: bold;">Employee Name :</p>
                            <p style="margin:3px 0px ">{{$data['employee']->name}}</p>
                        </div>
                        <div style="display:flex; gap: 15px;">
                            <p style="margin:3px 0px ; width:125px  ;  font-weight: bold;">Department :</p>
                            <p style="margin:3px 0px ">
                                @foreach ($data['employee']->departmentDetail as $department)
                                    {{$department->departmentNameDetail->department_name}},
                                @endforeach
                            </p>
                        </div>
                      <div style="display:flex; gap: 15px;">
                            <p style="margin:3px 0px ; width:125px  ;  font-weight: bold;">Date Of Joining:</p>
                            <p style="margin:3px 0px">{{$data['employee']->join_date}}</p>
                        </div>
                    </div>  
                    
                </div>
            </table>
                <table class="table table-hover" border="2" style="border-collapse: collapse; margin: 20px 0px;">
                    <tbody>
                        <tr>
                            <th style="padding: 5px 24px;   ">Earnings</th>
                            <th style="padding: 5px 24px;    ">Standard</th>
                            <th style="padding: 5px 24px;     ">Actual Amount</th>
                            <th style="padding: 5px 24px;     ">Deductions</th>
                            <th style="padding: 5px 24px;     ">Actual Amount</th>



                        </tr>
                        <tr>
                            <td style="padding: 5px 24px;     ">Basic pay</td>
                            <td style="padding: 5px 24px;    "></td>
                            <td style="padding: 5px 24px;    ">${{$data['basicPay']}}</td>
                            <td style="padding: 5px 24px;    ">PF</td>
                            <td style="padding: 5px 24px;    ">${{$data['pfCount']}}</td>






                        </tr>
                        <tr>
                            <td style="padding: 5px 24px;     ">HRA</td>
                            <td style="padding: 5px 24px;     "></td>
                            <td style="padding: 5px 24px;    ">${{$data['hraCalculate']}}</td>
                            <td style="padding: 5px 24px;    ">Professional Tax</td>
                            <td style="padding: 5px 24px;    ">${{$data['salaryDetail']->pt}}</td>






                        <tr>
                            <td style="padding: 5px 24px;     ">Medical Allowance</td>
                            <td style="padding: 5px 24px;     "></td>
                            <td style="padding: 5px 24px;    ">${{$data['medicalAllowance']}}</td>
                            <td style="padding: 5px 24px;    "></td>
                            <td style="padding: 5px 24px;    "></td>







                        </tr>
                        <tr>
                            <td style="padding: 5px 24px;     ">Conveyance Allowance</td>
                            <td style="padding: 5px 24px;     "></td>
                            <td style="padding: 5px 24px;    ">${{$data['conveyanceAllowance']}}</td>
                            <td style="padding: 5px 24px;    "></td>
                            <td style="padding: 5px 24px;    "></td>




                        </tr>
                        <tr>
                            <td style="padding: 5px 24px;     "><b>Total Earnings</b></td>
                            <td style="padding: 5px 24px;     "></td>
                            <td style="padding: 5px 24px;    ">${{number_format($data['salaryDetail']->payable_salary,2)}}</td>
                            <td style="padding: 5px 24px;    "><b>Total Deduction</b></td>
                            <td style="padding: 5px 24px;    ">${{number_format($data['pfCount'] + $data['salaryDetail']->pt,2)}}</td>






                        </tr>

                    </tbody>
                </table>
                <div style="display:flex; gap: 15px;">
                    <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">CURRENCY :
                    </p>
                    <p style="margin:3px 0px">INR</p>
                </div>
                <div style="display:flex; gap: 15px;">
                    <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase; ">Applicant
                        trainee stipend :</p>
                    <p style="margin:3px 0px">$ {{number_format($data['salaryDetail']->payable_salary -($data['pfCount'] + $data['salaryDetail']->pt),2)}}</p>
                </div>
                <div style="display:flex; gap: 15px;">
                    <p style="margin:3px 0px ; gap: 20px;     font-weight: bold; text-transform: uppercase;  ">AMOUNT IN
                        WORDS :</p>
                    <p style="margin:3px 0px">ELEVEN THOUSAND ONE RUPEES</p>
                </div>

        </div>
</body>

</html>