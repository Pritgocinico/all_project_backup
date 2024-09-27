<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCertificateRequest;
use App\Models\Log;
use App\Models\Certificate;
use App\Models\User;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class CertificateController extends Controller
{
    private $certificate;
    public function __construct()
    {
        $page = "Certificate";
        $this->certificate = resolve(Certificate::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $certificateList = $this->certificate->with('employee')->latest()->paginate(10);
        $employeeList = User::where('role_id', "!=", 1)->get();
        return view('admin.certificate.index', compact('certificateList', 'employeeList'));
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
        $employee = User::where('id', $empID)->first();
        $emp_department = BusinessSetting::where('id', $employee->department_id)->first();
        // $logoUrl = $emp_department->logo;
        $logoUrl = asset('storage/' . $emp_department->logo);
        // $mainLogoUrl = url('storage/logo/logo.jpg');
        $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png'); // Ensure full URL
        
        $dompdf = new Dompdf();
        $viewFile = view('admin.pdf.auto_certificate', compact('employee','description' ,'title','logoUrl', 'mainLogoUrl', 'director', 'manager'))->render();
        $dompdf->loadHtml($viewFile);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $fileName = $title . '_' . $empID . '.pdf';
        $filePath = 'certificates/' . $fileName;
        Storage::disk('public')->put($filePath, $output);

        $emp_certificate = new Certificate;
        $emp_certificate->title = $title;
        $emp_certificate->emp_id = $empID;
        $emp_certificate->description = $description;
        $emp_certificate->director = $director;
        $emp_certificate->manager = $manager;
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
                'download_url' => asset('storage/' . $filePath)
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong.'
        ]);
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
        //
    }
}
