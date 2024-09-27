<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDesignationRequest;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Models\Setting;
use PDF;

class DesignationController extends Controller
{
    private $designation;
    public function __construct()
    {
        $page = "Designation";
        view()->share('page', $page);
        $this->designation = resolve(Designation::class)->with('departmentDetail');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departmentList = Department::latest()->get();
        return view('admin.designation.index', compact('departmentList'));
    }

    public function designationAjaxList(Request $request)
    {
        $search = $request->search;
        $designationList = $this->designation
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('designations.name', 'like', '%' . $search . '%')
                        ->orWhere('designations.created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('departmentDetail', function ($query) use ($search) {
                            $query->where('departments.name', 'like', '%' . $search . '%');
                        })->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();
        return view('admin.designation.ajax_list', compact('designationList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $designationList = $this->designation
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('designations.name', 'like', '%' . $search . '%')
                        ->orWhere('designations.created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('departmentDetail', function ($query) use ($search) {
                            $query->where('departments.name', 'like', '%' . $search . '%');
                        })->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()
            ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Designation.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Designation Name', 'Department Name', 'Status', 'Created By', 'Created At');
            $callback = function () use ($designationList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($designationList as $designation) {
                    $date = "";
                    if (isset($designation->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($designation->created_at);
                    }
                    $status = "";
                    if ($designation->status == 1) {
                        $status = "Active";
                    } else {
                        $status = "Inactive";
                    }
                    $department = "";
                    if (isset($designation->departmentDetail)) {
                        $department = $designation->departmentDetail->name;
                    }
                    $createdBy = isset($designation->userDetail) ? $designation->userDetail->name : "-";
                    fputcsv($file, array($designation->name, $department, $status, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.designation', ['designationList' => $designationList,'setting' =>$setting]);
            return $pdf->download('Designation.pdf');
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
    public function store(CreateDesignationRequest $request)
    {
        $department = $this->designation->create([
            'name' => $request->name,
            'description' => $request->description,
            'department_id' => $request->department,
            'created_by' => auth()->user()->id,
        ]);
        if ($department) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " created a Designation named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Designation created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
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
        return response()->json(['status' => 1, 'data' => $designation], 200);
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
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " updated a Designation named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Designation updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $delete = $designation->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Designation',
                'description' => auth()->user()->name . " deleted a Designation named '" . $designation->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Designation deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
