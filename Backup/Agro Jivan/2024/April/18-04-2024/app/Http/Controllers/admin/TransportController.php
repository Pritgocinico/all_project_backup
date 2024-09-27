<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;

class TransportController extends Controller
{
    protected $employeeRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    public function index(){
        return view('admin.transport.batch_manually');
    }
    public function assignDriver(){
        return view('admin.transport.batch_manually');
    }
    public function inOut(){
        return view('admin.transport.in_out');
    }
    public function driverManagement(){
        $page = "Driver List";
        return view('admin.transport.driver_management',compact('page'));
    }
    public function printBill(){
        return view('admin.transport.print_bill');
    }

    public function driverManagementAjax(Request $request){
        $search = $request->search;
        $driverList = $this->employeeRepository->getAllDriverSearch($search);
        return view('admin.transport.driver_management_ajax',compact('driverList'));
    }
}
