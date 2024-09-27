<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\ProjectQuestion;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectQuestionController extends Controller
{
    public function __construct()
    {
        $page = "Project Question";
        $setting = Setting::first();
        $viewData = [
            'page' => $page,
            'setting' => $setting,
        ];
        view()->share($viewData);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionList = ProjectQuestion::latest()->get();
        return view('admin.question.index', compact('questionList'));
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
        $request->validate([
            'question_type'          => 'required',
            'question'          => 'required',
        ]);
        $projectQuestion = new ProjectQuestion();
        $projectQuestion->question = $request->question;
        $projectQuestion->question_type = $request->question_type;
        $projectQuestion->created_by = Auth::user()->id;
        $insert = $projectQuestion->save();
        if ($insert) {
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Project Question';
            $log->log       = $projectQuestion->question . ' has been Created.';
            $log->save();
            return redirect()->back()->with('success', 'Question Added Successfully');
        }
        return redirect()->back()->with('error', 'Something went wrong');
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
    public function edit(Request $request)
    {
        $id = $request->id;
        $question = ProjectQuestion::find($id);
        return response()->json($question);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $projectQuestion = ProjectQuestion::find($request->question_id);
        $projectQuestion->question = $request->question;
        $projectQuestion->question_type = $request->question_type;
        $update = $projectQuestion->save();
        if ($update) {
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Project Question';
            $log->log       = $projectQuestion->question . ' has been Updated.';
            $log->save();
            return response()->json(['data'=>[],'message' => 'Question Updated Successfully'],200);
        }
        return response()->json(['data'=>[],'message' => 'Something went wrong'],500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $projectQuestion = ProjectQuestion::find($id);
        $delete = $projectQuestion->delete();
        if ($delete) {
            $log = new Log();
            $log->user_id   = Auth::user()->name;
            $log->module    = 'Project Question';
            $log->log       = $projectQuestion->question . ' has been Deleted.';
            $log->save();
            return response()->json(['data'=>[],'message' => 'Question Deleted Successfully'],200);
        }
        return response()->json(['data'=>[],'message' => 'Something went wrong'],500);
    }
}
