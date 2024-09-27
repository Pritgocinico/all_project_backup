<?php 

namespace App\Repositories;

use App\Interfaces\FeedbackRepositoryInterface;
use App\Models\OrderFeedback;

class FeedbackRepository implements FeedbackRepositoryInterface
{
    public function getDetailByOrderId($id){
        return OrderFeedback::where('order_id',$id)->first();
    }

    public function store($data){
        return OrderFeedback::create($data);
    }

    public function update($updateDetail,$where){
        return OrderFeedback::where($where)->update($updateDetail);
    }

    public function getAllData($search){
        return OrderFeedback::with('orderDetail')
        ->when($search,function($query)use($search){
            $query->where('rating','like','%'.$search.'%')
            ->orWhere('order_description','like','%'.$search.'%')
            ->orWhere('order_id','like','%'.$search.'%');
        })->latest()->paginate(15);
    }

    
    public function totalOrderFeedback(){
        return OrderFeedback::count();
    }
}