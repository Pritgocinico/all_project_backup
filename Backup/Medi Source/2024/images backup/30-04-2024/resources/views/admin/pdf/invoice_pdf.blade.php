<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="width: 700px; border-top: 1px solid black; border-bottom: 1px solid black; margin-bottom: 200px;">

    <div class="border-top: 1px solid black; border-bottom: 1px solid black;">


        <table width="100%" cellpadding="0" border="0" align="center" cellspacing="0">
            <tr style="background-color: #72a6a5;">
                <td style="text-align:start; padding:20px ">
                    <p style="text-align: start; margin: 0 auto; padding:20px">
                        <img src="https://beta.medisourcerx.com/frontend/assets/images/main-logo-b.png" height="70px"
                            width="25%" alt="">
                    </p>
                </td>
                <td style="text-align: right; color: white; padding: 20px;">
                    <h1 style="text-transform: uppercase;">Tax Invoice</h1>
                    <p style="font-size: 20px; margin-bottom: -14px;">{{ $orders->order_id }}</p>
                </td>
            </tr>
        </table>

        <table style="width: 100%; margin-top:20px;">
            <tr style="width: 100%;">
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#72a6a5"
                                    style="color: white; padding:10px 9px 10px 9px;line-height:1em;">Supplier
                                    Information:</th>
                                <th width="10"></th>
                                <th align="left" width="325" bgcolor="#72a6a5"
                                    style="color: white; padding:10px 9px 6px 10px;line-height:1em;">Details of Invoice:
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td valign="top"
                                    style="width: 150px; font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                    <p style="font-size:14px;padding:0;margin-bottom:10px;">Medi SourceRX</p>
                                    <p>&nbsp;</p>
                                    <p style="font-size:14px;padding:0;margin:0;">123 Main Street, Los Angeles,
                                        California 90001,United States.</p>
                                    <p>&nbsp;</p>
                                </td>
                                <td>&nbsp;</td>
                                <td valign="top"
                                    style="font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                    <p style="font-size:14px;padding:0;margin:0;">Invoice Number: <strong>
                                            {{ $orders->order_id }}</strong></p>
                                    <p>&nbsp;</p>
                                    <p style="font-size:14px;padding:0;margin:0;">Invoice Date: {{ date('d-m-Y') }}</p>
                                    <p>&nbsp;</p>
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
                </td>
            </tr>
            <tr style="width: 100%; gap: 20px;">
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#72a6a5"
                                    style="padding:10px  9px 10px 9px;line-height:1em; color: white;">Recipient
                                    Information:</th>
                                <th width="10"></th>
                                <th align="left" width="325" bgcolor="#72a6a5"
                                    style="padding:10px 9px 10px 9px;line-height:1em; color: white;">Ship to Address:
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td valign="top"
                                    style="font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                    <p style="font-size:14px;padding:0;margin:0">{{ $orders->address }} ,
                                        {{ $orders->address1 }},
                                        {{ $orders->city }},
                                        {{ $orders->state }}, {{ $orders->zip_code }} .</p>
                                    <p>&nbsp;</p>
                                </td>
                                <td>&nbsp;</td>
                                <td valign="top"
                                    style="font-size:14px;padding:7px 9px 9px 9px;border-left:1px solid #eaeaea;border-bottom:1px solid #eaeaea;border-right:1px solid #eaeaea">
                                    <p style="font-size:14px;padding:0;margin:0">{{ $orders->billing_address }} ,
                                        {{ $orders->billing_address1 }},
                                        {{ $orders->billing_city }},
                                        {{ $orders->billing_state }}, {{ $orders->billing_zip_code }} .</p>
                                    <p>&nbsp;</p>
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
                </td>
            </tr>
        </table>

        <div class="table-section bill-tbl w-100 mt-10">
            <table class="table" style="border-collapse: collapse; width: 100%; ">
                <tr style="border-top: 1px solid #eaeaea;" bgcolor="#72a6a5">
                    <th class="w-55"
                        style="text-align: center; border: 1px solid #eaeaea; padding: 8px; color: white;">#</th>
                    <th class="w-15"
                        style="text-align: center; border: 1px solid #eaeaea; padding: 8px; color: white;">Product</th>
                    <th class="w-10"
                        style="text-align: center; border: 1px solid #eaeaea; padding: 8px; color: white;">Price</th>
                    <th class="w-5"
                        style="text-align: center; border: 1px solid #eaeaea; padding: 8px; color: white;">Quantity</th>
                    <th class="w-5"
                        style="text-align: center; border: 1px solid #eaeaea; padding: 8px; color: white;">Total Price
                    </th>
                </tr>
                @php
                    $totalPrice = 0;
                    $totalTaxPrice = 0;
                @endphp
                @foreach ($orders->orderItemDetail as $key => $item)
                @php
                    $dicountPrice = DB::table('doctor_prices')
                    ->where('product_id',$item->product_id)->where('doctor_id',Auth()->user()->id)->first();
                @endphp
                    <tr>
                        <td style="width: 50px; padding:5px 5px;text-align: center">{{ $key + 1 }}</td>
                        <td style="width: 50px; padding:5px 5px;text-align: center">
                            {{ isset($item->productDetail) ? $item->productDetail->sku : '' }}<br />
                            (discount:-{{ isset($dicountPrice)?$dicountPrice->price:0 }})
                        </td>
                        <td style="width: 50px; padding:5px 5px;text-align: center">
                            ${{ $item->total / $item->quantity }}</td>
                        <td style="width: 50px; padding:5px 5px;text-align: center">{{ $item->quantity }}</td>
                        <td style="width: 50px; padding:5px 5px;text-align: center">
                            ${{ number_format($item->total, 2) }}
                            @php $totalPrice = $totalPrice + $item->total @endphp
                        </td>
                    </tr>
                @endforeach
                <tr align="center" style="border-top: 1px solid #eaeaea;">
                    <td style="text-align: right; padding: 8px; border: 1px solid #eaeaea;" colspan="4">
                        <p>Shipping Charges (INR)</p>
                    </td>

                    <td style="text-align: center; border: 1px solid #eaeaea; padding: 8px;">
                        <p>${{$orders->shipping_charge}}</p>
                    </td>
                </tr>
                <tr align="center" style="border-top: 1px solid #eaeaea;">
                    <td style="text-align: right;  padding: 8px; border: 1px solid #eaeaea;" colspan="4">
                        <p>Total Amounts (INR)</p>
                    </td>
                    <td style="text-align: center; border: 1px solid #eaeaea; padding: 8px;">
                        @php
                                $total = $totalPrice + $orders->shipping_charge;
                            @endphp
                        <p>${{$total}}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="table-section bill-tbl w-100 mt-10 margin-top:20px;">
            <table class="table" style="border-collapse: collapse; width: 100%; ">
                <tr style="">
                    <td class="w-55" style="text-align: right;  padding-top: 14px; padding-bottom: 14px;">
                        <p>Invoice Total : <strong>
                            
                            ${{$total}}</strong></p>
                    </td>
                </tr>
                <tr style="border-top: 1px solid #eaeaea;">
                    <td class="w-55" style="text-align: right; border-top: 1px solid #eaeaea; padding-top: 14px;">
                        <p style="padding-top: 20px  border-top: 1px solid gary;">Invoice Total amount in words :
                            <strong>{{ NumberFormat::convertToWords($total)}}</strong></p>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>

</html>
