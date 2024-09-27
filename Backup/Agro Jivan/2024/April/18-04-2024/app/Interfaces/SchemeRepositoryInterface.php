<?php

namespace App\Interfaces;

interface SchemeRepositoryInterface 
{
    public function getAllDiscountTypeData();
    
    public function totalDiscountCount();

    public function getLastInsertId();

    public function store($data);

    public function getAllScheme($search,$type);

    public function getDetailById($id);
    
    public function update($update,$where);
    
    public function delete($id);
    
    public function storeDiscountItem($data);
    
    public function getDiscountItemById($id);

    public function updateDiscountItem($update,$where);
    
    public function getDetailByCode($code);

    public function getSchemeDetailByProduct($productId,$id);

    public function getSchemeCodeDetailByProduct($productId,$id);
}