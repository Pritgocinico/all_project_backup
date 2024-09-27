<?php

namespace App\Http\Controllers\admin;

use App\Helpers\PermissionHelper;
use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Notification;
use App\Models\InfoSheet;
use App\Http\Requests\StoreInfoSheetsRequest;
use App\Interfaces\InfoSheetRepositoryInterface;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Notifications\OffersNotification;
use Pusher\Pusher;

class InfoSheetsController extends Controller
{
    protected $infoSheetRepository,$employeeRepository = "";
    public function __construct(EmployeeRepositoryInterface $employeeRepository, InfoSheetRepositoryInterface $infoSheetRepository)
    {
        $this->infoSheetRepository = $infoSheetRepository;
        $this->employeeRepository = $employeeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Info Sheet";
        return view('admin.hr_manager.info_sheet.index',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = "Create Info Sheet";
        return view('admin.hr_manager.info_sheet.create',compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInfoSheetsRequest $request)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status == "on" ? '1' : '0',
            'created_by' => Auth()->user()->id,
        ];
        if ($request->hasfile('info_sheet')) {
            $file = $request->file('info_sheet');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/info_sheet/', $filename);
            $data['info_sheet'] = 'info_sheet/' . $filename;
        }
        $insert = $this->infoSheetRepository->store($data);
        if ($insert) {
            $log = 'Info Sheet (' . ucfirst($request->title) . ') Created by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);    
            try{
                $employeeList = $this->employeeRepository->getAllUsersIgnored(Auth()->user()->id);
                foreach ($employeeList as $emp) {
                    if(PermissionHelper::checkPermissionById($id,'info-sheet-list')){
                        $offerData = [ 
                            'user_id' => $emp->id,
                            'type' => 'InfoSheet',
                            'title' => 'New Info Sheet Added.',
                            'text' => $request->title,
                            'url' => route('info-sheet.edit', $insert),
                        ];
                        Notification::send($emp, new OffersNotification($offerData));
                        app('pusher')->trigger('notifications', 'new-notification', $offerData);
                    }
                }    
            }catch(\Exception $e){
            }
            
            return redirect('admin/info-sheet')->with('success', 'Info Sheet Created Successfully.');
        }
        return redirect('admin/info-sheet.create')->with('error', 'Something Went To Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(InfoSheet $infoSheets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InfoSheet $infoSheets,string $id)
    {
        $page ="Edit Info Sheet";
        $info = $this->infoSheetRepository->getDetailById($id);
        return view('admin.hr_manager.info_sheet.edit',compact('info','page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreInfoSheetsRequest $request,string $id)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status == "on" ? '1' : '0',
        ];
        if ($request->hasfile('info_sheet')) {
            $file = $request->file('info_sheet');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/info_sheet/', $filename);
            $data['info_sheet'] = 'info_sheet/' . $filename;
        }
        $where['id'] = $id;
        $update = $this->infoSheetRepository->update($data,$where);
        if ($update) {
            $log = 'Info Sheet (' . ucfirst($request->title) . ') Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            return redirect('admin/info-sheet')->with('success', 'Info Sheet Updated Successfully.');
        }
        return redirect('admin/info-sheet/' . $id . '/edit')->with('error', 'Something Went To Wrong.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $info = $this->infoSheetRepository->getDetailById($id);
        $delete = $this->infoSheetRepository->delete($id);
        if ($delete) {
            $log = 'Info Sheet (' . ucfirst($info->title) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            return response()->json(['data' => '', 'message' => 'Info Sheet Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $infoSheetList = $this->infoSheetRepository->getAllData($search,"","paginate","admin");
        return view('admin.hr_manager.info_sheet.ajax_list',compact('infoSheetList'));
    }
}
