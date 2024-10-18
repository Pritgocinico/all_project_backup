<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInfoSheetRequest;
use App\Models\Log;
use App\Models\InfoSheet;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\SendNotification;
use Pusher\Pusher;
use Notification;
use PDF;
use Illuminate\Http\Request;

class InfoSheetController extends Controller
{

    private $infosheet;
    public function __construct()
    {
        $page = "Incentive";
        $this->infosheet = resolve(InfoSheet::class);
        view()->share('page', $page);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.infosheet.index');
    }

    public function infosheetAjaxList(Request $request)
    {
        $search = $request->search;
        $infosheetList = $this->infosheet
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
        return view('admin.infosheet.ajax_list', compact('infosheetList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $infosheetList = $this->infosheet
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

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=infosheet Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Title','Description', 'Created At');
            $callback = function () use ($infosheetList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($infosheetList as $infosheet) {
                    $date = "";
                    if (isset($infosheet->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($infosheet->created_at);
                    }
                    $name = isset($infosheet->userDetail) ? $infosheet->userDetail->name :"-";
                    fputcsv($file, array($infosheet->name,$infosheet->description,$name ,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.infosheet', ['infosheetList' => $infosheetList,'setting' =>$setting]);
            return $pdf->download('Infosheet.pdf');
            
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $infosheetList = InfoSheet::get();
        $employees = User::where('status',1)->where('role_id', "!=" , '1')->get();
        return view('admin.infosheet.create', compact('infosheetList', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateInfoSheetRequest $request)
    {
        $data = [
            'name' => $request->title,
            'employee_id' => $request->employee,
            'date' => $request->incentive_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'created_by' => auth()->user()->id,
        ];

        if ($request->hasFile('incentive_doc')) {

            $file = $request->file('incentive_doc');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('incentive', $fileName, 'public');

            $data['info_sheet'] = $filePath;
        }

        $insert = $this->infosheet->create($data);
        if ($insert) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Incentive',
                'description' => auth()->user()->name . " created a incentive named '" . $request->name . "'"
            ]);
            $config = config('services')['pusher'];
            $userList = User::where('status',1)->where('id', "!=", Auth()->user()->id)->get();
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Incentive',
                    'title' => 'New Incentive Added.',
                    'text' => $request->title,
                    'url' => route('incentive.index'),
                ];

                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            return redirect()->route('incentive.index')->with('success', 'Incentive has been created successfully.');
        }
        return redirect()->route('incentive.create')->with('error', 'Something went wrong.');
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
        $employees = User::where('status',1)->where('role_id', "!=", '1')->get();
        return view('admin.infosheet.edit', compact('infosheet', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateInfoSheetRequest $request, string $id)
    {
        $infosheet = $this->infosheet->findOrFail($id);
        $data = [
            'name' => $request->title,
            'employee_id' => $request->employee,
            'date' => $request->incentive_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'updated_by' => auth()->user()->id,
        ];

        if ($request->hasFile('incentive_doc')) {

            $file = $request->file('incentive_doc');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('incentive', $fileName, 'public');

            if ($infosheet->incentive_doc) {
                Storage::disk('public')->delete($infosheet->incentive_doc);
            }

            $data['info_sheet'] = $filePath;
        }

        $update = $infosheet->update($data);
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'InfoSheet',
                'description' => auth()->user()->name . " updated a incentive named '" . $request->name . "'"
            ]);
            return redirect()->route('incentive.index')->with('success', 'Incentive has been Updated successfully.');
        }
        return redirect()->route('incentive.create')->with('error', 'Something went wrong.');
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
                'description' => auth()->user()->name . " Deleted Info Sheet named '" . $infosheet->name . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Info sheet has been deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
