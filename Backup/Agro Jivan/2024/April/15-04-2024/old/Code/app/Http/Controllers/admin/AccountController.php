<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(){
        return view('admin.account.order_deliver');
    }
    public function salarySlip(){
        return view('admin.account.salary_slip');
    }
    public function allStock(){
        return view('admin.account.all_stock');
    }
}
