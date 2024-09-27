<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SchemeRepositoryInterface;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mockery\Undefined;
use PDF;
use \Mpdf\Mpdf as mPDF;

class OrderController extends Controller
{
    protected $orderRepository, $categoryRepository, $productRepository, $employeeRepository, $schemeRepository, $employeeOrderRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, EmployeeRepositoryInterface $employeeRepository, SchemeRepositoryInterface $schemeRepository, EmployeeOrderRepositoryInterface $employeeOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
        $this->schemeRepository = $schemeRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
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
        $page = "All Orders";
        return view('admin.order.index', compact('page'));
    }

    public function getSubDistrict(Request $request)
    {
        $subdistrict = $this->orderRepository->getAllSubDistrict($request->id);
        return response()->json($subdistrict);
    }

    public function getVillage(Request $request)
    {
        $village = $this->orderRepository->getAllVillage($request->id);
        return response()->json($village);
    }

    public function create()
    {
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $state = $this->orderRepository->getAllStates();
        $district = $this->orderRepository->getAllDistrict();
        $villages = $this->orderRepository->getVillages();
        $employeeList = $this->employeeRepository->getAllEmployee();
        $subdistricts = $this->orderRepository->getSubDistrict();
        $allScheme = $this->schemeRepository->getAllScheme('', 'export');
        $page = "Create Order";
        return view('admin.order.create', compact('state', 'district', 'employeeList', 'categoryList', 'villages', 'subdistricts', 'allScheme', 'page'));
    }

    public function getAllCategory()
    {
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        return response()->json($categoryList);
    }

    public function getProductByCategory(Request $request)
    {
        $productList = $this->productRepository->getAllProductByCategoryId($request->id);
        return response()->json($productList);
    }
    public function getProductVariantDetail(Request $request)
    {
        $variantList = $this->productRepository->getVariantDetailByProductId($request->id);
        return response()->json($variantList);
    }

    public function getVariantDetailById(Request $request)
    {
        $variant = $this->productRepository->getVariantDetailById($request->id);
        return response()->json($variant);
    }

    public function store(CreateOrderRequest $request)
    {
        $totalOrder = $this->orderRepository->orderCount();

        $id = 0;
        if ($totalOrder > 0) {
            $id = $this->orderRepository->getLastInsertId();
        }
        $id = $id + 1;
        $order_id = 'AG-' . $id;
        $data = [
            'order_id' => $order_id,
            'customer_name' => $request->customer_name,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'district' => $request->district,
            'sub_district' => $request->sub_district,
            'village' => $request->village,
            'pincode' => $request->pincode,
            'excepted_delievery_date' => $request->excepted_delievery_date,
            'created_by' => Auth()->user()->id,
        ];
        $data['order_lead_status']            = $request->orderlead == "on" ? '1' : '0';
        $data['lead_followup_datetime']            = $request->lead_datetime;
        $data['amount']            = $request->amount;
        $data['divert_order_status']            =  $request->divertorder == "on" ? '1' : '0';
        if ($request->has('divert_to') &&  $request->divertorder == "on" ? '1' : '0' == '1') {
            $data['created_by']      = $request->divert_to;
            $data['staff']      = Auth::user()->id;
        } else {
            $data['created_by']      = Auth::user()->id;
        }
        $data['divert_note']      = $request->divert_note;
        $insert = $this->orderRepository->store($data);
        if ($insert) {
            $log = 'Order (' . $order_id . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Order', $log);
            if (!empty($request->category)) {
                $category  = $request->category;
                $product   = $request->products;
                $variant   = $request->variant;
                $quantity     = $request->quantity;
                $total        = $request->product_total;
                $price        = $request->pr_price;
                $schemeCode = $request->scheme_code;
                $v_scheme_code = "";
                $stock = $request->stock;
                foreach ($category as $key => $value) {
                    if ($value != 0) {
                        foreach ($quantity as $key1 => $val1) {
                            if ($key == $key1) {
                                $v_qty = $val1;
                            }
                        }
                        foreach ($total as $key5 => $val5) {
                            if ($key == $key5) {
                                $v_total = $val5;
                            }
                        }
                        foreach ($price as $key6 => $val6) {
                            if ($key == $key6) {
                                $v_price = $val6;
                            }
                        }
                        foreach ($product as $key3 => $val3) {
                            if ($key == $key3) {
                                $v_product = $val3;
                            }
                        }
                        foreach ($variant as $key4 => $val4) {
                            if ($key == $key4) {
                                $v_variant = $val4;
                            }
                        }
                        foreach ($stock as $key5 => $val5) {
                            if ($key == $key5) {
                                $v_stock = $val5;
                            }
                        }
                        foreach ($schemeCode as $key4 => $val5) {
                            if ($key == $key5) {
                                $v_scheme_code = $val5;
                            }
                        }
                        $orderItem = [
                            'order_id' => $insert->id,
                            'category_id' => $value,
                            'product_id' => $v_product,
                            'variant_id' => $v_variant,
                            'quantity' => $v_qty,
                            'price' => $v_price,
                            'amount' => $v_total,
                            'stock' => $v_stock,
                            'discount_code' => $v_scheme_code,
                            'created_by' => Auth::user()->id,
                        ];
                        $this->orderRepository->storeOrderItem($orderItem);
                    }
                }
            }
            if (!empty($request->free_product_variant_id)) {
                foreach ($request->free_product_variant_id as $key1 => $variantID) {
                    if ($variantID !== null) {

                        $product = $this->productRepository->getDetailById($request->free_product_variant_id[$key1]);
                        $code = $this->schemeRepository->getSchemeCodeDetailByProduct($request->free_product_variant_id[$key1], $variantID);
                        $freeProduct = [
                            'order_id' => $insert->id,
                            'category_id' => $product->category_id,
                            'product_id' => $request->free_product_variant_id[$key1],
                            'variant_id' => $variantID,
                            'price' => 0,
                            'amount' => 0,
                            'quantity' => 1,
                            'discount_code' => isset($code->schemeDetail) ? $code->schemeDetail->discount_code : "",
                            'created_by' => Auth::user()->id,
                            'is_free_product' => 1,
                        ];
                        $this->orderRepository->storeOrderItem($freeProduct);
                    }
                }
            }
            if (Auth()->user()->role_id == "4") {
                return redirect('confirm/confirm-orders')->with('success', 'Orders Created Successfully.');
            }
            if (Auth()->user()->role_id == "9") {
                return redirect('sales/sale-orders')->with('success', 'Orders Created Successfully.');
            }
            return redirect('admin/orders')->with('success', 'Orders Created Successfully.');
        }
        return redirect('admin/orders/create')->with('error', 'Something went to wrong.');
    }

    public function edit(string $id)
    {
        $orderData = $this->orderRepository->getDetailById($id);
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $state = $this->orderRepository->getAllStates();
        $district = $this->orderRepository->getAllDistrict();
        $employeeList = $this->employeeRepository->getAllEmployee();
        $subdistrict = $this->orderRepository->getAllSubDistrict($orderData->district);
        $village = $this->orderRepository->getAllVillage($orderData->sub_district);
        $allScheme = $this->schemeRepository->getAllScheme('', 'export');
        $page = "Edit Order";
        return view('admin.order.edit', compact('orderData', 'categoryList', 'state', 'district', 'employeeList', 'subdistrict', 'village', 'allScheme', 'page'));
    }

    public function update(CreateOrderRequest $request, string $id)
    {
        $data = [
            'customer_name' => $request->customer_name,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'district' => $request->district,
            'sub_district' => $request->sub_district,
            'village' => $request->village,
            'pincode' => $request->pincode,
            'excepted_delievery_date' => $request->excepted_delievery_date,
            'order_status' => $request->order_status,
        ];
        $data['order_lead_status']            = $request->orderlead == "on" ? '1' : '0';
        $data['lead_followup_datetime']            = $request->lead_datetime;
        $data['amount']            = $request->amount;
        if ($request->order_status == 2) {
            $data['confirm_date'] = Carbon::now()->format('Y-m-d h:i:s');
        }
        if ($request->order_status == 4) {
            $data['cancel_date'] = Carbon::now()->format('Y-m-d h:i:s');
        }
        if ($request->order_status == 5) {
            $data['return_date'] = Carbon::now()->format('Y-m-d h:i:s');
        }
        $where['id'] = $id;
        $update = $this->orderRepository->update($data, $where);
        if ($update) {
            $orderData = $this->orderRepository->getDetailById($id);
            $log = 'Order (' . $orderData->order_id . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Order', $log);
            $where = ['product_id' => $id];
            foreach ($request->category as $key => $category) {
                $code = "";
                if (isset($request->free_product_variant_id) && isset($request->free_product_variant_id[$key])) {
                    $code = $this->schemeRepository->getSchemeCodeDetailByProduct($request->free_product_variant_id[$key], $request->variant[$key]);
                }
                $variant = [
                    'category_id' => $category,
                    'product_id' => $request->products[$key],
                    'variant_id' => $request->variant[$key],
                    'price' => $request->price[$key],
                    'quantity' => $request->quantity[$key],
                    'amount' => $request->product_total[$key],
                    'discount_code' => isset($code->schemeDetail) ? $code->schemeDetail->discount_code : "",
                    'order_id' => $id,
                ];
                $productVariantExist = $this->orderRepository->checkOrderRepository($request->ids[$key], $id);
                if (isset($productVariantExist)) {
                    $this->orderRepository->updateProductVariant($variant, $where);
                } else {
                    $this->orderRepository->storeOrderItem($variant);
                }
            }
            if (Auth()->user()->role_id == "4") {
                return redirect('confirm/confirm-orders')->with('success', 'Orders Created Successfully.');
            }
            if (Auth()->user()->role_id == "9") {
                return redirect('sales/sale-orders')->with('success', 'Orders Created Successfully.');
            }
            return redirect('admin/orders')->with('success', 'Orders Updated Successfully.');
        }
        return redirect('admin/orders/create')->with('error', 'Something went to wrong.');
    }

    public function ajaxList(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $userId = $request->userId;
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate', "", $date, $order_district, $userId,$order_sub_district);

        return view('admin.order.ajax_list', compact('orderList', 'status'));
    }

    public function ordersList(Request $request)
    {
        $type = $request->type;
        $value = $request->value;

        $ordersList = $this->orderRepository->getOrdersAllData($type, $value);

        return response()->json(['data' => $ordersList, 'message' => '', 'status' => 1], 200);
        // return view('admin.order.order_list',compact('ordersList'));
    }

    public function exportFile(Request $request)
    {
        $status = $request->status;
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $userId = $request->userId;
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search, 'export', "", $date, $order_district, $userId,$order_sub_district);
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
                    if ($order->order_status == 1) {
                        $text = "Pending Order";
                    }
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
            $pdf = PDF::loadView('admin.pdf.order', ['orderList' => $orderList]);
            return $pdf->download('Order.pdf');
        }
    }

    public function show(string $id)
    {
        $order = $this->orderRepository->getDetailById($id);
        $states = $this->orderRepository->getAllStates();
        $page = "Order View";
        return view('admin.order.show', compact('order', 'states', 'page'));
    }

    public function confirmOrderQuery()
    {
        $status = request('status');
        if ($status == null) {
            $status = 2;
        }
        $text = "Confirm";
        if ($status == "1") {
            $text = "Pending";
        }
        if ($status == "4") {
            $text = "Cancel";
        }
        if ($status == "5") {
            $text = "Return";
        }
        if ($status == "6") {
            $text = "Delivered";
        }
        $page = $text . " Order List (" . Carbon::now()->format('d-m-Y') . ')';
        return view('admin.dashboard.order.index', compact('page', 'status'));
    }
    public function confirmOrderQueryAjax(Request $request)
    {
        $status = $request->status;
        $district = $request->district;
        $order_sub_district = $request->order_sub_district;
        $text = "Confirm";
        if ($status == "1") {
            $text = "Pending";
        }
        if ($status == "4") {
            $text = "Cancel";
        }
        if ($status == "5") {
            $text = "Return";
        }
        if ($status == "6") {
            $text = "Delivered";
        }

        $query = Order::where('order_status',$status)->when($status == "1", function ($query) {
            $query->whereDate('created_at', Carbon::now());
        })->when($status == "2", function ($query) {
            $query->whereDate('confirm_date', Carbon::now());
        })->when($status == "6", function ($query) {
            $query->whereDate('delivery_date', Carbon::now());
        })->when($status == "4", function ($query) {
            $query->whereDate('cancel_date', Carbon::now());
        })->when($status == "5", function ($query) {
            $query->whereDate('return_date', Carbon::now());
        })->when($district, function ($query)use($district) {
            $query->where('district', $district);
        })->when($order_sub_district, function ($query) use($order_sub_district) {
            $query->where('sub_district', $order_sub_district);
        })->latest()->paginate(15);
        return view('admin.dashboard.order.ajax_list', compact('query', 'status', 'text'));
    }

    public function confirmOrder()
    {
        $page = "Confirm Order List";
        return view('admin.order.confirm_order', compact('page'));
    }

    public function confirmAjaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 2;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate', "", $date, $order_district, $userId);

        return view('admin.order.ajax_list', compact('orderList', 'status'));
    }
    public function cancelOrder()
    {
        $page = "Cancel Order List";
        return view('admin.order.cancel_order', compact('page'));
    }

    public function cancelAjaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $status = 4;
        $order_district = $request->order_district;
        $userId = $request->userId;
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate', "", $date, $order_district, $userId,$order_sub_district);
        return view('admin.order.cancel_order_ajax_list', compact('orderList'));
    }

    public function deliveredAjaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->order_district;
        $status = 6;
        $userId = $request->userId;
        $order_sub_district = $request->order_sub_district;
        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate', "", $date, $order_district, $userId,$order_sub_district);

        return view('admin.order.delivered_order_ajax_list', compact('orderList'));
    }
    // public function confirmOrder(){
    //     return view('admin.order.confirm_order');
    // }
    // public function cancelOrder(){
    //     return view('admin.order.cancel_order');
    // }
    public function deliverOrder()
    {
        $page = "Completed Order List";
        return view('admin.order.deliver_order', compact('page'));
    }

    public function confirmExportCSV(Request $request)
    {
        $search = $request->search;
        $status = 2;

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
                    if ($order->order_status == 1) {
                        $text = "Pending Order";
                    }
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
            return $pdf->download('Confirm Order.pdf');
        }
    }

    public function generateOrderInvoice(Request $request)
    {
        $search = $request->search;
        $status = 2;

        $orderList = $this->orderRepository->getAllData($status, $search, 'export');
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L'
        ]);
        $cnt = 0;
        foreach ($orderList as $key => $orders) {
            $footer = '<table class="table table-hover" style="collapse; width: 100%; font-size: 18px;">
            <tbody>
                <tr>
                    <th style="padding: 5px 5px; width: 30%; border-bottom: 1 px solid black;">
                    <hr style="width: 100px">
                    <p>Sign</p>
                    </th>
                    <th style="padding: 5px 5px; width: 45%; border-bottom: 1 px solid black;">
                    
                    </th>
                    <th style="padding: 5px 5px; width: 45%; border-bottom: 1 px solid black;">
                    
                    </th>
                </tr>
            </tbody>
        </table>';
            $pdf->SetHTMLFooter($footer);
            $viewFile = view('admin.pdf.invoice_pdf', compact('orders'))->render();
            $pdf->WriteHTML($viewFile);
            if (count($orderList) !== $key + 1) {
                $pdf->AddPage();
            }
            $cnt = 1;
        }
        if ($cnt == 0) {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        return $pdf->Output('Order Invoice.pdf', "D");
    }

    public function generateSingleOrderInvoice(String $id)
    {
        $orders = $this->orderRepository->getDetailById($id);
        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L'
        ]);
        if (isset($orders)) {
            $footer = '<table class="table table-hover" style="collapse; width: 100%; font-size: 18px;">
            <tbody>
                <tr>
                    <th style="padding: 5px 5px; width: 30%; border-bottom: 1 px solid black;">
                    <hr style="width: 100px">
                    <p>Sign</p>
                    </th>
                    <th style="padding: 5px 5px; width: 45%; border-bottom: 1 px solid black;">
                    
                    </th>
                    <th style="padding: 5px 5px; width: 45%; border-bottom: 1 px solid black;">
                    
                    </th>
                </tr>
            </tbody>
        </table>';
            $pdf->SetHTMLFooter($footer);
            $viewFile = view('admin.pdf.invoice_pdf', compact('orders'))->render();
            $pdf->WriteHTML($viewFile);
        } else {
            $pdf->WriteHTML('<h5 style="text-align: center;">No Order Available.</h5>');
        }
        return $pdf->Output('Order Invoice.pdf', "D");
    }

    public function onDeliverOrder()
    {
        $driverId = request('driver_id');
        $page = "On Delivery Order List";
        return view('admin.confirmation.divert_transport', compact('driverId', 'page'));
    }

    public function updateOrderStatus(Request $request)
    {
        $data['order_status'] = $status = $request->status;
        if ($status == 2) {
            $data['confirm_date'] = Carbon::now();
        }
        if ($status == 4) {
            $data['cancel_date'] = Carbon::now();
        }
        if ($status == 5) {
            $data['return_date'] = Carbon::now();
        }
        if ($status == 6) {
            $data['delivery_date'] = Carbon::now();
        }
        $where['id'] = $request->order;
        $update = $this->orderRepository->update($data, $where);
        if ($update) {
            if($status ==6){
                
            }
            return response()->json(['data' => $update, 'message' => 'Order Status Updated Successfully.', 'status' => 1], 200);
        }
        return response()->json(['data' => "", 'message' => 'Something Went To Wrong.', 'status' => 0], 500);
    }

    public function removeOrderItem(Request $request){
        $where['id'] = $request->id;
        $delete = $this->orderRepository->deleteOrderItem($where);
        if ($delete) {
            return response()->json(['data' => '', 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
