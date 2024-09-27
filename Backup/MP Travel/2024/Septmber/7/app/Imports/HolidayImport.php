<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Holiday;
use App\Models\RoleUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HolidayImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $requiredKeys = ['holiday_name', 'holiday_date', 'description'];

        // Check if all required keys exist in the row
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $row)) {
                return null;
            }
        }
        $holidayDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['holiday_date']);
        $formattedHolidayDate = Carbon::instance($holidayDate)->format('Y-m-d');
        return new Holiday([
            'holiday_name' => $row['holiday_name'],
            'holiday_date' => $formattedHolidayDate,
            'description'  => $row['description'],
        ]);
        // }catch(\Exception $e){

        // }
    }
}
