<!DOCTYPE html>
<html>
<head>
    <title>Invoice-{{$order->id}}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{url('/')}}/public/assets/admin/icons/font-awesome/css/font-awesome.min.css">
</head>
<style type="text/css">
    @font-face {
        font-family: 'Satoshi Regular';
        src: url('{{url('/')}}/public/assets/admin/css/fonts/satoshi/Satoshi-Regular.otf');
       
    }
    *{
        font-family: 'Satoshi Regular' ;
        font-family: DejaVu Sans, sans-serif; 
    }
    body{
        font-family: 'Satoshi Regular' !important;
    }
    h1, h2, h3, h4, h5, h6{
        font-family: 'Satoshi Regular' !important;
    }
    p{
        font-family: 'Satoshi Regular';
    }
    span{
        font-family: 'Satoshi Regular' !important;
    }
    th{
        font-family: 'Satoshi Regular' !important;
    }
    td{
        font-family: 'Satoshi Regular' !important;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:100px;      
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    .w-55{
        width: 40%;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .float-right{
        float:right;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
    .text-end{
        text-align: right;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Invoice</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">Invoice Id - <span class="gray-color">#{{$order->id}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Id - <span class="gray-color">{{$order->order_id}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Order Date - <span class="gray-color">{{$order->created_at}}</span></p>
    </div>
    <div class="w-50 float-right text-end logo mt-10">
        <img src="{{url('/')}}/public/settings/arcon-logo.png" alt="Logo">
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">From</th>
            <th class="w-50">To</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <!--<p>Mountain View,</p>-->
                    <!--<p>California,</p>-->
                    <!--<p>United States</p>-->
                    <!--<p>Contact: (650) 253-0000</p>-->
                    <p style="font-size:16px; font-weight:bold;">{{$setting->company_name}}</p>
                    @if(!blank($setting->address))
                        <p>{{$setting->address}}</p>
                    @endif
                    @if(!blank($setting->city))
                        <p>{{$setting->city}},</p>
                    @endif
                    <p>@if(!blank($setting->state)){{$setting->state}}@endif @if(!blank($setting->postal_code))-{{$setting->postal_code}}.@endif</p>                   
                    <p>Contact: {{$setting->phone}}</p>
                    <p>GST Number: {{$setting->gst_number}}</p>
                    </div>
            </td>
            <td>
                <div class="box-text">
                    <p>{{$order->customer_name}}</p>
                    @if (!blank($order->floor_no))
                    <span>{{$order->floor_no}},</span>
                    @endif
                    @if (!blank($order->address))
                        <span>{{$order->address}},</span>
                    @endif
                    <br>
                    @if (!blank($order->locality))
                        <span>{{$order->locality}},</span>
                    @endif
                    @if (!blank($order->city))
                        <span>{{$order->city}},</span>
                    @endif
                    <br>
                    @if (!blank($order->state))
                        <span>{{$order->state}},</span>
                    @endif
                    @if (!blank($order->country))
                        <span>{{$order->country}},</span>
                    @endif
                    <p>{{$order->phone}}</p>
                    <p>{{$order->email}}</p>
                    <p>GST Number: 
                        <?php $usr = DB::table('users')->where('id',$order->user_id)->first(); ?>
                        @if(!blank($usr))
                            @if(!blank($usr->gst_number))
                                {{$usr->gst_number}}
                            @else
                                NA
                            @endif
                        @endif
                    </p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Deliverd At</th>
            <th class="w-50">Transport Name</th>
        </tr>
        <tr>
            <td class="text-center">@if($order->deliverd_at) {{$order->deliverd_at}} @else - @endif </td>
            <td class="text-center">@if($order->transport) {{$order->transport}} @else - @endif </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-55">Product</th>
            <!--<th class="w-15">Category</th>-->
            <th class="w-15">Price</th>
            <th class="w-10">Qty</th>
            <th class="w-5">CGST(%)</th>
            <th class="w-5">SGST(%)</th>
            <th class="w-15">Subtotal</th>
        </tr>
        <?php $amt = 0;
        ?>
            @if (count($order_items) > 0)
                @foreach ($order_items as $item)
                <?php 
                    $variant = DB::table('product_variants')->where('id',$item->variant_id)->first(); 
                    $amt = $amt + ($item->quantity*$item->price)
                ?>
                <tr align="center">
                    <td>
                        <?php 
                            $products = DB::table('products')->where('id',$item->product_id)->first();
                            if(!blank($products)){
                                $cgst = ($item->price*$products->cgst)/100; 
                                $sgst = ($item->price*$products->sgst)/100;
                                $pr = $item->price-$cgst-$sgst; 
                            }else{
                                $cgst = 0;
                                $sgst = 0;
                                $pr = $item->price;
                            }
                        ?>
                        @if(!blank($products))
                            {{$products->brand_name}} - {{$products->name}} [ @if(!blank($variant)) {{$variant->sku}} ( {{$variant->capacity}} ) @endif ]
                        @endif
                    </td>
                   
                    <td><i class="icon-ok green">&#x20B9;</i> {{$pr}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>
                        @if(!blank($products))
                            @if($products->cgst == '')
                                0%
                            @else
                                <i class="icon-ok green">&#x20B9;</i>{{$cgst}} ({{$products->cgst}}%)
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!blank($products))
                            @if($products->sgst == '')
                                0%
                            @else
                                <i class="icon-ok green">&#x20B9;</i>{{$sgst}} ({{$products->sgst}}%)
                            @endif
                        @endif
                    </td>
                    <td><i class="icon-ok green">&#x20B9;</i> {{$item->price*$item->quantity}}</td> 
                </tr>
                @endforeach
            @endif
        <tr>
            <td colspan="6">
                <div class="total-part">
                    <div class="total-left w-85 float-left text-bold" align="right">
                        <p>Total</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p><i class="icon-ok green">&#x20B9;</i> {{$amt}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>