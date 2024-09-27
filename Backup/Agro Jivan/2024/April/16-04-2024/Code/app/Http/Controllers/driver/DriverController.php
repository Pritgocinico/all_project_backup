<?php

namespace App\Http\Controllers\driver;

use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\AdminDashboardRepositoryInterface;
use Carbon\Carbon;

class DriverController extends Controller
{

    protected $employeeOrderRepository, $orderRepository, $adminDashboardRepository= "";
    public function __construct(AdminDashboardRepositoryInterface $adminDashboardRepository, EmployeeOrderRepositoryInterface $employeeOrderRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
        $this->orderRepository = $orderRepository;
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        view()->share('orderDistricts', $orderDistricts);
    }
    public function index(){
        $page = "Driver Dashboard";
        $data['totalCount'] = $this->adminDashboardRepository->totalDriverAssignedOrderCount();
        $data['completeCount'] = $this->adminDashboardRepository->totalDriverAssignedOrderCountWithStatus('6');
        $data['returnCount'] = $this->adminDashboardRepository->totalDriverAssignedOrderCountWithStatus('5'); 
        $data['pendingCount'] = $data['totalCount'] - ($data['completeCount'] + $data['returnCount']);
        return view('driver.index',compact('data','page'));
    }
    public function deliveryOrderList()
    {
        $type = "1";
        $page ="On Delivery Orders";
        return view('driver.order.index', compact('type','page'));
    }

    public function ajaxList(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $district = $request->district;
        $date = $request->date;

        $orderList = $this->employeeOrderRepository->getAllDataDriver($search, $status, $district, $date, 'paginate');

        return view('driver.order.ajax_list', compact('orderList'));
    }

    public function statusUpdate(Request $request){
        $update['order_status'] = $request->status;
        $where['id'] = $request->id;
        if($request->status == '5'){
            $update['return_reason'] = $request->reject_reason;
            $update['return_date'] = Carbon::now()->format('Y-m-d h:i:s');;
        }
        if($request->status == '6'){
            $update['delivery_date'] = Carbon::now()->format('Y-m-d h:i:s');;
        }
        $update = $this->orderRepository->update($update,$where);
        if ($update) {
            $order = $this->orderRepository->getDetailById($request->id);
            $log =  'Order ('.$order->order_id.') Updated by Driver' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('order', $log);
            return response()->json(['data' => '', 'message' => 'Order Status Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
    