<?php

namespace App\Interfaces;

interface EmployeeDashboardRepositoryInterface 
{
    public function countOrderStatus($from,$status);

    public function countLeaveStatus($from,$status);

    public function totalAbsent($from,$status);

    public function getStoreYearOrder();
    public function getPendingCount($month,$year,$status);
    public function getSalesData($year);
}