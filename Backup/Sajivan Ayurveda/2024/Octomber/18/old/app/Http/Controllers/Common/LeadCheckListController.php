<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeadCheckListRequest;
use App\Models\LeadCheckList;
use App\Models\LeadCheckListItem;
use App\Models\Log;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class LeadCheckListController extends Controller
{
    protected $leadCheckList;

    public function __construct(){
        $page = "Lead Check List";
        $this->leadCheckList = resolve(LeadCheckList::class)->with('userDetail','leadCheckListItems');
        view()->share('page', $page);
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.lead_check_list.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.lead_check_list.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadCheckListRequest $request)
    {
        $checkList = new LeadCheckList();
        $checkList->type = $request->lead_type;
        $checkList->created_by = Auth()->user()->id;
        $insert = $checkList->save();
        if($insert){
            if(isset($request->name)){
                foreach ($request->name as $key => $name) {
                    $checkListItem = new LeadCheckListItem();
                    $checkListItem->lead_check_list_id = $checkList->id;
                    $checkListItem->name = $name;
                    $checkListItem->save();
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead Check List',
                'description' => "Created Lead Check List - " . $request->type
            ]);
            return redirect()->route('lead-checklist.index')->with('success', 'Lead Check List created successfully.');
        }
        return redirect()->route('lead-checklist.create')->with('success', 'Something Went To Wrong.');
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
        $checkList = $this->leadCheckList->find($id);
        return view('admin.lead_check_list.edit', compact('checkList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadCheckListRequest $request, string $id)
    {
        $checkList = $this->leadCheckList->find($id);
        $checkList->type = $request->lead_type;
        $insert = $checkList->save();
        if($insert){
            if(isset($request->check_list_item_id)){
                foreach ($request->check_list_item_id as $key => $checkId) {
                    $checkListItem = LeadCheckListItem::find($checkId);
                    if(!$checkListItem){   
                        $checkListItem = new LeadCheckListItem();
                    }
                    $checkListItem->lead_check_list_id = $checkList->id;
                    $checkListItem->name = $request->name[$key];
                    $checkListItem->save();
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead Check List',
                'description' => "Updated Lead Check List - " . $request->type
            ]);
            return redirect()->route('lead-checklist.index')->with('success', 'Lead Check List updated successfully.');
        }
        return redirect()->route('lead-checklist.edit',$id)->with('success', 'Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $checkList = $this->leadCheckList->find($id);
        if($checkList){
            $checkList->delete();
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead Check List',
                'description' => "Deleted Lead Check List - " . $checkList->type
            ]);
            return response ()->json(['data' => [], 'message' => "Lead Check List deleted successfully."], 200);
        }
        return response ()->json(['data' => [], 'message' => "Something went to wrong."], 500);
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $leadCheckList = $this->leadCheckList->when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%')
                ->orWhereHas('userDetail', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('leadCheckListItems', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        })->latest()->get();
        return view('admin.lead_check_list.ajax_list', compact('leadCheckList'));

    }
    public function removeItem(Request $request){
        $leadCheckListItem = LeadCheckListItem::find($request->id);
        if($leadCheckListItem){
            $leadCheckListItem->delete();
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Lead Check List',
                'description' => "Removed Lead Check List Item - " . $leadCheckListItem->name,
            ]);
            return response()->json(['success' => true, 'message' => 'Lead Check List item removed successfully.'],200);
        }
        return response()->json(['success' => true, 'message' => 'Something went to wrong.'],500);
    }
    public function exportCSV(Request $request){
    
        $search = $request->search;

        $leadCheckList = $this->leadCheckList
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

            $columns = array('Lead Type','Name' ,'created_by', 'Created At');
            $callback = function () use ($leadCheckList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($leadCheckList as $lead) {
                    $date = "";
                    if (isset($lead->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($lead->created_at);
                    }
                    $name = "";
                    foreach ($lead->leadCheckListItems as $key => $item) {
                        $name .= $item->name . ", "; 
                    }
                    $createdBy = isset($lead->userDetail) ? $lead->userDetail->name : "-";
                    fputcsv($file, array($lead->type,$name, $createdBy, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.lead_check_list', ['leadCheckList' => $leadCheckList,'setting' =>$setting]);
            return $pdf->download('Document Check List.pdf');
        }
    }
}
