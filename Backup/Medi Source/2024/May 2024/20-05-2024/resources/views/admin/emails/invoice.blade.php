<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <style>
  table {
    width: 100%;
    border-collapse: collapse;
  }

</style> -->
</head>
{{-- {{dd($orders)}} --}}

<body style="width: 700px; border-left:1px solid #eaeaea; border: 1px solid gray;">
    <table style="width:100% ">
        <tr style="background-color: #00A0AA;">
            <td style="text-align:center;">
                <p
                    style="text-align: center; margin: 0 auto; border-bottom: 1px solid gray; padding-bottom: 6px; justify-content: center;">
                    <img src="{{url('/')}}/frontend/assets/images/main-logo-b.png" height="55px" alt="">
                </p>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-bottom:15px; margin-top:15px;">
        <tr>
            <td
                style="font-size: 20px; line-height: 20px; text-align: center; margin: 20px 0 20px 0px; text-decoration: underline; padding: 1px">
                <h1
                    style="font-size: 20px; line-height: 20px; text-align: center; margin: 20px 0 20px 0px; text-decoration: underline; padding: 1px">
                    YOUR ORDER CONFIRMATION</h1>
            </td>
        </tr>
    </table>
    <table style="padding: 0px 10px; width:100%; margin-bottom:15px;">
        <tr>
            <td>
                <h2 style="font-size: 20px;font-weight: normal;line-height: 22px;margin: 0 0 10px 0;padding: 0px 10px;">
                    Hello, {{ $orders->first_name }} {{ $orders->last_name }}</h2>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 14px; line-height: 20px; margin: 0; padding: 0px 10px">Thank you for your order
                    with MedisourceRX. </p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 14px; line-height: 14px; margin-top: 17px;padding: 0px 10px;">
                    <strong>ANY QUESTIONS? </strong><br>
                    Please contact us at: sales@medisourcerx.com or call: +1-714-455-1300
                </p>
            </td>
        </tr>
        <tr>
            <td>
                <h2 style="font-size: 14px; margin: 0; padding: 0px 10px;">Your Order #{{ $orders->order_id }} <small
                        style=" font-weight: normal;">(placed on {{ date('n/j/Y g:i:s A') }})</small></h2>
            </td>
        </tr>
        <tr>
            <td>
                <p style="padding: 0px 10px; margin-bottom:20px;">
                    <span style="font-size: 15px; ">Order Email Id: {{ $orders->email    }}</span></p>
            </td>
        </tr>
    </table>

    <table style="padding: 0px 10px; width:100%;">

        <tr style="padding: 0px 10px;">
            <td>
            <td>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA"
                                style="font-size:14px;padding:6px 9px 6px 9px;line-height:1em">Billing Information:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA"
                                style="font-size:14px;padding:6px 9px 6px 9px;line-height:1em">Payment Method:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td valign="top"
                                style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                <p style="font-size:12px;padding:0;margin:0"> {{ $orders->first_name }}
                                    {{ $orders->last_name }}</p>
                                <p style="font-size:12px;padding:0;margin:0">
                                    {{ $orders->address }},{{ $orders->city }},</p>
                                <p style="font-size:12px;padding:0;margin:0">{{ $orders->state }}, United States-
                                    {{ $orders->zip_code }}</p>
                                <p style="font-size:12px;padding:0;margin:0"> TEL: {{ $orders->phone }}</p>

                            </td>
                            <td>&nbsp;</td>
                            @php
                            $cardDetail = DB::table('card_details')
                            ->where('user_id', Auth()->user()->id)
                            ->orderBy('id', 'desc')
                            ->first();
                            // dd($cardDetail);
                            @endphp
                            <td valign="top"
                                style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                
                                <p style="font-size:12px;padding:0;margin:0"> <strong>Payment Mode:</strong> </p>

                                <p style="font-size:12px;padding:0;margin:0">Credit/Debit Card</p>
                                <p style="font-size:12px;padding:0;margin:0"> <strong>Card Number:</strong></p>
                                <p style="font-size:12px;padding:0;margin:0"> XXXX-XXXX-XXXX-{{ substr($cardDetail->card_number, -4) }}</p>
                                <p style="font-size:12px;padding:0;margin:0"> <strong>Name on the Card:</strong></p>
                                <p style="font-size:12px;padding:0;margin:0"> {{ $cardDetail->card_name }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="height:15px;line-height:15px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <thead>
                        <tr>
                            <th align="left" width="325" bgcolor="#EAEAEA"
                                style="font-size:14px;padding:6px 9px 6px 9px;line-height:1em">Shipping Address:</th>
                            <th width="10"></th>
                            <th align="left" width="325" bgcolor="#EAEAEA"
                                style="font-size:14px;padding:6px 9px 6px 9px;line-height:1em">Shipping Type:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td valign="top"
                                style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                <p style="font-size:12px;padding:0;margin:0"> {{ $orders->first_name }}
                                    {{ $orders->last_name }}</p>
                                <p style="font-size:12px;padding:0;margin:0">{{ $orders->billing_address }},
                                </p>
                                <p style="font-size:12px;padding:0;margin:0">{{ $orders->billing_city }},</p>
                                <p style="font-size:12px;padding:0;margin:0">{{ $orders->billing_state }},
                                    United States- {{ $orders->billing_zip_code }}</p>
                                <p style="font-size:12px;padding:0;margin:0">TEL: {{ $orders->phone }} </p>



                            </td>
                            <td>&nbsp;</td>
                            <td valign="top"
                                style="font-size:12px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                <p style="font-size:12px;padding:0;margin:0"> FedEx Shipping </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="height:15px;line-height:15px">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                {{-- {{dd($orders->orderItemDetail)}} --}}
                <table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #eaeaea">
                    <thead>
                        <tr>
                            <th align="left" bgcolor="#EAEAEA" style="font-size:14px;padding:3px 9px">Item</th>
                            <th align="left" bgcolor="#EAEAEA" style="font-size:14px;padding:3px 9px">Package Name</th>
                            <th align="left" bgcolor="#EAEAEA" style="font-size:14px;padding:3px 9px">Sub Total</th>
                            {{-- <th align="left" bgcolor="#EAEAEA" style="font-size:14px;padding:3px 9px">Qty</th> --}}
                            <th align="left" bgcolor="#EAEAEA" style="font-size:14px;padding:3px 9px">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @forelse ($orders->orderItemDetail as $item)
                        <tr>
                            <td align="left" valign="top"
                                style="font-size:12px;padding:3px 9px;border-bottom:1px dotted #cccccc"><strong
                                    style="font-size:14px">{{$item->product_name}}</strong> </td>
                            <td align="left" valign="top"
                                style="font-size:12px;padding:3px 9px;border-bottom:1px dotted #cccccc">
                                <span>{{$item->package_name}}</span>
                            </td>
                            <td align="left" valign="top"
                                style="font-size:12px;padding:3px 9px;border-bottom:1px dotted #cccccc">
                                ${{number_format($item->total/$item->quantity,2)}}</td>
                            {{-- <td align="left" valign="top"
                                style="font-size:12px;padding:3px 9px;border-bottom:1px dotted #cccccc">
                                {{$item->quantity}}</td> --}}
                            <td align="center" valign="top"
                                style="font-size:12px;padding:3px 9px;border-bottom:1px dotted #cccccc">
                                ${{number_format($item->total,2)}}</td>
                            @php $total = $total + $item->total; @endphp
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                    <tbody bgcolor="#F6F6F6">
                        <tr>
                            <td colspan="4" align="right" style="padding:5px 9px; font-size:12px;"> Sub Total
                            </td>
                            <td align="right" style="padding:5px 9px  font-size:12px;"> <span
                                    style="padding:5px 9px;">${{number_format($total,2)}}</span> </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="padding:5px 9px;  font-size:12px;"> Shipping
                                &amp; Handling </td>
                            <td align="right" style="padding:5px 9px  font-size:12px;"> <span
                                    style="padding:5px 9px;">${{number_format($orders->shipping_charge,2)}}</span> </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="padding:5px 9px;  font-size:12px;"> Discount
                            </td>
                            <td align="right" style="padding:5px 9px  font-size:12px;"> <span
                                    style="padding:5px 9px;">$0.00</span> </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right" style="padding:5px 9px;  font-size:12px;">
                                <strong>Grand Total</strong>
                            </td>
                            <td align="right" style="padding:5px 9px  font-size:12px;"> <strong><span
                                        style="padding:5px 9px;">${{number_format($orders->total ,2)}}</span></strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table style="width:100%;margin-top: 12px;">
                    <tbody>
                        <tr>
                            <td bgcolor="#EAEAEA" align="center"
                                style="background:#eaeaea;text-align:center; padding:10px; width:100%">
                                <center>
                                    <p style="font-size:14px;margin:0">Thank you, <strong>MedisourceRX</strong></p>
                                </center>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
            </td>
        </tr>
    </table>
</body>

</html>