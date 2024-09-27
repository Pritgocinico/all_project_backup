<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderFeedback;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubLocation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function store($data)
    {
        return Order::create($data);
    }

    // public function getAllData($status,$role,$search,$type){
    //     $query = $this->getQuery($status,$role,$search)->latest();
    //     if($type == 'paginate'){
    //         $query = $query->paginate(15);
    //     }
    //     if($type == "export"){
    //         $query = $query->get();
    //     }
    //     return $query;
    // }

    public function getAllData($status, $search, $type, $driverId = "", $date = "", $order_district = "",$userId = "",$order_sub_district ="")
    {
        $query = $this->getQuery($status, $search, $driverId, $date, $order_district,$userId,$order_sub_district)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == "export") {

            $query = $query->get();
        }
        return $query;
    }

    public function getQuery($status, $search, $driverId, $date = "", $order_district,$userId,$order_sub_district = "")
    {
        return Order::with('numberOrder', 'districtDetail', 'subDistrictDetail', 'userDetail', 'orderItem', 'orderItem.productDetail','orderItem.varientDetail', 'orderItem.productDetail.productVariantDetail', 'villageDetail', 'stateDetail')
            ->when($status, function ($query) use ($status) {
                $query->where('order_status', $status);
            })->when($driverId, function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->when($order_district, function ($query) use ($order_district) {
                $query->where('district', $order_district);
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1]);
            })->when($userId,function($query)use($userId){
                $query->where('created_by',$userId);
            })->when($order_sub_district,function($query)use($order_sub_district){
                $query->where('sub_district',$order_sub_district);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('phoneno', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('districtDetail', function ($query) use ($search) {
                            $query->where('district_name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            });
    }


    public function getDetailById($id)
    {
        return Order::with('numberOrder', 'stockDetail', 'orderItem', 'subDistrictDetail', 'districtDetail', 'villageDetail', 'stateDetail', 'userDetail', 'orderItem.varientDetail', 'orderItem.productDetail', 'orderItem.varientDetail', 'orderItem.categoryDetail')->where('id', $id)->first();
    }

    public function update($data, $where)
    {
        return Order::where($where)->update($data);
    }

    public function delete($id)
    {
        return Order::where('id', $id)->delete();
    }

    public function getUserByMobileOrPhone($name)
    {
        return Order::where('phone_number', $name)->orWhere('email', $name)->first();
    }

    public function getAllSubDistrict($id)
    {
        return SubLocation::select('sub_district', 'sub_district_name')->where('district', '=', $id)->distinct()->get();
    }

    public function getAllVillage($id)
    {
        return SubLocation::select('village_code', 'village_name')->where('sub_district', '=', $id)->distinct()->get();
    }

    public function getVillages()
    {
        return SubLocation::select('village_code', 'village_name')->get()->keyBy('village_name');
    }

    public function getOrdersAllData($type, $value)
    {
        return Order::with('villageDetail', 'numberOrder', 'orderItem', 'districtDetail', 'subDistrictDetail', 'orderItem.productDetail', 'orderItem.varientDetail', 'userDetail')->when($type == "village", function ($query) use ($value) {
            $query->where('village', $value);
        })->when($type == "sub_district", function ($query) use ($value) {
            $query->where('sub_district', $value);
        })->when($type == "phone", function ($query) use ($value) {
            $query->where('phoneno', $value);
        })->latest()->get();
    }

    public function getAllStates()
    {
        return Location::where('location_type', '=', 'STATE')->get();
    }

    public function getAllDistrict()
    {
        return SubLocation::select('district', 'district_name')->get()->keyBy('district_name');
    }

    public function orderCount()
    {
        return Order::count();
    }
    public function getLastInsertId()
    {
        return Order::all()->last()->id;
    }

    public function storeOrderItem($orderItem)
    {
        return OrderItem::create($orderItem);
    }

    public function checkOrderRepository($itemId, $orderId)
    {
        return OrderItem::where('id', $itemId)->where('order_id', $orderId)->first();
    }

    public function updateProductVariant($update, $where)
    {
        return OrderItem::where($where)->update($update);
    }

    public function getUserOrderData($id, $type)
    {
        return Order::with('orderItem', 'orderItem.productDetail', 'orderItem.productDetail.categoryDetail')->where('created_by', $id)
            ->when($type == 'daily', function ($query) {
                $query->whereDate('created_at', \Carbon\Carbon::today());
            })->when($type == 'weekly', function ($query) {
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->when($type == 'month', function ($query) {
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->when($type == 'year', function ($query) {
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();
    }

    public function getSubDistrict()
    {
        return SubLocation::select('sub_district', 'sub_district_name')->get()->keyBy('sub_district_name');
    }

    public function getCreatedUserId()
    {
        return Order::select('created_by')->with('userDetail:id,name')->whereHas('userDetail')->groupBy('created_by')->get();
    }

    public function getFeedbackOrders($search)
    {
        return Order::with('districtDetail', 'userDetail', 'feedbackDetail')->where('order_status', '6')->whereDoesntHave('feedbackDetail')
            ->when($search, function ($query) use ($search) {
                $query->where('order_id', 'like', '%' . $search . '%')
                    ->orWhereHas('districtDetail', function ($query) use ($search) {
                        $query->where('district_name', 'like', '%' . $search . '%');
                    });
            })->latest()->paginate(15);
    }

    public function getVipCustomerList($search, $type)
    {
        $query =  Order::with('districtDetail', 'subDistrictDetail', 'villageDetail', 'stateDetail')->where('order_status', 6)
            ->selectRaw('*, count(*) as total')
            ->when($search, function ($query) use ($search) {
                $query->where('customer_name', 'like', '%' . $search . '%');
            })
            ->groupBy('phoneno')
            ->havingRaw('total >= 3')
            ->latest();
        if ($type == "paginate") {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }
    public function getDetailByOrderId($id)
    {
        return Order::with('stockDetail', 'orderItem', 'subDistrictDetail', 'districtDetail', 'villageDetail', 'stateDetail', 'userDetail', 'orderItem.productDetail', 'orderItem.varientDetail', 'orderItem.categoryDetail')->where('order_id', $id)->first();
    }

    public function getOrderDetailReport($search, $type, $date, $field)
    {
        $date1 = explode('/', $date);
        $query = Order::with('userDetail', 'districtDetail', 'numberOrder')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('phoneno', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('districtDetail', function ($query) use ($search) {
                            $query->where('district_name', 'like', '%' . $search . '%');
                        });
                });
            })->when($type == "", function ($query) use ($date1, $date) {
                $query->when($date, function ($query) use ($date1) {
                    $query->whereBetween('created_at', [$date1[0], $date1[1]])
                        ->orWhereBetween('confirm_date', [$date1[0], $date1[1]])
                        ->orWhereBetween('return_date', [$date1[0], $date1[1]])
                        ->orWhereBetween('cancel_date', [$date1[0], $date1[1]])
                        ->orWhereBetween('delivery_date', [$date1[0], $date1[1]]);
                });
            })->when($type == "confirm_date", function ($query) use ($date1) {
                $query->whereBetween('confirm_date', [$date1[0], $date1[1]]);
            })->when($type == "return_date", function ($query) use ($date1) {
                $query->whereBetween('return_date', [$date1[0], $date1[1]]);
            })->when($type == "cancel_date", function ($query) use ($date1) {
                $query->whereBetween('cancel_date', [$date1[0], $date1[1]]);
            })->when($type == "complete_date", function ($query) use ($date1) {
                $query->whereBetween('delivery_date', [$date1[0], $date1[1]]);
            })->latest();

        if ($field == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($field == 'export') {
            $query = $query->get();
        }
        return $query;
    }
    public function getConfirmOrderProductList($search, $date, $categoryID, $field)
    {
        $productVariants = ProductVariant::with('getProductDetail', 'getProductDetail.categoryDetail')->with(['orderItems' => function ($query) {
            $query->selectRaw('variant_id, sum(quantity) as total_quantity,sum(amount) as total_amount')
                ->groupBy('variant_id');
        }])->whereHas('orderItems')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->WhereHas('getProductDetail', function ($query) use ($search) {
                        $query->where('product_name', 'like', '%' . $search . '%');
                    })->orWhereHas('getProductDetail.categoryDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                });
            })->when($categoryID, function ($query) use ($categoryID) {
                $query->where(function ($query) use ($categoryID) {
                    $query->WhereHas('getProductDetail.categoryDetail', function ($query) use ($categoryID) {
                        $query->where('id', $categoryID);
                    });
                });
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereHas('orderItems', function ($query) use ($date1,) {
                    $query->whereBetween('created_at', [$date1[0], $date1[1]]);
                });
            })->latest();
        if ($field == 'paginate') {
            $productVariants = $productVariants->paginate(15);
        }
        if ($field == 'export') {
            $productVariants = $productVariants->get();
        }
        return $productVariants;
    }

    public function getVariantDetail($id)
    {
        return DB::table('order_items')
            ->where('variant_id', $id)
            ->join('orders as o1', 'order_items.order_id', '=', 'o1.id')
            ->sum('order_items.quantity');
    }

    public function getSalesOrderReport($search, $date, $field)
    {

        $query = Order::with('userDetail', 'districtDetail', 'numberOrder')->where('order_status', 6)->latest()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('phoneno', 'like', '%' . $search . '%')
                        ->whereHas('districtDetail', function ($query) use ($search) {
                            $query->where('district_name', 'like', '%' . $search . '%');
                        });
                });
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereBetween('confirm_date', [$date1[0], $date1[1]]);
            });
        if ($field == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($field == 'export') {
            $query = $query->get();
        }
        return $query;
    }

    public function getStaffOrderReport($search, $field,$userId)
    {
        $query = User::with('allOrder')
        ->when($userId,function($query)use($userId){
            $query->where('id',$userId);
        })
        ->whereHas('allOrder')->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        if ($field == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($field == 'export') {
            $query = $query->get();
        }
        return $query;
    }

    public function getStatusCountDateFilter($status, $date, $id)
    {
        return Order::with('completeOrder')->where('created_by', $id)
            ->when($status, function ($query) use ($status) {
                $query->where('order_status', $status);
            })
            ->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1]);
            })->get();
    }
    public function getNotInBatchList(){
        return Order::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('batch_detail')
                ->whereRaw('batch_detail.order_id = orders.id');
        })
        ->where('order_status', '2')
        ->get();
    }

    public function getConfirmOrderByConfirmDate($search,$date,$order_district,$order_sub_district){
        $role_id = Auth()->user() !== null?Auth()->user()->role_id:"";
        return Order::with('districtDetail', 'confirmUserDetail')
            ->when($order_district, function ($query) use ($order_district) {
                $query->where('district', $order_district);
            })->when($order_sub_district, function ($query) use ($order_sub_district) {
                $query->where('sub_district', $order_sub_district);
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereDate('confirm_date', '>=', $date1[0])
                    ->whereDate('confirm_date', '<=', $date1[1]);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('order_id', 'like', '%' . $search . '%')
                        ->orWhere('customer_name', 'like', '%' . $search . '%')
                        ->orWhere('phoneno', 'like', '%' . $search . '%')
                        ->orWhere('amount', 'like', '%' . $search . '%')
                        ->orWhereHas('districtDetail', function ($query) use ($search) {
                            $query->where('district_name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->when($role_id !== 1,function($query)use($role_id){
                $query->where('confirm_by',$role_id);
            })->latest()->paginate(15);
    }

    public function deleteOrderItem($where){
        return OrderItem::where($where)->delete();
    }

    public function deleteOrder($where){
        return Order::where($where)->delete();
    }
}
