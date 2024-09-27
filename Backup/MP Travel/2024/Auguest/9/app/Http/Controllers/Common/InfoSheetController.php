<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInfoSheetRequest;
use App\Models\Log;
use App\Models\InfoSheet;
use App\Models\User;
use App\Notifications\SendNotification;
use Pusher\Pusher;
use Notification;
use Dompdf\Dompdf;
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

    public function infosheetAjaxList(Request $request){
        $search = $request->search;
        $infosheetList = $this->infosheet
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%');
            });
        })->latest()
        ->paginate(10);
        return view('admin.infosheet.ajax_list',compact('infosheetList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $infosheetList = $this->infosheet
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%');
            });
        })->latest()
        ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=infosheet Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Title', 'Status', 'Created At');
            $callback = function () use ($infosheetList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($infosheetList as $infosheet) {
                    $date = "";
                    if (isset($infosheet->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($infosheet->created_at);
                    }
                    $status = "";
                    if ($infosheet->status == 0) {
                        $status = 'Inactive';
                    }else{
                        $status = 'Active';
                    }
                    fputcsv($file, array($infosheet->name, $status, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.infosheet', ['infosheetList' => $infosheetList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Infosheet.pdf'
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $infosheetList = InfoSheet::get();
        return view('admin.infosheet.create', compact('infosheetList'));
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
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " created a infosheet named '" . $request->name . "'"
            ]);
            $config = config('services')['pusher'];
            $userList = User::where('id', "!=", Auth()->user()->id)->get();
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'InfoSheet',
                    'title' => 'New Info Sheet Added.',
                    'text' => $request->title,
                    'url' => route('info_sheet.index'),
                ];
                
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            return redirect()->route('info_sheet.index')->with('success', 'Info sheet has been created successfully.');
        }
        return redirect()->route('info_sheet.create')->with('error', 'Something went wrong.');
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
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " updated a infosheet named '" . $request->name . "'"
            ]);
            return redirect()->route('info_sheet.index')->with('success', 'Infosheet has been Updated successfully.');
        }
        return redirect()->route('info_sheet.create')->with('error', 'Something went wrong.');
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
