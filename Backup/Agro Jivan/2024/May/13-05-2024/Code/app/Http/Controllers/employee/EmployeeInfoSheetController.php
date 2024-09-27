<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInfoSheetsRequest;
use App\Interfaces\InfoSheetRepositoryInterface;
use App\Models\InfoSheet;
use Illuminate\Http\Request;
use PDF;

class EmployeeInfoSheetController extends Controller
{
    protected $infoSheetRepository = "";
    public function __construct(InfoSheetRepositoryInterface $infoSheetRepository)
    {
        $this->infoSheetRepository = $infoSheetRepository;
    }
    public function index(){
        $page = "Info Sheet List";
        return view('employee.info_sheet.index',compact('page'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $date = $request->date;
        $infoSheetList = $this->infoSheetRepository->getAllData($search,$date,"paginate","admin");
        return view('employee.info_sheet.ajax_list',compact('infoSheetList'));
    }

    public function exportCSV(Request $request){
        $infoList = $this->infoSheetRepository->getAllData($request->search,$request->date,"export","");
        if($request->format == "excel" || $request->format == "csv"){
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Info Sheet.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Title', 'Description', 'Info Sheet', 'Status','Created At');
            $callback = function () use ($infoList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($infoList as $info) {
                    $document = url('/')."/public/assets/media/".$info->info_sheet;
                    $date = "";
                    if (isset($info->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($info->created_at);
                    }
                    $text = 'Inactive';
                    if ($info->status == 1){
                        $text = 'Active';
                    }
                    fputcsv($file, array($info->title, $info->description, $document, $text,$date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if($request->format == "pdf"){
            $pdf = PDF::loadView('admin.pdf.info_sheet', ['infoSheetList' => $infoList]);
            return $pdf->download('Info Sheet.pdf');
        }
    }

    public function create(){
        return view('employee.info_sheet.create');
    }
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
                //send notification to Admin
                // $userSchema = User::first();
                // $details = [
                //     'name'  => 'Order Created.',
                //     'type'  => 'Order',
                //     'body'  => $order_id.' '.'created by '. Auth::user()->name,
                //     'url'   => route('admin.orders'),
                // ];
                // Notification::send($userSchema, new Notifications($details));
            }catch(\Exception $e){
            }

            return redirect('employee/info-sheet')->with('success', 'Info Sheet Created Successfully.');
        }
        return redirect('employee/info-sheet.create')->with('error', 'Something Went To Wrong.');
    }

    public function edit(InfoSheet $infoSheets,string $id)
    {
        $info = $this->infoSheetRepository->getDetailById($id);
        return view('employee.info_sheet.edit',compact('info'));
    }

    public function update(Request $request,$id){
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
            return redirect('employee/employee-info-sheet')->with('success', 'Info Sheet Updated Successfully.');
        }
        return redirect('employee/employee-info-sheet/' . $id . '/edit')->with('error', 'Something Went To Wrong.');
    }

    public function destroy(string $id){
        $info = $this->infoSheetRepository->getDetailById($id);
        $delete = $this->infoSheetRepository->delete($id);
        if ($delete) {
            $log = 'Info Sheet (' . ucfirst($info->title) . ') Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Info Sheet', $log);
            return response()->json(['data' => '', 'message' => 'Info Sheet Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
