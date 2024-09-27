<?php

namespace App\Interfaces;

interface TransportDepartmentRepositoryInterface 
{
    public function getVillageDetailBySubDistrict($districtId);

    public function confirmOrderList($userId,$date,$district,$subDistrict,$village,$customer_name,$type);
}