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
    .border-table{
        border: 1px solid;
        border-collapse: collapse;
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
            <td height="10" style="font-size: 0px; line-height: 10px; height: 10px; padding: 0px;">&nbsp;</td>
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
    <table width="100%" class="border-table" style="font-family: sans-serif; text-align:center;" cellpadding="10">
        <tr>
            <td width="100%" style="background-color:rgb(209, 204, 204); font-weight:bold; font-size:16px; border: 0.3mm solid rgb(21, 21, 21);">Consolidated Payout Report</td>
        </tr>
    </table>
    <table width="100%" class="border-table" style="font-family: sans-serif; text-align:left; justify-content:left; align-item:center;" cellpadding="10">
        <tr>
            <td style="font-weight:bold;">Agent Name</td>
            <td style="">{{$payout_data->agents->first_name.' '.$payout_data->agents->last_name}}</td>
        </tr>
    </table>
    <table width="100%" class="border-table" style="font-family: sans-serif; text-align:left; justify-content:left; align-item:center;" cellpadding="10">
        <tr>
            <td width="25%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">From Date</td>
            <td width="25%" style="border: 0.3mm solid rgb(21, 21, 21);">{{date('d-m-Y',strtotime($payout_data->start_date))}}</td>
            <td width="25%" style="font-weight:bold; border: 0.3mm solid rgb(21, 21, 21);">To Date</td>
            <td width="25%" style="border: 0.3mm solid rgb(21, 21, 21);">{{date('d-m-Y',strtotime($payout_data->end_date))}}</td>
        </tr>
    </table>
    <br>
    <table class="items" width="100%" style="border: 0.3mm solid rgb(21, 21, 21);font-size: 14px; border-collapse: collapse;" cellpadding="8">
        <thead>
            <tr style="background-color:rgb(209, 204, 204); border: 0.3mm solid rgb(21, 21, 21); font-weight:bold; font-size:14px;">
                <td width="5%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Sr.No.</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Policy No</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Insurance Type</strong></td>
                <td width="25%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Customer</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Policy date</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Net Premium</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>OD</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>TP</strong></td>
                <td width="10%" style="text-align: left;border: 0.3mm solid rgb(21, 21, 21);"><strong>Payout</strong></td>
            </tr>
        </thead>
        <tbody>
            <?php $policy = 0; $amount = 0; ?>
            @if(!blank($records))
                @foreach ($records as $record)
                    <?php
                        $amount += $record['payout'];
                    ?>
                    <tr style="border: 0.3mm solid rgb(21, 21, 21);">
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$loop->index+1}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['policy_no']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['type']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['customer']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['date']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['net_premium']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['od']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['tp']}}</td>
                        <td style="border: 0.3mm solid rgb(21, 21, 21);">{{$record['payout']}}</td>
                    </tr>
                @endforeach
            @else
                <tr style="text-align: center;">
                    <td colspan="6">Records Not Found.</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr style="border-top: 0.3mm solid rgb(21, 21, 21);">
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"><strong>Total:</strong></td>
                <td style="border: 0.3mm solid rgb(21, 21, 21);"><strong>{{$amount}}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
