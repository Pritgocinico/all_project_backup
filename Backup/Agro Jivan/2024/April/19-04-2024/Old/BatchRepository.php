<?php

namespace App\Repositories;

use App\Interfaces\BatchRepositoryInterface;
use App\Models\Batch;
use App\Models\BatchDetail;

class BatchRepository implements BatchRepositoryInterface
{
    public function createBatch($data)
    {
        return Batch::create($data);
    }

    public function generateBatchId()
    {
        return 'AGRJVN-BATCH-' . sprintf('%04d', Batch::all()->last() !== null ? Batch::all()->last()->id + 1 : 1);
    }

    public function createBatchItem($data)
    {
        return BatchDetail::create($data);
    }

    public function getAllBatchList($search, $date, $driverId, $type,$status)
    {
        $query = $this->getQuery($search, $date, $driverId,$status)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == 'export') {
            $query = $query->get();
        }

        return $query;
    }

    public function getQuery($search, $date, $driverId,$status)
    {
        return Batch::with('driverDetail', 'batchItemDetail', 'batchItemDetail.orderDetail', 'batchItemDetail.orderDetail.villageDetail','batchItemDetail.orderDetail.subDistrictDetail','batchItemDetail.orderDetail.subDistrictDetail')
            ->when($driverId, function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1]);
            })->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })->when($search, function ($query) use ($search) {
                $query->where('batch_id', 'like', '%' . $search . '%');
            })->when(Auth()->user() !== null && Auth()->user()->role_id == 8, function ($query) {
                $query->where('status', 1);
            });
    }
    public function getItemDetailById($id, $search, $date, $type)
    {
        $query =  BatchDetail::with('orderDetail','orderDetail.orderItem','orderDetail.orderItem.productDetail','orderDetail.orderItem.varientDetail','orderDetail.numberOrder', 'orderDetail.districtDetail','orderDetail.subDistrictDetail','orderDetail.villageDetail', 'orderDetail.userDetail')->where('batch_id', $id)->when($search, function ($query) use ($search, $date) {
            $query->WhereHas('orderDetail', function ($query) use ($search) {
                $query->where('order_id', 'like', '%' . $search . '%')
                ->orWhere('customer_name','like','%'.$search.'%')
                ->orWhere('phoneno','like','%'.$search.'%')
                ->orWhere('amount','like','%'.$search.'%')
                ->orWhereHas('districtDetail',function ($query) use($search){
                    $query->where('district_name','like','%'.$search.'%');
                });
            });
        })->when($date, function ($query) use ($date) {
            $query->WhereHas('orderDetail', function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
            });
        })->latest();

        if ($type == "paginate") {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }

    public function totalBatch()
    {
        return Batch::count();
    }

    public function getDetailById($id)
    {
        return Batch::with('driverDetail')->where('id', $id)->first();
    }

    public function updateBatch($update, $where)
    {
        return Batch::where($where)->update($update);
    }

    public function getBatchDetailById($id)
    {
        return BatchDetail::with('orderItemDetail', 'orderItemDetail.productDetail','orderItemDetail.varientDetail', 'orderItemDetail.schemeDetail', 'orderItemDetail.schemeDetail.discountItemDetail')->where('batch_id', $id)->get();
    }

    public function getAllDeliveredBatchList($search, $date, $driverId, $type)
    {
        $query =  Batch::with('driverDetail', 'batchItemDetail', 'batchItemDetail.orderDetail', 'batchItemDetail.orderDetail.villageDetail')
            ->when($driverId, function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1]);
            })->when($search, function ($query) use ($search) {
                $query->where('batch_id', 'like', '%' . $search . '%');
            })->where('status', 2)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == 'export') {
            $query = $query->get();
        }

        return $query;
    }

    public function getDetailByBatchId($id){
        return Batch::with('driverDetail')->where('batch_id',$id)->first();
    }

    public function getDashboardBatchList($search, $date, $driverId,$type){
        return Batch::withCount([
            'batchDetails as pending_orders' => function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('order_status', '3');
                });
            },
            'batchDetails as complete_orders' => function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('order_status', '5');
                });
            },
            'batchDetails as return_orders' => function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('order_status', '6');
                });
            },
        ])->with('driverDetail', 'batchItemDetail', 'batchItemDetail.orderDetail', 'batchItemDetail.orderDetail.villageDetail','batchItemDetail.orderDetail.subDistrictDetail','batchItemDetail.orderDetail.subDistrictDetail')
        ->when($driverId, function ($query) use ($driverId) {
            $query->where('driver_id', $driverId);
        })->when($date, function ($query) use ($date) {
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->when($search, function ($query) use ($search) {
            $query->where('batch_id', 'like', '%' . $search . '%');
        })->where('status', 1)->latest()->paginate(15);
    }
    public function totalTransportBatch($date)
    {
        return Batch::when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->count();
    }
}
