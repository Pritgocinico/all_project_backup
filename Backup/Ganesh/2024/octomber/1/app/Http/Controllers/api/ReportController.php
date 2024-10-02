<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
use App\Models\AccessToken;

class ReportController extends Controller
{

    public function completeProjectReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $projectList = Project::where('status', 2)->latest()->get();
            $array_push = array();
            if (!blank($projectList)) {
                foreach ($projectList as $project) {
                    $customerDetail = Customer::find($project->customer_id);
                    $totalCost = $project->quotation_cost + $project->transport_cost + $project->laber_cost;
                    $marginCost = $project->project_cost - $totalCost;
                    $array = array();
                    $array['id']            = $project->id;
                    $array['project_id'] = $project->project_generated_id;
                    $array['customer_id'] = $project->customer_id;
                    $array['customer_name'] = $customerDetail->name;
                    $array['project_profit'] = $marginCost;
                    $array['created_at'] = date('d/m/Y', strtotime($project->created_at));
                    array_push($array_push, $array);
                }
                return response()->json([
                    'status' => 1,
                    'logs' => $array_push
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
    public function pendingProjectReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'error' => implode(" ", $validator->errors()->all())], 404);
        } else {
            $authorization = $request->header('Authorization');
            $accessToken = AccessToken::where('access_token', $authorization)->first();
            $user = User::where('id', $accessToken->user_id)->first();
            $role = $user->role;
            $projectList = Project::where('status', 1)->latest()->get();
            $array_push = array();
            if (!blank($projectList)) {
                foreach ($projectList as $project) {
                    $customerDetail = Customer::find($project->customer_id);
                    $totalCost = $project->quotation_cost + $project->transport_cost + $project->laber_cost;
                    $marginCost = $project->project_cost - $totalCost;
                    $array = array();
                    $array['id']            = $project->id;
                    $array['project_id'] = $project->project_generated_id;
                    $array['customer_id'] = $project->customer_id;
                    $array['customer_name'] = $customerDetail->name;
                    $array['phone_number'] = $project->phone_number;
                    $array['step'] = $project->step;
                    $array['sub_step'] = $project->sub_step;
                    $array['measurement_date'] = date('d/m/Y', strtotime($project->created_at));
                    $array['created_at'] = date('d/m/Y', strtotime($project->created_at));
                    array_push($array_push, $array);
                }
                return response()->json([
                    'status' => 1,
                    'logs' => $array_push
                ], 200);
            } else {
                return response()->json(['status' => 0, 'error' => 'Something went wrong.'], 404);
            }
        }
    }
}
