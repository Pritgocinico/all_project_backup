<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use PDF;

class ReportController extends Controller
{
    protected $orderRepository, $categoryRepository,$employeeRepository;
    public function __construct(OrderRepositoryInterface $orderRepository, CategoryRepositoryInterface $categoryRepository,EmployeeRepositoryInterface $employeeRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->employeeRepository = $employeeRepository;
    }
    public function orderReport()
    {
        $page = "Order Report";
        return view('admin.report.order_report', compact('page'));
    }
    public function productOrderReport()
    {
        $page = "Sale By Product Report";
        $categoryList = $this->categoryRepository->getAllData();
        return view('admin.report.product_order_report', compact('page','categoryList'));
    }
    public function salesReport()
    {
        $page = "Sales Report";
        return view('admin.report.sale_report', compact('page'));
    }
    public function staffOrderReport()
    {
        $page = "Staff Sales Report";
        $userList = $this->employeeRepository->getAllUser();
        return view('admin.report.staff_order_report', compact('page','userList'));
    }

    public function orderReportAjax(Request $request)
    {
        $search = $request->search;
        $type = $request->date_type;
        $date = $request->date;
        $orderList = $this->orderRepository->getOrderDetailReport($search, $type, $date, 'paginate');
        return view('admin.report.order_report_ajax', compact('orderList'));
    }

    public function orderReportExport(Request $request)
    {
        $format = $request->format;
        $search = $request->search;
        $type = $request->date_type;
        $date = $request->date;
        $orderList = $this->orderRepository->getOrderDetailReport($search, $type, $date, 'export');
        if ($format == "csv" || $format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Order Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Order Id', 'Customer Name', 'Customer VIP', 'Phone Number', 'Amount', 'status', 'District', 'Created By', 'Date');
            $callback = function () use ($orderList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($orderList as $order) {
                    $date = $order->created_at;
                    if ($order->order_status == 1) {
                        $text = 'Pending Order';
                    }
                    if ($order->order_status == 2) {
                        $text = 'Confirmed';
                        $date = $order->confirm_date;
                    }
                    if ($order->order_status == 3) {
                        $text = 'On Delivery';
                    }
                    if ($order->order_status == 4) {
                        $text = 'Cancelled';
                        $date = $order->cancel_date;
                    }
                    if ($order->order_status == 5) {
                        $text = 'Returned';
                        $date = $order->return_date;
                    }
                    if ($order->order_status == 6) {
                        $text = 'Delivered';
                        $date = $order->return_date;
                    }
                    $district = isset($order->districtDetail) ? $order->districtDetail->district_name : "";
                    $user = isset($order->userDetail) ? $order->userDetail->name : '';
                    $date1 = "-";
                    if ($date !== null) {
                        $date1 = UtilityHelper::convertDmyWith12HourFormat($date);
                    }
                    $customerVip = "-";
                    if (count($order->numberOrder) >= 3) {
                        $customerVip = 'VIP';
                    }
                    fputcsv($file, array($order->order_id, $order->customer_name, $customerVip, $order->phoneno, $order->amount, $text, $district, $user, $date1));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.report.orders_report', ['orderList' => $orderList]);
            return $pdf->download('Order Report.pdf');
        }
    }

    public function productOrderReportAjax(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $categoryID = $request->category_id;
        $productList = $this->orderRepository->getConfirmOrderProductList($search,$date, $categoryID,'paginate');
        return view('admin.report.product_order_report_ajax', compact('productList'));
    }

    public function productOrderReportExport(Request $request)
    {
        $search = $request->search;
        $format = $request->format;
        $date = $request->date;
        $categoryID = $request->category_id;
        $productList = $this->orderRepository->getConfirmOrderProductList($search,$date, $categoryID, 'export');
        if ($format == "csv" || $format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Sale Product Report.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Product Name', 'Category', 'Total Quantity', 'Total Amount');
            $callback = function () use ($productList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                $totalRevenue = 0;
                foreach ($productList as $product) {
                    $category = "";
                    if(isset($product->getProductDetail)){
                        if(isset($product->getProductDetail->categoryDetail)){
                            $category = $product->getProductDetail->categoryDetail->name;
                        }
                    }
                    $name = isset($product->getProductDetail)?$product->getProductDetail->product_name: "-".[$product->sku_name];
                    $totalQuantity = 0;$totalAmount = 0;
                    foreach ($product->orderItems as $item){
                     $totalQuantity = $totalQuantity+$item->total_quantity;
                            $totalAmount = $totalAmount+$item->total_amount; 
                            $totalRevenue += $item->total_amount; 
                    }
                    fputcsv($file, array($name,  $category, $totalQuantity, $totalAmount));
                }
                fputcsv($file, array('',"","",""));
                fputcsv($file, array('Total Amount',"","",$totalRevenue));
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.report.product_order_report', ['productList' => $productList]);
            return $pdf->download('Sale Product Report.pdf');
        }
    }

    public function salesReportAjax(Request $request)
    {
        $search = $request->search;
        $type = $request->date_type;
        $date = $request->date;
        $orderList = $this->orderRepository->getSalesOrderReport($search, $date, 'paginate');
        return view('admin.report.sale_report_ajax', compact('orderList'));
    }
    public function salesReportExport(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $format = $request->format;
        $orderList = $this->orderRepository->getSalesOrderReport($search, $date, 'export');
        if ($format == "csv" || $format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Sales Report Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Order ID', 'Customer', 'Customer VIP', 'Phone Number', 'Amount', 'District', 'Created By', 'Confirm Date');
            $callback = function () use ($orderList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($orderList as $order) {
                    $status = "";
                    if (count($order->numberOrder) >= 3) {
                        $status = "VIP";
                    }
                    $district = isset($order->districtDetail) ? $order->districtDetail->district_name : '';
                    $user = isset($order->userDetail) ? $order->userDetail->name : '';
                    $date = "";
                    if ($order->confirm_date !== null) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($order->confirm_date);
                    }
                    fputcsv($file, array($order->order_id, $order->customer_name, $order->phoneno, $status, $order->amount, $district, $user, $user, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.report.sale_report', ['orderList' => $orderList]);
            return $pdf->download('Sales Report.pdf');
        }
    }

    public function staffOrderReportAjax(Request $request)
    {
        $search = $request->search;
        $date = $request->search_date;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getStaffOrderReport($search, 'paginate',$userId);
        foreach ($orderList as $key => $order) {
            $order['pending_order_count'] = count($this->orderRepository->getStatusCountDateFilter('1',$date,$order->id));
            $order['confirm_order_count'] = count($this->orderRepository->getStatusCountDateFilter('2',$date,$order->id));
            $order['on_delivery_order_count'] = count($this->orderRepository->getStatusCountDateFilter('3',$date,$order->id));
            $order['cancel_order_count'] = count($this->orderRepository->getStatusCountDateFilter('4',$date,$order->id));
            $order['return_order_count'] = count($this->orderRepository->getStatusCountDateFilter('5',$date,$order->id));
            $order['complete_order_count'] = count($this->orderRepository->getStatusCountDateFilter('6',$date,$order->id));
            $order['all_order_count'] = count($this->orderRepository->getStatusCountDateFilter('',$date,$order->id));
            $order['completeOrder'] = $this->orderRepository->getStatusCountDateFilter('6',$date,$order->id);
        }
        return view('admin.report.staff_order_report_ajax', compact('orderList'));
    }

    public function staffOrderReportExport(Request $request)
    {
        $search = $request->search;
        $date = $request->search_date;
        $format = $request->format;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getStaffOrderReport($search, 'export',$userId);
        foreach ($orderList as $key => $order) {
            $order['pending_order_count'] = count($this->orderRepository->getStatusCountDateFilter('1',$date,$order->id));
            $order['confirm_order_count'] = count($this->orderRepository->getStatusCountDateFilter('2',$date,$order->id));
            $order['on_delivery_order_count'] = count($this->orderRepository->getStatusCountDateFilter('3',$date,$order->id));
            $order['cancel_order_count'] = count($this->orderRepository->getStatusCountDateFilter('4',$date,$order->id));
            $order['return_order_count'] = count($this->orderRepository->getStatusCountDateFilter('5',$date,$order->id));
            $order['complete_order_count'] = count($this->orderRepository->getStatusCountDateFilter('6',$date,$order->id));
            $order['all_order_count'] = count($this->orderRepository->getStatusCountDateFilter('',$date,$order->id));
            $order['completeOrder'] = $this->orderRepository->getStatusCountDateFilter('6',$date,$order->id);
        }
        if ($format == "csv" || $format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Staff Sales Report.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Staff Name', 'Pending Order', 'Confirm Order', 'On Delivery Order', 'Cancel Order', 'Return Order', 'Delivered Order', 'Total Order', 'amount');
            $callback = function () use ($orderList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($orderList as $order) {
                    $totalAmount = 0;
                    foreach ($order->completeOrder as $complete) {
                        $totalAmount += $complete->amount;
                    }
                    fputcsv($file, array($order->name, $order->pending_order_count, $order->confirm_order_count, $order->on_delivery_order_count, $order->cancel_order_count, $order->return_order_count, $order->complete_order_count, $order->all_order_count, $totalAmount));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.report.staff_order_report', ['orderList' => $orderList]);
            return $pdf->download('Staff Sales Report.pdf');
        }
    }
}
