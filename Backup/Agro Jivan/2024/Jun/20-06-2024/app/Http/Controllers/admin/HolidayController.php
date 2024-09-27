<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateHolidayRequest;
use App\Interfaces\HolidayRepositoryInterface;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    protected $holidayRepository = "";
    public function __construct(HolidayRepositoryInterface $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Holiday List";
        return view('admin.hr_manager.holiday.index',compact('page'));
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
        $data = [
            'holiday_date' => $request->holiday_date,
            'holiday_name' => $request->holiday_name,
        ];
        $insert = $this->holidayRepository->store($data);
        if ($insert) {
            $log = 'Holiday (date=' . $request->holiday_date . ' and name='.ucfirst($request->holiday_name).') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Holiday ', $log);
            return response()->json(['data' => $insert, 'message' => 'Holiday Created Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
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
        $holiday = $this->holidayRepository->getDetailById($id);
        return response()->json(['data' => $holiday, 'message' => '', 'status' => 1], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateHolidayRequest $request, string $id)
    {
        $update = [
            'holiday_date' => $request->holiday_date,
            'holiday_name' => $request->holiday_name,
            'status' => $request->status == "on" ? '1' : '0',
        ];
        $where['id'] =$id;
        $update = $this->holidayRepository->update($update,$where);
        if ($update) {
            $log = 'Holiday (date=' . $request->holiday_date . ' and name='.ucfirst($request->holiday_name).') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Holiday ', $log);
            return response()->json(['data' => $update, 'message' => 'Holiday Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = $this->holidayRepository->getDetailById($id);
        $delete = $this->holidayRepository->delete($id);
        if($delete){
            $log = 'Holiday (date=' . $holiday->holiday_date . ' and name=' .ucfirst($holiday->holiday_name). ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Holiday', $log);
            return response()->json(['data'=>$delete,'message'=> 'Holiday Deleted Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);
    }

    public function ajaxList(Request $request)
    {
        $holidayList = $this->holidayRepository->getAllData($request->search);
        return view('admin.hr_manager.holiday.ajax_list', compact('holidayList'));
    }
}
