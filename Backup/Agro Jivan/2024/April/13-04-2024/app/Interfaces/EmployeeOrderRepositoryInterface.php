<?php

namespace App\Interfaces;

interface EmployeeOrderRepositoryInterface
{
    public function getAllData($search,$status,$district,$date,$orderStatusType,$type);
    
    public function getUseDistrictOrder();
    
    public function getAllDataDriver($search,$status,$district,$date,$type);

}