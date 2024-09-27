<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\TeamRepositoryInterface;
use PDF;

class TeamController extends Controller
{
    protected $teamRepository,$employeeRepository;
    public function __construct(TeamRepositoryInterface $teamRepository,EmployeeRepositoryInterface $employeeRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->employeeRepository = $employeeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Team List";
        $managerList = $this->employeeRepository->getAllManagerList();
        $employeeList = $this->employeeRepository->getAllEmployeeTeam();
        return view('admin.team.index',compact('managerList','employeeList','page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTeamRequest $request)
    {
        $teamId = $this->teamRepository->generateTeamId();
        $data['team_id']=$teamId;
        $data['manager_id']=Auth()->user()->id;
        if(Auth()->user() !== null && Auth()->user()->role_id == 1){
            $data['manager_id']=$request->manager;
        }
        $data['team_name']=$request->team_name;
        $insert = $this->teamRepository->store($data);
        if($insert){
            foreach ($request->team_member as $key => $member) {
                $teamMember['team_id'] = $insert->id;
                $teamMember['user_id'] = $member;
                $this->teamRepository->storeTeamDetail($teamMember);
            }
            $log =  'Team (' . $teamId . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Team', $log);
            return response()->json(['data' => '', 'message' => 'Team Created Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = $this->teamRepository->getDetailById($id);
        $page = "View Team";
        $date = request('date');
        return view('admin.team.show',compact('team','id','page','date'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $team = $this->teamRepository->getDetailById($id);
        return response()->json($team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data['manager_id'] = $request->manager;
        $where['id'] = $id;
        $data['team_name']=$request->team_name;
        $update = $this->teamRepository->update($data,$where);
        if($update){
            $team = $this->teamRepository->getDetailById($id);
            $log =  'Team (' . $team->teamId . ') updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Team', $log);
            return response()->json(['data' => '', 'message' => 'Team Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function teamAjaxList(Request $request){
        $search = $request->search;
        $teamList = $this->teamRepository->getAllData($search,'paginate');
        return view('admin.team.ajax_list',compact('teamList'));
    }

    public function employeeList(Request $request){
        $id = $request->id;
        $employee = $this->employeeRepository->getNotIncludeTeamEmployee($id);
        return response()->json($employee);
    }

    public function updateTeamEmployee(CreateTeamRequest $request){
        foreach ($request->team_member as $key => $member) {
            $teamMember['team_id'] = $request->add_team_id;
            $teamMember['user_id'] = $member;
            $this->teamRepository->storeTeamDetail($teamMember);
        }
        return response()->json(['data' => '', 'message' => 'Team Member Updated Successfully', 'status' => 1], 200);
    }

    public function viewTeamEmployeeAjax(Request $request){
        $id = $request->id;
        $search = $request->search;
        $date = $request->date;
        $teamDetail = $this->teamRepository->getTeamDetailById($id,$search,$date);
        $team = $this->teamRepository->getManagerOrderCount($id,$date);
        return view('admin.team.view_ajax',compact('teamDetail','team'));
    }

    public function removeTeamMember(Request $request){
        $where['team_id'] = $request->team_id;
        $where['user_id'] = $request->emp_id;
        $remove = $this->teamRepository->removeMember($where);
        if($remove){
            $team = $this->teamRepository->getDetailById($request->team_id);
            $log =  'Team (' . $team->teamId . ') from Member  Removed by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Team', $log);
            return response()->json(['data' => '', 'message' => 'Team Member Removed Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function exportCSV(Request $request){
        $search = $request->search;
        $teamList = $this->teamRepository->getAllData($search,'export');
        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Team.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Team ID', 'Manager Name', 'Team Size', 'Created AT');
            $callback = function () use ($teamList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($teamList as $team) {
                    $manager = isset($team->managerDetail) ? $team->managerDetail->name : '-';
                    $totalMember = isset($team->teamMember) ? count($team->teamMember) : '0';
                    $date = "";
                    if($team->created_at !== null){
                        $date = UtilityHelper::convertDmyWith12HourFormat($team->created_at);
                    }
                    fputcsv($file, array($team->team_id, $manager , $totalMember,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.team_list', ['teamList' => $teamList]);
            return $pdf->download('Team.pdf');
        }
    }
}
