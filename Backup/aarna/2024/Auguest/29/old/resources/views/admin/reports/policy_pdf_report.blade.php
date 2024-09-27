<html>
<head>
    <style>
    body {
        font-family: sans-serif;
        font-size: 10pt;
    }

    p {
        margin: 0pt;
    }

    table.items {
        border: 0.1mm solid #e7e7e7;
    }

    td {
        vertical-align: top;
    }

    .items td {
        border-left: 0.1mm solid #e7e7e7;
        border-right: 0.1mm solid #e7e7e7;
    }

    table thead td {
        text-align: center;
        border: 0.1mm solid #e7e7e7;
    }

    .items td.blanktotal {
        background-color: #EEEEEE;
        border: 0.1mm solid #e7e7e7;
        background-color: #FFFFFF;
        border: 0mm none #e7e7e7;
        border-top: 0.1mm solid #e7e7e7;
        border-right: 0.1mm solid #e7e7e7;
    }

    .items td.totals {
        text-align: right;
        border: 0.1mm solid #e7e7e7;
    }

    .items td.cost {
        text-align: "."center;
    }
    </style>
</head>

<body>
    <table width="100%" style="font-family: sans-serif;" cellpadding="10">
        <tr>
            <td width="100%" style="padding: 0px; text-align: center;">
                <img src="{{url('/')}}/assets/logo.png" width="200" height="100" alt="Logo" align="center" border="0">
            </td>
        </tr>
        <tr>
            <td height="10" style="font-size: 0px; line-height: 10px; height: 10px; padding-top: 30px;">&nbsp;</td>
        </tr>
        <tr>
            <td width="100%" style="text-align: right; font-size: 16px; font-weight: bold; padding: 0px; margin-top:20px;" cellpadding="10">
                Report Date : {{date('d/m/Y H:i:s')}}
            </td>
        </tr>
        <tr>
            <td height="10" style="font-size: 0px; line-height: 10px; height: 10px; padding: 0px;">&nbsp;</td>
        </tr>
    </table>
    <table width="100%" style="font-family: sans-serif; text-align:center;" cellpadding="10">
        <tr>
            <td width="100%" style="background-color:rgb(209, 204, 204); font-weight:bold; font-size:16px; border: 0.3mm solid rgb(21, 21, 21);">@if($requests['policy_type'] == 1) Vehicle @else Health @endif Policy Report</td>
        </tr>
        <tr>
            <td width="100%" style="font-weight:bold;">Filter Criteria</td>
        </tr>
    </table>
    <table width="100%" style="font-family: sans-serif; text-align:center;">
        <tr>
            <td width="100%">
                <table width="100%" style="font-family: sans-serif; text-align:center; justify-content:center; align-item:center;" cellpadding="10">
                    <tr>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Created From Date</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['policy_created_start_date'] != "")?date('d-m-Y',strtotime($requests['policy_created_start_date'])):""}}</td>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Created To Date</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['policy_created_end_date'] != "")?date('d-m-Y',strtotime($requests['policy_created_end_date'])):""}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="font-family: sans-serif; text-align:center;">
        <tr>
            <td width="100%">
                <table width="100%" style="font-family: sans-serif; text-align:center; justify-content:center; align-item:center;" cellpadding="10">
                    <tr>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Expiry From Date</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['policy_expiry_start_date'] != "")?date('d-m-Y',strtotime($requests['policy_expiry_start_date'])):""}}</td>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Expiry To Date</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['policy_expiry_end_date'] != "")?date('d-m-Y',strtotime($requests['policy_expiry_end_date'])):""}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="font-family: sans-serif; text-align:center;">
        <tr>
            <td width="100%" style="text-align:center;">
                <table width="100%" style="font-family: sans-serif; text-align:center; justify-content:center; align-item:center;" cellpadding="10">
                    <tr>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">LOB</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['insurance_company'] != 0)?$requests['insurance_company']:""}}</td>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Business Type</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{$requests['business_type']}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="font-family: sans-serif; text-align:center;">
        <tr>
            <td width="100%" style="text-align:center;">
                <table width="100%" style="font-family: sans-serif; text-align:center; justify-content:center; align-item:center;" cellpadding="10">
                    <tr>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Company Name</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{($requests['insurance_company'] != 0)?$requests['insurance_company']:""}}</td>
                        <td width="20%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">Customer Name</td>
                        <td width="30%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">{{$requests['customer_name']}}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <table class="items" width="100%" style="border: 0.3mm solid rgb(21, 21, 21);font-size: 14px; border-collapse: collapse;" cellpadding="8">
        <thead>
            <tr style="background-color:rgb(209, 204, 204); border: 0.3mm solid rgb(21, 21, 21); font-weight:bold; font-size:14px;">
                <td width="5%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Sr.No.</strong></td>
                <td width="15%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>LOB</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>@if($requests['policy_type'] == 1) Business Type @else Plan Name @endif</strong></td>
                <td width="15%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Start Date</strong></td>
                <td width="15%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>End Date</strong></td>
                <td width="20%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Policy No</strong></td>
                <td width="20%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Customer Name</strong></td>
            </tr>
        </thead>
        <tbody>
            <?php $policy = 0; $amount = 0;?>
            @if(!blank($records))
                @foreach ($records as $record)
                    <tr style="border: 0.3mm solid rgb(21, 21, 21);">
                        <td width="5%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$loop->index+1}}</td>
                        <td width="15%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['category']}}</td>
                        <td width="10%" style="border: 0.3mm solid rgb(21, 21, 21);">@if($requests['policy_type'] == 1) {{$record['business_type']}} @else {{$record['plan_name']}} @endif</td>
                        <td width="15%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['start_date']}}</td>
                        <td width="15%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['end_date']}}</td>
                        <td width="20%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['policy_no']}}</td>
                        <td width="20%" style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['customer']}}</td>
                    </tr>
                @endforeach
            @else
                <tr style="text-align: center;">
                    <td colspan="7">Records Not Found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
