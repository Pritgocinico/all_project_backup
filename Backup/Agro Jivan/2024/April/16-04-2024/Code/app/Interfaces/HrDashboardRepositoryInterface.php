<?php

namespace App\Interfaces;

interface HrDashboardRepositoryInterface 
{
   public function employeeCount();
   public function holidayCount();
   public function getPresentCount();
   public function getAbsentCount();
   public function getTicketCount();
   public function getInfoSheetCount();
}  