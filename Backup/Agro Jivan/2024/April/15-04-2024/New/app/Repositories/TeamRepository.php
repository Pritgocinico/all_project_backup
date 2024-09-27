<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Models\TeamDetail;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface
{
    public function getAllData($search, $type)
    {
        $query =  Team::with('managerDetail', 'teamMember', 'teamMember.userDetail', 'teamMember.userDetail.confirmOrder')->when($search, function ($query) use ($search) {
            $query->where('team_id', 'like', '%' . $search . '%')
                ->orWhereHas('managerDetail', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->latest();
        if ($type == "paginate") {
            $query = $query->paginate(15);
        }
        if ($type == "export") {
            $query = $query->get();
        }
        return $query;
    }

    public function generateTeamId()
    {
        return 'AGRJVN-TEAM-' . sprintf('%04d', Team::all()->last() !== null ? Team::all()->last()->id + 1 : 1);
    }

    public function store($data)
    {
        return Team::create($data);
    }

    public function storeTeamDetail($data)
    {
        return TeamDetail::create($data);
    }

    public function getDetailById($id)
    {
        return Team::with('managerDetail','deliveredOrder')->withCount('pendingOrder', 'confirmOrder', 'deliveredOrder', 'cancelOrder', 'returnOrder', 'allOrder', 'onDeliveryOrder')->where('id', $id)->first();
    }

    public function update($data, $where)
    {
        return Team::where($where)->update($data);
    }

    public function getTeamDetailById($id, $search,$date = "")
    {
        return TeamDetail::with('userDetail')->withCount([
            'pendingOrder' => function ($query) use($date) {
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                    $query->whereDate('created_at',">=",$date1[0])->whereDate('created_at',"<=",$date1[1])->where('order_status','1');
                });
            },
            'onDeliveryOrder' => function ($query) use($date) {
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                    $query->whereDate('created_at',">=",$date1[0])->whereDate('created_at',"<=",$date1[1])->where('order_status','3');
                });
            },
            'confirmOrder' => function ($query) use($date) {
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                    $query->whereDate('confirm_date',">=",$date1[0])->whereDate('confirm_date',"<=",$date1[1]);
                });
            },
            'deliveredOrder' => function ($query)  use($date){
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                    $query->whereDate('delivery_date',">=",$date1[0])->whereDate('delivery_date',"<=",$date1[1]);
                });
            },
            'cancelOrder' => function ($query) use($date) {
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                $query->whereDate('cancel_date',">=",$date1[0])->whereDate('cancel_date',"<=",$date1[1]);
                });
            },
            'returnOrder' => function ($query)  use($date){
                $query->when($date,function($query)use($date){
                    $date1 = explode('/',$date);
                $query->whereDate('return_date',">=",$date1[0])->whereDate('return_date',"<=",$date1[1]);
                });
            },
            'allOrder' => function ($query) use($date) {
                $query->when($date,function($query)use($date){
                $date1 = explode('/',$date);
                $query->whereDate('created_at',">=",$date1[0])->whereDate('created_at',"<=",$date1[1]);
                });
            }
        ])->where('team_id', $id)
            ->when($search, function ($query) use ($search) {
                $query->whereHas('userDetail', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('pending_order_count', 'desc')->paginate(15);
    }

    public function removeMember($where)
    {
        return TeamDetail::where($where)->delete();
    }

    public function getTopFiveUserTeam($date)
    {
        $teams = Team::with(['teamDetails.user', 'teamDetails.user.orders'])
            ->get();
        foreach ($teams as $team) {
            foreach ($team->teamDetails as $teamDetail) {
                $user = $teamDetail->user;
                $teamDetail['total_pending_orders_count'] = 0;
                $teamDetail['total_confirm_orders_count'] = 0;

                if (isset($user)) {
                    $ordersWithinRange = $user->orders()->when($date,function($query)use($date){
                        $date1 = explode('/', $date);
                        $query->whereDate('created_at', [$date1[0], $date1[1]]);
                    })->get();

                    $teamDetail['total_pending_orders_count'] = $ordersWithinRange->where('order_status', 1)->count();
                    $teamDetail['total_confirm_orders_count'] = $ordersWithinRange->count();
                }
            }
            $team->teamDetails = $team->teamDetails->sortByDesc(function ($teamDetail) {
                return [
                    'total_pending_orders_count' => $teamDetail->total_pending_orders_count,
                    'user_id' => $teamDetail->user->id,
                ];
            })->take(5);
        }
        return $teams;
    }
}
