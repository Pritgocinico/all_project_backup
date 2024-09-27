<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLeaveRequest;
use App\Models\Leave;
use App\Models\Log;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Notification;
use Dompdf\Dompdf;

class LeaveController extends Controller
{
    private $leave;
    public function __construct()
    {
        $page = "Leave";
        $this->leave = resolve(Leave::class)->with('userDetail');
        view()->share('page', $page);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveList = $this->leave->when(Auth()->user()->role_id !== 1, function ($query) {
            $query->where('user_id', Auth()->user()->id);
        })->paginate(10);
        return view('admin.leave.index', compact('leaveList'));
    }

    public function leaveAjaxList(Request $request){
        $search = $request->search;
        $leaveList = $this->leave
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('user_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('leaves.leave_type', 'like', '%' . $search . '%')
                      ->orWhere('leaves.leave_from', 'like', '%' . $search . '%')
                      ->orWhere('leaves.leave_to', 'like', '%' . $search . '%')
                      ->orWhere('leaves.reason', 'like', '%' . $search . '%')
                      ->orWhere('leaves.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->paginate(10);
        return view('admin.leave.ajax_list',compact('leaveList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $leaveList = $this->leave
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('user_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('leaves.leave_type', 'like', '%' . $search . '%')
                      ->orWhere('leaves.leave_from', 'like', '%' . $search . '%')
                      ->orWhere('leaves.leave_to', 'like', '%' . $search . '%')
                      ->orWhere('leaves.reason', 'like', '%' . $search . '%')
                      ->orWhere('leaves.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=leave Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Employee Name', 'Leave Type', 'Leave From', 'Leave To', 'Reason', 'Leave Status', 'Leave Feature', 'Total Leave day', 'Created At');
            $callback = function () use ($leaveList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($leaveList as $leave) {
                    $date = "";
                    if (isset($leave->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($leave->created_at);
                    }
                    $employee = ucfirst($leave->userDetail->name);
                    $leave_from = \Carbon\Carbon::parse($leave->leave_from)->format('d-m-Y');
                    $leave_to = \Carbon\Carbon::parse($leave->leave_to)->format('d-m-Y');
                    if($leave->leave_feature == 0){
                        $leave_feature = "Half Day";
                    }else {
                        $leave_feature = "Full Day";
                    }
                    if($leave->leave_status == 1){
                        $leave_status = 'Approved';
                    }elseif ($leave->leave_status == 2) {
                        $leave_status = 'Rejected';
                    }else{
                        $leave_status = 'Pending';
                    }
                    fputcsv($file, array($employee, $leave->leave_type, $leave_from, $leave_to, $leave->reason, $leave_status, $leave_feature, $leave->total_leave_day, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.leave', ['leaveList' => $leaveList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Leave.pdf'
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leave.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLeaveRequest $request)
    {
        $totalLeaveDay = UtilityHelper::getDiffBetweenDateLeave($request->leave_feature, $request->leave_from, $request->leave_to);
        $data = [
            'user_id' => auth()->user()->id,
            'leave_type' => $request->leave_type,
            'leave_from' => $request->leave_from,
            'leave_to' => $request->leave_to,
            'reason' => $request->leave_reason,
            'leave_feature' => $request->leave_feature,
            'total_leave_day' => $totalLeaveDay,
        ];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('leave_attachment', $fileName, 'public');
            $data['attachment'] = $filePath;
        }
        $insert = $this->leave->create($data);
        if ($insert) {
            $userList = User::where('role_id', 1)->get();
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Leave',
                    'title' => 'New Leave Added.',
                    'text' => $request->leave_reason,
                    'url' => route('leave.index'),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Leave',
                'description' => auth()->user()->name . " created a leave"
            ]);
            return redirect()->route('leave.index')->with('success', 'Leave has been created successfully.');
        }
        return redirect()->route('leave.create')->with('error', 'Something Went to Wrong.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        return view('admin.leave.create', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateLeaveRequest $request, Leave $leave)
    {
        $totalLeaveDay = UtilityHelper::getDiffBetweenDateLeave($request->leave_feature, $request->leave_from, $request->leave_to);
        $data = [
            'user_id' => auth()->user()->id,
            'leave_type' => $request->leave_type,
            'leave_from' => $request->leave_from,
            'leave_to' => $request->leave_to,
            'reason' => $request->leave_reason,
            'leave_feature' => $request->leave_feature,
            'total_leave_day' => $totalLeaveDay,
        ];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('leave_attachment', $fileName, 'public');
            $data['attachment'] = $filePath;
        }
        $update = $leave->update($data);
        if ($update) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Leave',
                'description' => auth()->user()->name . " updated a leave"
            ]);
            return redirect()->route('leave.index')->with('success', 'Leave has been updated successfully.');
        }
        return redirect()->route('leave.edit', $leave)->with('success', 'Leave has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $delete = $leave->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Leave',
                'description' => auth()->user()->name . " deleted a leave",
            ]);
            return response()->json(['status' => 1, 'message' => 'Leave deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }

    public function changeLeaveStatus(Request $request)
    {
        $text = "Approve";
        $type = "Leave Approved.";
        $title = "Leave Approved.";
        if ($request->status == 2) {
            $type = "Leave Rejected.";
            $title =  $request->reject_reason;
            $text = "Reject";
        }
        $leave = Leave::find($request->id);
        $leave->leave_status = $request->status;
        $leave->reject_reason = $request->reject_reason;
        $update = $leave->save();
        if ($update) {
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $offerData = [
                'user_id' => $leave->user_id,
                'type' => 'Leave',
                'title' => $type,
                'text' => $title,
                'url' => route('leave.index'),
            ];
            Notification::send($leave, new SendNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Leave',
                'description' => auth()->user()->name . " changed a leave " . $text,
            ]);
            return response()->json(['status' => 1, 'message' => 'Leave ' . $text . ' changed successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }
}
