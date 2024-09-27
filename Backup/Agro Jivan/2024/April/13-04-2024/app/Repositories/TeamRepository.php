<?php

namespace App\Repositories;

use App\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Models\TeamDetail;
use Illuminate\Support\Facades\DB;

class TeamRepository implements TeamRepositoryInterface 
{
    public function getAllData($search,$type){
        $query =  Team::with('managerDetail','teamMember','teamMember.userDetail','teamMember.userDetail.confirmOrder')->when($search,function($query)use($search){
            $query->where('team_id','like','%'.$search.'%')
            ->orWhereHas('managerDetail',function($query)use($search){
                $query->where('name','like','%'.$search.'%');
            });
        })->latest();
        if($type == "paginate"){
            $query = $query->paginate(15);
        }
        if($type == "export"){
            $query = $query->get();
        }
        return $query;
    }

    public function generateTeamId(){
        return 'AGRJVN-TEAM-'.sprintf('%04d',Team::all()->last() !== null?Team::all()->last()->id+1:1);
    }

    public function store($data){
        return Team::create($data);
    }

    public function storeTeamDetail($data){
        return TeamDetail::create($data);
    }

    public function getDetailById($id){
        return Team::with('managerDetail')->withCount('pendingOrder','confirmOrder','deliveredOrder','cancelOrder','returnOrder','allOrder')->where('id',$id)->first();
    }

    public function update($data,$where){
        return Team::where($where)->update($data);
    }

    public function getTeamDetailById($id,$search){
        return TeamDetail::with('userDetail')->withCount('pendingOrder','confirmOrder','deliveredOrder','cancelOrder','returnOrder','allOrder')->where('team_id',$id)
        ->when($search,function($query)use($search){
            $query->whereHas('userDetail',function($query)use($search){
                $query->where('name','like','%'.$search.'%');
            });
        })
        ->orderBy('pending_order_count','desc')->paginate(15);
    }

    public function removeMember($where){
        return TeamDetail::where($where)->delete();
    }

    public function getTopFiveUserTeam(){
        $teams = Team::with(['teamDetails.user', 'teamDetails.user.orders'])
            ->get();
        foreach ($teams as $team) {
            $team->teamDetails->sortByDesc(function ($teamDetail) {
                if(isset($teamDetail->user)){
                    $teamDetail->user->orders->where('order_status', 1)->count();
                }
            });
            foreach ($team->teamDetails as $teamDetail) {
                $user = $teamDetail->user;
                $teamDetail['total_pending_orders_count'] = 0;
                $teamDetail['total_confirm_orders_count'] = 0;
                if(isset($user)){
                    $teamDetail['total_pending_orders_count'] = $user->orders->where('order_status', 1)->count();
                    $teamDetail['total_confirm_orders_count'] = $user->orders->count();
                }
            }
        }
        return $teams;
    }
}