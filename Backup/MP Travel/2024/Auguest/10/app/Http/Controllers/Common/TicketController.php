<?php

namespace App\Http\Controllers\Common;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\Log;
use App\Models\TicketComment;
use App\Models\User;
use App\Notifications\SendNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Notification;
use Dompdf\Dompdf;

class TicketController extends Controller
{
    private $ticket;
    public function __construct()
    {
        $this->ticket = resolve(Ticket::class)->with('userDetail', 'departmentDetail', 'ticketCommentDetail', 'ticketCommentDetail.userDetail');
        $this->middleware('auth');
        view()->share('page', 'Ticket');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departmentList = Department::latest()->get();
        $ticketList = $this->ticket->when(Auth()->user()->role_id !== 1, function ($query) {
            $query->where('emp_id', Auth()->user()->id);
        })->latest()->paginate(10);
        return view('admin.ticket.index', compact('ticketList', 'departmentList'));
    }

    public function ticketAjaxList(Request $request){
        $search = $request->search;
        $ticketList = $this->ticket
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('emp_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('tickets.ticket_id', 'like', '%' . $search . '%')
                      ->orWhere('tickets.ticket_type', 'like', '%' . $search . '%')
                      ->orWhere('tickets.subject', 'like', '%' . $search . '%')
                      ->orWhere('tickets.description', 'like', '%' . $search . '%')
                      ->orWhere('tickets.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('departmentDetail', function ($query) use ($search) {
                        $query->where('departments.name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->paginate(10);
        return view('admin.ticket.ajax_list',compact('ticketList'));
    }

    public function exportFile(Request $request)
    {
        $search = $request->search;
        
        $ticketList = $this->ticket
        ->when(Auth()->user()->role_id == 2,function($query){
            $query->where('emp_id',Auth()->user()->id);
        })
        ->when($search, function($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('tickets.ticket_id', 'like', '%' . $search . '%')
                      ->orWhere('tickets.ticket_type', 'like', '%' . $search . '%')
                      ->orWhere('tickets.subject', 'like', '%' . $search . '%')
                      ->orWhere('tickets.description', 'like', '%' . $search . '%')
                      ->orWhere('tickets.created_at', 'like', '%' . $search . '%')
                      ->orWhereHas('departmentDetail', function ($query) use ($search) {
                        $query->where('departments.name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('userDetail', function ($query) use ($search) {
                        $query->where('users.name', 'like', '%' . $search . '%');
                    });
            });
        })->latest()
        ->get();

        if ($request->format == "csv" || $request->format == "excel") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=ticket Export.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );

            $columns = array('Ticket ID', 'User Name', 'Department Name', 'Ticket Type', 'Subject', 'Description', 'Status', 'Created At');
            $callback = function () use ($ticketList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($ticketList as $ticket) {
                    $date = "";
                    if (isset($ticket->created_at)) {
                        $date = UtilityHelper::convertDmyAMPMFormat($ticket->created_at);
                    }
                    if ($ticket->status == 0) {
                        $status = 'Close';
                    }else{
                        $status = 'Open';
                    }
                    fputcsv($file, array($ticket->ticket_id, $ticket->userDetail->name, $ticket->departmentDetail->name, $ticket->ticket_type, $ticket->subject, $ticket->description, $status, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $mainLogoUrl = asset('storage/logo/UQcj8rCZqMmTVJ89a4zx0Vzz7UZ5AtbbWazxf2cd.png');
            $html = view('admin.pdf.ticket', ['ticketList' => $ticketList, 'mainLogoUrl' => $mainLogoUrl])->render();

            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            return response()->streamDownload(
                function () use ($dompdf) {
                    echo $dompdf->output();
                },
                'Ticket.pdf'
            );
        }
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
    public function store(CreateTicketRequest $request)
    {
        $lastId =  Ticket::all()->last() ? Ticket::all()->last()->id + 1 : 1;
        $ticketId = 'MP-TICKET-' . substr("00000{$lastId}", -6);
        $data = [
            'ticket_id' => $ticketId,
            'department_id' => $request->department,
            'ticket_type' => $request->ticket_type,
            'subject' => $request->subject,
            'description' => $request->description,
            'emp_id' => Auth()->user()->id,
        ];
        $ticket = $this->ticket->create($data);
        if ($ticket) {
            $userList = User::where('role_id', 1)->get();
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Ticket',
                    'title' => "Ticket is Created For subject is ". $request->subject,
                    'text' => "Ticket Create",
                    'url' => route('ticket.index'),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " created a ticket named '" . $request->subject . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Ticket created successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to wrong.'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('admin.ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return response()->json(['status' => 1, 'data' => $ticket], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTicketRequest $request, Ticket $ticket)
    {
        $update = $ticket->update([
            'department_id' => $request->department,
            'ticket_type' => $request->ticket_type,
            'subject' => $request->subject,
            'description' => $request->description,
            'emp_id' => Auth()->user()->id,
            'status' => $request->status == "on" ? 1 : 0,
        ]);
        if ($update) {
            
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " updated a ticket named '" . $request->subject . "'"
            ]);
            return response()->json(['status' => 1, 'message' => 'Ticket updated successfully.'], 200);
        }
        return response()->json(['status' => 1, 'message' => 'Something Went to Wrong.'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $delete = $ticket->delete();
        if ($delete) {
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " deleted a ticket"
            ]);
            return response()->json(['status' => 1, 'message' => 'Ticket deleted successfully.'], 200);
        }
        return response()->json(['status' => 0, 'message' => 'Something went to wrong.'], 500);
    }

    public function addCommentTicket(Request $request)
    {
        $data = [
            'ticket_id' => $request->ticket_id,
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
        $ticket = TicketComment::create($data);
        if ($ticket) {
            $userList = User::where('role_id', 1)->where('id','!=',Auth()->user()->id)->get();
            $config = config('services')['pusher'];
            $pusher = new Pusher($config['key'],  $config['secret'], $config['app_id'], [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            foreach ($userList as $key => $user) {
                $offerData = [
                    'user_id' => $user->id,
                    'type' => 'Ticket',
                    'title' => "New Comment added is ".$request->comment_message,
                    'text' => "Ticket Comment",
                    'url' => route('ticket.show',$request->ticket_id),
                ];
                Notification::send($user, new SendNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }
            $ticketDetail = Ticket::find($request->ticket_id);
            $offerData = [
                'user_id' => $ticketDetail->emp_id,
                'type' => 'Ticket',
                'title' => "New Comment added is ".$request->comment_message,
                'text' => "Ticket Comment",
                'url' => route('ticket.show',$request->ticket_id),
            ];
            $pusher->trigger('notifications', 'new-notification', $offerData);
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " added a comment in ticket"
            ]);
            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
