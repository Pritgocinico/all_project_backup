<?php

namespace App\Repositories;

use App\Interfaces\LeadRepositoryInterface;
use App\Models\Lead;
use App\Models\LeadItem;

class LeadRepository implements LeadRepositoryInterface 
{
    public function store($data){
        return Lead::create($data);
    }
    public function leadCount(){
        return Lead::count();
    }

    public function getLastInsertId(){
        return Lead::all()->last() !== null?Lead::all()->last()->id+1:1;
    }

    public function storeLeadItem($data){
        return LeadItem::create($data);
    }
    
    public function getLeadDetails($type,$value){
        return Lead::with('villageDetail','leadDetail','districtDetail','subDistrictDetail','leadDetail.productDetail','leadDetail.variantDetail')->when($type == "village",function($query) use($value){
            $query->where('village',$value);
        })->when($type == "phone",function($query) use($value){
            $query->where('phone_no',$value);
        })->when($type == "sub_district",function($query) use($value){
            $query->where('sub_district',$value);
        })->latest()->get();
    }

    public function getAllLeadDetail($search,$date,$district){
        $role = Auth()->user()->role_id;
        return Lead::with('districtDetail','userDetail')
        ->when($search,function($query) use($search){
            $query->where('lead_id', 'like', '%'.$search.'%')
            ->orWhereHas('districtDetail',function($query) use($search){
                $query->where('district_name', 'like', '%'.$search.'%');
            });
        })->when($date,function($query) use($date){
                $date1 = explode('/',$date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->when($district,function($query)use($district){
            $query->where('district',$district);
        })->when($role !== 1,function($query){
            $query->where('created_by',Auth()->user()->id);
        })->latest()->paginate(15);
    }
    public function getAllLeadDetailExport($search,$date,$district){
        return Lead::with('districtDetail','userDetail')
        ->when($search,function($query) use($search){
            $query->where('order_id', 'like', '%'.$search.'%')
            ->orWhereHas('districtDetail',function($query) use($search){
                $query->where('district_name', 'like', '%'.$search.'%');
            });
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereDate('created_at', '>=', $date1[0])
            ->whereDate('created_at', '<=', $date1[1]);
    })->when($district,function($query)use($district){
        $query->where('district',$district);
    })->latest()->get();
    }

    public function getUseDistrictLead(){
        return Lead::with('districtDetail')->select('district')->get()->keyBy('district');
    }

    public function getDetailById($id){
        return Lead::with('leadDetail','leadDetail.productDetail','districtDetail','subDistrictDetail','userDetail','villageDetail','stateDetail','leadDetail.variantDetail','leadDetail.categoryDetail')->where('id',$id)->first();
    }

}