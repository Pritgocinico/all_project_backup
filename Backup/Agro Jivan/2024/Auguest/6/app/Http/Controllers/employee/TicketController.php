<?php

namespace App\Http\Controllers\employee;

use App\Helpers\UserLogHelper;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use Illuminate\Http\Request;
use PDF;
use Notification;
use App\Notifications\OffersNotification;
use Pusher\Pusher;

class TicketController extends Controller
{
    protected $ticketRepository,$employeeRepository = "";
    public function __construct(TicketRepositoryInterface $ticketRepository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->employeeRepository = $employeeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = "Ticket List";
        return view('employee.ticket.index',compact('page'));
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
    public function store(Request $request)
    {
        if(!is_null($request->emp_id)){
            $ids = $request->emp_id;
        }else{
            $ids = Auth()->user()->id;
        }   
        $data = [
            'created_by' => $ids,
            'ticket_type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
        ];
        $insert = $this->ticketRepository->store($data);
        if ($insert) {
            if ($insert) {
                $log = 'Ticket Created by ' . ucfirst(Auth()->user()->name);
                UserLogHelper::storeLog('Ticket', $log);
                return response()->json(['data' => $insert, 'message' => 'Ticket Created Successfully', 'status' => 1], 200);
            }
            return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = $this->ticketRepository->getDataById($id);
        $page = "View Ticket";
        return view('employee.ticket.show', compact('ticket','page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = $this->ticketRepository->getDataById($id);
        return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'ticket_type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'description' => $request->description,
            'status' => $request->status == "on" ? "1" : "0",
        ];
        $where = ['id' => $id];
        $update = $this->ticketRepository->update($data, $where);
        if ($update) {
            $log = 'Ticket Updated by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Ticket', $log);
            return response()->json(['data' => $update, 'message' => 'Ticket Updated Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete = $this->ticketRepository->delete($id);
        if ($delete) {
            $log = 'Ticket Deleted by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Ticket', $log);
            return response()->json(['data' => $delete, 'message' => 'Ticket Deleted Successfully', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }

    public function ajaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $ticketList = $this->ticketRepository->getAllTicket($search, $date, "paginate");
        return view('employee.ticket.ajax_list', compact('ticketList'));
    }

    public function exportCSV(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $ticketList = $this->ticketRepository->getAllTicket($search, $date, "export");
        if ($request->format == "excel" || $request->format == "csv") {
            $headers = array(
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=Ticket.csv",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            );
            $columns = array('Ticket Type', 'Subject', 'Description', 'Status', 'Created At');
            $callback = function () use ($ticketList, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                foreach ($ticketList as $ticket) {
                    $text = 'Inactive';
                    if ($ticket->status == 1) {
                        $text = 'Active';
                    }
                    $date = "";
                    if (isset($ticket->created_at)) {
                        $date = UtilityHelper::convertDmyWith12HourFormat($ticket->created_at);
                    }
                    fputcsv($file, array($ticket->ticket_type, $ticket->subject, $ticket->description, $text, $date));
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
        if ($request->format == "pdf") {
            $pdf = PDF::loadView('admin.pdf.ticket', ['ticketList' => $ticketList]);
            return $pdf->download('Ticket.pdf');
        }
    }

    public function ticketComment(Request $request)
    {
        $data = [
            'ticket_id' => $request->ticket_id,
            'comment' => $request->comment_message,
            'message_type' => 'text',
            'sent_by' => Auth()->user()->id,
        ];
        if ($request->hasfile('message_file')) {
            $file = $request->file('message_file');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move('public/assets/upload/message/', $filename);
            $data['message_file'] = 'message/' . $filename;
            $data['message_type'] = 'file';
        }
        if ($request->comment_message !== null && $request->message_file !== null) {
            $data['message_type'] = "text_file";
        }
        $ticket = $this->ticketRepository->storeTicketComment($data);
        if ($ticket) {
            $log =  "Ticket " . $ticket->id . ' Comment Added by ' . ucfirst(Auth()->user()->name);
            UserLogHelper::storeLog('Ticket', $log);
            $ticketDetail = $this->ticketRepository->getDataById($ticket->ticket_id);
            $userSchema = $this->employeeRepository->getDetailById('1');
            $pusher = new Pusher(env('PUSHER_APP_KEY'),  env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $offerData = [
                'user_id' => $userSchema->id,
                'type' => 'Ticket',
                'title' => 'You received an Ticket Comment.',
                'text' => 'Ticket Comment',
                'url' => route('ticket-show', $ticket->ticket_id),
            ];
            Notification::send($userSchema, new OffersNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);
            $hrDetail = $this->employeeRepository->getAllHR();
            foreach ($hrDetail as $hr) {
                $offerData = [
                    'user_id' => $hr->id,
                    'type' => 'Ticket',
                    'title' => 'You received an Ticket Comment.',
                    'text' => 'Ticket Comment',
                    'url' => route('hr-ticket-view', $ticket->ticket_id),
                ];
                Notification::send($hr, new OffersNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }

            if ($ticketDetail->ticket_type == "System Repair") {
                $employeeList = $this->employeeRepository->getAllSystemEngineer();
                foreach ($employeeList as $key => $employee) {
                    $offerData = [
                        'user_id' => $employee->id,
                        'type' => 'Ticket',
                        'title' => 'You received an Ticket Comment.',
                        'text' => 'Ticket Comment',
                        'url' => route('engineer-ticket-view', $ticket->ticket_id),
                    ];
                    Notification::send($employee, new OffersNotification($offerData));
                    $pusher->trigger('notifications', 'new-notification', $offerData);
                }
            }

            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
