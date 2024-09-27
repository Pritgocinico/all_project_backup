<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\AdditionalProjectDetail;
use App\Models\Log;
use App\Models\Project;
use App\Models\ProjectQuestion;
use App\Models\QaDoneTask;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

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
    public function store_qa_question(Request $request){
        $request->validate([
            'qa_question'          => 'required',
        ]);
        $newQuestion = new ProjectQuestion();
        $newQuestion->project_id = $request->project_id;
        $newQuestion->question = $request->qa_question;
        $newQuestion->question_type = 3;
        $newQuestion->created_by = auth()->user()->id;
        $newQuestion->save();

        $log = new Log();
        $log->user_id   = Auth::user()->name;
        $log->module    = 'Fitting';
        $log->log       = 'Fitting Added';
        $log->save();

        $fittingDoneTasks = QaDoneTask::where('project_id', $request->project_id)->get();

        if (!blank($fittingDoneTasks)) {
            $fittingQuestion = new QaDoneTask();
            $fittingQuestion->project_id = $request->project_id;
            $fittingQuestion->question_id = $newQuestion->id;
            $fittingQuestion->save();
        }
        if (Auth::user()->role == 1) {
            return redirect()->route('view.qa.question', $request->project_id)->with('success', 'QA question submitted successfully.');
        } else {
            return redirect()->route('quotation_view.fitting', $request->project_id)->with('success', 'QA question submitted successfully.');
        }
    }
    public function view_store_qa(Request $request){
        $requestData = $request->all();
        $projectData = Project::where('id', $requestData['project_id'])->first();
        $issueData = AdditionalProjectDetail::where('project_id', $requestData['project_id'])->where('status', 'issue')->latest()->first();
        foreach ($requestData as $key => $value) {
            if (strpos($key, 'chk') === 0) {
                $id = substr($key, 3);
                $isChecked = ($value === 'on');
                if ($projectData->add_work == 2) {
                    $workshopTask = QaDoneTask::updateOrCreate(
                        ['project_id' => $request->project_id, 'question_id' => $id, 'add_type' => 2, 'issue_id' => $issueData->id],
                        ['chk' => $isChecked ? 'on' : 'off']
                    );
                } else {
                    $workshopTask = QaDoneTask::updateOrCreate(
                        ['project_id' => $request->project_id, 'question_id' => $id],
                        ['chk' => $isChecked ? 'on' : 'off']
                    );
                }
            }
        }
        
        if (Auth::user()->role == 1) {
            return redirect()->route('view.qa.question', $request->project_id)->with('success', 'Workshop created successfully.');
        } else {
            return redirect()->route('quotation_view.workshop', $request->project_id)->with('success', 'Workshop created successfully.');
        }
    }
}
