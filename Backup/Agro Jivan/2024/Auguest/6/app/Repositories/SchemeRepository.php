<?php

namespace App\Repositories;

use App\Interfaces\SchemeRepositoryInterface;
use App\Models\Discount;
use App\Models\DiscountItem;
use App\Models\DiscountType;

class SchemeRepository implements SchemeRepositoryInterface 
{
    public function getAllDiscountTypeData(){
        return DiscountType::latest()->get();
    }

    public function totalDiscountCount(){
        return Discount::count();
    }

    public function getLastInsertId(){
        return Discount::all()->last()->id;
    }

    public function store($data){
        return Discount::create($data);
    }

    public function getAllScheme($search,$type){
        $query =  Discount::with('discountItemDetail','discountItemDetail.productDetail','discountItemDetail.freeProductDetail','discountTypeDetail')->when($search,function($query) use($search){
            $query->where('discount_code', 'like', '%'.$search.'%')
            ->orWhereHas('discountItemDetail.productDetail',function($query) use($search){
                $query->where('product_name', 'like', '%'.$search.'%');
            })->orWhereHas('discountItemDetail.freeProductDetail',function($query) use($search){
                $query->where('product_name', 'like', '%'.$search.'%');
            })->orWhereHas('discountTypeDetail',function($query) use($search){
                $query->where('title', 'like', '%'.$search.'%');
            });
        })->latest();
        if($type == "paginate"){
            $query =$query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }

    public function getDetailById($id){
        return Discount::with('discountItemDetail')->where('id',$id)->first();
    }

    public function update($update,$where){
        return Discount::where($where)->update($update);
    }

    public function delete($id){
        return Discount::where('id',$id)->delete();
    }

    public function storeDiscountItem($data){
        return DiscountItem::create($data);
    }

    public function getDiscountItemById($id){
        return DiscountItem::where('id',$id)->first();
    }

    public function updateDiscountItem($update,$where){
        return DiscountItem::where($where)->update($update);
    }
    
    public function getDetailByCode($code){
        return Discount::with('discountItemDetail','discountItemDetail.productDetail','discountItemDetail.productDetail.getProductDetail','discountItemDetail.freeProductDetail','discountItemDetail.freeProductDetail.getProductDetail')->where('discount_code',$code)->first();
    }

    public function getSchemeDetailByProduct($productId,$id){
        return DiscountItem::with('productDetail','productDetail.getProductDetail','freeProductDetail','freeProductDetail.getProductDetail')->where('product_id',$productId)->where('discount_id',$id)->first();
    }

    public function getSchemeCodeDetailByProduct($freeProduct,$variantID){
        return DiscountItem::with('schemeDetail')->where('product_id',$variantID)->where('free_product_id',$freeProduct)->first();
    }
}