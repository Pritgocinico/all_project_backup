<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateHolidayRequest;
use App\Models\Holiday;
use App\Models\Log;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Notification;
use Dompdf\Dompdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HolidayImport;

class HolidayController extends Controller
{
    private $holiday;
    public function __construct()
    {
        $page = "Holiday";
        $this->holiday = resolve(Holiday::class);
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidayList = $this->holiday->latest()->paginate(10);
        return view('admin.holiday.index', compact('holidayList'));
    }

    public function holidayAjaxList(Request $request){
        $search = $request->search;
        $holidayList = $this->holiday
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('holiday_name', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->paginate(10);
        return view('admin.holiday.ajax_list',compact('holidayList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $holidayList = $this->holiday
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('holiday_name', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
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
                "Content-Disposition" => "attachment; filename=holiday Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Holiday Name', 'Holiday Date', 'Description', 'Status','Created By' ,'Created At');
            $callback = function () use ($holidayList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($holidayList as $holiday) {
                    $date = "";
                    if (isset($holiday->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($holiday->created_at);
                    }
                    $holidayDate = "";
                    if (isset($holiday->created_at)) {
                        $holidayDate = UtilityHelper::convertDMYFormat($holiday->holiday_date);
                    }
                    $status = "";
                    if ($holiday->status == 0) {
                        $status = 'Inactive';
                    }else{
                        $status = 'Active';
                    }
                    $name = isset($holiday->userDetail)? $holiday->userDetail->name :"-";
                    fputcsv($file, array($holiday->holiday_name, $holidayDate, $holiday->description, $status, $name,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.holiday', ['holidayList' => $holidayList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Holiday.pdf'
            );
        }
        return response()->json(['data'=>"",'message' => 'Please Select Export Format.','status'=>1],500);
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
    public function store(CreateHolidayRequest $request)
    {
        $holiday_data = $this->holiday->create([
            'holiday_name' => $request->holiday_name,
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
            'status' => $request->status == "on" ? 1 : 0,
            'created_by' => auth()->user()->id,
        ]);
        if($holiday_data){
            $userList = User::where('id', "!=", Auth()->user()->id)->get();
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Holiday',
                    'title' => "New Holiday added for ".$request->holiday_date,
                    'text' => $request->holiday_name,
                    'url' => route('holiday.index'),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Holiday',
                'description' => auth()->user()->name . " created a Holiday named '" . $request->holiday_name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Holiday created successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
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
    public function edit(Holiday $holiday)
    {
        return response()->json(['status'=>1,'data' => $holiday],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateHolidayRequest $request, Holiday $holiday)
    {
        $update = $holiday->update([
            'holiday_name' => $request->holiday_name,
            'holiday_date' => $request->holiday_date,
            'description' => $request->description,
            'status' => $request->status == "on" ? 1 : 0,
            'updated_by' => auth()->user()->id,
        ]);
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Holiday',
                'description' => auth()->user()->name . " updated a Holiday named '" . $request->holiday_name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Holiday updated successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $delete = $holiday->delete();
        if($delete){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Holiday',
                'description' => auth()->user()->name . " deleted a Holiday named '" . $holiday->holiday_name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Holiday deleted successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
    }

    public function importExcel(Request $request){
        if($request->hasFile('import_file')){
            $import = Excel::import(new HolidayImport, $request->import_file->getRealPath(), null, \Maatwebsite\Excel\Excel::XLSX);
            if(isset($import)){
                return redirect()->route('holiday.index')->with('success','Holiday Imported Successfully.');
            }
            return redirect()->route('holiday.index')->with('error','Something Went to Wrong.');
        }
    }
}
