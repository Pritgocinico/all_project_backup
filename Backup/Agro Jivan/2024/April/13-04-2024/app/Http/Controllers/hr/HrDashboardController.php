<?php

namespace App\Http\Controllers\hr;

use App\Http\Controllers\Controller;
use App\Interfaces\HrDashboardRepositoryInterface;
use Illuminate\Http\Request;

class HrDashboardController extends Controller
{
    protected $hrDashboardRepository = "";
    public function __construct(HrDashboardRepositoryInterface $hrDashboardRepository)
    {
        $this->hrDashboardRepository = $hrDashboardRepository;
    }
    public function index(){
        $page = "Human Resource Dashboard";
        $totalEmployee = $this->hrDashboardRepository->employeeCount();
        $holidayCount = $this->hrDashboardRepository->holidayCount();
        $presentCount = $this->hrDashboardRepository->getPresentCount();
        $absentCount = $this->hrDashboardRepository->getAbsentCount();
        $ticketCount = $this->hrDashboardRepository->getTicketCount();
        $infoSheetCount = $this->hrDashboardRepository->getInfoSheetCount();
        return view('hr.index',compact('totalEmployee','holidayCount','presentCount','absentCount','ticketCount','infoSheetCount','page'));
    }

    public function attendance(){
        return view('hr.attendance');
    }
    public function certificate(){
        return view('hr.certificate');
    }
}
