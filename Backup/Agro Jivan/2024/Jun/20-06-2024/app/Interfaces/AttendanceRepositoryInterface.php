<?php

namespace App\Interfaces;

interface AttendanceRepositoryInterface 
{
    public function getPresentCount($month,$id,$status);

    public function getTodayAttendanceUser();
    public function storeBreakLog($data);
    public function updateAttendanceDetail($update,$where);
    public function getBreakLogDetail();
    public function userBreakLog();
    public function updateBreakLogTime($update,$where);
    public function getUserBreak($id,$data);
    public function getTodayAttendanceUserByID($id);
    public function dailyAttendanceDetail($search,$type,$status,$date);
}