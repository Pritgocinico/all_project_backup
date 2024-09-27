<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\HrDashboardRepositoryInterface;
use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\InfoSheet;
use App\Models\Ticket;
use App\Models\User;

class HrDashboardRepository implements HrDashboardRepositoryInterface 
{
    public function employeeCount(){
        return User::where('role_id','!=',1)->count();
    }

    public function holidayCount(){
        return Holiday::whereYear('holiday_date',date('Y'))->count();
    }

    public function getPresentCount(){
        return Attendance::whereIn('status',['1','2'])->whereDate('attendance_date',UtilityHelper::convertYmd(""))->count();
    }
    public function getAbsentCount(){
        return Attendance::where('status','0')->whereDate('attendance_date',UtilityHelper::convertYmd(""))->count();
    }
    public function getTicketCount(){
        return Ticket::count();
    }
    public function getInfoSheetCount(){
        return InfoSheet::count();
    }
}