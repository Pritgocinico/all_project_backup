<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShiftTimeRequest;
use App\Models\Log;
use App\Models\ShiftTime;
use Illuminate\Http\Request;

class ShiftTimeController extends Controller
{
    private $shiftTime;
    public function __construct()
    {
        $this->middleware('auth');
        view()->share('page', 'Shift Time');
        $this->shiftTime = resolve(ShiftTime::class);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shiftTimeList = $this->shiftTime->latest()->paginate(10);
        return view('admin.shift_time.index',compact('shiftTimeList'));
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
    public function store(CreateShiftTimeRequest $request)
    {
        $lastId =  ShiftTime::all()->last() ? ShiftTime::all()->last()->id + 1 : 1;
        $shiftCode = 'MP-'.strtoupper(substr($request->shift_name,0,3)).'-' . substr("00000{$lastId}", -6);

        $insert = $this->shiftTime->create([
            'shift_name' => $request->shift_name,
            'shift_start_time' => $request->shift_start_time,
            'shift_end_time' => $request->shift_end_time,
            'shift_code' => $shiftCode
        ]);
        if($insert){
            Log::create([
                'module' => 'Shift Time',
                'user_id' => auth()->user()->id,
                'message' => "Created Shift Time - " . $request->shift_name
            ]);
            return response()->json(['status'=>1,'message' => 'Shift Time created successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShiftTime $shiftTime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShiftTime $shiftTime)
    {
        return response()->json(['status'=>1,'data' => $shiftTime],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateShiftTimeRequest $request, ShiftTime $shiftTime)
    {
        $update = $shiftTime->update([
            'shift_name' => $request->shift_name,
            'shift_start_time' => $request->shift_start_time,
            'shift_end_time' => $request->shift_end_time
        ]);
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Shift Time',
                'description' => auth()->user()->name . " updated a Shift Time named '" . $request->shift_name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Shift Time updated successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShiftTime $shiftTime)
    {
        $delete = $shiftTime->delete();
        if($delete){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Shift Time',
                'description' => auth()->user()->name . " deleted a Shift Time named '" . $shiftTime->shift_name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Shift Time deleted successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }
}
