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
                    <h1 style="color: black;">Packing Slip</h1>
                    <table style="border-collapse: collapse;margin-left: auto;margin-top: 20px; ">
                        <tr style="border: 1px solid black;text-align: center;">
                            <td style="border: 1px solid black;padding: 10px 20px;color: black; text-align: center;">Date</td>
                            <td style="padding: 10px 20px; color: black; text-align: center;">Invoice #</td>
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
                                    style="color: white;padding:10px 9px 10px 9px;line-height:1em;border: 1px solid black;color: black; text-align: center;">Bill To</td>
                                <th width="10"></th>
                                <td align="left" width="325"
                                    style="color: white;padding:10px 9px 10px 9px;line-height:1em;border: 1px solid black;color: black; text-align: center;">Ship To
                                </td>
                            </tr>
                        <tbody>
                            <tr>
                                <td valign="top"
                                    style="width: 150px; font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black">
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->first_name . " " . $orders->last_name}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->organization_name)?$orders->organization_name.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->billing_address}},</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->billing_address1)?$orders->billing_address1.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->billing_city}}, {{$orders->billing_state}}, {{$orders->billing_zip_code}}</p>
                                </td>
                                <td>&nbsp;</td>
                                <td valign="top"
                                    style="font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid black;border-bottom:1px solid black;border-right:1px solid black">
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->first_name . " " . $orders->last_name}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->organization_name)?$orders->organization_name.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->address}},</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{isset($orders->address1)?$orders->address1.",":""}}</p>
                                    <p style="font-size:14px;padding:0;margin:0;">{{$orders->city}}, {{$orders->state}}, {{$orders->zip_code}}</p>
                                    <p>&nbsp;</p>
                            </tr>
                        </tbody>
                    </table>
                   
                </td>
            </tr>
           
        </table>

        <table style="border-collapse: collapse;margin-left: auto;margin-top: 15px; ">
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">S.O. No.</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">P.O. No.</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Ship</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Via</td>
            </tr>
            <tr style="border: 1px solid black;text-align: center;">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;"></td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;"></td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;"></td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;"></td>
            </tr>
           
        </table>

        <table style="border-collapse: collapse;margin-left: auto; border: 1px solid black; margin-top: 15px;">
            <tr style="border: 1px solid black;text-align: center; " bgcolor="#e6e6e6">
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Item</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Description</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Qty</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Lot Number</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">S</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Prev. Inv</td>
                <td style="border: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Backordered</td>

            </tr>
            @foreach ($orders->orderItemDetail as $item)
            <tr style="text-align: center;">
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{isset($item->productDetail)?$item->productDetail->sku:""}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{$item->product_name}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">{{$item->quantity}}</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;"></td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">0</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">0</td>
                    <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">0</td>
                </tr>
            @endforeach
            <tr style="text-align: center;">
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">Freight</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">FedEx Package 1 Tracking #: 273889213816 Freight Shipping charge</td>
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
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
            </tr>
            <tr style="text-align: center;">
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
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
                <td style="border-right: 1px solid black;padding: 10px 10px;color: black; font-size: 12px; text-align: center;">&nbsp;</td>
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

