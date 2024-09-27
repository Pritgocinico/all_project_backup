<?php

namespace App\Interfaces;

interface HolidayRepositoryInterface 
{
    public function getAllData($search);
    public function getAllHoliday();
    public function getAllAttendance($id);
    public function store($data);
    public function getDetailById($id);
    public function update($update,$where);
    public function delete($id);
    public function getAllDataExport($search);
    public function getTodayHoliday();
}