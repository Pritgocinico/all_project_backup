<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\FollowUpEvent;
use App\Models\FollowUpMember;
use App\Models\Lead;
use App\Models\LeadMember;
use App\Models\User;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    protected $followUp;
    public function __construct()
    {
        $this->followUp = resolve(FollowUpEvent::class);
        view()->share('page', 'Follow Up');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $followUpList = $this->followUp->latest()->paginate(10);
        $employees      = User::where('role_id', 2)->get();
        $leads          = Lead::with('leadMemberDetail', 'leadMemberDetail.userDetail')->get();
        return view('admin.follow_up.index', compact('followUpList', 'leads', 'employees'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
}
