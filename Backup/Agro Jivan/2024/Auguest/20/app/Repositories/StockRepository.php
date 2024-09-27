<?php

namespace App\Repositories;

use App\Interfaces\StockRepositoryInterface;
use App\Models\InOutStock;
use Illuminate\Support\Facades\DB;

class StockRepository implements StockRepositoryInterface
{
    public function getStockDetail($order_id, $product, $variant_id)
    {
        return InOutStock::where('order_id', $order_id)->where('product_id', $product)->where('variant_id', $variant_id)->first();
    }

    public function store($data)
    {
        return InOutStock::create($data);
    }

    public function update($data, $where)
    {
        return InOutStock::where($where)->update($data);
    }

    public function getTotalInOutStock($date = "")
    {
        return InOutStock::when($date,function($query)use($date){
            $date1 = explode('/', $date);
            $query->whereDate('created_at', '>=', $date1[0])
                ->whereDate('created_at', '<=', $date1[1]);
        })->select(DB::raw('SUM(in_stock) as total_in_stock'), DB::raw('SUM(out_stock) as total_out_stock'))->first();
    }

    public function getAllStockDetail($search, $date)
    {
        return InOutStock::with('orderDetail', 'productDetail', 'variantDetail')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('orderDetail', function ($query) use ($search) {
                    $query->where('order_id', 'like', '%' . $search . '%');
                    $query->orWhere('customer_name', 'like', '%' . $search . '%');
                })->orWhereHas('productDetail', function ($query) use ($search) {
                    $query->orWhere('product_name', 'like', '%' . $search . '%');
                })->orWhereHas('variantDetail', function ($query) use ($search) {
                    $query->orWhere('sku_name', 'like', '%' . $search . '%');
                })->orWhere('in_stock', 'like', '%' . $search . '%')->orWhere('out_stock', 'like', '%' . $search . '%');;
            })->when($date, function ($query) use ($date) {
                $date1 = explode('/', $date);
                $query->whereBetween('in_stock_date_time', [$date1[0],$date1[1]])
                    ->orWhereBetween('out_stock_date_time', [$date1[0],$date1[1]]);
            })->latest()->paginate(15);
    }
}
