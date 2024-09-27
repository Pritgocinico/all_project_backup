<?php

namespace App\Http\Controllers\system;

use App\Helpers\UserLogHelper;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use App\Notifications\OffersNotification;
use Notification;
use Illuminate\Http\Request;
use Pusher\Pusher;

class SystemEngineerController extends Controller
{
    protected $ticketRepository, $employeeRepository;
    public function __construct(TicketRepositoryInterface $ticketRepository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function index()
    {
        $page = "System Engineer Dashboard";
        return view('system.index', compact('page'));
    }

    public function ticketCountAjax(Request $request){
        $date = $request->date;
        $ticketCount = $this->ticketRepository->totalTicketCount('System Repair',$date);
        return response()->json($ticketCount);
    }

    public function ticketList($id = null)
    {
        $page = "Ticket List";
        $employeeList = $this->employeeRepository->getAllData("","","",'export');
        return view('system.ticket.index', compact('id', 'employeeList','page'));
    }
    public function ajaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $eID = $request->e_id;
        $ticketList = $this->ticketRepository->getSystemTicketList($search, $date, $eID, 'paginate');
        return view('system.ticket.ajax_list', compact('ticketList'));
    }
    public function systemList(Request $request)
    {
        $page ="System Code List";
        $employeeList = $this->employeeRepository->getAllEmployee();
        return view('system.codes.index', compact('employeeList','page'));
    }
    public function systemAjaxList(Request $request)
    {
        $search = $request->search;
        $date = $request->date;
        $emp_id = $request->emp_id;
        $SystemCodeList = $this->employeeRepository->getEmployeeDetail($search, $emp_id, $date, 'paginate');
        return view('system.codes.ajax_list', compact('SystemCodeList'));
    }
    public function view(string $id)
    {
        $page = "Ticket View";
        $ticket = $this->ticketRepository->getDataById($id);
        return view('system.ticket.show', compact('ticket','page'));
    }

    public function addCommentTicket(Request $request)
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
            $userSchema = $this->employeeRepository->getDetailById($ticketDetail->created_by);

            $offerData = [
                'user_id' => $userSchema->id,
                'type' => 'message',
                'title' => 'You received an comment in ticket.',
                'text' => 'Thank you',
                'url' => route('ticket.show', $ticket->ticket_id),
            ];

            Notification::send($userSchema, new OffersNotification($offerData));

            $pusher = new Pusher(env('PUSHER_APP_KEY'),  env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            $pusher->trigger('notifications', 'new-notification', $offerData);

            $hrDetail = $this->employeeRepository->getAllHR();
            foreach ($hrDetail as $hr) {
                $offerData['user_id'] = $hr->id;
                Notification::send($hr, new OffersNotification($offerData));
                $pusher->trigger('notifications', 'new-notification', $offerData);
            }

            $user = $this->employeeRepository->getDetailById(1);
            $offerData['user_id'] = 1;
            Notification::send($user, new OffersNotification($offerData));
            $pusher->trigger('notifications', 'new-notification', $offerData);
            return response()->json(['data' => $ticket, 'message' => '', 'status' => 1], 200);
        }
        return response()->json(['data' => '', 'message' => 'Something Went To Wrong.', 'status' => 1], 500);
    }
}
