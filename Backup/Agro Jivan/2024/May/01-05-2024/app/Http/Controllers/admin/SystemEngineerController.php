<?php


namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\SystemEngineerRepositoryInterface;
use App\Http\Requests\CreateSystemEngineerRequest;
use App\Http\Requests\UpdateSystemEngineerRequest;
use Illuminate\Support\Facades\Response;
use App\Helpers\UtilityHelper;
use PDF;

use Illuminate\Http\Request;

class SystemEngineerController extends Controller
{
    protected $roleRepository,$systemengineerRepository = "";
    public function __construct(RoleRepositoryInterface $roleRepository,SystemEngineerRepositoryInterface $systemengineerRepository){
        $this->roleRepository = $roleRepository;
        $this->systemengineerRepository = $systemengineerRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleList = $this->roleRepository->getAllRole();
        return view('admin.systemengineer.index',compact('roleList'));
    }

    public function ajaxList(Request $request){
        $status = $request->status;
        $role = $request->role;
        $search = $request->search;
        $systemengineerList =$this->systemengineerRepository->getAllData($status,$role,$search,'paginate');
        return view('admin.systemengineer.ajax_list',compact('systemengineerList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roleList = $this->roleRepository->getAllRole();
        return view('admin.systemengineer.create',compact('roleList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSystemEngineerRequest $request)
    {
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'password'=>\Hash::make($request->password),
            'status'=>$request->status == "on"?'1':'0',
            'role_id'=>$request->role,
        ];
       
        $insert =$this->systemengineerRepository->store($data);
        if($insert){
            $role = ['user_id' =>$insert->id,'role_id'=>$insert->role_id];
            $roleList = $this->roleRepository->storeRoleUser($role);
            return redirect('admin/systemengineer')->with('success','Employee Created Successfully.');
        }
        return redirect('systemengineer.create')->with('error','Something Went To Wrong.');
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
        $systemengineer =$this->systemengineerRepository->getDetailById($id);
        $roleList = $this->roleRepository->getAllRole();
        return view('admin.systemengineer.edit',compact('roleList','systemengineer'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSystemEngineerRequest $request, string $id)
    {
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_number'=>$request->phone_number,
            'status'=>$request->status == "on"?'1':'0',
            'role_id'=>$request->role,
        ];
        if($request->password !== ""){
            $data['password'] = \Hash::make($request->password);
        }
       
        $where = ['id'=>$id];
        $update =$this->systemengineerRepository->update($data,$where);
        if($update){
            $whereRole = ['user_id' =>$id];
            $role = ['role_id'=>$request->role];
            $roleList = $this->roleRepository->update($role,$whereRole);
            return redirect('admin/systemengineer')->with('success','System Engineer Updated Successfully.');
        }
        return redirect('systemengineer',$id)->with('error','Something Went To Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $delete =$this->systemengineerRepository->delete($id);
        if($delete){
            return response()->json(['data'=>'','message'=> 'Employee Deleted Successfully','status'=>1],200);
        }
        return response()->json(['data'=>'','message'=> 'Something Went To Wrong.','status'=>1],500);
    }

   

}
