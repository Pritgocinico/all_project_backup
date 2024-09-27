<?php

namespace App\Http\Controllers\transport;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBatchRequest;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\StockRepositoryInterface;
use App\Interfaces\TransportDepartmentRepositoryInterface;
use Carbon\Carbon;
use \Mpdf\Mpdf as mPDF;
use PDF;

class TransportDepartmentController extends Controller
{
    protected $employeeRepository, $orderRepository, $transportDepartmentRepository, $batchRepository, $stockRepository, $productRepository, $adminDashboardRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository, OrderRepositoryInterface $orderRepository, TransportDepartmentRepositoryInterface $transportDepartmentRepository, BatchRepositoryInterface $batchRepository, StockRepositoryInterface $stockRepository, ProductRepositoryInterface $productRepository, AdminDashboardRepositoryInterface $adminDashboardRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->orderRepository = $orderRepository;
        $this->transportDepartmentRepository = $transportDepartmentRepository;
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->batchRepository = $batchRepository;
        $this->stockRepository = $stockRepository;
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $page = "Transport Department Dashboard";
        $driverList = $this->employeeRepository->getAllDriver();
        $confirmCount = $this->adminDashboardRepository->totalOrderCount('2');
        $batchCount = $this->batchRepository->totalBatch();
        $inOutStockCount = $this->stockRepository->getTotalInOutStock();
        return view('transport.index', compact('driverList', 'confirmCount', 'batchCount', 'inOutStockCount','page'));
    }

    public function orderList()
    {
        $employeeList = $this->orderRepository->getCreatedUserId();
        $districtList = $this->orderRepository->getAllDistrict();
        $driverList = $this->employeeRepository->getAllDriver();
        $stockRepository = $this->stockRepository->getTotalInOutStock();
        $page = "Confirm Order List";
        return view('transport.order.index', compact('employeeList', 'districtList', 'driverList','page'));
    }

    public function getVillageDetail(Request $request)
    {
        $id = $request->id;
        $villageList = $this->transportDepartmentRepository->getVillageDetailBySubDistrict($id);
        return response()->json($villageList);
    }

    public function ajaxList(Request $request)
    {
        $userId = $request->empID;
        $date = $request->date;
        $district = $request->district;
        $subDistrict = $request->subDistrict;
        $village = $request->village;
        $customer_name = $request->customer_name;
        $orderList = $this->transportDepartmentRepository->confirmOrderList($userId, $date, $district, $subDistrict, $village,$customer_name, 'paginate');
        return view('transport.order.ajax_list', compact('orderList'));
    }

    public function bulkAssignDriver(CreateBatchRequest $request)
    {
        $batchCode = $this->batchRepository->generateBatchId();
        $data = [
            'batch_id' => $batchCode,
            'driver_id' => $request->driver,
            'car_name' => $request->car_name,
            'car_no' => $request->car_number,
        ];
        $insert = $this->batchRepository->createBatch($data);
        if ($insert) {
            $log =  'Batch  (' . $batchCode . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Batch', $log);
            foreach ($request->orderId as $key => $order) {
                $dataItem['batch_id'] = $insert->id;
                $dataItem['order_id'] = $order;
                $this->batchRepository->createBatchItem($dataItem);
                $where['id'] = $order;
                $update['driver_id'] = $request->driver;
                $update['order_status'] = '3';
                $this->orderRepository->update($update, $where);
            }
            return response()->json(['data' => $insert, 'message' => 'Assign Driver Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function batchList()
    {
        $driverList = $this->employeeRepository->getAllDriver();
        $page = "Batch List";
        return view('transport.batch.index', compact('driverList','page'));
    }

    public function batchAjaxList(Request $request)
    {
        $search = $request->search;
        $driverId = $request->diver_id;
        $date = $request->batch_date;

        $batchList = $this->batchRepository->getAllBatchList($search, $date, $driverId, 'paginate');
        return view('transport.batch.ajax_list', compact('batchList'));
    }

    public function batchView(string $id)
    {
        $batchDetail = $this->batchRepository->getDetailById($id);
        $page = "Batch View";
        return view('transport.batch.show', compact('id', 'batchDetail','page'));
    }

    public function batchViewAjaxList(Request $request)
    {
        $id = $request->id;
        $search = $request->search;
        $date = $request->batch_date;
        $batchItem = $this->batchRepository->getItemDetailById($id, $search, $date, 'paginate');
        return view('transport.batch.show_ajax', compact('batchItem'));
    }

    public function generateInvoice(Request $request)
    {
        $id = $request->id;
        $search = $request->search;
        $date = $request->batch_date;
        $batchItem = $this->batchRepository->getItemDetailById($id, $search, $date, 'export');

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L'
        ]);
        $cnt = 0;
        foreach ($batchItem as $key => $orders) {
            $footer = '<p style="margin-top: 50px; margin-bottom: 0;"><hr></p>
            <p><strong>Sign</strong></p>';
            $pdf->SetHTMLFooter($footer);
            $viewFile = view('admin.pdf.batch_invoice_pdf', compact('orders'))->render();
            $pdf->WriteHTML($viewFile);
            if (count($batchItem) !== $key + 1) {
                $pdf->AddPage('A4');
            }
            $cnt = 1;
        }

        if ($cnt == 0) {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        return $pdf->Output('Invoice.pdf', "D");
    }

    public function show(string $id)
    {
        $page = "Order View";
        $order = $this->orderRepository->getDetailById($id);
        $states = $this->orderRepository->getAllStates();
        return view('admin.order.show', compact('order', 'states','page'));
    }

    public function stockList()
    {
        $page = "In Out Stock List";
        return view('transport.in_out_stock.index',compact('page'));
    }
    public function stockAjaxListList(Request $request)
    {
        $search = $request->search;
        $status = 3;

        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate');
        return view('transport.in_out_stock.ajax_list', compact('orderList'));
    }

    public function getOrderDetailById(Request $request)
    {
        $order = $this->orderRepository->getDetailById($request->id);
        return response()->json($order);
    }
    public function getDetailByOrderId(Request $request)
    {
        $order = $this->orderRepository->getDetailByOrderId($request->id);
        return response()->json($order);
    }
    public function storeStockDetail(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $type = $data['type'];
        unset($data['type']);
        foreach ($data['product_id'] as $key => $product) {
            $checkExistStock = $this->stockRepository->getStockDetail($request->order_id, $product, $request->variant_id[$key]);
            if ($checkExistStock !== null) {
                $where = [
                    'id' => $checkExistStock->id,
                ];
                if ($type == "In") {
                    $updateStock['in_stock'] = $checkExistStock->in_stock + $request->in_stock[$key][0];
                    $updateStock['in_stock_date_time'] = Carbon::now();
                }
                if ($type == "Out") {
                    $updateStock['out_stock'] = $checkExistStock->out_stock + $request->in_stock[$key][0];
                    $updateStock['out_stock_date_time'] = Carbon::now();
                }
                $this->stockRepository->update($updateStock, $where);
            } else {
                $storeStock = [
                    'order_id' => $request->order_id,
                    'product_id' => $product,
                    'variant_id' => $request->variant_id[$key],
                    'created_by' => Auth()->user()->id,
                ];
                if ($type == "In") {
                    $storeStock['in_stock'] = $request->in_stock[$key];
                    $storeStock['in_stock_date_time'] = Carbon::now();
                }
                if ($type == "Out") {
                    $storeStock['out_stock'] = $request->in_stock[$key];
                    $storeStock['out_stock_date_time'] = Carbon::now();
                }
                $this->stockRepository->store($storeStock);
            }
            $variantDetail = $this->productRepository->getVariantDetailById($request->variant_id[$key]);
            $whereVariant['id'] = $variantDetail->id;
            if ($type == "In") {
                $updateVariant['stock'] = $variantDetail->stock + $request->in_stock[$key];
            }
            if ($type == "Out") {
                $updateVariant['stock'] = $variantDetail->stock - $request->in_stock[$key];
            }
            $this->productRepository->updateProductVariant($updateVariant, $whereVariant);
        }
        $log =  'Stock Updated by ' . ucfirst(Auth()->user()->name);
        UserLogHelper::storeLog('Stock', $log);
        return response()->json(['data' => '', 'message' => 'Stock Updated Successfully', 'status' => 1], 200);
    }

    public function scanStoreStockDetail(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $type = $data['type'];
        unset($data['type']);
        foreach ($data['product_id'] as $key => $product) {
            $checkExistStock = $this->stockRepository->getStockDetail($request->scan_order_id[$key], $product, $request->variant_id[$key]);
            if ($checkExistStock !== null) {
                $where = [
                    'id' => $checkExistStock->id,
                ];
                if ($type == "in") {
                    $updateStock['in_stock'] = $checkExistStock->in_stock + $request->in_stock[$key][0];
                    $updateStock['in_stock_date_time'] = Carbon::now();
                }
                if ($type == "out") {
                    $updateStock['out_stock'] = $checkExistStock->out_stock + $request->in_stock[$key][0];
                    $updateStock['out_stock_date_time'] = Carbon::now();
                }
                $this->stockRepository->update($updateStock, $where);
            } else {
                $storeStock = [
                    'order_id' => $request->scan_order_id[$key],
                    'product_id' => $product,
                    'variant_id' => $request->variant_id[$key],
                    'created_by' => Auth()->user()->id,
                ];
                if ($type == "in") {
                    $storeStock['in_stock'] = $request->in_stock[$key];
                    $storeStock['in_stock_date_time'] = Carbon::now();
                }
                if ($type == "out") {
                    $storeStock['out_stock'] = $request->in_stock[$key];
                    $storeStock['out_stock_date_time'] = Carbon::now();
                }
                $this->stockRepository->store($storeStock);
            }
            $variantDetail = $this->productRepository->getVariantDetailById($request->variant_id[$key]);
            $whereVariant['id'] = $variantDetail->id;
            if ($type == "in") {
                $updateVariant['stock'] = $variantDetail->stock + $request->in_stock[$key];
            }
            if ($type == "out") {
                $updateVariant['stock'] = $variantDetail->stock - $request->in_stock[$key];
            }
            $this->productRepository->updateProductVariant($updateVariant, $whereVariant);
        }
        $log =  'Stock Updated by ' . ucfirst(Auth()->user()->name);
        UserLogHelper::storeLog('Stock', $log);
        return response()->json(['data' => '', 'message' => 'Stock Updated Successfully', 'status' => 1], 200);
    }

    function exportCSV(Request $request)
    {
        $search = $request->search;
        $driverId = $request->diver_id;
        $date = $request->batch_date;

        $batchList = $this->batchRepository->getAllBatchList($search, $date, $driverId, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Batch.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Batch Id', 'Village Name', 'Driver Name', 'Car Number','Total Order', 'status', 'Created At');
            $callback = function () use ($batchList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($batchList as $batch) {
                    $village = "";
                    foreach ($batch->batchItemDetail as $item) {
                        if ($item->orderDetail !== null) {
                            if ($item->orderDetail->subDistrictDetail !== null) {
                                $village = $item->orderDetail->subDistrictDetail->sub_district_name . ",";
                            }
                        }
                    }
                    $totalOrder = count($batch->batchItemDetail);
                    $driver = $batch->driverDetail !== null ? $batch->driverDetail->name : '';
                    $car = $batch->car_no !== null ? $batch->car_no : '-';
                    $text = $batch->status !== 2 ? 'Delivered' : 'On Delivery';
                    $date = "";
                    if (isset($batch->created_at)) {
                        $date = UtilityHelper::convertFullDateTime($batch->created_at);
                    }
                    fputcsv($file, array($batch->batch_id, $village, $driver, $car,$totalOrder ,$text, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.batch', ['batchList' => $batchList]);
            return $pdf->download('Batch.pdf');
        }
    }
    public function batchPDF(Request $request){
        $batch = $this->batchRepository->getDetailByBatchId($request->batchId);
        $batchDetail = $this->batchRepository->getBatchDetailById($batch->id);
        $data['batch_id'] = $batch->batch_id;
        $product = [];
        foreach ($batchDetail as $batch) {
            $product['total_order'] = count($batch->orderItemDetail);
            foreach ($batch->orderItemDetail as $key => $item) {
                $product['product_id'] = $item->product_id;
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
        $pdf = PDF::loadView('admin.pdf.single_batch', ['data' => $data]);
        return $pdf->download('Batch Order Product.pdf');
    }

    public function exportBatchOrder(Request $request){
        $id = $request->id;
        $search = $request->search;
        $date = $request->batch_date;
        $batchItem = $this->batchRepository->getItemDetailById($id, $search, $date, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Batch.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            // dd($batchItem);
            $columns = array('Order Id', 'Customer Name', 'Amount', 'Product Detail','District Name', 'Sub District Name', 'Village Name');
            $callback = function () use ($batchItem, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($batchItem as $order) {
                    $district = "";
                    $subDistrict = "";
                    $village = "";
                    $orderId = isset($order->orderDetail) ? $order->orderDetail->order_id : '';
                    $customerName = isset($order->orderDetail) ? $order->orderDetail->customer_name : '';
                    $amount = isset($order->orderDetail) ? $order->orderDetail->amount : '';
                    $productName ="";
                    if (isset($order->orderDetail)){
                        if(isset($order->orderDetail->orderItem)){
                            foreach ($order->orderDetail->orderItem as $key => $item) {
                                if(isset($item->productDetail)){
                                    $productName .= $item->productDetail->product_name; 
                                }
                                if($item->varientDetail){
                                    $productName .= $item->varientDetail->sku_name;
                                }
                                
                            }
                        }  
                    }
                        if (isset($order->orderDetail)) {
                            if ($order->orderDetail->districtDetail !== null) {
                                $district  = $order->orderDetail->districtDetail->district_name;
                            }
                            if ($order->orderDetail->subDistrictDetail !== null) {
                                $subDistrict  = $order->orderDetail->subDistrictDetail->sub_district_name . ",";
                            }
                            if ($order->orderDetail->villageDetail !== null) {
                                $village  = $order->orderDetail->villageDetail->village_name . ",";
                            }
                        }
                    fputcsv($file, array($orderId, $customerName,$amount, $productName, $district,$subDistrict ,$village));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.batch_order', ['batchItem' => $batchItem]);
            return $pdf->download('Batch.pdf');
        }
    }
}
