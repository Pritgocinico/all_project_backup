<?php

namespace App\Interfaces;

interface AdminDashboardRepositoryInterface 
{
    public function totalOrderCount($status);
    
    public function totalAllOrderCount();
    public function totalDriverAssignedOrderCount();
    public function totalDriverAssignedOrderCountWithStatus($status);
    
    public function getTotalEmployeeAbsentPresent($type,$status);
    public function totalAdminOrderCount($date,$status);
    public function getTotalBatchOnRoute();
}