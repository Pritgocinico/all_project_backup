<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    protected $departmentRepository = "";
    public function __construct(DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Department";
        return view('admin.department.index',compact('page'));
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
    public function store(Request $request)
    {
        $data = ['department_name' => $request->department_name];
        $insert = $this->departmentRepository->store($data);
        if($insert){
            $log = 'Department (' . ucfirst($request->department_name) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Department', $log);
            return response()->json(['data'=>$insert,'message'=> 'Department Created Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = $this->departmentRepository->getDetailById($id);
        return response()->json(['data'=>$department,'message'=> '','status'=>1],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'department_name' =>$request->department_name,
            'status' =>$request->status == "on"?'1':'0',
        ];
        $where = ['id'=>$id];
        $update = $this->departmentRepository->update($data,$where);
        if($update){
            $log = 'Department (' . ucfirst($request->department_name) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Department', $log);
            return response()->json(['data'=>$update,'message'=> 'Department Updated Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = $this->departmentRepository->getDetailById($id);
        $delete = $this->departmentRepository->delete($id);
        if($delete){
            $log = 'Department (' . ucfirst($department->department_name) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Department', $log);
            return response()->json(['data'=>$delete,'message'=> 'Department Deleted Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $departmentList = $this->departmentRepository->getAllDepartment($search);
        return view('admin.department.ajax_list',compact('departmentList'));
    }
}
