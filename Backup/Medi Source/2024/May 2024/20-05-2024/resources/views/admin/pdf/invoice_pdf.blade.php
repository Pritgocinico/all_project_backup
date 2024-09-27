<!DOCTYPE html>
<html lang="en">

<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="width: 700px; margin-bottom: 15px;">

    <!-- sales order -->
    <div class="border-top: 1px solid black; border-bottom: 1px solid black;">
        <table width="100%" cellpadding="0" border="0" align="center" cellspacing="0">
            <tr style="">
                <td style="text-align: left; padding: 3px; ">
                    <table style="margin-top: 0px; " align="left">
                        <tr style="text-align: left; ">
                            <td style="padding: 3px 0px;color: black;">MedisourceRx</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="padding: 3px 0px;color: black;">10525 Humbolt Street</td>
                        </tr>
                        <tr style="text-align: left;">
                            <td style="padding: 3px 0px;color: black;">Los Alamitos, CA 90720</td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: right; color: white; padding: 3px;">
                    <h1 style="color: black;">Sales Order</h1>
                    <table style="border-collapse: collapse;margin-left: auto;margin-top: 20px; ">
                        <tr style="border: 1px solid black;text-align: center;">
                            <td style="border: 1px solid black;padding: 10px 20px;color: black; text-align: center;">Date</td>
                            <td style="padding: 10px 20px; color: black; text-align: center;">S.O. No.</td>
                        </tr>
                        <tr style="border: 1px solid black;text-align: center;">
                            <td style="border: 1px solid black;padding: 10px 20px;color: black; text-align: center;">{{date('m/d/Y')}}</td>
                            <td style="padding: 10px 20px; color: black; text-align: center;">{{$orders->order_id}}</td>
                        
                        </tr>
                        
                    </table>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top: 12px;">
            <tr style="width: 100%;">
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td align="left" width="325"
                                    style="color: white;padding:10px 9px 10px 9px;line-height:1em;border: 1px solid black;color: black; text-align: center;">Name / Address</td>
                                <th width="10"></th>
                                <td align="left" width="325"
                                    style="color: white;padding:10px 9px 10px 9px;line-height:1em;border: 1px solid black;color: black; text-align: center;">Ship To
                                </td>
                            </tr>
                        <tbody>
                            <tr>
                                <td valign="top"
                                    style="width: 150px; font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black">
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->organization_name}}</p>
                                    {{-- <p style="font-size:14px;padding:0;margin:0;">Jonathan Moga</p> --}}
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->address}},</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->address1)?$orders->address1.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->city}}, {{$orders->state}}, {{$orders->zip_code}}</p>
                                </td>
                                <td>&nbsp;</td>
                                <td valign="top"
                                    style="font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black">
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->organization_name}}</p>
                                    {{-- <p style="font-size:14px;padding:0;margin:0;">Jonathan Moga</p> --}}
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->billing_address}},</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->billing_address1)?$orders->billing_address1.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->billing_city}}, {{$orders->billing_state}}, {{$orders->billing_zip_code}}</p>
                                    <p>&nbsp;</p>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
           
        </table>
        <table style="border-collapse: collapse;margin-left: auto;margin-top: 15px; ">
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">P.O. No.</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Terms</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Rep</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Account #</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Requested Sh...</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Ship Via</td>
            </tr>
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->p_o_number:""}}</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->terms:""}}</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->rep:""}}</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->account_number:""}}</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->requested_ship:""}}</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{ isset($orders->orderPdfDetail)?$orders->orderPdfDetail->ship_via:""}}</td>
            </tr>
           
        </table>
        <table style="border-collapse: collapse;margin-left: auto; border: 1px solid black; margin-top: 15px;">
            <tr style="border: 1px solid black;text-align: center; " bgcolor="#e6e6e6">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Item</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Description</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Ordered</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Lot Number</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">U/M</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Rate</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Lot#</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Amount</td>
            </tr>
            @php $subTotal = 0; @endphp
            @foreach ($orders->orderItemDetail as $item)
            <tr style="text-align: center;">
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{isset($item->productDetail)?$item->productDetail->sku:""}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{$item->product_name}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{$item->quantity}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{isset($item->orderProductItemDetail)?$item->orderProductItemDetail->lot_number:""}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Vials</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">${{number_format($item->total / $item->quantity,2)}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{isset($item->orderProductItemDetail)?$item->orderProductItemDetail->lot:""}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">${{number_format($item->total,2)}}</td>
                    @php $subTotal = $subTotal + $item->total; @endphp
                </tr>
            @endforeach
            <tr style="text-align: center;">
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
            </tr>
            <tr style="text-align: center;">
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 0px 10px;color: black; font-size: 12px; text-align: center;">Sub Total:-</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">${{number_format($subTotal,2)}}</td>
            </tr>
            <tr style="text-align: center;">
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 0px 10px;color: black; font-size: 12px; text-align: center;">Shipping:-</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">${{number_format($orders->shipping_charge,2)}}</td>
            </tr>
          
            <tr style="text-align: center;">
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 20px; text-align: center;">Total</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">${{number_format($subTotal +$orders->shipping_charge,2)}}</td>
            </tr>
        </table>

        <table style="border-collapse: collapse;margin-left: auto;margin-top: 15px; ">
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">Phone #</td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">Fax #</td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">E-mail</td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">Web Site</td>
            </tr>
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">714-455-1300</td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;">714-455-1395</td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;"><a href="mailto:info@medisourcerx.com" style="color: black; text-decoration: none;">info@medisourcerx.com</a></td>
                <td style="border: 1px solid black;padding: 10px 30px;color: black; font-size: 12px; text-align: center;"><a href="http://www.medisourcerx.com/" style="color: black; text-decoration: none;">www.medisourcerx.com</a></td>
             
            </tr>
           
        </table>
    </div>

</body>

</html>

