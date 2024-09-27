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
        <img src="{{url('/')}}/public/settings/arcon.png" alt="Logo">
    </div>
</div>
<div class="add-detail mt-10">
    <div class="mt-10">
        <table class="table w-100 mt-10">
            <tr>
                <td>
                    <p class="m-0 text-bold w-100">Customer Name </p>
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
            <tr>
                <td>
                    <p class="m-0 text-bold w-100">Address </p>
                </td>
                <td> 
                    <span class="gray-color">
                        @if (!blank($statement['floor_no']))
                            {{$statement['floor_no']}},
                        @endif
                        @if (!blank($statement['address']))
                            {{$statement['address']}},
                        @endif
                        <br>
                        @if (!blank($statement['locality']))
                            {{$statement['locality']}},
                        @endif
                        @if (!blank($statement['city']))
                            {{$statement['city']}},
                        @endif
                        <br>
                        @if (!blank($statement['state']))
                            {{$statement['state']}},
                        @endif
                        @if (!blank($statement['country']))
                            {{$statement['country']}},
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="m-0 text-bold w-100">Transport Name</p>
                </td>
                <td> 
                    <span class="gray-color">{{$statement['transport']}}</span>
                </td>
            </tr>
        </table>
    </div>
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10 statement">
        <tr>
            <th class="w-5">#</th>
            <th class="w-20">Order ID</th>
            <th class="w-55">Products</th>
            <th class="w-10">Name</th>
            <th class="w-10">Phone</th>
            <th class="w-10">Email</th>
            <th class="w-10">Total</th>
        </tr>
        <?php $amt = 0;
        ?>
          @if(!blank($statement['orders']))
            @foreach($statement['orders'] as $order)
            <?php $amt = $amt + $order['total']; ?>
            <tr>
                <td>{{$loop->index+1}}</td>
                <td>{{$order['order_id']}}<br>( {{$order['created_at']}} )</td>
                <td>
                    @if (count($order['items']) > 0)
                        @foreach ($order['items'] as $item)
                            <?php 
                                $variant = DB::table('product_variants')->where('id',$item['variant_id'])->first(); 
                                $products = DB::table('products')->where('id',$item['product_id'])->first();
                            ?>
                            @if(!blank($products))
                            {{$products->name}} [ @if(!blank($variant)) {{$variant->sku}} ( {{$variant->capacity}} ) @endif ] <br>
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>{{$order['customer_name']}}</td>
                <td>{{$order['phone']}}</td>
                <td>{{$order['email']}}</td>
                <td><i class="icon-ok green">&#x20B9;</i>{{$order['total']}}</td>
            </tr>
            @endforeach
          @endif
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left text-bold" align="right">
                        <p>Total</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p><i class="icon-ok green">&#x20B9;</i>{{$amt}}</p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>
</html>