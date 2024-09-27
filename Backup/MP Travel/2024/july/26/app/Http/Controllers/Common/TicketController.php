<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTicketRequest;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\Log;
use App\Models\TicketComment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    private $ticket;
    public function __construct()
    {
        $this->ticket = resolve(Ticket::class)->with('userDetail','departmentDetail','ticketCommentDetail','ticketCommentDetail.userDetail');
        $this->middleware('auth');
        view()->share('page','Ticket');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departmentList = Department::latest()->get();
        $ticketList = $this->ticket->when(Auth()->user()->role_id !== 1,function ($query){
            $query->where('emp_id',Auth()->user()->id);
        })->latest()->paginate(10);
        return view('admin.ticket.index', compact('ticketList','departmentList'));
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
        $lastId =  Ticket::all()->last()?Ticket::all()->last()->id+1:1;
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
        if($ticket){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " created a ticket named '" . $request->subject . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Ticket created successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to wrong.'],500);
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
        return response()->json(['status'=>1,'data' => $ticket],200);
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
        if($update){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " updated a ticket named '" . $request->subject . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Ticket updated successfully.'],200);
        }
        return response()->json(['status'=>1,'message' => 'Something Went to Wrong.'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $delete = $ticket->delete();
        if($delete){
            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Ticket',
                'description' => auth()->user()->name . " deleted a ticket"
            ]);
            return response()->json(['status'=>1,'message' => 'Ticket deleted successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
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
