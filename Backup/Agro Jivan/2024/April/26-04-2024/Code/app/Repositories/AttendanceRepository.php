<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\AttendanceRepositoryInterface;
use App\Models\Attendance;
use App\Models\BreakLog;
use Carbon\Carbon;

class AttendanceRepository implements AttendanceRepositoryInterface 
{
    public function getPresentCount($month,$id,$status){
        return Attendance::where('user_id',$id)->whereMonth('attendance_date',$month)->where('status',$status)->count();
    }

    public function getTodayAttendanceUser(){
        return Attendance::where('user_id',Auth()->user()->id)->whereDate('attendance_date',UtilityHelper::convertYmd(''))->first();
    }

    public function storeBreakLog($data){
        return BreakLog::create($data);
    }

    public function updateAttendanceDetail($update,$where){
        return Attendance::where($where)->update($update);
    }

    public function getBreakLogDetail(){
        return BreakLog::where('user_id',Auth()->user()->id)->orderBy('id','DESC')->whereDate('created_at', Carbon::today())->get();
    }

    public function userBreakLog(){
        return BreakLog::where('user_id',Auth()->user()->id)->orderBy('id','DESC')->first();
    }

    public function updateBreakLogTime($update,$where){
        return BreakLog::where($where)->update($update);
    }

    public function getUserBreak($id,$date){
        return BreakLog::where('user_id',$id)->orderBy('id','DESC')->whereDate('date', $date)->get();                    
    }

    public function getTodayAttendanceUserByID($id){
        return Attendance::where('user_id',$id)->whereDate('attendance_date',UtilityHelper::convertYmd(''))->first();
    }

    public function dailyAttendanceDetail($search,$type,$status,$date){
        return Attendance::whereHas('userDetail',function($query)use($type,$search){
            $query->where(function($query)use($type,$search){
                $query->when($type == "manager",function($query){
                    $query->where('is_manager','1');
                })->when($search,function ($query)use($search){
                    $query->where('name','like','%'.$search.'%');
                });
            });
        })->when($status == "present",function($query){
            $query->where(function($query){
                $query->whereIn('status',['1','2']);
            });
        })->when($status == "absent",function($query){
            $query->where(function($query){
                $query->where('status','0');
            });
        })->when($date,function($query)use($date){
            $query->whereDate('attendance_date', UtilityHelper::convertYmd($date));
        })->paginate(15);
    }
}