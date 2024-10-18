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

    public function getAllData($search,$district,$sub_district,$type){
        $query = OrderFeedback::with('orderDetail','orderDetail.districtDetail','orderDetail.subDistrictDetail')
        ->when($search,function($query)use($search){
            $query->where('rating','like','%'.$search.'%')
            ->orWhere('order_description','like','%'.$search.'%')
            ->orWhere('order_id','like','%'.$search.'%');
        })->when($district,function($query)use($district){
            $query->whereHas('orderDetail',function($query)use($district){
                $query->where('district','like','%'.$district.'%');
            });
        })->when($sub_district,function($query) use($sub_district){
            $query->whereHas('orderDetail',function($query) use($sub_district){
                $query->where('sub_district','like','%'.$sub_district.'%');
            });
        })->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }

    
    public function totalOrderFeedback($date = ""){
        return OrderFeedback::when($date,function($query)use($date){
            $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->count();
    }
}