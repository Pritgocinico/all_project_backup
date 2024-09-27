<?php

namespace App\Repositories;

use App\Interfaces\TransportDepartmentRepositoryInterface;
use App\Models\Order;
use App\Models\SubLocation;

class TransportDepartmentRepository implements TransportDepartmentRepositoryInterface
{
    public function getVillageDetailBySubDistrict($districtId){
        return SubLocation::select('village_code','village_name')->whereIn('sub_district',$districtId)->get();
    }

    public function confirmOrderList($userId,$date,$district,$subDistrict,$village,$customer_name,$type){
        $query = Order::with('orderItem','orderItem.productDetail','orderItem.varientDetail')->where('order_status','2')->when($userId,function($query)use($userId){
            $query->where('created_by',$userId);
        })->when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('confirm_date', '>=', $date1[0])
                    ->whereDate('confirm_date', '<=', $date1[1]);
        })->when($customer_name,function($query)use($customer_name){
            $query->where('customer_name', 'like', '%'.$customer_name.'%');
        })->when($district,function($query)use($district){
            $query->where('district', $district);
        })->when($subDistrict,function($query)use($subDistrict){
            $query->whereIn('sub_district', $subDistrict);
        })->when($village,function($query)use($village){
            $query->whereIn('village', $village);
        });
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
}
