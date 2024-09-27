<?php

namespace App\Interfaces;

interface ProductRepositoryInterface 
{
    public function checkCategoryInProduct($id);

    public function store($data);

    public function storeVariant($data);
    
    public function getAllProduct($status,$search,$type,$categoryId = "");

    public function getDetailById($id);

    public function deleteVariant($where);

    public function update($data,$where);
    
    public function delete($id);

    public function checkProductVariant($variantId,$id);

    public function updateProductVariant($update,$where);

    public function getAllProductByCategoryId($id);

    public function getVariantDetailByProductId($id);
    
    public function getVariantDetailById($id);

    public function getAllProductDetail($search,$date,$type);

    public function getLastInsertId();

    public function getTopTenProduct($search);

    public function totalProduct();

    public function getProductList($status,$search,$type,$categoryId = "");
}