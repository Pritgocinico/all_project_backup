<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Log;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    private $designation;
    public function __construct()
    {
        $this->designation = resolve(Designation::class)->with('departmentDetail');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designationList = $this->designation->paginate(10);
        $departmentList = Department::latest()->get();
        return view('admin.designation.index', compact('designationList','departmentList'));
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
    public function store(CreateDesignationRequest $request)
    {
        $department = $this->designation->create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department,
            'created_by' => auth()->user()->id,
        ]);
        if($department){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " created a Designation named '" . $request->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Designation created successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return response()->json(['status'=>1,'data' => $designation],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDesignationRequest $request, Designation $designation)
    {
        $update = $designation->update([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department,
            'status' => $request->status == "on" ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ]);
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " updated a Designation named '" . $request->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Designation updated successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $delete = $designation->delete();
        if($delete){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " deleted a Designation named '" . $designation->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Designation deleted successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
    }
}
