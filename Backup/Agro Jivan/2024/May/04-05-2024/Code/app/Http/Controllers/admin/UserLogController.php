<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\UserLogRepositoryInterface;

class UserLogController extends Controller
{
    protected $userLogRepository = "";
    public function __construct(UserLogRepositoryInterface $userLogRepository)
    {
        $this->userLogRepository = $userLogRepository;
    }
    public function index(){
        $page = "All Logs";
        return view('admin.logs.index',compact('page'));
    }

    public function ajaxList(Request $request){
        $search = $request->search;
        $date = $request->search_date;
        $logList = $this->userLogRepository->getAllLog($search,$date);
        return view('admin.logs.ajax_list',compact('logList'));
    }
}
