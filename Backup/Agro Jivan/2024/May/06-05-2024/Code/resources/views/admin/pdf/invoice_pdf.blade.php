<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="border: 1px solid black; width: 1000px;">
        <table class="table table-hover" border="1" style="border-collapse: collapse; width: 100%; font-size: 18px;">
            <tbody>
                <tr>
                    @if (isset($orders->numberOrder) && count($orders->numberOrder) >= 3)
                        <th style="padding: 5px 5px; width: 10%; border: 0;">
                            <img src="{{ asset('public/assets/images/invoice/vip-tag.png') }}" height="100"
                                width="100">
                        </th>
                    @endif
                    <th style="padding: 5px 5px; width: 70%; border: 0;">
                        <h4 style="width: 100%; text-align: center; font-size: 30px; margin:0;">Tax Invoice</h4>
                    </th>
                    <th style="padding: 5px 24px; width: 10%; border: 0;">
                        <img src="{{ asset('public/assets/images/invoice/scanner.svg') }}" height="140"
                            width="140">
                    </th>
                    <th style="padding: 5px 5px; width: 30%; border: 0; ">
                        <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($orders->order_id, 'C39') }}"
                            height="30" width="180" />
                        <p>{{ $orders->order_id }}</p>
                        <p style="margin:3px 0px; font-size: 20px; "><span style="">{{ date('d-m-Y') }}</span></p>
                    </th>
                </tr>
            </tbody>
        </table>

        <table class="table table-hover" border="1"
            style="border-collapse: collapse; width: 100%; font-size: 18px;">
            <tbody>
                <tr>
                    <td style="padding: 5px 24px; width: 50%; text-align: left;    ">
                        <p style="margin:3px 0px;  font-weight: bold; font-size: 20px; ">Supplier Information:</p>
                        @php $status = 0 @endphp
                        @foreach ($orders->orderItem as $key => $item)
                        @if($item->productDetail->sku_name == "COTTON")
                            @php $status = 1 @endphp
                        @endif
                        @endforeach
                        @if($status == 1)
                        <p style="margin:3px 0px; font-size: 20px;">AGRO JIVAN</p>
                        <p style="margin:3px 0px; font-size: 20px;"> Address: 616- Shivalik Satyamev, Near Vakil Saheb 
                            Brigde, S. P. Ring Road, Bopal, Ahmedabad- 380 058 (GUJ.)</p>
                            <p style="margin:3px 0px; font-size: 20px; ">GSTIN: <span style="">24AUPPD7929L1Z3</span>
                        @else
                        <p style="margin:3px 0px; font-size: 20px;">AUNITY INDIA PRIVATE LIMITED</p>
                        <p style="margin:3px 0px; font-size: 20px;"> Address: 615, Shivalik Satyamev, Near Vakil Saheb
                            Bridge, Bopal, Daskroi, Ahmedabad- 380058, Gujarat.</p>
                            <p style="margin:3px 0px; font-size: 20px; ">GSTIN: <span style="">24AAZCA6344F1ZE</span>
                        @endif
                        <p style="margin:3px 0px; font-size: 20px; ">Agent Name: <span style="">{{isset($orders->userDetail)?$orders->userDetail->name:"-"}}</span>
                        </p>
                    </td>
                    <td style="padding: 5px 24px; width: 50%; text-align: left; margin-bottom: auto;">
                        <p style="margin:3px 0px;  font-weight: bold; font-size: 20px; ">Recipient Information:</p>
                        <p style="margin:3px 0px; font-size: 20px;">Name:- <b>{{ $orders->customer_name }}</b></p>
                        <p style="margin:3px 0px; font-size: 20px;">Address:- <b>{{ $orders->address }}</b> </p>
                        <p style="margin:3px 0px; font-size: 20px;">Village:-
                            <b>{{ $orders->villageDetail->village_name }}</b> </p>
                        <p style="margin:3px 0px; font-size: 20px;">Sub District:-
                            <b>{{ $orders->subDistrictDetail->sub_district_name }}</b> </p>
                        <p style="margin:3px 0px; font-size: 20px;">District:-
                            <b>{{ $orders->districtDetail->district_name }}</b> </p>
                        <p style="margin:3px 0px; font-size: 20px;">State:- <b>{{ $orders->stateDetail->name }}</b>
                        </p>
                        <p style="margin:3px 0px; font-size: 20px;">Mobile Number:- <b>{{ $orders->phoneno }}</b> </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="margin-bottom: 0px;">
            <table class="table table-hover" border="1"
                style="border-collapse: collapse; width: 100%; font-size: 14px;">
                <tbody>
                    <tr>
                        <th style="width: 50px; padding:5px 0px;   ">#</th>
                        <th style="width: 130px; padding:5px 0px;   ">Product</th>
                        <th style="width: 130px; padding:5px 0px;   ">Variant</th>
                        <th style="width: 50px; padding:5px 0px;   ">HSN</th>
                        <th style="width: 50px; padding:5px 0px;   ">Quantity</th>
                        <th style="width: 50px; padding:5px 0px;   ">Taxable Value</th>
                        <th style="width: 50px; padding:5px 0px;   ">GST Rate</th>
                        <th style="width: 50px; padding:5px 0px;     ">Amount</th>
                        <th style="width: 50px; padding:5px 0px;     ">
                            CGST/SGST
                        </th>
                    </tr>
                    @php
                        $totalTaxPrice = 0;
                        $totalCGPrice = 0;
                        $totalSGPrice = 0;
                    @endphp
                    @foreach ($orders->orderItem as $key => $item)
                    <tr>
                            <td style="width: 50px; padding:5px 5px;text-align: center">{{ $key + 1 }}</td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                {{ $item->productDetail->product_name }}
                            </td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                {{ isset($item->varientDetail) ? $item->varientDetail->sku_name : '-' }}
                            </td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">38089910</td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">{{ $item->quantity }}</td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                @php $gstPrice = $item->varientDetail->price; @endphp
                                @if ($item->productDetail->product_type !== null && $item->productDetail->product_type !== 'without_tax_product')
                                    @php
                                        $gstPrice = $item->varientDetail->price_without_tax;
                                    @endphp
                                @endif
                                {{ number_format($gstPrice, 2) }}
                            </td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                {{ $item->productDetail->c_gst + $item->productDetail->s_gst }} %</td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                {{ number_format($item->amount, 2) }}
                            </td>
                            <td style="width: 50px; padding:5px 5px;text-align: center">
                                @php
                                    $totalTaxPrice += $item->amount;
                                    $CGPrice = ($item->amount * $item->productDetail->c_gst) / 100;
                                    $totalCGPrice += $CGPrice;
                                @endphp
                                {{ number_format($CGPrice, 2) }} /
                                @php
                                    $SGPrice = ($item->amount * $item->productDetail->s_gst) / 100;
                                    $totalSGPrice += $SGPrice;
                                @endphp
                                {{ number_format($SGPrice, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="7" style="padding: 5px 24px; border: 0;white-space: nowrap; text-align: right">
                            Total Amounts (INR)</td>
                        <td style="padding: 5px 24px;text-align: center">{{ number_format($totalTaxPrice, 2) }}</td>
                        <td style="padding: 5px 5px;text-align: center">
                            {{ number_format($totalCGPrice, 2) }} / {{ number_format($totalSGPrice, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: right; border-bottom: 1px solid; border-top: 0; margin: 0; gap: 15px;">
                <p style="padding: 10px 10px ; gap: 20px; margin: 0; font-size: 20px;">Invoice Total: <span
                        style=" font-weight: bold; ">
                        @php
                        @endphp
                        {{ number_format(round($totalTaxPrice), 2) }}

                    </span>
                </p>
            </div>
            <div style=" text-align: right; border: 0px solid; border-top: 0; margin: 0; gap: 15px;">
                <p style="padding: 10px 10px ; gap: 20px; margin: 0; font-size: 20px;">Invoice Total amount in words:
                    <span style=" font-weight: bold; ">
                        @php
                            $amountInWords = ucwords(
                                (new NumberFormatter('en_IN', NumberFormatter::SPELLOUT))->format($totalTaxPrice),
                            );
                        @endphp
                        {{ $amountInWords }}
                    </span>
                </p>
            </div>
        </div>
</body>

</html>
