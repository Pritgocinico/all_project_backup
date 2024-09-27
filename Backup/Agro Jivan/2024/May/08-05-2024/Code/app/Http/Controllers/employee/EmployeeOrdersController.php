<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\LeadRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\SchemeRepositoryInterface;
use PDF;

class EmployeeOrdersController extends Controller
{
    protected $employeeOrderRepository, $orderRepository, $categoryRepository, $productRepository, $employeeRepository, $leadRepository, $schemeRepository = "";
    public function __construct(EmployeeOrderRepositoryInterface $employeeOrderRepository, CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, EmployeeRepositoryInterface $employeeRepository, LeadRepositoryInterface $leadRepository, SchemeRepositoryInterface $schemeRepository)
    {
        $this->employeeOrderRepository = $employeeOrderRepository;
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
        $this->leadRepository = $leadRepository;
        $this->schemeRepository = $schemeRepository;
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        $orderSubDistricts = $this->employeeOrderRepository->getUseSubDistrictOrder();
        $viewData = [
            'orderDistricts' => $orderDistricts,
            'orderSubDistricts' => $orderSubDistricts,
        ];
        view()->share($viewData);
    }

    public function index()
    {
        $type = "";
        $page = "Employee Orders";
        return view('employee.order.index', compact('type', 'page'));
    }

    public function create()
    {
        $page = "Create Order";
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $state = $this->orderRepository->getAllStates();
        $district = $this->orderRepository->getAllDistrict();
        $villages = $this->orderRepository->getVillages();
        $subdistricts = $this->orderRepository->getSubDistrict();
        $employeeList = $this->employeeRepository->getAllEmployee();
        $allScheme = $this->schemeRepository->getAllScheme('', 'export');
        return view('employee.order.create', compact('allScheme', 'state', 'district', 'employeeList', 'categoryList', 'villages', 'subdistricts', 'page'));
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
        if ($request->orderlead == "on") {
            $id = 0;
            if ($totalOrder > 0) {
                $id = $this->leadRepository->getLastInsertId();
            }
            $id = $id + 1;
            $str_length = 4;

            $str = substr("0000{$id}", -$str_length);
            $data['lead_id'] = 'LD-' . $str;
            $data['phone_no'] = $request->phoneno;
            $insert = $this->leadRepository->store($data);
        } else {
            $insert = $this->orderRepository->store($data);
        }
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
                if ($category !== null) {
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
                            $orderItem = [
                                'category_id' => $value,
                                'product_id' => $v_product,
                                'variant_id' => $v_variant,
                                'quantity' => $v_qty,
                                'price' => $v_price,
                                'amount' => $v_total,
                                'created_by' => Auth::user()->id,
                            ];
                            if ($request->orderlead == "on") {
                                $orderItem['lead_id'] = $insert->id;
                                $this->leadRepository->storeLeadItem($orderItem);
                            } else {
                                $orderItem['order_id'] = $insert->id;
                                $this->orderRepository->storeOrderItem($orderItem);
                            }
                        }
                    }
                }
                if ($request->orderlead !== "on") {
                    if (!empty($request->free_product_variant_id)) {
                        foreach ($request->free_product_variant_id as $key1 => $variantID) {
                            if ($variantID !== null) {

                                $product = $this->productRepository->getDetailById($request->free_product_variant_id[$key1]);
                                $code = $this->schemeRepository->getSchemeCodeDetailByProduct($request->free_product_variant_id[$key1], $variantID);
                                $freeProduct = [
                                    'order_id' => $insert->id,
                                    'category_id' => $product->id,
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
                }
            }
            return redirect('employee/employee-orders')->with('success', 'Orders Created Successfully.');
        }
        return redirect('employee/employee-orders/create')->with('error', 'Something went to wrong.');
    }

    public function edit(string $id)
    {
        $page = "Edit Order";
        $orderData = $this->orderRepository->getDetailById($id);
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $state = $this->orderRepository->getAllStates();
        $district = $this->orderRepository->getAllDistrict();
        $employeeList = $this->employeeRepository->getAllEmployee();
        $subdistrict = $this->orderRepository->getAllSubDistrict($orderData->district);
        $village = $this->orderRepository->getAllVillage($orderData->sub_district);
        $villages = $this->orderRepository->getVillages();
        $allScheme = $this->schemeRepository->getAllScheme('', 'export');
        return view('employee.order.edit', compact('allScheme', 'orderData', 'categoryList', 'state', 'district', 'employeeList', 'subdistrict', 'village', 'villages', 'page'));
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
        $data['amount'] = $request->amount;
        $where['id'] = $id;
        if ($request->orderlead == "on") {
            $id = 0;
            $totalLead = $this->leadRepository->leadCount();
            if ($totalLead > 0) {
                $id = $this->leadRepository->getLastInsertId();
            }
            $id = $id + 1;
            $str_length = 4;

            $str = substr("0000{$id}", -$str_length);
            $data['lead_id'] = 'LD-' . $str;
            $data['phone_no'] = $request->phoneno;
            $data['created_by'] = Auth::user()->id;
            $update = $this->leadRepository->store($data);
            // $delete
        } else {
            $update = $this->orderRepository->update($data, $where);
        }
        if ($update) {
            $orderData = $this->orderRepository->getDetailById($id);
            $log = 'Order (' . $orderData->order_id . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Order', $log);
            $where = ['product_id' => $id];
            if ($request->category !== null) {
                foreach ($request->category as $key => $category) {
                    $variant = [
                        'category_id' => $category,
                        'product_id' => $request->products[$key],
                        'variant_id' => $request->variant[$key],
                        'price' => $request->price[$key],
                        'quantity' => $request->quantity[$key],
                        'amount' => $request->product_total[$key],
                        'discount_code' => $request->scheme_code[$key],
                        'order_id' => $id,
                    ];
                    if ($request->orderlead == "on") {
                        $this->leadRepository->storeLeadItem($variant);
                    } else {
                        $productVariantExist = $this->orderRepository->checkOrderRepository($request->ids[$key], $id);
                        if (isset($productVariantExist)) {
                            $this->orderRepository->updateProductVariant($variant, $where);
                        } else {
                            $this->orderRepository->storeOrderItem($variant);
                        }
                    }
                }
            }
            if ($request->orderlead == "on") {
                $whereOrder['id'] = $id;
                $this->orderRepository->deleteOrder($whereOrder);
            }
            return redirect('employee/employee-orders')->with('success', 'Orders Updated Successfully.');
        }
        return redirect('employee/employee-orders/create')->with('error', 'Something went to wrong.');
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

    public function ordersList(Request $request)
    {
        $type = $request->type;
        $value = $request->value;

        $ordersList = $this->orderRepository->getOrdersAllData($type, $value);

        return response()->json(['data' => $ordersList, 'message' => '', 'status' => 1], 200);
    }

    public function OrderajaxList(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;

        $orderList = $this->employeeOrderRepository->getAllData($search, $status, $district, $date, "all", 'paginate', $order_sub_district);

        return view('employee.order.ajax_list', compact('orderList'));
    }

    public function pendingOrder()
    {
        $type = "pending";
        $page = "Employee Pending Order List";
        return view('employee.order.pending_order', compact('type', 'page'));
    }

    public function pendingOrderList(Request $request)
    {
        $search = $request->search;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;
        $pending_order = $this->employeeOrderRepository->getAllData($search, "", $district, $date, 1, 'paginate', $order_sub_district);

        return view('employee.order.pending_list', compact('pending_order'));
    }

    public function cancelOrder()
    {
        $type = "cancel";
        $page = "Employee Cancel Order List";
        return view('employee.order.cancel_order', compact('type', 'page'));
    }

    public function cancelOrderList(Request $request)
    {
        $search = $request->search;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;
        $cancel_order = $this->employeeOrderRepository->getAllData($search, "", $district, $date, 4, 'paginate', $order_sub_district);

        return view('employee.order.cancel_list', compact('cancel_order'));
    }

    public function returnOrder()
    {
        $type = "return";
        $page = "Employee Return Order List";
        return view('employee.order.return_order', compact('type', 'page'));
    }

    public function returnOrderList(Request $request)
    {
        $search = $request->search;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;

        $return_order = $this->employeeOrderRepository->getAllData($search, "", $district, $date, 5, 'paginate', $order_sub_district);

        return view('employee.order.return_list', compact('return_order'));
    }

    public function completedOrder()
    {
        $type = "complete";
        $page = "Employee Delivered Order List";
        return view('employee.order.completed_order', compact('type', 'page'));
    }

    public function completedOrderList(Request $request)
    {
        $search = $request->search;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;
        $completed_order = $this->employeeOrderRepository->getAllData($search, "", $district, $date, 6, 'paginate', $order_sub_district);
        return view('employee.order.completed_list', compact('completed_order'));
    }
    public function exportCSV(Request $request)
    {

        $search = $request->search;
        $status = $request->status;
        $district = $request->district;
        $date = $request->date;
        $type = $request->type;
        $orderStatus = "all";
        $fileName = 'User All Order Export';
        if ($type == "pending") {
            $orderStatus = 1;
            $fileName = 'User Pending Order Export';
        }
        if ($type == "cancel") {
            $orderStatus = 4;
            $fileName = 'User Cancel Order Export';
        }
        if ($type == 'return') {
            $orderStatus = 5;
            $fileName = 'User Return Order Export';
        }
        if ($type == "complete") {
            $orderStatus = 6;
            $fileName = 'User Complete Order Export';
        }
        $orderList = $this->employeeOrderRepository->getAllData($search, $status, $district, $date, $orderStatus, 'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=" . $fileName . ".csv",
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
                    if ($order->order_status == 6) {
                        $text = "Completed";
                    }
                    $district = isset($order->districtDetail) ? $order->districtDetail->district_name : "";
                    $user = isset($order->userDetail) ? $order->userDetail->name : '';
                    $date = "";
                    if (isset($order->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($order->created_at);
                    }
                    fputcsv($file, array($order->order_id, $order->customer_name, $order->phoneno, $order->amount, $text, $district, $user, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.order', ['orderList' => $orderList]);
            return $pdf->download($fileName . '.pdf');
        }
    }
    public function show(string $id)
    {
        $order = $this->orderRepository->getDetailById($id);
        $page = "View Order";
        return view('employee.order.show', compact('order', 'page'));
    }

    public function confirmOrder()
    {
        $type = "confirm";
        $page = "Employee Confirm Order List";
        return view('employee.order.confirm_order', compact('type', 'page'));
    }

    public function confirmOrderList(Request $request)
    {
        $search = $request->search;
        $district = $request->district;
        $date = $request->date;
        $order_sub_district = $request->order_sub_district;
        $return_order = $this->employeeOrderRepository->getAllData($search, "", $district, $date, 2, 'paginate', $order_sub_district);

        return view('employee.order.confirm_order_list', compact('return_order'));
    }
}
