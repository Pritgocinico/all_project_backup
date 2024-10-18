<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Disease;
use Illuminate\Http\Request;
use App\Http\Requests\CreateDiseaseRequest;
use App\Models\Log;
use App\Models\Setting;
use PDF;

class DiseaseController extends Controller
{
    private $diseases;
    public function __construct()
    {
        $page = "Disease";
        $this->diseases = resolve(Disease::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.disease.index');
    }

    public function diseaseAjaxList(Request $request)
    {
        $search = $request->search;
        $diseasesList = $this->diseases
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
        return view('admin.disease.ajax_list', compact('diseasesList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $diseasesList = $this->diseases
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
                "Content-Disposition" => "attachment; filename=Disease.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Disease Name', 'created_by', 'Created At');
            $callback = function () use ($diseasesList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($diseasesList as $disease) {
                    $date = "";
                    if (isset($disease->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($disease->created_at);
                    }
                    $createdBy = isset($disease->userDetail) ? $disease->userDetail->name : "-";
                    fputcsv($file, array($disease->name, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.disease', ['diseasesList' => $diseasesList,'setting' =>$setting]);
            return $pdf->download('Disease.pdf');
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
    public function store(CreateDiseaseRequest $request)
    {
        $data = [
            'created_by' => auth()->user()->id,
            'name' => $request->name,
        ];
        $disease = $this->diseases->create($data);
        if ($disease) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Disease',
                'description' => auth()->user()->name . " created a disease named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Disease created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
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
    public function edit(Disease $disease)
    {
        return response()->json(['status' => 1, 'data' => $disease], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateDiseaseRequest $request, Disease $disease)
    {
        $update = $disease->update([
            'name' => $request->name,
            'updated_by' => auth()->user()->id,
        ]);
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Disease',
                'description' => auth()->user()->name . " updated a disease named '" . $request->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Disease updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disease $disease)
    {
        $delete = $disease->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Disease',
                'description' => auth()->user()->name . " deleted a disease named '" . $disease->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Disease deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
