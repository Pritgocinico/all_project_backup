<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDepartmentRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

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
        return view('admin.department.index');
    }

    public function departmentAjaxList(Request $request)
    {
        $search = $request->search;
        $departList = $this->departments
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();
        return view('admin.department.ajax_list', compact('departList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $departList = $this->departments
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Department.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Department Name', 'created_by', 'Created At');
            $callback = function () use ($departList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($departList as $department) {
                    $date = "";
                    if (isset($department->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($department->created_at);
                    }
                    $createdBy = isset($department->userDetail) ? $department->userDetail->name : "-";
                    fputcsv($file, array($department->name, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.department', ['departList' => $departList,'setting' =>$setting]);
            return $pdf->download('Department.pdf');
        }
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
        if ($department) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " created a department named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Department created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
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
        return response()->json(['status' => 1, 'data' => $department], 200);
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
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " updated a department named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Department updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $delete = $department->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Department',
                'description' => auth()->user()->name . " deleted a department named '" . $department->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Department deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
    public function getDesignationByDepartment(Request $request)
    {
        $department = $request->department;
        $designation = Designation::where('department_id', $department)->where('status', '1')->get();
        return response()->json($designation);
    }
}
