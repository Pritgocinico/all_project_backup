<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductRepository implements ProductRepositoryInterface
{
    public function checkCategoryInProduct($id)
    {
        return Product::where('category_id', $id)->count();
    }
    public function store($data)
    {
        return Product::create($data);
    }
    public function storeVariant($data)
    {
        return ProductVariant::create($data);
    }
    public function getProductList($status, $search, $type,$categoryId =""){
        $query = $this->getQuery($status, $search,$categoryId)->orderByRaw("CASE WHEN status = '1' THEN 0 ELSE 1 END");
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }
    public function getAllProduct($status, $search, $type,$categoryId ="")
    {
        $query = $this->getQuery($status, $search,$categoryId)->latest();
        if ($type == 'paginate') {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }

    public function getQuery($status, $search,$categoryId)
    {
        return Product::with('categoryDetail', 'productVariantDetail')->whereNull('deleted_at')->where('status','1')
            ->when($status != "", function ($query) use ($status) {
                $query->where('status', $status);
            })->when($categoryId != "", function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->when($search, function ($query) use ($search) {
                $query->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('sku_name', 'like', '%' . $search . '%')
                    ->orWhereHas('categoryDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
    }

    public function getDetailById($id)
    {
        return Product::where('id', $id)->with('productVariantDetail', 'categoryDetail')->first();
    }

    public function deleteVariant($where)
    {
        return ProductVariant::where($where)->delete();
    }

    public function update($data, $where)
    {
        return Product::where($where)->update($data);
    }

    public function delete($id)
    {
        return Product::where('id', $id)->delete();
    }

    public function checkProductVariant($variantId, $id)
    {
        return ProductVariant::where('id', $variantId)->where('product_id', $id)->first();
    }

    public function updateProductVariant($update, $where)
    {
        return ProductVariant::where($where)->update($update);
    }

    public function getAllProductByCategoryId($id)
    {
        return Product::where('category_id', $id)->whereNull('deleted_at')->where('status',1)->get();
    }

    public function getVariantDetailByProductId($id)
    {
        return ProductVariant::where('product_id', $id)->get();
    }
    public function getVariantDetailById($id)
    {
        return ProductVariant::where('id', $id)->first();
    }

    public function getAllProductDetail($search, $categoryId,$type)
    {
        $query =  Product::with('categoryDetail', 'categoryDetail.categoryDetail', 'productVariantDetail')->whereNull('deleted_at')->where('status','1')
            ->when($categoryId != "", function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })->when($search, function ($query) use ($search) {
                $query->where('product_name', 'like', '%' . $search . '%')
                    ->orWhere('sku_name', 'like', '%' . $search . '%')
                    ->orWhereHas('categoryDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })->latest();
        if($type =="paginate"){
            $query = $query->paginate(15);
        }
        if($type =="export"){
            $query = $query->get();
        }
        return $query;
    }

    public function getLastInsertId()
    {
        return Product::all()->last()->id;
    }

    public function getTopTenProduct($search)
    {
        return OrderItem::with('productDetail')->select('product_id')
            ->selectRaw('COUNT(*) as total_orders')
            ->groupBy('product_id')
            ->orderByDesc('total_orders')
            ->take(10)
            ->get();
    }

    public function totalProduct(){
        return Product::count();
    }
}
