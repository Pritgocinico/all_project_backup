<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInfoSheetRequest;
use App\Models\Log;
use App\Models\InfoSheet;
use Illuminate\Http\Request;

class InfoSheetController extends Controller
{

    private $infosheet;
    public function __construct()
    {
        $page = "Info Sheet";
        $this->infosheet = resolve(InfoSheet::class);
        view()->share('page', $page);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $infosheetList = $this->infosheet->latest()->paginate(10);
        return view('admin.infosheet.index', compact('infosheetList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $infosheetList = InfoSheet::get();
        return view('admin.infosheet.create',compact('infosheetList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateInfoSheetRequest $request)
    {
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status == "on" ? 1 : 0,
            'created_by' => auth()->user()->id,
        ];

        if ($request->hasFile('info_sheet')) {
            
            $file = $request->file('info_sheet');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('infosheets', $fileName, 'public');

            $data['info_sheet'] = $filePath;
        }
        
        $insert = $this->infosheet->create($data);
        if($insert){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " created a infosheet named '" . $request->name . "'"
            ]);
            return redirect()->route('infosheet.index')->with('success', 'Infosheet has been created successfully.');
        }
        return redirect()->route('infosheet.create')->with('success', 'Something went wrong.');
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
        $infosheet = $this->infosheet->find($id);
        return view('admin.infosheet.create', compact('infosheet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateInfoSheetRequest $request, string $id)
    {
        $infosheet = $this->infosheet->findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status == "on" ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ];

        if ($request->hasFile('info_sheet')) {
            
            $file = $request->file('info_sheet');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('infosheets', $fileName, 'public');

            if ($infosheet->info_sheet) {
                Storage::disk('public')->delete($infosheet->info_sheet);
            }
    
            $data['info_sheet'] = $filePath;
        }
        
        $update = $infosheet->update($data);
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " updated a infosheet named '" . $request->name . "'"
            ]);
            return redirect()->route('infosheet.index')->with('success', 'Infosheet has been Updated successfully.');
        }
        return redirect()->route('infosheet.create')->with('success', 'Something went wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $infosheet = $this->infosheet->find($id);
        $delete = $infosheet->delete();
        if ($delete) {
            Log::create([
                'user_id' => Auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " Deleted InfoSheet named '" . $infosheet->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'InfoSheet has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
