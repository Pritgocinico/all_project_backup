<?php 

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\EmployeeOrderRepositoryInterface;
use App\Models\Order;
use Carbon\Carbon;

class EmployeeOrderRepository implements EmployeeOrderRepositoryInterface
{
    public function getAllData($search,$status,$district,$date,$orderStatusType,$type,$order_sub_district = "")
    {
        $query = $this->getQuery($search,$status,$district,$date,$orderStatusType,$order_sub_district)->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
    
    public function getQuery($search,$status,$district,$date,$orderStatusType = "",$order_sub_district){
        return Order::with('numberOrder')->where('created_by', auth()->user()->id)
        ->when($search,function ($query) use($search){
            $query->where('customer_name','like', '%'.$search.'%')
            ->orWhere('order_id','like','%'.$search.'%')
            ->orWhere('phoneno','like','%'.$search.'%');
        })->when($district, function($query) use($district){
            $query->where('district',$district);
        })->when($order_sub_district, function($query) use($order_sub_district){
            $query->where('sub_district',$order_sub_district);
        })->when($orderStatusType == 6,function($query)use($date){
            $query->when($date,function($query) use($date){
                $date1 = explode('/',$date);
                $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
            });
        })->when($orderStatusType !== 6,function($query)use($date){
            $query->when($date,function($query) use($date){
                $date1 = explode('/',$date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
            });
        })->when($status,function($query) use($status){
            $query->where('order_status',$status);
        })->when($orderStatusType != "all",function($query) use($orderStatusType){
            $query->where('order_status',$orderStatusType);
        });
    }

    public function getUseDistrictOrder(){
        return Order::with('districtDetail')->select('district')->get()->keyBy('district');
    }

    public function getAllDataDriver($search,$status,$district,$date,$type){
        $query =  Order::with('numberOrder')->where('driver_id',Auth()->user()->id)->where('order_status','3')
        ->when($search,function ($query) use($search){
            $query->where('customer_name','like', '%'.$search.'%')
            ->orWhere('order_id','like','%'.$search.'%')
            ->orWhere('phoneno','like','%'.$search.'%');
        })->when($district, function($query) use($district){
            $query->where('district',$district);
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereDate('confirm_date', '>=', $date1[0])
            ->whereDate('confirm_date', '<=', $date1[1]);
        })->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }

        return $query;
    }

    public function getUserOrder(){
        return Order::with('userDetail')->select('created_by')->get()->keyBy('created_by');
    }

    public function getUseSubDistrictOrder($district = ""){
        return Order::with('subDistrictDetail')
        ->when($district,function($query)use($district){
            $query->where('district',$district);
        })
        ->select('district','sub_district')->get()->keyBy('sub_district');
    }
    public function getAllDataTransport($search,$status,$district,$date,$type){
        $query =  Order::with('numberOrder')->where('order_status','3')
        ->when($search,function ($query) use($search){
            $query->where('customer_name','like', '%'.$search.'%')
            ->orWhere('order_id','like','%'.$search.'%')
            ->orWhere('phoneno','like','%'.$search.'%');
        })->when($district, function($query) use($district){
            $query->where('district',$district);
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereDate('created_at', '>=', $date1[0])
            ->whereDate('created_at', '<=', $date1[1]);
        })->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }

        return $query;
    }

}