<?php

namespace App\Repositories;

use App\Interfaces\TicketRepositoryInterface;
use App\Models\Ticket;
use App\Models\TicketComment;
use PDO;

class TicketRepository implements TicketRepositoryInterface 
{
    public function getAllTicket($search,$date,$type){
        $query =  $this->getQuery($search,$date)->where('created_by',Auth()->user()->id)->latest();
        if($type = "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }

    public function store($data){
        return Ticket::create($data);
    }

    public function getDataById($id){
        return Ticket::with('userDetail','ticketCommentDetail','ticketCommentDetail.userDetail')->where('id',$id)->first();
    }

    public function update($data,$where){
        return Ticket::where($where)->update($data);
    }

    public function delete($id){
        return Ticket::where('id',$id)->delete();
    }

    public function getAllTicketData($search,$dateFilter ="",$userId ="",$type){
        return $this->getQuery($search, $dateFilter)
        ->when($type,function($query) use($type){
            $query->where('ticket_type',$type);
        })->when($userId,function($query)use($userId){
            $query->where('created_by',$userId);
        })->latest()->paginate(15);
    }

    public function getQuery($search,$date=""){
        return Ticket::with('userDetail')->when($search,function($query) use($search){
            $query->where('ticket_type', 'like', '%'.$search.'%')
            ->orWhere('subject', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereDate('created_at', '>=', $date1[0])
            ->whereDate('created_at', '<=', $date1[1]);
        });
    }

    public function storeTicketComment($data){
        return TicketComment::create($data);
    }

    public function totalTicketCount($type){
        return Ticket::where('ticket_type',$type)->count();
    }

    public function getSystemTicketList($search,$date,$empID,$type){
        $query = Ticket::with('userDetail')->where('ticket_type', 'System Repair')
        ->when($search,function($query) use($search){
            $query->where('subject', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
        })->when($date,function($query) use($date){
            $date1 = explode('/',$date);
            $query->whereDate('created_at', '>=', $date1[0])
            ->whereDate('created_at', '<=', $date1[1]);
        })->when($empID,function($query) use($empID){
            $query->where('created_by', $empID);
        })->latest();

        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }
}