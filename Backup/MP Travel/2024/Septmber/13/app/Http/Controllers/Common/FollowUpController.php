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
use App\Http\Requests\CreateFollowUpRequest;
use App\Models\FollowUpChecklistItem;
use App\Models\FollowUpCommentFile;
use App\Models\FollowUpFile;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Mail;

class FollowUpController extends Controller
{
    protected $followUp;
    public function __construct()
    {
        $this->followUp = resolve(FollowUpEvent::class)->with('followUpMemberDetail', 'followUpMemberDetail.userDetail', 'subTaskData');
        view()->share('page', 'Follow Up');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees      = User::where('role_id', 2)->get();
        $leads          = Lead::whereHas('followUpDetail')->when(Auth()->user()->role_id !== "1", function ($query) {
            $query->where(function ($query) {
                $query->where('created_by', Auth()->user()->id)
                    ->orWhereHas('leadMemberDetail', function ($query) {
                        $query->where('user_id', Auth()->user()->id);
                    });
            });
        })->with('customerDetail', 'leadMemberDetail', 'leadMemberDetail.userDetail', 'followUpDetail')->latest()->get();
        return view('admin.follow_up.index', compact('leads', 'employees'));
    }

    public function followupAjaxList(Request $request)
    {
        $search = $request->search;
        $leads          = Lead::whereHas('followUpDetail')->when($search,function($query) use($search){
            $query->where('lead_id','like','%'.$search.'%')
            ->orWhereHas('customerDetail',function($query)use($search){
                $query->where('name','like','%'.$search.'%')
                    ->orWhere('customer_id','like','%'.$search.'%');
            })->orWhereHas('followUpDetail',function($query)use($search){
                $query->where('event_name','like','%'.$search.'%');
            });
        })->when(Auth()->user()->role_id !== "1", function ($query) {
            $query->where(function ($query) {
                $query->where('created_by', Auth()->user()->id)
                    ->orWhereHas('leadMemberDetail', function ($query) {
                        $query->where('user_id', Auth()->user()->id);
                    });
            });
        })->with('customerDetail', 'leadMemberDetail', 'leadMemberDetail.userDetail', 'followUpDetail')->latest()->get();
        $employees      = User::where('role_id', 2)->get();
        return view('admin.follow_up.ajax_list', compact('leads', 'employees'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;

        $followUpList = $this->followUp
            ->when(Auth()->user()->role_id == 2, function ($query) {
                $query->where('created_by', Auth()->user()->id);
            })
            ->when($search, function ($query, $search) {
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
                    $name = isset($follow->userDetail) ? $follow->userDetail->name : "-";
                    fputcsv($file, array($follow->event_name, $name, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $setting = Setting::first();
            $pdf = PDF::loadView('admin.pdf.followup', ['followUpList' => $followUpList, 'setting' => $setting]);
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
        return view('admin.follow_up.create', compact('followUpList', 'users', 'leads'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFollowUpRequest $request)
    {
        $followUpEvent = new FollowUpEvent();
        $followUpEvent->lead_id = $request->lead_id;
        $followUpEvent->type = $request->type;
        $followUpEvent->followup_type = $request->followup_type;
        $followUpEvent->created_by = Auth()->user()->id;
        if ($request->followup_type == 1) {
            $followUpEvent->event_name = $request->subject;
            $followUpEvent->event_start = $request->start_date;
            $followUpEvent->event_end = $request->end_date;
            $followUpEvent->remarks = $request->remarks;
        } elseif ($request->followup_type == 2) {
            $followUpEvent->event_name = $request->exist_subject;
            $followUpEvent->followup = $request->followup;
            $followUpEvent->event_start = $request->exist_start_date;
            $followUpEvent->event_end = $request->exist_end_date;
            $followUpEvent->remarks = $request->exist_remarks;
        }
        $followUpLastId =  FollowUpEvent::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $followUpCode = 'MP-FLD-' .$request->lead_id."-". substr("00000{$followUpLastId}", -6);
        $followUpEvent->follow_up_code = $followUpCode;
        if ($request->hasFile('reference_file')) {
            $file = $request->file('reference_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('follow_up', $newFilename, 'public');
            $followUpEvent->file = $pathLogo;
        }
        $insert = $followUpEvent->save();
        if ($insert) {
            if (isset($request->assigned_to)) {
                foreach ($request->assigned_to as $key => $assign) {
                    $followUpMember = new FollowUpMember();
                    $followUpMember->followup_id = $followUpEvent->id;
                    $followUpMember->user_id = $assign;
                    $followUpMember->save();
                    $userDetail = User::where('id', $assign)->first();
                    $email = $userDetail->email;
                    $data = [
                        'userDetail' => $userDetail,
                        'followUpEvent' => $followUpEvent,
                        'createdUser' => User::where('id', Auth()->user()->id)->first(),
                    ];
                    try {
                        Mail::send('admin.email.follow_up', $data, function ($message) use ($email) {
                            $message->to($email)
                                ->subject("Need to follow of pending follow up");
                        });
                    } catch (\Throwable $th) {
                        FacadesLog::info('Error sending follow-up email: ' . $th->getMessage());
                    }
                    if ($key == 0) {
                        $email = Auth()->user()->email;
                        try {
                            Mail::send('admin.email.follow_up', $data, function ($message) use ($email) {
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
        $followUp = FollowUpEvent::where('id', $id)->with('followUpFiles', 'subTaskData', 'subTaskData.createdUserDetail', 'leadDetail', 'commentDetail', 'commentDetail.userDetail', 'commentDetail.commentFileDetail', 'followUpMemberDetail', 'followUpMemberDetail.userDetail')->first();
        return view('admin.follow_up.show', compact('followUp'));
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
        $followUpMember = FollowUpMember::where('followup_id', $id)->pluck('user_id')->toArray();
        return view('admin.follow_up.edit', compact('followUpList', 'users', 'leads', 'followUpEvent', 'followUpMember'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateFollowUpRequest $request, string $id)
    {
        $followUpEvent = FollowUpEvent::find($id);
        $followUpEvent->lead_id = $request->lead_id;
        $followUpEvent->type = $request->type;
        $followUpEvent->followup_type = $request->followup_type;
        if ($request->followup_type == 1) {
            $followUpEvent->event_name = $request->subject;
            $followUpEvent->event_start = $request->start_date;
            $followUpEvent->event_end = $request->end_date;
            $followUpEvent->remarks = $request->remarks;
        } elseif ($request->followup_type == 2) {
            $followUpEvent->event_name = $request->exist_subject;
            $followUpEvent->followup = $request->followup;
            $followUpEvent->event_start = $request->exist_start_date;
            $followUpEvent->event_end = $request->exist_end_date;
            $followUpEvent->remarks = $request->exist_remarks;
        }

        if ($request->hasFile('reference_file')) {
            $file = $request->file('reference_file');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $pathLogo = $file->storeAs('follow_up', $newFilename, 'public');
            $followUpEvent->file = $pathLogo;
        }
        $insert = $followUpEvent->save();
        if ($insert) {
            if (isset($request->assigned_to)) {
                FollowUpMember::where('followup_id', $id)->delete();
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
        if (isset($data)) {
            $delete = $data->delete();
            if ($delete) {
                Log::create([
                    'user_id' => auth()->user()->id,
                    'module' => 'Follow Up',
                    'description' => auth()->user()->name . " Deleted  a Follow Up named '" . $data->event_name . "'"
                ]);
                return response()->json(['status' => 1, 'message' => "Follow up deleted successfully"], 200);
            }
            return response()->json(['status' => 1, 'message' => "Something went to Wrong."], 500);
        }
        return response()->json(['status' => 1, 'message' => "Something went to Wrong."], 500);
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

    public function followCommentStore(Request $request)
    {
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
                $user = User::where('id', $follow->user_id)->first();
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Follow Up',
                    'title' => "New Comment added is " . $request->comment_message,
                    'text' => "Follow Up Comment",
                    'url' => route('follow-up.show', $request->follow_id),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }

            // $pusher->trigger('notifications', 'new-notification', $offerData);
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Follow Up',
                'description' => auth()->user()->name . " added a comment in Follow Up",
            ]);
            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
    public function followup_checklist_item(Request $request)
    {
        $followUpCheck = new FollowUpChecklistItem();
        $followUpCheck->task_status  = $request->status;
        $followUpCheck->note         = $request->note;
        $followUpCheck->follow_up_id      = $request->follow_up_id;
        $followUpCheck->created_by   = Auth::user()->id;
        $followUpCheckLastId =  FollowUpChecklistItem::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $subFollowUpCode = 'MP-SUB-FLD-' . substr("00000{$followUpCheckLastId}", -6);
        $followUpCheck->sub_follow_up_code = $subFollowUpCode;
        $insert = $followUpCheck->save();

        try {
            $task = FollowUpEvent::where('id', $request->follow_up_id)->first();
            $TaskAssignee = FollowUpMember::where('followup_id', $request->follow_up_id);
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($TaskAssignee as $assignee) {
                $userSchema = User::where('id', $assignee->id)->first();
                $offerData = [
                    'user_id' => $assignee->id,
                    'type' => 'Follow Up Check List Item Added.',
                    'title' => "FOllow Up " . $task->event_name,
                    'text' => "Follow Up Check List Item",
                    'url' => route('follow-up.index'),
                ];
                Notification::send($userSchema, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
        } catch (\Exception $e) {
        }
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Add New Follow Up Sub Task '" . $followUpCheck->note . "'"
        ]);
        return redirect()->back();
    }
    public function add_followup_checklist_item(Request $request)
    {
        $followUpCheck = new FollowUpChecklistItem();
        $followUpCheck->task_status  = $request->status;
        $followUpCheck->note         = $request->note;
        $followUpCheck->follow_up_id      = $request->follow_up_id;
        $followUpCheck->created_by   = Auth::user()->id;
        $insert = $followUpCheck->save();

        try {
            $task = FollowUpEvent::where('id', $request->follow_up_id)->first();
            $TaskAssignee = FollowUpMember::where('followup_id', $request->follow_up_id);
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($TaskAssignee as $assignee) {
                $userSchema = User::where('id', $assignee->id)->first();
                $offerData = [
                    'user_id' => $assignee->id,
                    'type' => 'Follow Up Check List Item Added.',
                    'title' => "FOllow Up " . $task->event_name,
                    'text' => "Follow Up Check List Item",
                    'url' => route('follow-up.index'),
                ];
                Notification::send($userSchema, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
        } catch (\Exception $e) {
        }
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Add New Follow Up Sub Task '" . $followUpCheck->note . "'"
        ]);
        return response()->json(['data' => ''], 200);
    }
    public function add_followUp_data(Request $request)
    {
        $task = new FollowUpEvent();
        $task->event_name              = $request->subject;
        $task->lead_id          = $request->lead_id;
        $task->event_start           = $request->start_date;
        $task->event_end             = $request->due_date;
        $task->priority             = $request->priority;
        $task->remarks          = $request->description;
        $task->created_by           = Auth::user()->id;
        $task->status               = 1;
        $followUpLastId =  FollowUpEvent::all()->last() ? Lead::all()->last()->id + 1 : 1;
        $followUpCode = 'MP-FLD-' .$request->lead_id."-". substr("00000{$followUpLastId}", -6);
        $task->follow_up_code = $followUpCode;
        $insert = $task->save();
        if ($insert) {
            if ($request->file('filenames')) {
                foreach ($request->file('filenames') as $file) {
                    $returnAttachment = new FollowUpFile();
                    $returnAttachment->follow_up_id = $task->id;
                    $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $pathLogo1 = $file->storeAs('follow_up', $newFilename, 'public');
                    $returnAttachment->file = $pathLogo1;
                    $returnAttachment->file_type = $file->getClientOriginalExtension();;
                    $returnAttachment->file_name = $file->getClientOriginalName();;
                    $returnAttachment->save();
                }
            }
            if ($request->file('assignees')) {
                foreach ($request->file('assignees') as $assign) {
                    $task_file = new FollowUpMember();
                    $task_file->followup_id  = $assign;
                    $task_file->save();
                }
            }
            try {
                if (Auth::user()->role != 1) {
                    $userSchema = User::first();
                    $details = [
                        'name' => 'Task Created.',
                        'type'  => 'Task',
                        'body' => $req->subject . ' ' . 'Created.',
                        'url' => route('view.task', $task->id),
                    ];
                    Notification::send($userSchema, new SendNotification($details));
                    $config = config('services')['pusher'];
                    $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                        'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
                    ]);
                    $pusher->trigger('notifications', 'new-notification', $userSchema);
                }
            } catch (\Exception $e) {
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Follow Up',
                'description' => auth()->user()->name . " Add New Follow Up '" . $task->event_name . "'"
            ]);
            return redirect()->route('follow-up.index')->with('success', 'Follow Up Added Successfully.');
        } else {
            return redirect()->route('follow-up.index')->with('error', 'Something Went to wrong.');
        }
    }
    public function update_checklist_status(Request $req)
    {
        $item = FollowUpChecklistItem::where('id', $req->id)->first();
        $item->task_status = $req->val;
        if ($req->val == 1) {
            $item->checked_by = Auth::user()->id;
            $item->complete_date = Carbon::now();
            $task = FollowUpEvent::where('id', $item->task_id)->first();
            try {
                if (Auth::user()->role != 1) {
                    $userSchema = User::first();
                    $details = [
                        'name' => 'Sub Task Completed.',
                        'type'  => 'Sub Task',
                        'body' => $task->subject . ' ' . 'Sub Task Completed.',
                        'url' => route('view.task', $task->id),
                    ];
                    Notification::send($userSchema, new SendNotification($details));
                }
                $assignees = FollowUpMember::where('task_id', $task->id)->get();
                foreach ($assignees as $ass) {
                    $userSchema = User::where('id', $ass->user_id)->first();
                    $details = [
                        'name'  => 'Sub Task Completed File Uploaded.',
                        'type'  => 'Sub Task',
                        'body'  => $task->subject . ' ' . 'Sub Task Completed.',
                        'url'   => route('employee.view.task', $task->id),
                    ];
                    Notification::send($userSchema, new SendNotification($details));
                    $config = config('services')['pusher'];
                    $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                        'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
                    ]);
                    $pusher->trigger('notifications', 'new-notification', $userSchema);
                }
            } catch (\Exception $e) {
            }
        } else {
            $item->checked_by = NULL;
        }
        $item->save();
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Change Follow Upn Sub Task Status"
        ]);
        return response()->json(['success' => 1]);
    }
    public function uploadFollowUpFile(Request $req)
    {
        $followUpFile = new FollowUpFile();
        $followUpFile->follow_up_id = $req->task_id;
        if ($req->hasFile('document')) {
            $file = $req->file('document');
            $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $gstCertificatePath = $file->storeAs('follow_up', $newFilename, 'public');
            $followUpFile->file = $gstCertificatePath;
            $followUpFile->file_name = $file->getClientOriginalName();
            $followUpFile->file_type = $file->getClientOriginalExtension();
        }
        $followUpFile->save();
        $task = FollowUpEvent::where('id', $req->task_id)->first();
        try {
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            if (Auth::user()->role != 1) {
                $userSchema = User::first();
                $details = [
                    'name' => 'Task File Uploaded.',
                    'type'  => 'Task',
                    'body' => $task->subject . ' ' . 'File Uploaded.',
                    'url' => route('follow-up.show', $task->id),
                ];
                Notification::send($userSchema, new SendNotification($details));
                $pusher->trigger('notifications', 'new-notification', $userSchema);
            }
            $assignees = FollowUpMember::where('task_id', $task->id)->get();
            foreach ($assignees as $ass) {
                $userSchema = User::where('id', $assignees->user_id)->first();
                $details = [
                    'name'  => 'Task File Uploaded.',
                    'type'  => 'Task',
                    'body'  => $task->subject . ' ' . 'File Uploaded.',
                    'url'   => route('follow-up.show', $task->id),
                ];
                Notification::send($userSchema, new SendNotification($details));
                $pusher->trigger('notifications', 'new-notification', $userSchema);
            }
        } catch (\Exception $e) {
        }
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Upload new Follow Up file and add new Comment." . $task->event_name,
        ]);
        return redirect()->back();
    }
    public function followUpComment(Request $req)
    {
        $cmt = $req->comment;
        $code = explode('@', $cmt);
        $followUpData = FollowUpEvent::where('id', $req->task_id)->first();

        $comment = new FollowUpComment();
        $comment->type           = 'lead';
        $comment->follow_id           = $followUpData->id;
        $comment->lead_id           = $followUpData->lead_id;
        $comment->comment           = $req->comment;
        $comment->user_id        = Auth::user()->id;
        $comments = $comment->save();

        if ($req->has('file')) {
            $imageUpload = new FollowUpCommentFile();
            $imageUpload->follow_up_id       = $req->task_id;
            $imageUpload->comment_id    = $comment->id;

            if ($req->hasFile('file')) {
                $file = $req->file('file');
                $newFilename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $gstCertificatePath = $file->storeAs('follow_up', $newFilename, 'public');
                $imageUpload->file     = $gstCertificatePath;
                $imageUpload->file_name          = $file->getClientOriginalName();
                $imageUpload->file_type     = $file->getClientOriginalExtension();
            }
            $imageUpload->save();
        }
        try {
            $userSchema = User::first();
            $task = FollowUpEvent::where('id', $req->task_id)->first();
            $details = [
                'name' => 'Task Commented.',
                'type'  => 'Task',
                'body' => $task->subject . ' ' . 'Commented.',
                'url' => route('follow-up.show', $task->id),
            ];
            Notification::send($userSchema, new SendNotification($details));
        } catch (\Exception $e) {
        }

        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Add new Comment." . $task->event_name,
        ]);
        return response()->json(['success' => 1], 200);
    }
    public function update_due_date(Request $req)
    {
        $item = FollowUpEvent::where('id', $req->id)->first();
        $item->event_end = $req->due_date;
        $item->save();
        try {
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            if (Auth::user()->role != 1) {
                //Send Notification to admin
                $userSchema = User::first();
                $details = [
                    'name' => 'Task Due Date Updated.',
                    'type'  => 'Task',
                    'body' => $item->subject . ' ' . 'Due Date Updated.',
                    'url' => route('view.task', $item->id),
                ];
                Notification::send($userSchema, new SendNotification($details));
                $pusher->trigger('notifications', 'new-notification', $userSchema);
            }
            $assignees = FollowUpMember::where('task_id', $item->id)->get();
            foreach ($assignees as $ass) {
                $employee = User::where('id', $ass->user_id)->first();
                $userSchema = User::where('id', $employee->user_id)->first();
                $details = [
                    'name'  => 'Task Due Date Updated.',
                    'type'  => 'Task',
                    'body'  => $item->subject . ' ' . 'Due Date Updated.',
                    'url'   => route('employee.view.task', $item->id),
                ];
                Notification::send($userSchema, new SendNotification($details));
                $pusher->trigger('notifications', 'new-notification', $userSchema);
            }
        } catch (\Exception $e) {
        }

        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Change Due Date OF ." . $item->event_name,
        ]);
        return redirect()->back();
        // return response()->json(['success'=>1]);
    }
    public function update_checklist_note(Request $request)
    {
        $item = FollowUpChecklistItem::where('id', $request->id)->first();
        $item->note = $request->val;
        $item->save();
        return redirect()->back();
    }
    function deleteChecklistItem($id)
    {
        $item = FollowUpChecklistItem::where('id', $id);
        $item->delete();
        return response()->json(['data' => "", 'message' => 'Remove Sub Follow Up Successfully.'], 200);
    }
    public function change_status(Request $request)
    {
        $item = FollowUpEvent::where('id', $request->id)->first();
        $item->event_status = $request->status;
        $item->save();
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Change Status OF ." . $item->event_name,
        ]);
        return response()->json(['message'=> 'followup Status Updated'],200);
    }
    public function change_priority(Request $request)
    {
        $item = FollowUpEvent::where('id', $request->id)->first();
        $item->priority = $request->priority;
        $item->save();
        Log::create([
            'user_id' => auth()->user()->id,
            'module' => 'Follow Up',
            'description' => auth()->user()->name . " Change Priority OF ." . $item->event_name,
        ]);

        return response()->json(['message'=>'Priority Changed Successfully.'], 200);
    }
    public function taskEmployee(Request $request)
    {
        $id = $request->id;
        $assignees  = FollowUpMember::where('task_id', $id)->get();
        $i = 0;
        $userData = [];
        foreach ($assignees as $as) {
            $i++;
            $emp  = User::where('id', $as->user_id)->first();
            $ass['id'] = $emp->id;
            $ass['name'] = $emp->employee_name;
            $ass['avatar'] = 'Avatar1.gif';
            $ass['href'] = '';
            array_push($userData, $ass);
        }
        return response()->json(['emp' => $userData]);
    }
}
