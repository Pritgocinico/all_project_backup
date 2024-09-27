<?php

namespace App\Repositories;

use App\Interfaces\EmployeeDashboardRepositoryInterface;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class EmployeeDashboardRepository implements EmployeeDashboardRepositoryInterface
{
    public function countOrderStatus($date, $status)
    {
        return Order::where('order_status',$status)->when($status == "1",function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->when($status == "2",function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('confirm_date', '>=', $date1[0])
                ->whereDate('confirm_date', '<=', $date1[1]);
        })->when($status == "6",function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
        })->when($status == "4",function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('cancel_date', '>=', $date1[0])
                ->whereDate('cancel_date', '<=', $date1[1]);
        })->when($status == "5",function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('return_date', '>=', $date1[0])
                ->whereDate('return_date', '<=', $date1[1]);
        })->where('created_by',Auth()->user()->id)->count();
    }

    public function countLeaveStatus($from, $status)
    {
        return Leave::where('created_by', Auth()->user()->id)->when($status, function ($query) use ($status) {
            $query->where('leave_status', $status);
        })->when($from, function ($query) use ($from) {
            $date1 = explode('/', $from);
            $query->whereBetween('leave_from', [$date1[0], $date1[1]])
                ->orWhereBetween('leave_to', [$date1[0], $date1[1]]);
        })->get();
    }

    public function totalAbsent($date, $status)
    {
        return Attendance::where('status', $status)->when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('attendance_date', $date1[0])
                ->whereDate('attendance_date', '<=', $date1[1]);
        })->count();
    }

    public function getStoreYearOrder()
    {
        return Order::select(DB::raw('YEAR(created_at) as year'))
            ->distinct()->orderby('year','desc')
            ->orderBy('year')
            ->pluck('year');
    }

    public function getPendingCount($month,$year,$status){
        return Order::where('order_status',$status)->whereMonth('created_at',$month)
        ->whereYear('created_at', $year)
        ->count();
    }

    public function getSalesData($year){
        $date1 = explode('/',$year);
        return Order::where('created_at', ">=",$date1[0])->where('created_at', "<=",$date1[1])
        ->select('order_status', DB::raw('count(*) as count'))
        ->groupBy('order_status')
        ->get();
    }
}
