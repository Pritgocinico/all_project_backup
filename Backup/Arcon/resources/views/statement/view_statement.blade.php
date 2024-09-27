<!DOCTYPE html>
<html>
<head>
    <title>Statement-{{\Carbon\Carbon::now()}}</title>
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
    .w-10{
        width:10%;
    }
    .w-20{
        width:15%;
    }
    .w-5{
        width:5%;
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
    .statement th{
        font-size: 12px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Statement</h1>
</div>
<div class="add-detail mt-10">
    <div class="logo mt-10">
        <img src="{{url('/')}}/public/settings/arcon-logo.png" alt="Logo">
    </div>
</div>
<div class="add-detail mt-10">
    <div class="mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <td>
                    @if(isset($statement['role']))
                        @if($statement['role'] == 3)
                            <p class="m-0 text-bold w-100">Agent Name </p>
                        @endif
                    @else
                        <p class="m-0 text-bold w-100">Customer Name </p>
                    @endif
                </td>
                <td>
                    <span class="gray-color">{{$statement['name']}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="m-0 text-bold w-100">Phone Number </p>
                </td>
                <td>
                    <span class="gray-color">{{$statement['phone']}}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="m-0 text-bold w-100">Email Address </p>
                </td>
                <td>
                    <span class="gray-color">{{$statement['email']}}</span>
                </td>
            </tr>
            @if(isset($statement['role']))
                @if($statement['role'] == 2 )
                    <tr>
                        <td>
                            <p class="m-0 text-bold w-100">Address </p>
                        </td>
                        <td>
                            <span class="gray-color">
                                @if (isset($statement['floor_no']) && !blank($statement['floor_no']))
                                    {{$statement['floor_no']}},
                                @endif
                                @if (isset($statement['address']) && !blank($statement['address']))
                                    {{$statement['address']}},
                                @endif
                                <br>
                                @if (isset($statement['locality']) && !blank($statement['locality']))
                                    {{$statement['locality']}},
                                @endif
                                @if (isset($statement['city']) && !blank($statement['city']))
                                    {{$statement['city']}},
                                @endif
                                <br>
                                @if (isset($statement['state']) && !blank($statement['state']))
                                    {{$statement['state']}},
                                @endif
                                India
                                <!--@if (isset($statement['country']) && !blank($statement['country']))-->
                                <!--    {{$statement['country']}},-->
                                <!--@endif-->
                            </span>
                        </td>
                    </tr>
                    @if (isset($statement['transport']))
                        <tr>
                            <td>
                                <p class="m-0 text-bold w-100">Transport Name </p>
                            </td>
                            <td>
                                <span class="gray-color">{{$statement['transport']}}</span>
                            </td>
                        </tr>
                    @endif
                @elseif($statement['role'] == 3)
                    <tr>
                        <td class="m-0 text-bold">Head Quarter</td>
                        <td><span class="gray-color">{{$statement['headquarter']}}</span></td>
                    </tr>
                @endif
            @else
                <tr>
                    <td>
                        <p class="m-0 text-bold w-100">Address </p>
                    </td>
                    <td>
                        <span class="gray-color">
                            @if (isset($statement['floor_no']) && !blank($statement['floor_no']))
                                {{$statement['floor_no']}},
                            @endif
                            @if (isset($statement['address']) && !blank($statement['address']))
                                {{$statement['address']}},
                            @endif
                            <br>
                            @if (isset($statement['locality']) && !blank($statement['locality']))
                                {{$statement['locality']}},
                            @endif
                            @if (isset($statement['city']) && !blank($statement['city']))
                                {{$statement['city']}},
                            @endif
                            <br>
                            @if (isset($statement['state']) && !blank($statement['state']))
                                {{$statement['state']}},
                            @endif
                            @if (isset($statement['country']) && !blank($statement['country']))
                                {{$statement['country']}},
                            @endif
                        </span>
                    </td>
                </tr>
                @if (isset($statement['transport']))
                    <tr>
                        <td>
                            <p class="m-0 text-bold w-100">Transport Name </p>
                        </td>
                        <td>
                            <span class="gray-color">{{$statement['transport']}}</span>
                        </td>
                    </tr>
                @endif
            @endif
        </table>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10 statement">
        <tr>
            <th class="w-5">#</th>
            <th class="w-20">Order ID</th>
            <th class="w-50">Products</th>
            <th class="w-10">Price</th>
            <th class="w-5">Quantity</th>
            <th class="w-5">CGST(%)</th>
            <th class="w-5">SGST(%)</th>
            <!--<th class="w-10">Name</th>-->
            <!--<th class="w-10">Phone</th>-->
            <!--<th class="w-10">Email</th>-->
            <th class="w-10">Total</th>
        </tr>
        <?php $amt = 0;
        ?>
          @if(!blank($statement['orders']))
            <?php $i = 1; ?>
            @foreach($statement['orders'] as $order)
            <?php $amt = $amt + $order['total'];
                $order_items = [];
                $order_items = DB::table('order_items')->where('order_id',$order['id'])->get();
            ?>
            @if(!blank($order_items) && count($order_items) > 0)
            @foreach ($order_items as $item)
                
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$order['order_id']}}<br>( {{$order['created_at']}} )</td>
                    <td>
                        <?php
                            $variant = DB::table('product_variants')->where('id',$item->variant_id)->first();
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
                            {{$products->name}} [ @if(!blank($variant)) {{$variant->sku}} ( {{$variant->capacity}} ) @endif ] <br>
                        @endif
                    </td>
                    <td>&#x20B9; {{$pr}}</td>
                    <td>{{$item->quantity}}</td>
                    <td>
                        @if(!blank($products))
                            @if($products->cgst == '')
                                0%
                            @else
                                &#x20B9;{{$cgst}} ({{$products->cgst}}%)
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(!blank($products))
                            @if($products->sgst == '')
                                0%
                            @else
                                &#x20B9;{{$sgst}} ({{$products->sgst}}%)
                            @endif
                        @endif
                    </td>
                    <!--<td>{{$order['customer_name']}}</td>-->
                    <!--<td>{{$order['phone']}}</td>-->
                    <!--<td>{{$order['email']}}</td>-->
                    <td><i class="icon-ok green">&#x20B9;</i> {{$item->total}}</td>
                </tr>
                @endforeach
            @endif
            @endforeach
          @endif
        <tr>
            <td colspan="8">
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
