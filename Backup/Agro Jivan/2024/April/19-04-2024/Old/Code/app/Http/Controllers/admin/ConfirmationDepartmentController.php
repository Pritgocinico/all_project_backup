<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Carbon\Carbon;
use PDF;

class ConfirmationDepartmentController extends Controller
{
    protected $orderRepository, $categoryRepository, $productRepository, $employeeRepository,$employeeOrderRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, EmployeeRepositoryInterface $employeeRepository,EmployeeOrderRepositoryInterface $employeeOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        $userDetail = $this->employeeOrderRepository->getUserOrder();
        $viewData = [
            'orderDistricts' => $orderDistricts,
            'userData' => $userDetail
        ];
        view()->share($viewData);
    }

    public function index()
    {
        $page = "Manual Confirm Order";
        return view('admin.confirmation.manual_order',compact('page'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 1;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',"",$date,$order_district,$userId);
        
        return view('admin.confirmation.ajax_list',compact('orderList'));
    }

    public function cancelOrderRequest(Request $request){
  
        $data['cancel_reason'] = $request->reason;
        $data['order_status'] = 4;
        $data['cancel_date'] = Carbon::now()->format('Y-m-d h:i:s');
        $where['id'] = $request->id;
        $update = $this->orderRepository->update($data, $where);   
        if ($update) {
            $log =  $request->id . 'cancelled order by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst('cancelled order #'.$request->id), $log);
            return response()->json(['data' => '', 'message' => 'Order Cancelled Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function confirmOrderRequest(Request $request){
        $data['cancel_reason'] = "";
        $data['order_status'] = 2;
        $data['confirm_date'] = Carbon::now()->format('Y-m-d h:i:s');
        $where['id'] = $request->id;
        $update = $this->orderRepository->update($data, $where);   
        if ($update) {
            $log =  $request->id . 'order confirmed by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst($request->id.'order confirmed'), $log);
            return response()->json(['data' => '', 'message' => 'Order Confirmed Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
    
    public function returnOrder(){
        $page = "Return Order List";
        return view('admin.confirmation.return_order',compact('page'));
    }
    
    public function returnajaxList(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 5;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',"",$date,$order_district,$userId);
        
        return view('admin.confirmation.return_ajax_list',compact('orderList'));
    }

    public function divertTransport(){
        $driverId = request('driver_id');
        $page = "Divert Transport Orders List";
        return view('admin.confirmation.divert_transport',compact('driverId','page'));
    }

    public function divertTransportajaxList(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 3;
        $driverId = $request->driverId;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',$driverId,$date,$order_district,$userId);
        
        return view('admin.confirmation.divert_transport_ajax_list',compact('orderList'));
    }
    
    public function batchTransport(){
        return view('admin.confirmation.batch_transport');
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $status = 1;
        if($request->type == "return"){
            $status =5;
        }
        if($request->type == "cancel"){
            $status =4;
        }
        if($request->type == "deliver"){
            $status =6;
        }
        if($request->type == "divert"){
            $status =3;
        }
        $orderList = $this->orderRepository->getAllData($status, $search, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Order Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Order Id', 'Customer Name', 'Phone Number', 'Amount', 'status', 'District', 'Created By', 'Created At');
            $callback = function () use ($orderList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($orderList as $order) {
                    $text = "Pending Order";
                    if ($order->order_status == 2) {
                        $text = "Confirmed";
                    }
                    if ($order->order_status == 3) {
                        $text = "On Deliver";
                    }
                    if ($order->order_status == 4) {
                        $text = "Cancel";
                    }
                    if ($order->order_status == 5) {
                        $text = "Completed";
                    }
                    if ($order->order_status == 6) {
                        $text = "Returned";
                    }
                    $district = isset($order->districtDetail) ? $order->districtDetail->district_name : "";
                    $user = isset($order->userDetail) ? $order->userDetail->name : '';
                    $date = "";
                    if (isset($order->created_at)) {
                        $date = UtilityHelper::convertFullDateTime($order->created_at);
                    }
                    fputcsv($file, array($order->order_id, $order->customer_name, $order->phoneno, $order->amount, $text, $district, $user, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {

            // return view('admin.pdf.product',compact('productList'));
            $pdf = PDF::loadView('admin.pdf.order', ['orderList' => $orderList]);
            return $pdf->download('Order.pdf');
        }
    }
}
