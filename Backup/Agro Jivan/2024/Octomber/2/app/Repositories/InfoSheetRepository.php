<?php

namespace App\Repositories;

use App\Interfaces\InfoSheetRepositoryInterface;
use App\Models\InfoSheet;

class InfoSheetRepository implements InfoSheetRepositoryInterface 
{
    public function store($data){
        return InfoSheet::create($data);
    }

    public function getAllData($search,$date = "",$type = "paginate",$role){
        $query = $this->getQuery($search,$date,$role)->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
    
    public function getDetailById($id){
        return InfoSheet::where('id',$id)->first();
    }

    public function update($data,$where){
        return InfoSheet::where($where)->update($data);
    }

    public function delete($id){
        return InfoSheet::where('id',$id)->delete();
    }
    public function getQuery($search,$date,$role){
        return InfoSheet::when($search,function($query) use($search){
            $query->where('title', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
        })->when($date,function($query)use($date){
            $date1 = explode('/',$date);
            $query->whereDate('created_at', '>=', $date1[0])
            ->whereDate('created_at', '<=', $date1[1]);
        })->when($role == "",function($query){
            $query->where('created_by',Auth()->user()->id);
        });
    }
}