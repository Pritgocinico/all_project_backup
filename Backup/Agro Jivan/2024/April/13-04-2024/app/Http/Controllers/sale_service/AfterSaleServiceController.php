<?php

namespace App\Http\Controllers\sale_service;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\FeedbackRepositoryInterface;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class AfterSaleServiceController extends Controller
{
    protected $orderRepository,$feedbackRepository;
    public function __construct(OrderRepositoryInterface $orderRepository,FeedbackRepositoryInterface $feedbackRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->feedbackRepository = $feedbackRepository;
    }
    public function index(){
        $page = "After Sale Service Dashboard";
        $orderCount = count($this->orderRepository->getAllData('6',"",'export'));
        $feedbackCount = $this->feedbackRepository->totalOrderFeedback();
        return view('sale_service.index',compact('orderCount','feedbackCount','page'));
    }

    public function orderList(){
        $page = "Delivered Order List";
        return view('sale_service.order.index',compact('page'));
    }
    public function orderAjaxList(Request $request){
        $search = $request->search;
        $orderList = $this->orderRepository->getFeedbackOrders($search);
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

    public function feedbackList(){
        $page = "Order Feedback List";
        return view('admin.feedback.index',compact('page'));
    }
    public function feedbackAjaxList(Request $request){
        $search = $request->search;
        $feedbackList = $this->feedbackRepository->getAllData($search);
        return view('admin.feedback.ajax_list',compact('feedbackList'));
    }
}
