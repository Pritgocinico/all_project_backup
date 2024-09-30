<?php

namespace App\Http\Controllers\sale_service;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\FeedbackRepositoryInterface;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;
use PDF;

class AfterSaleServiceController extends Controller
{
    protected $orderRepository,$feedbackRepository,$adminDashboardRepository,$employeeOrderRepository;
    public function __construct(OrderRepositoryInterface $orderRepository,FeedbackRepositoryInterface $feedbackRepository,AdminDashboardRepositoryInterface $adminDashboardRepository,EmployeeOrderRepositoryInterface $employeeOrderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->feedbackRepository = $feedbackRepository;
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->employeeOrderRepository = $employeeOrderRepository;
    }
    public function index(){
        $page = "After Sale Service Dashboard";
        return view('sale_service.index',compact('page'));
    }

    public function dashboardAjax(Request $request){
        $date = $request->date;
        $data['orderCount'] =$this->adminDashboardRepository->totalOrderCount('6',$date);
        $data['feedbackCount'] =$this->feedbackRepository->totalOrderFeedback($date);
        return response()->json($data);
    }

    public function orderList(){
        $page = "Delivered Order List";
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        $orderSubDistricts = $this->employeeOrderRepository->getUseSubDistrictOrder();
        return view('sale_service.order.index',compact('page','orderDistricts','orderSubDistricts'));
    }
    public function orderAjaxList(Request $request){
        $search = $request->search;
        $district = $request->district;
        $subDistrict = $request->sub_district;
        $orderList = $this->orderRepository->getFeedbackOrders($search,$district,$subDistrict);
        return view('sale_service.order.ajax_list',compact('orderList'));
    }
    
    public function feedbackDetail(Request $request){
        $orderId = $request->order_id;
        $feedback = $this->feedbackRepository->getDetailByOrderId($orderId);
        return response()->json($feedback);
    }
    
    public function storeFeedbackDetail(Request $request){
        $feedback = $this->feedbackRepository->getDetailByOrderId($request->order_id);
        if(isset($feedback)){
            $updateDetail['rating'] = $request->rating;
            $updateDetail['order_description'] = $request->order_feedback;
            $where['order_id'] = $request->order_id;
            $update = $this->feedbackRepository->update($updateDetail,$where);
            if($update){
                $log =  'Rating Updated on Order('.$request->order_id.')' . ucfirst(Auth()->user()->name);
                UserLogHelper::storeLog('Rating', $log);
                return response()->json(['data' => '', 'message' => 'Rating Updated Successfully', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
        }
        $data['order_id'] = $request->order_id;
        $data['rating'] = $request->rating;
        $data['order_description'] = $request->order_feedback;
        $insert = $this->feedbackRepository->store($data);
        if($insert){
            $log =  'Rating added on Order('.$request->order_id.')' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Rating', $log);
            return response()->json(['data' => '', 'message' => 'Rating Added Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function feedbackList(Request $request){
        $page = "Order Feedback List";
        $orderDistricts = $this->employeeOrderRepository->getUseDistrictOrder();
        $orderSubDistricts = $this->employeeOrderRepository->getUseSubDistrictOrder();
        return view('admin.feedback.index',compact('page','orderDistricts','orderSubDistricts'));
    }
    public function feedbackAjaxList(Request $request){
        $search = $request->search;
        $district = $request->district;
        $sub_district = $request->sub_district;
        $feedbackList = $this->feedbackRepository->getAllData($search,$district,$sub_district,'paginate');
        return view('admin.feedback.ajax_list',compact('feedbackList'));
    }

    public function exportCSV(Request $request){
        $search = $request->search;
        $district = $request->district;
        $sub_district = $request->sub_district;
        $format = $request->format;
        $feedbackList = $this->feedbackRepository->getAllData($search,$district,$sub_district,'export');
        if($format == 'csv' || $format == 'excel'){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Feedback.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Order Id', 'District Name', 'Sub District Name', 'Rating', 'Description', 'Created At');
            $callback = function () use ($feedbackList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($feedbackList as $feedback) {
                    $districtName = "";
                    if (isset($feedback->orderDetail) && isset($feedback->orderDetail->districtDetail)){
                        $districtName = $feedback->orderDetail->districtDetail->district_name;
                    }
                    $subDistrictName = "";
                    if (isset($feedback->orderDetail) && isset($feedback->orderDetail->subDistrictDetail)){
                        $subDistrictName = $feedback->orderDetail->subDistrictDetail->sub_district_name;
                    }
                    $orderId = isset($feedback->orderDetail) ? $feedback->orderDetail->order_id : '';
                    $rating = $feedback->rating ??0;
                    $date = "";
                    if (isset($feedback->created_at)){
                        $date = UtilityHelper::convertDmyWith12HourFormat($feedback->created_at);
                    }
                    fputcsv($file, array($orderId, $districtName, $subDistrictName, $rating, $feedback->order_description,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } else {
            $pdf = PDF::loadView('admin.pdf.feedback', ['feedbackList' => $feedbackList]);
            return $pdf->download('Feedback List.pdf');
        }
        // return Excel::download(new FeedbackExport($feedbackList), 'feedback.csv');
    }
}
