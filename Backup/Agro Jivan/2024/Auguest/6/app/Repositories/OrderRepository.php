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
use PDO;

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

    public function getAllData($status, $search, $type, $driverId = "", $date = "", $order_district = "", $userId = "", $order_sub_district = "")
    {
        $query = $this->getQuery($status, $search, $driverId, $date, $order_district, $userId, $order_sub_district)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == "export") {

            $query = $query->get();
        }
        return $query;
    }

    public function getQuery($status, $search, $driverId, $date = "", $order_district, $userId, $order_sub_district = "")
    {
        return Order::with('numberOrder', 'districtDetail', 'subDistrictDetail', 'userDetail', 'orderItem', 'orderItem.productDetail', 'orderItem.varientDetail', 'orderItem.productDetail.productVariantDetail', 'villageDetail', 'stateDetail')
            ->when($status, function ($query) use ($status) {
                $query->where('order_status', $status);
            })->when($driverId, function ($query) use ($driverId) {
                $query->where('driver_id', $driverId);
            })->when($order_district, function ($query) use ($order_district) {
                $query->where('district', $order_district);
            })->when($date, function ($query) use ($date, $status) {
                $date1 = explode('/', $date);
                $query->when($status == 6, function ($query) use ($date1) {
                    $query->whereDate('delivery_date', '>=', $date1[0])
                        ->whereDate('delivery_date', '<=', $date1[1]);
                })->when($status == 1 || $status == 3 || $status == null, function ($query) use ($date1) {
                    $query->whereDate('created_at', '>=', $date1[0])
                        ->whereDate('created_at', '<=', $date1[1]);
                })->when($status == 2, function ($query) use ($date1) {
                    $query->whereDate('confirm_date', '>=', $date1[0])
                        ->whereDate('confirm_date', '<=', $date1[1]);
                })->when($status == 4, function ($query) use ($date1) {
                    $query->whereDate('cancel_date', '>=', $date1[0])
                        ->whereDate('cancel_date', '<=', $date1[1]);
                })->when($status == 5, function ($query) use ($date1) {
                    $query->whereDate('return_date', '>=', $date1[0])
                        ->whereDate('return_date', '<=', $date1[1]);
                });
            })->when($userId, function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })->when($order_sub_district, function ($query) use ($order_sub_district) {
                $query->where('sub_district', $order_sub_district);
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
        })->orderBy('order_status','desc')->get();
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
        $productVariants = ProductVariant::with('getProductDetail', 'getProductDetail.categoryDetail')->has('getProductDetail')->with(['orderItems' => function ($query) use ($date) {
            $query->selectRaw('variant_id, sum(quantity) as total_quantity,sum(amount) as total_amount')->when($date, function ($query) use ($date) {
                $query->where(function ($query) use ($date) {
                    $date1 = explode('/', $date);
                    $dateData = Carbon::parse('2024-04-01');
                    $inputDate = Carbon::parse($date1[0]);
                    if ($inputDate->lessThan($dateData)) {
                        $date1[0] = $dateData;
                    }
                    $query->where(function ($query) use ($date1) {
                        $query->whereDate('created_at', ">=", $date1[0])->whereDate('created_at', "<=", $date1[1]);
                    });
                })
                    ->whereHas('orderDetail', function ($query) {
                        $query->where('order_status', '=', 6);
                    });
            })
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

    public function getStaffOrderReport($search, $field, $userId, $date)
    {
        $date1 = explode('/', $date);
        $dateData = Carbon::parse('2024-04-01');
        $inputDate = Carbon::parse($date1[0]);
        if ($inputDate->lessThan($dateData)) {
            $date1[0] = $dateData;
        }
        $query = User::with('allOrder')->withCount(['confirmOrders' => function ($query) use ($date1) {
            $query->whereDate('confirm_date', '>=', $date1[0])
                ->whereDate('confirm_date', '<=', $date1[1]);
        }, 'allOrder' => function ($query) use ($date1) {
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        }, 'cancelOrder' => function ($query) use ($date1) {
            $query->whereDate('cancel_date', '>=', $date1[0])
                ->whereDate('cancel_date', '<=', $date1[1]);
        }, 'returnOrder' => function ($query) use ($date1) {
            $query->whereDate('return_date', '>=', $date1[0])
                ->whereDate('return_date', '<=', $date1[1]);
        }, 'completeOrder' => function ($query) use ($date1) {
            $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
        }, 'pendingOrder' => function ($query) use ($date1) {
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        }, 'onDeliveryOrder' => function ($query) use ($date1) {
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        }])->withSum(['completeOrder' => function ($query) use ($date1) {
            $query->whereDate('delivery_date', '>=', $date1[0])
                ->whereDate('delivery_date', '<=', $date1[1]);
        }], 'amount')->when($userId, function ($query) use ($userId) {
            $query->where('id', $userId);
        })
            ->whereHas('allOrder')->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            });
        $query->orderBy('complete_order_sum_amount', 'desc');
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
                // dd($date);
                $date1 = explode('/', $date);
                $dateData = Carbon::parse('2024-04-01');
                $inputDate = Carbon::parse($date1[0]);
                if ($inputDate->lessThan($dateData)) {
                    $date1[0] = $dateData;
                }
                $query->whereDate('created_at', '>=', $date1[0])
                    ->whereDate('created_at', '<=', $date1[1]);
            })->get();
    }
    public function getNotInBatchList()
    {
        return Order::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('batch_detail')
                ->whereRaw('batch_detail.order_id = orders.id');
        })
            ->where('order_status', '2')
            ->get();
    }

    public function getConfirmOrderByConfirmDate($search, $date, $order_district, $order_sub_district)
    {
        $role_id = Auth()->user() !== null ? Auth()->user()->role_id : "";
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
            })->when($role_id !== 1, function ($query) use ($role_id) {
                $query->where('confirm_by', $role_id);
            })->latest()->paginate(15);
    }

    public function deleteOrderItem($where)
    {
        return OrderItem::where($where)->delete();
    }

    public function deleteOrder($where)
    {
        return Order::where($where)->delete();
    }

    public function getSelectedOrder($orderId)
    {
        return $this->getQuery("6", "", "", "", "", "", "")->whereIn('id', $orderId)->latest()->get();
    }

    public function filterOrders($query, $order_status, $dateRange, $order_district, $order_sub_district)
    {
        return $query->when($order_district, function ($query) use ($order_district) {
            $query->where('district', $order_district);
        })
            ->when($order_sub_district, function ($query) use ($order_sub_district) {
                $query->where('sub_district', $order_sub_district);
            })->when(in_array($order_status, ['1', '3']), function ($query) use ($dateRange) {
                $query->whereDate('orders.created_at', '>=', $dateRange[0])
                    ->whereDate('orders.created_at', '<=', $dateRange[1]);
            })
            ->when($order_status == '2', function ($query) use ($dateRange) {
                $query->whereDate('orders.confirm_date', '>=', $dateRange[0])
                    ->whereDate('orders.confirm_date', '<=', $dateRange[1]);
            })
            ->when($order_status == '4', function ($query) use ($dateRange) {
                $query->whereDate('orders.cancel_date', '>=', $dateRange[0])
                    ->whereDate('orders.cancel_date', '<=', $dateRange[1]);
            })
            ->when($order_status == '5', function ($query) use ($dateRange) {
                $query->whereDate('orders.return_date', '>=', $dateRange[0])
                    ->whereDate('orders.return_date', '<=', $dateRange[1]);
            })
            ->when($order_status == '6', function ($query) use ($dateRange) {
                $query->whereDate('orders.delivery_date', '>=', $dateRange[0])
                    ->whereDate('orders.delivery_date', '<=', $dateRange[1]);
            });
    }

    public function getSalesOrderReport($search, $date, $field, $order_status, $user_id, $order_district, $order_sub_district)
    {
        $dateRange = $date ? explode('/', $date) : null;

        $query = ProductVariant::with([
            'productDetail',
            'orderItems.orderDetail.districtDetail',
            'orderItems.orderDetail.subDistrictDetail'
        ])
            ->whereHas('orderItems', function ($query) use ($order_district, $order_sub_district, $dateRange, $order_status, $user_id) {
                $query->whereHas('orderDetail', function ($query) use ($order_district, $order_sub_district, $dateRange, $order_status, $user_id) {
                    $query->where('order_status', $order_status);
                    if ($user_id) {
                        $query->where('created_by', $user_id);
                    }
                    if ($dateRange) {
                        $this->filterOrders($query, $order_status, $dateRange, $order_district, $order_sub_district);
                    }
                    if ($order_district) {
                        $query->where('district', $order_district);
                    }
                    if ($order_sub_district) {
                        $query->where('sub_district', $order_sub_district);
                    }
                });
            })
            ->withSum(['orderItems as total_quantity' => function ($query) use ($user_id,$dateRange, $order_status, $order_district, $order_sub_district) {
                $query->whereHas('orderDetail', function ($query) use ($user_id,$order_status, $dateRange, $order_district, $order_sub_district) {
                    $query->where('order_status', $order_status)->when($user_id,function($query)use($user_id){
                        $query->where('created_by', $user_id);
                    });
                    if ($dateRange) {
                        $this->filterOrders($query, $order_status, $dateRange, $order_district, $order_sub_district);
                    }
                })->select(DB::raw('sum(quantity)'));
            }], 'quantity')
            ->withSum(['orderItems as total_amount' => function ($query) use ($user_id,$dateRange, $order_status, $order_district, $order_sub_district) {
                $query->whereHas('orderDetail', function ($query) use ($user_id,$order_status, $dateRange, $order_district, $order_sub_district) {
                    $query->where('order_status', $order_status)->when($user_id,function($query)use($user_id){
                        $query->where('created_by', $user_id);
                    });
                    if ($dateRange) {
                        $this->filterOrders($query, $order_status, $dateRange, $order_district, $order_sub_district);
                    }
                })->select(DB::raw('sum(quantity * price)'));
            }], 'amount')
            ->addSelect(['total_order_count' => function ($query) use ($dateRange,$user_id,$order_district, $order_sub_district, $order_status) {
                $query->select(DB::raw('count(distinct order_id)'))
                    ->from('order_items')
                    ->whereColumn('variant_id', 'product_variant.id')
                    ->whereExists(function ($subQuery) use ($dateRange,$user_id,$order_district, $order_sub_district, $order_status) {
                        $subQuery->select(DB::raw(1))
                            ->from('orders')
                            ->whereColumn('order_items.order_id', 'orders.id')
                            ->where('order_status', '=', $order_status)
                            ->where('orders.deleted_at', null)
                            ->when($order_district, function ($subQuery) use ($order_district) {
                                $subQuery->where('district', $order_district);
                            })
                            ->when($order_sub_district, function ($subQuery) use ($order_sub_district) {
                                $subQuery->where('sub_district', $order_sub_district);
                            })
                            ->when($order_sub_district, function ($subQuery) use ($order_sub_district) {
                                $subQuery->where('sub_district', $order_sub_district);
                            })->when($user_id,function($query)use($user_id){
                                $query->where('created_by', $user_id);
                            });
                            if($dateRange){
                                $this->filterOrders($subQuery, $order_status, $dateRange, $order_district, $order_sub_district);
                            }
                    });
            }])
            ->latest();
        if ($field == 'paginate') {
            return $query->paginate(15);
        } elseif ($field == 'export') {
            return $query->get();
        }
        return $query;
    }
}
