<?php

namespace App\Http\Controllers\confirm;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use App\Interfaces\AdminDashboardRepositoryInterface;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;

class ConfirmationDepartmentController extends Controller
{
    protected $orderRepository, $employeeRepository, $adminDashboardRepository = "";
    public function __construct(AdminDashboardRepositoryInterface $adminDashboardRepository, OrderRepositoryInterface $orderRepository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->orderRepository = $orderRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $page = "Confirmation Department Dashboard";
        return view('confirm.index', compact('page'));
    }

    public function orderAjax(Request $request)
    {
        $date = $request->date;
        $data['totalCount'] = $this->adminDashboardRepository->totalAllOrderCount($date);
        $data['pendingCount'] = $this->adminDashboardRepository->totalOrderCount('1', $date);
        $data['confirmCount'] = $this->adminDashboardRepository->totalOrderCount('2', $date);
        $data['cancelCount'] = $this->adminDashboardRepository->totalOrderCount('4', $date);
        $data['returnCount'] = $this->adminDashboardRepository->totalOrderCount('5', $date);
        $data['completeCount'] = $this->adminDashboardRepository->totalOrderCount('6', $date);
        return response()->json($data);
    }

    public function pendingOrderList()
    {
        return view('confirm.order.pending');
    }

    public function pendingOrderAjax(Request $request)
    {
        $search = $request->search;
        $status = 1;

        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate');
        return view('confirm.order.ajax_list', compact('orderList'));
    }

    public function confirmOrderList()
    {
        $employeeList = $this->employeeRepository->getAllData('', 5, '', 'export');
        return view('confirm.assign_driver.confirm_order', compact('employeeList'));
    }

    public function confirmOrderAjax(Request $request)
    {
        $search = $request->search;
        $status = 2;

        $orderList = $this->orderRepository->getAllData($status, $search, 'paginate');
        return view('confirm.assign_driver.ajax_list', compact('orderList'));
    }

    public function assignDriver(Request $request)
    {
        $update['driver_id'] = $request->driver_id;
        $update['order_status'] = 3;
        $where['id'] = $request->order_id;

        $update = $this->orderRepository->update($update, $where);
        if ($update) {
            $order = $this->orderRepository->getDetailById($request->order_id);
            $log =  'Order (' . $order->order_id . ') Assign Driver Confirmation Department' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('order', $log);
            return response()->json(['data' => '', 'message' => 'Order Status Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function topFiveConfirmOrder()
    {
        $page = "Top Five Employee List";
        return view('confirm.top_order.index', compact('page'));
    }

    public function topFiveConfirmOrderAjax(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $userList = $this->employeeRepository->getTopFiveConfirmOrder($search, $date);
        return view('confirm.top_order.ajax_list', compact('userList'));
    }

    public function chartAjax(Request $request)
    {
        $date = $request->date;
        $pending = count($this->orderRepository->getAllData(1, '', 'export', "", $date));
        $confirmed = count($this->orderRepository->getAllData(2, '', 'export', "", $date));
        $delivered = count($this->orderRepository->getAllData(3, '', 'export', "", $date));
        $canceled = count($this->orderRepository->getAllData(4, '', 'export', "", $date));
        $returned = count($this->orderRepository->getAllData(5, '', 'export', "", $date));
        $completed = count($this->orderRepository->getAllData(6, '', 'export', "", $date));
        $data = [
            'labels' => ['Pending', 'Confirmed', 'Delivered', 'Canceled', 'Returned', 'Completed'],
            'data' => [$pending, $confirmed, $delivered, $canceled, $returned, $completed],
        ];
        return response()->json($data);
    }

    public function winnerList(Request $request)
    {
        $page = "Top Winner List";
        $date = request('date');
        $type = request('type');
        return view('admin.winner.index', compact('page', 'date', 'type'));
    }
    public function winnerListAjax(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $type = $request->type;
        if (Auth()->user() !== null && Auth()->user()->role_id == '1') {
            $winnerData = $this->getWinnerAdmin($type, $search);
        } else {
            $winnerData = $this->getWinnerArray($date, $search);
        }
        return view('admin.winner.ajax_list', compact('winnerData'));
    }
    public function winnerListExport(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $format = $request->format;
        $type = $request->type;
        if (Auth()->user() !== null && Auth()->user()->role_id == '1') {
            $winnerData = $this->getWinnerAdmin($type, $search);
        } else {
            $winnerData = $this->getWinnerArray($date, $search);
        }
        if ($format == "csv" || $format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Winner list.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('User Name', 'Order');
            $callback = function () use ($winnerData, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($winnerData as $winner) {
                    fputcsv($file, array($winner['winner'], $winner['order']));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {

            $pdf = PDF::loadView('admin.pdf.winner', ['winnerData' => $winnerData]);
            return $pdf->download('Winner list.pdf');
        }
    }

    public function getWinnerArray($date, $search)
    {
        $users = DB::table('users')->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->where('role_id', '!=', 1)->get();
        $data1 = [];
        $status = "6";
        if (Auth()->user() !== null && Auth()->user()->role_id == 2) {
            $status = "1";
        }
        $date1 = explode('/', $date);
        foreach ($users as $user) {
            $data = [];
            $order = DB::table('orders')->where('order_status', $status)->where('created_by', $user->id)
                ->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1])
                ->count();
            $data[$user->name] = $order;
            array_push($data1, $data);
        }
        $winner = 0;
        sort($data1);
        $winnerData = [];
        foreach ($data1 as $orders) {
            $com = [];
            foreach ($orders as $key => $value) {
                $winner = $key;
                $com['winner'] = $winner;
                $com['order'] = $value;
                array_push($winnerData, $com);
            }
        }
        usort($winnerData, function ($a, $b) {
            return $a['order'] < $b['order'];
        });
        return $winnerData;
    }
    public function getWinnerAdmin($type, $search)
    {
        $users = DB::table('users')->where('role_id', '!=', 1)->get();
        $data1 = [];
        foreach ($users as $user) {
            $data = [];
            $order = DB::table('orders')
                ->where('created_by', $user->id)
                ->when($type == 'daily', function ($query) {
                    $query->whereDate('created_at', \Carbon\Carbon::today());
                })
                ->when($type == 'weekly', function ($query) {
                    $query->whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
                })
                ->when($type == 'monthly', function ($query) {
                    $query->whereMonth('created_at', \Carbon\Carbon::now()->month);
                })
                ->count();
            $data[$user->name] = $order;
            array_push($data1, $data);
        }
        $cnt = 0;
        $winner = 0;
        sort($data1);
        $winnerData = [];
        foreach ($data1 as $ky => $orders) {
            $com = [];
            foreach ($orders as $key => $value) {
                $cnt = $value;
                $winner = $key;
                $com['winner'] = $winner;
                $com['order'] = $value;
                array_push($winnerData, $com);
            }
        }
        usort($winnerData, function ($a, $b) {
            return $a['order'] < $b['order'];
        });

        return $winnerData;
    }

    public function confirmOrderItem(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->district;
        $order_sub_district = $request->order_sub_district;
        $status = $request->status;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search, 'export', "", $date, $order_district, $userId,$order_sub_district);
        $data = $this->getConfirmOrderItem($orderList);
        return response()->json($data);
    }

    public function generateConfirmOrderItemPDF(Request $request){
        $search = $request->search;
        $date = $request->date;
        $order_district = $request->district;
        $order_sub_district = $request->order_sub_district;
        $status = $request->status;
        $userId = $request->userId;
        $orderList = $this->orderRepository->getAllData($status, $search,'export',"",$date,$order_district,$userId,$order_sub_district);
        $data = $this->getConfirmOrderItem($orderList);
        $pdf = PDF::loadView('admin.pdf.pending_order_item', ['data' => $data]);
        return $pdf->download('Confirm Order Product.pdf');
    }
    public function getConfirmOrderItem($orderList){
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
}
