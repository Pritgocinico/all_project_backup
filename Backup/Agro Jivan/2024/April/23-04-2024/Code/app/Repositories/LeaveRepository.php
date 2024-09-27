<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\LeaveRepositoryInterface;
use App\Models\Leave;
use Carbon\Carbon;
use PDO;

class LeaveRepository implements LeaveRepositoryInterface 
{

    public function getAllData($search,$date,$status,$feature,$type){
        $query =  $this->getQuery($search,$date,$status,$feature,)->where('created_by',Auth()->user()->id)->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
    
    public function store($data){
        return Leave::create($data);
    }

    public function getDetailById($id){
        return Leave::where('id',$id)->first();
    }

    public function update($data,$where){
        return Leave::where($where)->update($data);
    }

    public function delete($id){
        return Leave::where('id',$id)->delete();
    }

    public function getAllLeaveData($search, $date, $userId = "",$type = "paginate"){
        $query = $this->getQuery($search, $date,"","",$userId)->latest();
        
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }

    public function getQuery($search,$date ="",$status ="",$feature ="",$id = ""){
        return Leave::with('userDetail')->when($search,function($query) use($search){
            $query->where('leave_type', 'like', '%'.$search.'%')
            ->orWhere('reason', 'like', '%'.$search.'%');
        })->when($status,function($query)use($status){
            $query->where('leave_status',$status);
        })->when($feature,function($query)use($feature){
            $query->when($feature == 2,function ($query)use($feature){
                $query->where('leave_feature','0');
            })->when($feature == 1,function($query){
                $query->where('leave_feature',1);
            });
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereBetween('leave_from',[$date1[0],$date1[1]]);
            // ->orWhereBetween('leave_to',[$date1[0],$date1[1]]);
        })->when($id,function($query)use($id){
            $query->where('created_by',$id);
        });
    }

    public function getAllDataCalendar($id){
        return Leave::where('created_by', $id)->get()
            ->map(function ($item) {
                return [
                    'title' => $item->leave_type,
                    'start' => UtilityHelper::convertYmd($item->leave_from),
                    'end' => UtilityHelper::convertYmd($item->leave_to),
                    'color' => $item->leave_status == 2 ? 'green' : 'red',
                ];
            })->toArray();
    }

    public function getTotalMonthLeave($month,$id){
        return Leave::where('created_by',$id)->whereMonth('leave_from', $month)->latest()->get();
    }

    public function getTotalYearLeave($id){
        $currentYear = Carbon::now()->year;
        return Leave::where('created_by',$id)->whereYear('leave_from', $currentYear)
            ->orWhereYear('leave_to', $currentYear)->get();
    }

    public function getMonthLeave($month){
        $year = date('Y');
        return Leave::whereYear('leave_from', $year)
        ->whereMonth('leave_from', $month)
        ->orWhereMonth('leave_from', $month)
        ->get();
    }

    public function getTodayUserLeave($id){
        return Leave::where('created_by',$id)->whereDate('leave_from', '<=', date('Y-m-d'))
        ->where('leave_to', '<=', date('Y-m-d'))->count();
    }
    // public function getPreviousLeaveData(){
    //     return Leave::where('leave_from', '<', $id)->update($data);
    //     return Leave::where('leave_from',$id)->whereMonth('leave_from', $month)->latest()->get();
    // }
}