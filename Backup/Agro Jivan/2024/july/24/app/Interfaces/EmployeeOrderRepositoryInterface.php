<?php

namespace App\Interfaces;

interface EmployeeOrderRepositoryInterface
{
    public function getAllData($search,$status,$district,$date,$orderStatusType,$type,$order_sub_district);
    
    public function getUseDistrictOrder();
    
    public function getAllDataDriver($search,$status,$district,$date,$type);
    
    public function getUserOrder();
    
    public function getUseSubDistrictOrder($district);
    
    public function getAllDataTransport($search,$status,$district,$date,$type);
}