<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Carbon\Carbon;
use PDF;
use Notification;
use App\Notifications\OffersNotification;

class ConfirmationDepartmentController extends Controller
{
    protected $orderRepository,$batchRepository, $categoryRepository, $productRepository, $employeeRepository,$employeeOrderRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, EmployeeRepositoryInterface $employeeRepository,EmployeeOrderRepositoryInterface $employeeOrderRepository,BatchRepositoryInterface $batchRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
        $this->batchRepository = $batchRepository;
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        $orderSubDistricts = $this->employeeOrderRepository->getUseSubDistrictOrder();
        $userDetail = $this->employeeOrderRepository->getUserOrder();
        $viewData = [
            'orderDistricts' => $orderDistricts,
            'userData' => $userDetail,
            'orderSubDistricts' => $orderSubDistricts,
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
        $order_sub_district = $request->order_sub_district;
        $status = 1;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',"",$date,$order_district,$userId,$order_sub_district);
        
        return view('admin.confirmation.ajax_list',compact('orderList'));
    }

    public function cancelOrderRequest(Request $request){
        $data['cancel_reason'] = $request->reason;
        $data['order_status'] = 4;
        $data['cancel_date'] = Carbon::now()->format('Y-m-d h:i:s');
        $where['id'] = $request->id;
        $update = $this->orderRepository->update($data, $where);
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
                Notification::send($confirm, new OffersNotification($offerData));
            }
            $salesManager = $this->employeeRepository->getAllData('', 9, '', 'export');
            foreach ($salesManager as $key => $sale) {
                $offerData = [ 
                    'user_id' => $sale->id,
                    'type' => 'cancel',
                    'title' => $order->order_id.' Order is cancel',
                    'text' => $request->reason,
                    'url' => route('employee-orders.show', $order->id),
                ];
                Notification::send($sale, new OffersNotification($offerData));
            }
            app('pusher')->trigger('notifications', 'new-notification', $offerData);
            $log =  $request->id . 'cancelled order by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog(ucfirst('cancelled order #'.$request->id), $log);
            $batch = $this->batchRepository->getBatchDetailByOrderId($request->id);
            if(isset($batch)){
                $this->batchRepository->deleteBatchDetailByOrderId($request->id);
            }
            return response()->json(['data' => '', 'message' => 'Order Cancelled Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function confirmOrderRequest(Request $request){
        $data['cancel_reason'] = "";
        $data['order_status'] = 2;
        $data['confirm_by'] = Auth()->user()->id;
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
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',"",$date,$order_district,$userId,$order_sub_district);
        
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
        $order_sub_district = $request->order_sub_district;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'paginate',$driverId,$date,$order_district,$userId,$order_sub_district);
        
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
        $date = $request->date;
        $order_district = $request->order_district;
        $userId = $request->userId;
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search, 'export',"",$date,$order_district,$userId,$order_sub_district);
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
            // dd($orderList);
            $pdf = PDF::loadView('admin.pdf.order', ['orderList' => $orderList]);
            return $pdf->download('Order.pdf');
        }
    }

    public function getAllPendingOrderItem(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 1;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'export',"",$date,$order_district,$userId);
        $data = $this->pendingOrderProductDetail($orderList);
        return response()->json($data);
    }

    public function generatePendingOrderItemPDF(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 1;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'export',"",$date,$order_district,$userId);
        $data = $this->pendingOrderProductDetail($orderList);
        $pdf = PDF::loadView('admin.pdf.pending_order_item', ['data' => $data]);
        return $pdf->download('Pending Order Product.pdf');
    }

    function pendingOrderProductDetail($orderList){
        $product = [];
        $data = [];
        foreach ($orderList as $order) {
            $product['total_order'] = count($order->orderItem);
            foreach ($order->orderItem as $key => $item) {
                $product['product_id'] = $item->product_id;
                $product['variant_id'] = $item->variant_id;
                $product['variant_name'] = isset($item->varientDetail) ? $item->varientDetail->sku_name : "-";
                $product['product_name'] = isset($item->productDetail) ? $item->productDetail->product_name : "-";
                $product['quantity'] = $item->quantity;
                array_push($data, $product);
                if ($item->schemeDetail == null) {
                    if ($data[$key]['product_id'] == $item->product_id) {
                        $data[$key]['quantity'] += $item->quantity;
                    } else {
                        $product['product_id'] = $item->product_id;
                        $product['quantity'] = $item->quantity + $item->quantity;
                        $product['product_name'] = isset($item->productDetail) ? $item->productDetail->product_name : "-";
                        array_push($data, $product);
                    }
                } else {
                    foreach ($item->schemeDetail->discountItemDetail as $key => $discount) {
                        if ($data[$key]['product_id'] == $discount->product_id) {
                            $data[$key]['quantity'] += $item->quantity;
                        } else {
                            $product['product_id'] = $item->product_id;
                            $product['quantity'] = $item->quantity + $item->quantity;
                            $product['product_name'] = isset($item->productDetail) ? $item->productDetail->product_name : "-";
                            array_push($data, $product);
                        }
                    }
                }
            }
        }
        $totalOrders = collect($data)->groupBy('variant_id')->map(function ($group) {
            return [
                'total_order' => $group->sum('total_order'),
                'quantity' => $group->sum('quantity'),
                'product_id' => $group->first()['product_id'],
                'variant_id' => $group->first()['variant_id'],
                'variant_name' => $group->first()['variant_name'],
                'product_name' => $group->first()['product_name'],
            ];
        })->values()->toArray();
        return $totalOrders;
    }

    public function allConfirmOrderList(){
        $page = "All Confirm Order List";
        return view('admin.confirmation.all_confirm_order',compact('page'));
    }

    public function allConfirmOrderAjax(Request $request){
        $search =$request->search;
        $date =$request->date;
        $order_district =$request->order_district;
        $order_sub_district =$request->order_sub_district;

        $orderList = $this->orderRepository->getConfirmOrderByConfirmDate($search,$date,$order_district,$order_sub_district);
        return view('admin.confirmation.all_confirm_order_ajax',compact('orderList'));
    }
}
