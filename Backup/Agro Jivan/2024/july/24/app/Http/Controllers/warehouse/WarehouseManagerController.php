<?php


namespace App\Http\Controllers\warehouse;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use PDF;

class WarehouseManagerController extends Controller
{
    protected $productRepository, $categoryRepository, $employeeRepository, $batchRepository, $orderRepository;
    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository, EmployeeRepositoryInterface $employeeRepository, BatchRepositoryInterface $batchRepository, OrderRepositoryInterface $orderRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->batchRepository = $batchRepository;
        $this->employeeRepository = $employeeRepository;
        $this->orderRepository = $orderRepository;
    }
    public function index()
    {
        $page = "Warehouse Manager Dashboard";
        $driverList = $this->employeeRepository->getAllDriver();
        $batchCount = $this->batchRepository->getTotalBatch();
        $productCount = $this->productRepository->totalProduct();
        $orderList = $this->orderRepository->getNotInBatchList();
        return view('warehouse.index', compact('driverList', 'batchCount', 'productCount', 'page', 'orderList'));
    }

    public function stockList()
    {
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $page = "Stock List";
        return view('warehouse.stock.index', compact('categoryList', 'page'));
    }

    public function stockAjaxList(Request $request)
    {
        $productList = $this->productRepository->getAllProductDetail($request->search, $request->category_id, 'paginate');
        return view('warehouse.stock.ajax_list', compact('productList'));
    }

    public function updateBatch(Request $request)
    {
        $update['status'] = $request->status;
        $where['id'] = $request->id;
        $update = $this->batchRepository->updateBatch($update, $where);
        if ($update) {
            $batch = $this->batchRepository->getDetailById($request->id);
            $log =  $batch->batch_id . ' Updated Status by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog("Batch", $log);
            $batchDetail = $this->batchRepository->getBatchDetailById($request->id);
            if ($request->status == 2) {
                foreach ($batchDetail as $key => $batch) {
                    if (isset($batch->orderDetail) && $batch->orderDetail->order_status == "3") {
                        $data['order_status'] = 6;
                        $where['id'] = $batch->orderDetail->id;
                        $this->orderRepository->update($data, $where);
                    }
                }
            }
            return response()->json(['data' => '', 'message' => 'Batch Status updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function getBatchDetail(Request $request)
    {
        $batchDetail = $this->batchRepository->getBatchDetailById($request->id);
        $batchData = $this->batchRepository->getDetailById($request->id);
        $data['batch_id'] = $batchData->batch_id;
        $product = [];
        foreach ($batchDetail as $key1 => $batch) {
            $product['total_order'] = count($batch->orderItemDetail);
            foreach ($batch->orderItemDetail as $key => $item) {
                $product['product_id'] = $item->product_id;
                $product['variant_id'] = $item->variant_id;
                $product['variant_name'] = isset($item->varientDetail) ? $item->varientDetail->sku_name : "-";
                $product['product_name'] = isset($item->productDetail) ? $item->productDetail->product_name : "-";
                $product['quantity'] = $item->quantity;
                $product['total_order'] = 1;
                array_push($data, $product);
                if ($item->schemeDetail !== null) {
                    foreach ($item->schemeDetail->discountItemDetail as $key => $discount) {
                        $product['variant_id'] = $item->variant_id;
                        $product['quantity'] = $item->quantity;
                        $product['variant_id'] = $item->variant_id;
                        $product['variant_name'] = isset($item->varientDetail) ? $item->varientDetail->sku_name : "-";
                        $product['product_name'] = isset($item->productDetail) ? $item->productDetail->product_name : "-";
                        $product['quantity'] = $item->quantity;
                        array_push($data, $product);
                    }
                }
            }
        }
        $totalOrders = collect($data)->groupBy('variant_id')->map(function ($group) {
            if (!is_string($group->first())) {
                return [
                    'total_order' => $group->sum('total_order'),
                    'quantity' => $group->sum('quantity'),
                    'product_id' => $group->first()['product_id'],
                    'variant_id' => $group->first()['variant_id'],
                    'variant_name' => $group->first()['variant_name'],
                    'product_name' => $group->first()['product_name'],
                ];
            }
        })->values()->toArray();
        $totalOrders['batch_id'] = $batchData->batch_id;
        return response()->json($totalOrders);
    }

    public function deliveredBatchList()
    {
        $page = "Delivered Batch List";
        $driverList = $this->employeeRepository->getAllDriver();
        return view('warehouse.delivered_batch.index', compact('driverList', 'page'));
    }
    public function deliveredBatchAjaxList(Request $request)
    {
        $search = $request->search;
        $driverId = $request->diver_id;
        $date = $request->batch_date;

        $batchList = $this->batchRepository->getAllDeliveredBatchList($search, $date, $driverId, 'paginate');
        return view('warehouse.delivered_batch.ajax_list', compact('batchList'));
    }

    function exportCSV(Request $request)
    {
        $productList = $this->productRepository->getAllProductDetail($request->search, $request->category_id, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Stock.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Product Name', 'Category Name', 'Product Variant', 'Stock', 'Total Stock');
            $callback = function () use ($productList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($productList as $product) {
                    $variantName = isset($product->categoryDetail->categoryDetail) ? $product->categoryDetail->categoryDetail->name . " - " : '';
                    $stock = isset($product->categoryDetail) ? $product->categoryDetail->name : '';
                    $totalStock = 0;
                    $productVariant = "";
                    $productStock = 0;
                    foreach ($product->productVariantDetail as $variant) {
                        $productVariant = $variant->sku_name;
                        $productStock = $variant->stock;
                        $totalStock += $variant->stock;
                    }
                    $date = "";
                    fputcsv($file, array($product->product_name, $variantName . " " . $stock, $productVariant, $productStock, $totalStock));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.stock', ['productList' => $productList]);
            return $pdf->download('Stock.pdf');
        }
    }

    function deliveredBatchExport(Request $request)
    {
        $search = $request->search;
        $driverId = $request->diver_id;
        $date = $request->batch_date;

        $batchList = $this->batchRepository->getAllDeliveredBatchList($search, $date, $driverId, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Delivered Batch.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Batch ID', 'Village Name', 'Driver Name', 'Status', 'Created AT');
            $callback = function () use ($batchList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($batchList as $batch) {
                    $village = "";
                    foreach ($batch->batchItemDetail as $item) {
                        if (isset($item->orderDetail)) {
                            if (isset($item->orderDetail->villageDetail)) {
                                $village = $item->orderDetail->villageDetail->village_name;
                            }
                        }
                    }
                    $date = "";
                    if ($batch->created_at) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($batch->created_at);
                    }
                    $name = isset($batch->driverDetail) ? $batch->driverDetail->name : '';
                    fputcsv($file, array($batch->batch_id, $village, $name, 'Delivered', $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.Delivered Batch', ['batchList' => $batchList]);
            return $pdf->download('Delivered Batch.pdf');
        }
    }

    public function addOrderInBatch(Request $request)
    {
        $batchId = $request->batchId;
        $orderId = $request->orderIdArray;
        $driverId = $request->driver_id;
        $update['driver_id'] = $driverId;
        $where['id'] = $batchId;
        $this->batchRepository->updateBatch($update, $where);
        if ($orderId !== null) {
            foreach ($orderId as $key => $order) {
                $data = [
                    'order_id' => $order,
                    'batch_id' => $batchId,
                ];
                $this->batchRepository->createBatchItem($data);
            }
        }
        $batchOrderList = $this->batchRepository->getItemDetailById($batchId, "", "", "export");
        foreach ($batchOrderList as $key => $batchOrder) {
            $whereOrder['id'] = $batchOrder->order_id;
            $updateOrder['driver_id'] = $driverId;
            $this->orderRepository->update($updateOrder, $whereOrder);
        }

        return response()->json(['data' => "", 'message' => 'Batch Updated Successfully.'], 200);
    }

    public function deleteBatch(Request $request)
    {
        $batch = $this->batchRepository->getDetailById($request->id);
        $where['id'] = $request->id;
        $delete = $this->batchRepository->delete($where);
        if ($delete) {
            $log =  $batch->batch_id . ' Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog("Batch", $log);
            return response()->json(['data' => '', 'message' => 'Batch Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
