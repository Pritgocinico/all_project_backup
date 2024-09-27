<?php

namespace App\Interfaces;

interface BatchRepositoryInterface 
{
    public function createBatch($data);
    public function generateBatchId();
    public function createBatchItem($data);
    public function getAllBatchList($search, $date, $driverId,$type,$status);
    public function getItemDetailById($id,$search,$date,$type,$status="");
    public function totalBatch();
    public function totalTransportBatch($date);
    public function getDetailById($id);
    public function updateBatch($update,$where);
    public function getBatchDetailById($id);
    public function getDetailByBatchId($id);
    public function getAllDeliveredBatchList($search, $date, $driverId,$type);
    public function getDashboardBatchList($search, $date, $driverId,$type);
    public function getBatchDetailByOrderId($id);
    public function deleteBatchDetailByOrderId($id);

    public function getTotalBatch();

    public function removerOrder($where);
}