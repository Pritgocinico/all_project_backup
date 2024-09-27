<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\ServicePreference;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class ServicePreferenceController extends Controller
{
    private $servicePreference;
    public function __construct()
    {
        $page = "Service Preference";
        $this->servicePreference = resolve(ServicePreference::class)->with('userDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.service_preference.index');
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
        $servicePreference = new ServicePreference();
        $servicePreference->name = $request->service_preference_name;
        $servicePreference->created_by = Auth()->user()->id;
        $insert = $servicePreference->save();
        if ($insert) {
            Log::create([
                'module' => 'Service Preference',
                'user_id' => auth()->user()->id,
                'description' => "Created Service Preference - " . $request->name
            ]);
            return response()->json(['status' => 1, 'message' => 'Service Preference created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicePreference $servicePreference)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServicePreference $servicePreference)
    {
        return response()->json(['data' => $servicePreference, 'status' => 1], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServicePreference $servicePreference)
    {
        if ($servicePreference) {
            $servicePreference->name = $request->service_preference_name;
            $update = $servicePreference->save();
            if ($update) {
                Log::create([
                    'module' => 'Service Preference',
                    'user_id' => auth()->user()->id,
                    'description' => "Updated Service Preference - " . $request->name
                ]);
                return response()->json(['status' => 1, 'message' => 'Service Preference updated successfully.'], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicePreference $servicePreference)
    {
        if ($servicePreference) {
            $delete = $servicePreference->delete();
            if ($delete) {
                Log::create([
                    'module' => 'Service Preference',
                    'user_id' => auth()->user()->id,
                    'description' => "Deleted Service Preference - " . $servicePreference->name
                ]);
                return response()->json(['status' => 1, 'message' => 'Service Preference deleted successfully.'], 200);
            }
            return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }
    public function ajaxList(Request $request)
    {
        $servicePreferenceList = $this->servicePreference->get();
        return view('admin.service_preference.ajax_list', compact('servicePreferenceList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $format = $request->format;
        $servicePreferenceList = $this->servicePreference
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%')
                    ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })->latest()->get();
        if($format == "csv" || $format == "excel"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Service Preference.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Service Preference Name', 'created_by', 'Created At');
            $callback = function () use ($servicePreferenceList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($servicePreferenceList as $servicePreference) {
                    $date = "";
                    if (isset($servicePreference->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($servicePreference->created_at);
                    }
                    $createdBy = isset($servicePreference->userDetail) ? $servicePreference->userDetail->name : "-";
                    fputcsv($file, array($servicePreference->name, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        } else {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.service_preference', ['servicePreferenceList' => $servicePreferenceList,'setting' =>$setting]);
            return $pdf->download('Service Preference.pdf');
        }
    }
}
