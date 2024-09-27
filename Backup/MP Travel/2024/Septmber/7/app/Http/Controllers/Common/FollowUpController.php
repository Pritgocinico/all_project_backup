<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\FollowUpComment;
use App\Models\FollowUpEvent;
use App\Models\FollowUpMember;
use App\Models\Lead;
use App\Models\Log;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Notification;
use App\Helpers\UtilityHelper;
use App\Models\FollowUpChecklistItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;

class FollowUpController extends Controller
{
    protected $followUp;
    public function __construct()
    {
        $this->followUp = resolve(FollowUpEvent::class)->with('followUpMemberDetail','followUpMemberDetail.userDetail','subTaskData');
        view()->share('page', 'Follow Up');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees      = User::where('role_id', 2)->get();
        $leads          = Lead::with('leadMemberDetail', 'leadMemberDetail.userDetail','followUpDetail')->get();
        return view('admin.follow_up.index', compact('leads', 'employees'));
    }

    public function followupAjaxList(Request $request){
        $search = $request->search;
        $followUpList = $this->followUp
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('event_name', 'like', '%' . $search . '%')
                      ->orWhere('created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->get();
        $employees      = User::where('role_id', 2)->get();
        $leads          = Lead::with('leadMemberDetail', 'leadMemberDetail.userDetail')->get();
        return view('admin.follow_up.ajax_list',compact('followUpList', 'leads', 'employees'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $followUpList = $this->followUp
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('created_by',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('event_name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%')
                    ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=followup Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Event Name', 'Created At');
            $callback = function () use ($followUpList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($followUpList as $follow) {
                    $date = "";
                    if (isset($follow->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($follow->created_at);
                    }
                    $name = isset($follow->userDetail) ? $follow->userDetail->name :"-";
                    fputcsv($file, array($follow->event_name,$name, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.followup', ['followUpList' => $followUpList,'setting' =>$setting]);
            return $pdf->download('FollowUp.pdf');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $followUpList = $this->followUp->get();
        $users      = User::where('role_id', 2)->get();
        $leads          = Lead::with('leadMemberDetail', 'leadMemberDetail.userDetail')->get();
        return view('admin.follow_up.create',compact('followUpList','users','leads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $followUpEvent = new FollowUpEvent();
        $followUpEvent->lead_id = $request->lead_id;
        $followUpEvent->type = $request->type;
        $followUpEvent->followup_type = $request->followup_type;
        $followUpEvent->created_by = Auth()->user()->id;
        if($request->followup_type == 1){
            $followUpEvent->event_name = $request->subject;
            $followUpEvent->event_start = $request->start_date;
            $followUpEvent->event_end = $request->end_date;
            $followUpEvent->remarks = $request->remarks;
        }elseif($request->followup_type == 2){
            $followUpEvent->event_name = $request->exist_subject;
            $followUpEvent->followup = $request->followup;
            $followUpEvent->event_start = $request->exist_start_date;
            $followUpEvent->event_end = $request->exist_end_date;
            $followUpEvent->remarks = $request->exist_remarks;
        }

        if($request->hasFile('reference_file')){
            $file = $request->file('reference_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('follow_up', $newFilename, 'public');
            $followUpEvent->file = $pathLogo;
        }
        $insert = $followUpEvent->save();
        if($insert){
            if(isset($request->assigned_to)){
                foreach ($request->assigned_to as $key => $assign) {
                    $followUpMember = new FollowUpMember();
                    $followUpMember->followup_id = $followUpEvent->id;
                    $followUpMember->user_id = $assign;
                    $followUpMember->save();
                    $userDetail = User::where('id',$assign)->first();
                    $email = $userDetail->email;
                    $data = [
                        'userDetail' => $userDetail,
                        'followUpEvent' => $followUpEvent,
                        'createdUser' => User::where('id',Auth()->user()->id)->first(),
                    ];
                    try {
                        Mail::send('admin.email.follow_up',$data, function ($message) use ($email) {
                            $message->to($email)
                                    ->subject("Need to follow of pending follow up");
                        });
                    } catch (\Throwable $th) {
                        FacadesLog::info('Error sending follow-up email: ' . $th->getMessage());
                    }
                    if($key == 0){
                        $email = Auth()->user()->email;
                        try {
                            Mail::send('admin.email.follow_up',$data, function ($message) use ($email) {
                                $message->to($email)
                                        ->subject("Need to follow of pending follow up");
                            });
                        } catch (\Throwable $th) {
                            FacadesLog::info('Error sending follow-up email: ' . $th->getMessage());
                        }
                    }
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Follow Up',
                'description' => auth()->user()->name . " Created a Follow Up named '" . $followUpEvent->event_name . "'"
            ]);
            return redirect()->route('follow-up.index')->with('success', 'Follow up created successfully');
        }
        return redirect()->back()->with('error', 'Failed to create follow up');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = FollowUpEvent::where('id',$id)->with('leadDetail','commentDetail')->first();
        return view('admin.follow_up.show',compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $followUpEvent = FollowUpEvent::find($id);
        $followUpList = $this->followUp->get();
        $users      = User::where('role_id', 2)->get();
        $leads          = Lead::with('leadMemberDetail', 'leadMemberDetail.userDetail')->get();
        $followUpMember = FollowUpMember::where('followup_id',$id)->pluck('user_id')->toArray();
        return view('admin.follow_up.edit',compact('followUpList','users','leads','followUpEvent','followUpMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $followUpEvent = FollowUpEvent::find($id);
        $followUpEvent->lead_id = $request->lead_id;
        $followUpEvent->type = $request->type;
        $followUpEvent->followup_type = $request->followup_type;
        if($request->followup_type == 1){
            $followUpEvent->event_name = $request->subject;
            $followUpEvent->event_start = $request->start_date;
            $followUpEvent->event_end = $request->end_date;
            $followUpEvent->remarks = $request->remarks;
        }elseif($request->followup_type == 2){
            $followUpEvent->event_name = $request->exist_subject;
            $followUpEvent->followup = $request->followup;
            $followUpEvent->event_start = $request->exist_start_date;
            $followUpEvent->event_end = $request->exist_end_date;
            $followUpEvent->remarks = $request->exist_remarks;
        }

        if($request->hasFile('reference_file')){
            $file = $request->file('reference_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('follow_up', $newFilename, 'public');
            $followUpEvent->file = $pathLogo;
        }
        $insert = $followUpEvent->save();
        if($insert){
            if(isset($request->assigned_to)){
                FollowUpMember::where('followup_id',$id)->delete();
                foreach ($request->assigned_to as $key => $assign) {
                    $followUpMember = new FollowUpMember();
                    $followUpMember->followup_id = $id;
                    $followUpMember->user_id = $assign;
                    $followUpMember->save();
                }
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Follow Up',
                'description' => auth()->user()->name . " Updated a Follow Up named '" . $followUpEvent->event_name . "'"
            ]);
            return redirect()->route('follow-up.index')->with('success', 'Follow up Updated successfully');
        }
        return redirect()->back()->with('error', 'Failed to create follow up');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = FollowUpEvent::find($id);
        if(isset($data)){
            $delete = $data->delete();
            if($delete){
                Log::create([
                    'user_id' => auth()->user()->id,
                    'module' => 'Follow Up',
                    'description' => auth()->user()->name . " Deleted  a Follow Up named '" . $data->event_name . "'"
                ]);
                return response()->json(['status' =>1 ,'message' => "Follow up deleted successfully"],200);
            }
            return response()->json(['status' =>1 ,'message' => "Something went to Wrong."],500);
        }
        return response()->json(['status' =>1 ,'message' => "Something went to Wrong."],500);
    }

    public function followUpEventAjax(Request $request)
    {
        $event = FollowupEvent::whereDate('event_start', '>=', $request->start)
        ->whereDate('event_end',   '<=', $request->end)
        ->get();
        $data = [];
        foreach ($event as $row) {
            if ($row->followup_type == 1) {
                $color = '#5d9b3e';
            } else {
                $color = '#c31d1d';
            }
            if ($row->type == 1) {
                $data[] = [
                    'id'                => $row->id,
                    'title'             => $row->event_name,
                    'start'             => date(DATE_ISO8601, strtotime($row->event_start)),
                    'end'               => date(DATE_ISO8601, strtotime($row->event_end)),
                    'backgroundColor'   => '#111',
                    'textColor'         => $color,
                ];
            } else {
                $data[] = [
                    'id'    => $row->id,
                    'title' => $row->event_name,
                    'start' => date(DATE_ISO8601, strtotime($row->event_start)),
                    'end' => date(DATE_ISO8601, strtotime($row->event_end)),
                    'backgroundColor' => '#d6acf7',
                    'textColor'       => $color,
                ];
            }
        }
        return response()->json($data);
    }

    public function followCommentStore(Request $request){
        $data = [
            'type' => 'lead',
            'follow_id' => $request->follow_id,
            'lead_id' => $request->lead_id,
            'comment' => $request->comment_message,
            'message_type' => 'text',
            'user_id' => Auth()->user()->id,
        ];
        if ($request->hasfile('message_file')) {
            $file = $request->file('message_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $messageFilePath = $file->storeAs('message_file', $newFilename, 'public');
            $data['message_file'] = $messageFilePath;
            $data['message_type'] = 'file';
        }
        if ($request->comment_message !== null && $request->message_file !== null) {
            $data['message_type'] = "text_file";
        }
        $ticket = FollowUpComment::create($data);
        if ($ticket) {
            $followList = FollowUpMember::where('followup_id', $request->follow_id)->get();
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($followList as $key => $follow) {
                $user = User::where('id',$follow->user_id)->first();
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Follow Up',
                    'title' => "New Comment added is ".$request->comment_message,
                    'text' => "Follow Up Comment",
                    'url' => route('follow-up.show',$request->follow_id),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            
            $pusher->trigger('notifications', 'new-notification', $offerData);
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Follow Up',
                'description' => auth()->user()->name . " added a comment in Follow Up",
            ]);
            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
    public function followup_checklist_item(Request $request){
        $item = new FollowUpChecklistItem();
        $item->task_status  = $request->status;
        $item->note         = $request->note;
        $item->follow_up_id      = $request->follow_up_id;
        $item->created_by   = Auth::user()->id;
        $item->save();
        try{
            //send notification
            $task = FollowUpEvent::where('id',$request->task_id)->first();
            $TaskAssignee = FollowUpMember::where('followup_id',$request->task_id);
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach($TaskAssignee as $assignee){
                $userSchema = User::where('id',$assignee->id)->first();
                $offerData = [
                    'user_id' => $assignee->id,
                    'type' => 'Follow Up Check List Item Added.',
                    'title' => "FOllow Up ".$task->event_name,
                    'text' => "Follow Up Check List Item",
                    'url' => route('follow-up.index'),
                ];
                Notification::send($userSchema, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
        }catch(\Exception $e){
                
        }
        return redirect()->back();
    }
}
