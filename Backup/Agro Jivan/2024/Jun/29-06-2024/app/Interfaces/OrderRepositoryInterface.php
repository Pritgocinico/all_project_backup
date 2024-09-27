<?php

namespace App\Interfaces;

interface OrderRepositoryInterface 
{
    public function store($data);   

    // public function getAllData($status,$role,$search,$type);
    
    public function getAllData($status, $search,$type,$driverId = "",$date="",$order_district="",$userId = "",$order_sub_district = "");

    public function getDetailById($id);
    
    public function update($data,$where);

    public function delete($id);
    
    public function getUserByMobileOrPhone($name);

    public function getAllSubDistrict($id);

    public function getAllVillage($id);

    public function getVillages();

    public function getOrdersAllData($type, $value);

    public function getAllStates();

    public function getAllDistrict();

    public function orderCount();

    public function getLastInsertId();

    public function storeOrderItem($orderItem);

    public function checkOrderRepository($itemId,$orderId);

    public function updateProductVariant($variant, $where);

    public function getUserOrderData($id,$type);

    public function getSubDistrict();

    public function getCreatedUserId();

    public function getFeedbackOrders($search);

    public function getVipCustomerList($search,$type);
    public function getDetailByOrderId($id);
    public function getOrderDetailReport($search,$type,$date,$field);
    public function getConfirmOrderProductList($search,$date, $categoryID,$field);
    public function getVariantDetail($id);
    public function getSalesOrderReport($search,$date,$field,$order_status,$user_id,$order_district,$order_sub_district);
    public function getStaffOrderReport($search,$field,$userId,$date);

    public function getStatusCountDateFilter($status, $date,$id);

    public function getNotInBatchList();
    public function getConfirmOrderByConfirmDate($search,$date,$order_district,$order_sub_district);

    public function deleteOrderItem($where);

    public function deleteOrder($where);
    public function getSelectedOrder($orderId);
}