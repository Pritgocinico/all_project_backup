<?php

namespace App\Repositories;

use App\Helpers\UtilityHelper;
use App\Interfaces\HolidayRepositoryInterface;
use App\Models\Attendance;
use App\Models\Holiday;

class HolidayRepository implements HolidayRepositoryInterface 
{
    public function getAllData($search){
        return Holiday::where('holiday_name','like', '%'.$search.'%')->latest()->paginate(15);
    }
    public function getAllHoliday(){
        return Holiday::get()
            ->map(function ($item) {
                return [
                    'title' => $item->holiday_name,
                    'start' => UtilityHelper::convertYmd($item->holiday_date),
                    'end' => UtilityHelper::convertYmd($item->holiday_date),
                    'color' => '#d0995d',
                ];
            })->toArray();
    }

    public function getAllAttendance($id){
        return Attendance::where('user_id', $id)
        ->get()
        ->map(function ($item) {
            $title = "Absent";
            $color = "red";
            if($item->status == 1){
                $title = 'Present';
                $color = "green";
            }
            if($item->status == 2){
                $title = 'Half Day';
                $color = "gray";
            }
            return [
                'title' => ($title),
                'start' => UtilityHelper::convertYmd($item->created_at),
                'end' => UtilityHelper::convertYmd($item->created_at),
                'color' => $color,
            ];
        })->toArray();
    }

    public function store($data){
        return Holiday::create($data);
    }

    public function getDetailById($id){
        return Holiday::where('id',$id)->first();
    }

    public function update($update,$where){
        return Holiday::where($where)->update($update);
    }

    public function delete($id){
        return Holiday::where('id',$id)->delete();
    }

    public function getAllDataExport($search){
        return Holiday::where('holiday_name','like', '%'.$search.'%')->latest()->get();
    }

    public function getTodayHoliday(){
        return Holiday::where('holiday_date',date('Y-m-d'))->get();
    }
}