<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Interfaces\AdminDashboardRepositoryInterface;
use App\Interfaces\BatchRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    protected $adminDashboardRepository, $employeeRepository, $teamRepository, $batchRepository = "";
    public function __construct(AdminDashboardRepositoryInterface $adminDashboardRepository, EmployeeRepositoryInterface $employeeRepository, TeamRepositoryInterface $teamRepository, BatchRepositoryInterface $batchRepository)
    {
        $this->adminDashboardRepository = $adminDashboardRepository;
        $this->employeeRepository = $employeeRepository;
        $this->teamRepository = $teamRepository;
        $this->batchRepository = $batchRepository;
    }
    public function dashboard()
    {
        $page = "Admin Dashboard";
        $totalEmployeePresent = $this->adminDashboardRepository->getTotalEmployeeAbsentPresent('', 'present');
        $totalEmployeeAbsent = $this->adminDashboardRepository->getTotalEmployeeAbsentPresent('', 'absent');
        $totalManagerPresent = $this->adminDashboardRepository->getTotalEmployeeAbsentPresent('manager', 'present');
        $totalManagerAbsent = $this->adminDashboardRepository->getTotalEmployeeAbsentPresent('manager', 'absent');
        $totalBatchRoute = $this->adminDashboardRepository->getTotalBatchOnRoute();
        $driverList = $this->employeeRepository->getAllDriver();
        $teamList = $this->teamRepository->getTopFiveUserTeam("");
        return view('admin.index', compact('driverList', 'totalEmployeePresent', 'totalEmployeeAbsent', 'totalManagerPresent', 'totalManagerAbsent', 'totalBatchRoute', 'page', 'teamList'));
    }
    
    public function dashboardOrderAjax(Request $request)
    {
        $data['pendingCount'] = $this->adminDashboardRepository->totalAdminOrderCount($request->search_date, '1');
        $data['confirmCount'] = $this->adminDashboardRepository->totalAdminOrderCount($request->search_date, '2');
        $data['cancelCount'] = $this->adminDashboardRepository->totalAdminOrderCount($request->search_date, '4');
        $data['returnCount'] = $this->adminDashboardRepository->totalAdminOrderCount($request->search_date, '5');
        $data['completeCount'] = $this->adminDashboardRepository->totalAdminOrderCount($request->search_date, '6');
        
        return response()->json($data);
    }
    
    public function dashboardBatchAjax(Request $request)
    {
        $search = $request->search;
        $driverId = $request->diver_id;
        $date = $request->batch_date;
        
        $batchList = $this->batchRepository->getDashboardBatchList($search, $date, $driverId, 'paginate');
        return view('admin.dashboard.ajax_list',compact('batchList'));
    }
    
    public function dashboardTeamAjax(Request $request){
        $date = $request->date;
        $teamList = $this->teamRepository->getTopFiveUserTeam($date);
        return view('admin.dashboard.team_ajax',compact('teamList'));
    }
}
