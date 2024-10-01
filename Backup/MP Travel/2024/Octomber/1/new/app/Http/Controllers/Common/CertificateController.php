<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCertificateRequest;
use App\Models\Log;
use App\Models\Certificate;
use App\Models\User;
use App\Models\BusinessSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    private $certificate;
    public function __construct()
    {
        $page = "Certificate";
        $this->certificate = resolve(Certificate::class)->with('userDetail','employee');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeList = User::where('role_id', "!=", 1)->get();
        $monthList = UtilityHelper::getPastMonths();
        return view('admin.certificate.index', compact('employeeList', 'monthList'));
    }

    public function certificateAjaxList(Request $request)
    {
        $search = $request->search;
        $certificateList = $this->certificate
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('certificates.title', 'like', '%' . $search . '%')
                        ->orWhere('certificates.month_name', 'like', '%' . $search . '%')
                        ->orWhere('certificates.created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('employee', function ($query) use ($search) {
                            $query->where('users.name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(Auth()->user()->role_id == 2, function ($query) {
                return $query->where('emp_id', Auth()->user()->id);
            })
            ->latest()
            ->get();
        $employeeList = User::where('role_id', "!=", 1)->get();
        $monthList = UtilityHelper::getPastMonths();
        return view('admin.certificate.ajax_list', compact('certificateList', 'employeeList', 'monthList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        $certificateList = $this->certificate
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('certificates.title', 'like', '%' . $search . '%')
                        ->orWhere('certificates.month_name', 'like', '%' . $search . '%')
                        ->orWhere('certificates.created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('employee', function ($query) use ($search) {
                            $query->where('users.name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->when(Auth()->user()->role_id == 2, function ($query) {
                return $query->where('emp_id', Auth()->user()->id);
            })
            ->latest()
            ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=certificate Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Title', 'Employee', 'Month Name', 'Created At');
            $callback = function () use ($certificateList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($certificateList as $certificate) {
                    $date = "";
                    if (isset($certificate->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($certificate->created_at);
                    }

                    fputcsv($file, array($certificate->title, $certificate->employee->name, $certificate->month_name, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.certificate', ['certificateList' => $certificateList,'setting' =>$setting]);
            return $pdf->download('Certificate.pdf');
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
    public function store(CreateCertificateRequest $request)
    {
        $empID = $request->emp_id;
        $title = $request->title;
        $description = $request->description;
        $director = $request->director;
        $manager = $request->manager;
        $emp_certificate = new Certificate;
        $emp_certificate->title = $title;
        $emp_certificate->emp_id = $empID;
        $emp_certificate->description = $description;
        $emp_certificate->director = $director;
        $emp_certificate->manager = $manager;
        $emp_certificate->month_name = $request->month_name;
        $emp_certificate->created_by = Auth()->user()->id;
        $mangerSignPath = "";
        $faLogoPath = "";
        if ($request->hasFile('director_signature_file')) {
            $file = $request->file('director_signature_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $faLogoPath = $file->storeAs('director_signature', $newFilename, 'public');
            $emp_certificate->director_signature_file = $faLogoPath;
        }
        if ($request->hasFile('manager_signature_file')) {
            $file = $request->file('manager_signature_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $mangerSignPath = $file->storeAs('manager_signature', $newFilename, 'public');
            $emp_certificate->manager_signature_file = $mangerSignPath;
        }
        $employee = User::where('id', $empID)->first();
        $emp_department = BusinessSetting::where('id', $employee->department_id)->first();

        $setting = Setting::first();
        $pdf = PDF::loadView('admin.pdf.auto_certificate', ['employee' => $employee,'description' => $description,'title' => $title, 'director' => $director ,'faLogoPath' =>$faLogoPath ,'mangerSignPath' => $mangerSignPath,'manager' => $manager , 'emp_department' => $emp_department , 'setting' => $setting]);

        $fileName = Str::random(40) . '_Certificate_' . $empID . '.pdf';
        $filePath = 'certificates/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        $emp_certificate->file_path = $filePath;
        $emp_certificate->status = $request->status == "on" ? 1 : 0;

        $insert = $emp_certificate->save();
        if ($insert) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'Certificate',
                'description' => auth()->user()->name . " Generated a Certificate named '" . $title . "'"
            ]);
            return response()->json([
                'status' => 'success',
                'message' => "Certificate created successfully. ",
                'download_url' => asset('storage/' . $filePath)
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ], 500);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->certificate->find($id);
        if ($data) {
            $delete = $data->delete();
            if ($delete) {
                Log::create([
                    'user_id' => Auth()->user()->id,
                    'module' => 'Certificate',
                    'description' => auth()->user()->name . " Deleted a Certificate named '" . $data->title . "'"
                ]);
                return response()->json([
                    'status' => 'success',
                    'message' => "Certificate deleted successfully. "
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'message' => "Something went to Wrong."
            ], 500);
        }
        return response()->json([
            'status' => 'error',
            'message' => "Something went to Wrong."
        ], 500);
    }
}
