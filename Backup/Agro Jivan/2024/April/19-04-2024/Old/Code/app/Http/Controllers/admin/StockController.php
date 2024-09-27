<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class StockController extends Controller
{
    public function index(){
        return view('admin.stock.index');
    }
    public function approveStock(){
        return view('admin.stock.approve_stock');
    }

    public function accountList(){
        
    }
}
