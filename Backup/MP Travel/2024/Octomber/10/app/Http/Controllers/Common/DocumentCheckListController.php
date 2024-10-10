<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\DocumentCheckList;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class DocumentCheckListController extends Controller
{
    private $documentCheck;
    public function __construct()
    {
        $page = "Document Check List";
        $this->documentCheck = resolve(DocumentCheckList::class)->with('countryDetail', 'userDetail');
        view()->share('page', $page);
    }
    public function index()
    {
        $countryList = Country::get();
        return view('admin.document_checklist.index', compact('countryList'));
    }
    public function ajaxList(Request $request)
    {
        $documentCheckList = $this->documentCheck->latest()->get();
        return view('admin.document_checklist.ajax_list', compact('documentCheckList'));
    }

    public function store(Request $request)
    {
        $documentCheck = new DocumentCheckList();
        $documentCheck->country_code = $request->country_name;
        $documentCheck->visa_type = $request->visa_type;
        $documentCheck->created_by = Auth()->user()->id;
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('document_checklist', $fileName, 'public');
            $documentCheck->document_file = $filePath;
        }
        $insert = $documentCheck->save();
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Document Check List',
                'description' => "Created Document Check List - " . $request->country_name
            ]);
            return response()->json(['message' => 'Document Check List created successfully.'], 200);
        }
        return response()->json(['message' => 'Something Went To Wrong.'], 500);
    }
    public function edit($id)
    {
        $documentCheck = $this->documentCheck->find($id);
        return response()->json(['message' => '', 'data' => $documentCheck], 200);
    }
    public function update(Request $request, $id)
    {
        $documentCheck = $this->documentCheck->find($id);
        $documentCheck->country_code = $request->edit_country_name;
        $documentCheck->visa_type = $request->edit_visa_type;
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('document_checklist', $fileName, 'public');
            $documentCheck->document_file = $filePath;
        }
        $insert = $documentCheck->save();
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Document Check List',
                'description' => "Updated Document Check List - " . $request->country_name
            ]);
            return response()->json(['message' => 'Document Check List updated successfully.'], 200);
        }
        return response()->json(['message' => 'Something Went To Wrong.'], 500);
    }
    public function destroy($id)
    {
        $documentCheck = $this->documentCheck->find($id);
        $delete = $documentCheck->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Document Check List',
                'description' => "Deleted Document Check List - " . $documentCheck->country_code
            ]);
            return response()->json(['message' => 'Document Check List deleted successfully.'], 200);
        }
        return response()->json(['message' => 'Something Went To Wrong.'], 500);
    }

    public function exportCSV(Request $request)
    {

        $search = $request->search;

        $documentCheckList = $this->documentCheck
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($query) use ($search) {
                    $query->where('country_code', 'like', '%' . $search . '%')
                        ->orWhere('visa_type', 'like', '%' . $search . '%')
                        ->orWhere('created_at', 'like', '%' . $search . '%')
                        ->orWhereHas('userDetail', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                });
            })->latest()->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Document Check List.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Country Name', 'visa type', 'created_by', 'Created At');
            $callback = function () use ($documentCheckList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($documentCheckList as $document) {
                    $date = "";
                    if (isset($document->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($document->created_at);
                    }
                    $createdBy = isset($document->userDetail) ? $document->userDetail->name : "-";
                    fputcsv($file, array($document->country_code, $document->visa_type, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.document_check_list', ['documentCheckList' => $documentCheckList, 'setting' => $setting]);
            return $pdf->download('Document Check List.pdf');
        }
    }
    public function countryDocument(Request $request)
    {
        $documentCheckList = $this->documentCheck->where('country_code', $request->country)->first();
        if ($documentCheckList) {
            return response()->json(['message' => '', 'data' => $documentCheckList], 200);
        }
        return response()->json(['message' => '', 'data' => []], 500);
    }
}
