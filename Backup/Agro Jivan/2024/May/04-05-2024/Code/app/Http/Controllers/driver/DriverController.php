<?php

namespace App\Http\Controllers\driver;

use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Notifications\OffersNotification;
use Carbon\Carbon;
use Notification;

class DriverController extends Controller
{

    protected $employeeOrderRepository, $orderRepository, $adminDashboardRepository,$employeeRepository= "";
    public function __construct(AdminDashboardRepositoryInterface $adminDashboardRepository, EmployeeOrderRepositoryInterface $employeeOrderRepository, OrderRepositoryInterface $orderRepository,EmployeeRepositoryInterface $employeeRepository)
    {
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
        $this->orderRepository = $orderRepository;
        $this->employeeRepository = $employeeRepository;
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
        if(Auth()->user()->role_id == 7){
            $orderList = $this->employeeOrderRepository->getAllDataTransport($search, $status, $district, $date, 'paginate');
        }
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
            $employee = $this->employeeRepository->getDetailById($order->created_by);
            if($employee->role_id == 2){
                $offerData = [ 
                    'user_id' => $order->created_by,
                    'type' => 'cancel',
                    'title' => $order->order_id.' Order is cancel',
                    'text' => $request->reason,
                    'url' => route('employee-orders.show', $order->id),
                ];
                Notification::send($employee, new OffersNotification($offerData));
            }
            $admin = $this->orderRepository->getDetailById(1);
            if(isset($admin)){
                $offerData = [ 
                    'user_id' => $admin->id,
                    'type' => 'cancel',
                    'title' => $order->order_id.' Order is cancel',
                    'text' => $request->reason,
                    'url' => route('orders.show', $order->id),
                ];
                Notification::send($employee, new OffersNotification($offerData));
            }
            $confirmList = $this->employeeRepository->getAllData('', 4, '', 'export');
            foreach ($confirmList as $key => $confirm) {
                $offerData = [ 
                    'user_id' => $confirm->id,
                    'type' => 'cancel',
                    'title' => $order->order_id.' Order is cancel',
                    'text' => $request->reason,
                    'url' => route('confirm-orders.show', $order->id),
                ];
                Notification::send($employee, new OffersNotification($offerData));
            }
            $salesManager = $this->employeeRepository->getAllData('', 9, '', 'export');
            foreach ($salesManager as $key => $sale) {
                $offerData = [ 
                    'user_id' => $sale->id,
                    'type' => 'cancel',
                    'title' => $order->order_id.' Order is cancel',
                    'text' => $request->reason,
                    'url' => route('confirm-orders.show', $order->id),
                ];
                Notification::send($employee, new OffersNotification($offerData));
            }
            app('pusher')->trigger('notifications', 'new-notification', $offerData);
            $log =  'Order ('.$order->order_id.') Updated by Driver' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('order', $log);
            return response()->json(['data' => '', 'message' => 'Order Status Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
    