<?php

namespace App\Http\Controllers\admin;

use App\Helpers\UserLogHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\EmployeeRepositoryInterface;
use App\Interfaces\TicketRepositoryInterface;
use Notification;
use App\Notifications\OffersNotification;
use Pusher\Pusher;

class TicketController extends Controller
{
    protected $ticketRepository, $employeeRepository = "";

    public function __construct(TicketRepositoryInterface $ticketRepository, EmployeeRepositoryInterface $employeeRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->employeeRepository = $employeeRepository;
    }

    public function ticketList()
    {
        $page = "Ticket List";
        return view('admin.hr_manager.ticket.index',compact('page'));
    }

    public function ticketListAjax(Request $request)
    {
        $search = $request->search;
        $ticketList = $this->ticketRepository->getAllTicketData($search, "", "", "");
        return view('admin.hr_manager.ticket.ajax_list', compact('ticketList'));
    }

    public function show(string $id)
    {
        $ticket = $this->ticketRepository->getDataById($id);
        $page = "Ticket Comment";
        return view('admin.hr_manager.ticket.show', compact('ticket','page'));
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
                'type' => 'Ticket',
                'title' => 'You received an Ticket Comment.',
                'text' => 'Ticket Comment',
                'url' => route('ticket.show', $ticket->ticket_id),
            ];
            $pusher = new Pusher(env('PUSHER_APP_KEY'),  env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap2'),
            ]);
            Notification::send($userSchema, new OffersNotification($offerData));
            $hrDetail = $this->employeeRepository->getAllHR();
            $offerData['url'] = route('hr-ticket-view', $ticket->ticket_id);
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
