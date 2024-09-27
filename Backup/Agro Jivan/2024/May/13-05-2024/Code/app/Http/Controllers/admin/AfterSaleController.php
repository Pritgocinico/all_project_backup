<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AfterSaleController extends Controller
{
    public function index(){
        return view('admin.after_sale.complete_order');
    }
    public function orderReport(){
        return view('admin.after_sale.order_report');
    }
    public function product(){
        return view('admin.after_sale.product');
    }
    public function feedback(){
        return view('admin.after_sale.feedback');
    }
}
