<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeadRequest;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\LeadRepositoryInterface;
use App\Interfaces\LeaveRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class EmployeeLeadController extends Controller
{
    protected $orderRepository, $categoryRepository, $productRepository, $employeeRepository, $leadRepository = "";
    public function __construct(CategoryRepositoryInterface $categoryRepository, OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository, EmployeeRepositoryInterface $employeeRepository, LeadRepositoryInterface $leadRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->employeeRepository = $employeeRepository;
        $this->leadRepository = $leadRepository;
        $leadDistricts = $this->leadRepository->getUseDistrictLead();
        view()->share('leadDistricts', $leadDistricts);
    }
    public function index()
    {
        $page = "Lead List";
        return view('employee.lead.index', compact('page'));
    }

    public function create()
    {
        $page = "Create Lead";
        $categoryList = $this->categoryRepository->getAllCategoryWithChild();
        $state = $this->orderRepository->getAllStates();
        $district = $this->orderRepository->getAllDistrict();
        $employeeList = $this->employeeRepository->getAllEmployee();
        $subdistricts = $this->orderRepository->getSubDistrict();
        $villages = $this->orderRepository->getVillages();
        $villageList = $this->employeeRepository->getVillages();
        return view('employee.lead.create', compact('state', 'district', 'employeeList', 'categoryList', 'villageList', 'subdistricts', 'villages', 'page'));
    }

    public function store(CreateLeadRequest $request)
    {
        $totalOrder = $this->leadRepository->leadCount();

        $id = 0;
        if ($totalOrder > 0) {
            $id = $this->leadRepository->getLastInsertId();
        }
        $id = $id + 1;
        $str_length = 4;

        $str = substr("0000{$id}", -$str_length);
        $lead_id = 'LD-' . $str;
        $data = [
            'lead_id' => $lead_id,
            'customer_name' => $request->customer_name,
            'phone_no' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'district' => $request->district,
            'sub_district' => $request->sub_district,
            'village' => $request->village,
            'pin_code' => $request->pincode,
            'expected_delivery_date' => $request->excepted_delievery_date,
            'remarks' => $request->remarks,
            'created_by' => Auth()->user()->id,
        ];
        $data['lead_status'] = $request->order_lead == "on" ? '1' : '0';
        $data['lead_follow_date_time']   = $request->lead_datetime;
        $data['amount']            = $request->amount;
        $data['divert_note']      = $request->divert_note;
        if ($request->order_lead == "on") {
            $id = 0;
            if ($totalOrder > 0) {
                $id = $this->orderRepository->getLastInsertId();
            }
            $id = $id + 1;
            $str_length = 4;

            $str = substr("0000{$id}", -$str_length);
            $data['order_id'] = 'AG-' . $str;
            $data['phoneno'] = $request->phoneno;

            $insert = $this->orderRepository->store($data);
        } else {

            $insert = $this->leadRepository->store($data);
        }
        
        if ($insert) {
            $log = 'Lead (' . $lead_id . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Lead', $log);
            if (!empty($request->category)) {
                $category  = $request->category;
                $product   = $request->products;
                $variant   = $request->variant;
                $quantity     = $request->quantity;
                $total        = $request->product_total;
                $price        = $request->pr_price;
                $stock        = $request->stock;
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
                        foreach ($stock as $key4 => $val4) {
                            if ($key == $key4) {
                                $v_stock = $val4;
                            }
                        }
                        $orderItem = [
                            'category_id' => $value,
                            'product_id' => $v_product,
                            'variant_id' => $v_variant,
                            'quantity' => $v_qty,
                            'price' => $v_price,
                            'amount' => $v_total,
                            'stock' => $v_stock,
                            'created_by' => Auth::user()->id,
                        ];
                        if ($request->order_lead == "on") {
                            $orderItem['order_id'] = $insert->id;
                            $this->orderRepository->storeOrderItem($orderItem);
                        } else {
                            $orderItem['lead_id'] = $insert->id;
                            $this->leadRepository->storeLeadItem($orderItem);
                        }
                    }
                }
            }
            return redirect('employee/employee-lead')->with('success', 'Lead Created Successfully.');
        }
        return redirect('employee/employee-lead/create')->with('error', 'Something went to wrong.');
    }

    public function getLeadDetail(Request $request)
    {
        $leadList = $this->leadRepository->getLeadDetails($request->type, $request->value);
        return response()->json(['data' => $leadList, 'message' => '', 'status' => 1], 200);
    }

    public function ajaxList(Request $request)
    {
        $leadList = $this->leadRepository->getAllLeadDetail($request->search, $request->date, $request->district);
        return view('employee.lead.ajax_list', compact('leadList'));
    }

    public function exportCSV(Request $request)
    {
        $leadList = $this->leadRepository->getAllLeadDetailExport($request->search, $request->date, $request->district);
        if ($request->format == "excel" || $request->format == "csv") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Lead.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Lead Id', 'Customer Name', 'Phone Number', 'Amount', 'status', 'District', 'Created By', 'Created At');
            $callback = function () use ($leadList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($leadList as $order) {

                    $text = "Completed";
                    $district = isset($order->districtDetail) ? $order->districtDetail->district_name : "";
                    $user = isset($order->userDetail) ? $order->userDetail->name : '';
                    $date = "";
                    if (isset($order->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($order->created_at);
                    }
                    fputcsv($file, array($order->lead_id, $order->customer_name, $order->phone_no, $order->amount, $text, $district, $user, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.lead', ['orderList' => $leadList]);
            return $pdf->download('Lead.pdf');
        }
    }
    public function show(string $id)
    {
        $lead = $this->leadRepository->getDetailById($id);
        $page = "Lead View";
        return view('employee.lead.show', compact('lead', 'page'));
    }

    public function convertLeadToOrder(Request $request)
    {
        $id = $request->id;
        $lead = $this->leadRepository->getDetailById($id);
        if (isset($lead)) {
            $totalOrder = $this->orderRepository->orderCount();

            $id = 0;
            if ($totalOrder > 0) {
                $id = $this->orderRepository->getLastInsertId();
            }
            $id = $id + 1;
            $str_length = 4;

            $str = substr("0000{$id}", -$str_length);
            $order_id = 'AG-' . $str;
            $data = [
                'order_id' => $order_id,
                'customer_name' => $lead->customer_name,
                'phoneno' => $lead->phone_no,
                'address' => $lead->address,
                'state' => $lead->state,
                'district' => $lead->district,
                'sub_district' => $lead->sub_district,
                'village' => $lead->village,
                'pincode' => $lead->pincode,
                'excepted_delievery_date' => $lead->excepted_delievery_date,
                'created_by' => Auth::user()->id,
            ];
            $data['order_lead_status']            = $lead->lead_status;
            $data['lead_followup_datetime']            = $lead->lead_follow_date_time;
            $data['amount']            = $lead->amount;
            $insert = $this->orderRepository->store($data);
            if (isset($lead->leadDetail) && $insert) {
                $log = 'Order (' . $order_id . ') Created by ' . ucfirst(Auth()->user()->name);
                UserLogHelper::storeLog('Order', $log);
                $log = 'Lead (' . $lead->lead_id . ') Converted into order ' . ucfirst(Auth()->user()->name);
                UserLogHelper::storeLog('Lead', $log);
                if ($lead->leadDetail !== []) {
                    foreach ($lead->leadDetail as $key => $leadDetail) {
                        $orderItem = [
                            'order_id' => $insert->id,
                            'category_id' => $leadDetail->category_id,
                            'product_id' => $leadDetail->product_id,
                            'variant_id' => $leadDetail->variant_id,
                            'quantity' => $leadDetail->quantity,
                            'price' => $leadDetail->price,
                            'amount' => $leadDetail->price * $leadDetail->quantity,
                            'stock' => $leadDetail->stock,
                            'created_by' => Auth::user()->id,
                        ];
                        $this->orderRepository->storeOrderItem($orderItem);
                    }
                }
                return response()->json(['data' => '', 'message' => 'Lead Converted Successfully.', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something went to wrong.', 'status' => 1], 500);
        }
        return response()->json(['data' => '', 'message' => 'Something went to wrong.', 'status' => 1], 500);
    }
}
