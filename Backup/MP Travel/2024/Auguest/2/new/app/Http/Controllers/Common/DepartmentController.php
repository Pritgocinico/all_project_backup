<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDepartmentRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Log;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    private $departments;
    public function __construct()
    {
        $page = "Department";
        $this->departments = resolve(Department::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departList = $this->departments->paginate(10);
        return view('admin.department.index', compact('departList'));
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
    public function store(CreateDepartmentRequest $request)
    {
        $data = [
            'created_by' => auth()->user()->id,
            'name' => $request->name,
        ];
        $department = $this->departments->create($data);
        if($department){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " created a department named '" . $request->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Department created successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return response()->json(['status'=>1,'data' => $department],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDepartmentRequest $request, Department $department)
    {
        $update = $department->update([
            'name' => $request->name,
            'status' => $request->status == "on" ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ]);
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " updated a department named '" . $request->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Department updated successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $delete = $department->delete();
        if($delete){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " deleted a department named '" . $department->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Department deleted successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
    }
    public function getDesignationByDepartment(Request $request){
        $department = $request->department;
        $designation = Designation::where('department_id', $department)->where('status', '1')->get();
        return response()->json($designation);
    }
}