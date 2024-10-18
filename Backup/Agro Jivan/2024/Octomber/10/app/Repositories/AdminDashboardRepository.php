<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Models\Batch;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardRepository implements AdminDashboardRepositoryInterface
{
    public function totalOrderCount($status, $date = "")
    {
        return Order::where('order_status', $status)->when($status == "1", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1])->where('order_status', '1');
            });
        })->when($status == "3", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1])->where('order_status', '3');
            });
        })->when($status == "2", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('confirm_date', '>=', $date1[0])
                    ->whereDate('confirm_date', '<=', $date1[1]);
            });
        })->when($status == "6", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('delivery_date', '>=', $date1[0])
                    ->whereDate('delivery_date', '<=', $date1[1]);
            });
        })->when($status == "4", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('cancel_date', '>=', $date1[0])
                    ->whereDate('cancel_date', '<=', $date1[1]);
            });
        })->when($status == "5", function ($query) use ($date) {
            $query->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('return_date', '>=', $date1[0])
                    ->whereDate('return_date', '<=', $date1[1]);
            });
        })->count();
    }

    public function totalAllOrderCount($date)
    {
        return Order::when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->count();
    }

    public function totalDriverAssignedOrderCount()
    {
        return Order::where('driver_id', Auth()->user() !== null ? Auth()->user()->id : "")->where('order_status', '3')->count();
    }

    public function totalDriverAssignedOrderCountWithStatus($status)
    {
        return Order::where('driver_id', Auth()->user() !== null ? Auth()->user()->id : "")->where('order_status', $status)->count();
    }

    public function getTotalEmployeeAbsentPresent($type, $status)
    {
        return User::with('presentAttendanceDetail')
            ->when($type == "employee", function ($query) {
                $query->where('role_id', 2);
            })->when($type == "manager", function ($query) {
                $query->where('is_manager', 1);
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
    public function totalAdminOrderCount($date, $status)
    {
        return  Order::where('order_status', $status)->when($status == "1", function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->when($status == "2", function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('confirm_date', '>=', $date1[0])
                ->whereDate('confirm_date', '<=', $date1[1]);
        })->when($status == "6", function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
        })->when($status == "4", function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('cancel_date', '>=', $date1[0])
                ->whereDate('cancel_date', '<=', $date1[1]);
        })->when($status == "5", function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('return_date', '>=', $date1[0])
                ->whereDate('return_date', '<=', $date1[1]);
        })->count();
    }

    public function getTotalBatchOnRoute()
    {
        return Batch::where('status', '1')->count();
    }

    public function userLoginCount($type)
    {
        return User::where('is_login', $type)->where('role_id', "!=", 1)->count();
    }

    public function getOrderProductChart($date)
    {
        $date1 = explode('/', $date);
        $variantOrders = Order::select(
            'order_items.variant_id',
            'order_items.product_id',
            'orders.id',
            DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween(DB::raw('DATE(orders.created_at)'), [$date1[0], $date1[1]])
            ->groupBy('order_items.product_id', 'order_items.variant_id')
            ->with(['orderItem.varientDetail:id,sku_name'])
            ->get();
        $result = [];
        $label = [];
        $data = [];
        foreach ($variantOrders as $key => $value) {
            $count  = 0;
            foreach ($value->orderItem as $key1 => $item) {
                if (isset($item->varientDetail)) {
                    $count = 1;
                    if (!in_array($item->varientDetail->sku_name, $label)) {
                        array_push($label, isset($item->varientDetail) ? $item->varientDetail->sku_name : "");
                    }
                }
            }
            if($count == 1){
                array_push($data, (int)$value->total_quantity);
            }
        }
        $result['labels'] = $label;
        $result['data'] = $data;
        return $result;
    }
}
