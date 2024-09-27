<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateHolidayRequest;
use App\Models\Holiday;
use App\Models\Log;
use Illuminate\Http\Request;

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
}
