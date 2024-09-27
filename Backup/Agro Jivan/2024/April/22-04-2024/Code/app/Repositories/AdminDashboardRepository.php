<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Models\Batch;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardRepository implements AdminDashboardRepositoryInterface
{
    public function totalOrderCount($status,$date = "")
    {
        return Order::when($status == "1",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1])->where('order_status', '1');
            });
        })->when($status == "3",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1])->where('order_status', '3');
            });
        })->when($status == "2",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('confirm_date', '>=', $date1[0])
                ->whereDate('confirm_date', '<=', $date1[1]);
            });
        })->when($status == "6",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
            });
        })->when($status == "4",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('cancel_date', '>=', $date1[0])
                ->whereDate('cancel_date', '<=', $date1[1]);
            });
        })->when($status == "5",function ($query) use ($date) {
            $query->when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('return_date', '>=', $date1[0])
                ->whereDate('return_date', '<=', $date1[1]);
            });
        })->count();
    }

    public function totalAllOrderCount($date)
    {
        return Order::when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->count();
    }

    public function totalDriverAssignedOrderCount()
    {
        return Order::where('driver_id', Auth()->user() !== null ? Auth()->user()->id : "")->count();
    }

    public function totalDriverAssignedOrderCountWithStatus($status)
    {
        return Order::where('driver_id', Auth()->user() !== null ? Auth()->user()->id : "")->where('order_status', $status)->count();
    }

    public function getTotalEmployeeAbsentPresent($type,$status)
    {
        return User::with('presentAttendanceDetail')
            ->when($type == "employee",function($query){
                $query->where('role_id',2);
            })->when($type == "manager",function($query){
                $query->where('is_manager',1);
            })
            ->whereHas('presentAttendanceDetail', function ($query) use ($status) {
                $query->whereDate('attendance_date', Carbon::now()->format('Y-m-d'))
                    ->when($status == 'present', function ($query) {
                        $query->whereIn('status', ['1', '2']);
                    })->when($status == 'absent', function ($query) {
                        $query->where('status', '0');
                    });
            })
            ->get()->pluck('attendances')
            ->flatten()
            ->count();
    }
    public function totalAdminOrderCount($date,$status){
        return Order::when($status == "1",function ($query) use ($date) {
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
        })->count();
    }

    public function getTotalBatchOnRoute(){
        return Batch::where('status','1')->count();
    }

    public function userLoginCount($type){
        return User::where('is_login',$type)->where('role_id',"!=",1)->count();
    }
}