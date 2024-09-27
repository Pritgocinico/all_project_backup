<?php

namespace App\Http\Controllers\confirm;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\AdminDashboardRepositoryInterface;
use Illuminate\Http\Request;

class ConfirmationDepartmentController extends Controller
{
    protected $orderRepository,$employeeRepository, $adminDashboardRepository= "";
    public function __construct(AdminDashboardRepositoryInterface $adminDashboardRepository, OrderRepositoryInterface $orderRepository,EmployeeRepositoryInterface $employeeRepository)
    {
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->orderRepository = $orderRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index(){
        $page = "Confirmation Department Dashboard";
        return view('confirm.index',compact('page'));
    }

    public function orderAjax(Request $request){
        $date = $request->date;
        $data['totalCount'] = $this->adminDashboardRepository->totalAllOrderCount($date);
        $data['pendingCount'] = $this->adminDashboardRepository->totalOrderCount('1',$date);
        $data['confirmCount'] = $this->adminDashboardRepository->totalOrderCount('2',$date);
        $data['cancelCount'] = $this->adminDashboardRepository->totalOrderCount('4',$date);
        $data['returnCount'] = $this->adminDashboardRepository->totalOrderCount('5',$date);
        $data['completeCount'] = $this->adminDashboardRepository->totalOrderCount('6',$date);
        return response()->json($data);
    }

    public function pendingOrderList(){
        return view('confirm.order.pending');
    }

    public function pendingOrderAjax(Request $request){
        $search = $request->search;
        $status = 1;

        $orderList = $this->orderRepository->getAllData($status, $search,'paginate');
        return view('confirm.order.ajax_list',compact('orderList'));
    }

    public function confirmOrderList(){
        $employeeList = $this->employeeRepository->getAllData('',5,'','export');
        return view('confirm.assign_driver.confirm_order',compact('employeeList'));
    }

    public function confirmOrderAjax(Request $request){
        $search = $request->search;
        $status = 2;

        $orderList = $this->orderRepository->getAllData($status, $search,'paginate');
        return view('confirm.assign_driver.ajax_list',compact('orderList'));
    }

    public function assignDriver(Request $request){
        $update['driver_id'] = $request->driver_id;
        $update['order_status'] = 3;
        $where['id'] = $request->order_id;

        $update =$this->orderRepository->update($update,$where);
        if ($update) {
            $order = $this->orderRepository->getDetailById($request->order_id);
            $log =  'Order ('.$order->order_id.') Assign Driver Confirmation Department' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('order', $log);
            return response()->json(['data' => '', 'message' => 'Order Status Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function topFiveConfirmOrder(){
        $page = "Top Five Employee List";
        return view('confirm.top_order.index',compact('page'));
    }

    public function topFiveConfirmOrderAjax(Request $request){
        $search = $request->search;
        $date = $request->date;
        $userList = $this->employeeRepository->getTopFiveConfirmOrder($search,$date);
        return view('confirm.top_order.ajax_list',compact('userList'));
    }

    public function chartAjax(Request $request){
        $date = $request->date;
        $pending = count($this->orderRepository->getAllData(1,'','export',"",$date));
        $confirmed = count($this->orderRepository->getAllData(2,'','export',"",$date));
        $delivered = count($this->orderRepository->getAllData(3,'','export',"",$date));
        $canceled = count($this->orderRepository->getAllData(4,'','export',"",$date));
        $returned = count($this->orderRepository->getAllData(5,'','export',"",$date));
        $completed = count($this->orderRepository->getAllData(6,'','export',"",$date));
        $data = [            
            'labels' => ['Pending', 'Confirmed', 'Delivered', 'Canceled', 'Returned','Completed'],
            'data' => [$pending, $confirmed, $delivered, $canceled, $returned,$completed],
        ];
        return response()->json($data);
    }
}
