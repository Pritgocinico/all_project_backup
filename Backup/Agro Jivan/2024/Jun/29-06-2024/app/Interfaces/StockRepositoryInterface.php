<?php

namespace App\Interfaces;

interface StockRepositoryInterface 
{
    public function getStockDetail($order_id,$product,$variant_id);
    public function store($data);
    public function update($data,$where);
    public function getTotalInOutStock($date);
    public function getAllStockDetail($search,$date);
}