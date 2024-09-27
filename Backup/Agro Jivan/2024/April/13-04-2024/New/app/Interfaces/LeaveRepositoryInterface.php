<?php

namespace App\Interfaces;

interface LeaveRepositoryInterface 
{
    public function getAllData($search,$date,$status,$feature,$type);
    
    public function store($data);
    
    public function getDetailById($id);
    
    public function update($data,$where);
    
    public function delete($id);

    public function getAllLeaveData($search, $date, $userId,$type);
    
    public function getAllDataCalendar($id);

    public function getTotalMonthLeave($month,$id);

    public function getTotalYearLeave($id);

    public function getMonthLeave($month);
    public function getTodayUserLeave($id);
}