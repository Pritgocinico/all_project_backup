<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\HolidayRepositoryInterface;
use Illuminate\Http\Request;
use PDF;

class EmployeeHolidayController extends Controller
{
    protected $holidayRepository = "";
    public function __construct(HolidayRepositoryInterface $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    public function index()
    {
        $page = "Holiday List";
        return view('employee.holiday.index',compact('page'));
    }

    public function ajaxList(Request $request)
    {
        $holidayList = $this->holidayRepository->getAllData($request->search);
        return view('employee.holiday.ajax_list',compact('holidayList'));
    }

    public function exportCSV(Request $request){
        $holidayList = $this->holidayRepository->getAllDataExport($request->search);
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Holiday.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Holiday Date', 'Reason', 'Created At');
            $callback = function () use ($holidayList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($holidayList as $holiday) {
                    $holidayDate = UtilityHelper::convertDmy($holiday->holiday_date);
                    $date = "";
                    if (isset($holiday->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($holiday->created_at);
                    }
                    fputcsv($file, array($holidayDate, $holiday->holiday_name,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.holiday', ['holiday' => $holidayList]);
            return $pdf->download('Holiday.pdf');
        }
    }
}
