<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Access;
use App\Models\Log;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $roles;

    public function __construct()
    {
        $this->middleware('auth');  
        $page = "Role";
        view()->share('page', $page);
        $this->roles = resolve(Role::class)->with('access','access.menu');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleList = $this->roles->paginate(10);
        return view('admin.role.index', compact('roleList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::all();
        return view('admin.role.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roleId = Role::create(
            ['name' => $request->role_name,
            'created_by' => auth()->user()->id,
        ]);
        if ($roleId) {

            foreach ($request->menuAndAccessLevel as $mna) {
                $key = key($mna);
                Access::create([
                    'role_id' => $roleId->id,
                    'menu_id' => $key,
                    'status' => $mna[$key]
                ]);
            }

            Log::create([
                'user_id' => auth()->user()->id,
                'module' => 'Role',
                'description' => auth()->user()->name . " created a role named '" . $request->role_name . "'"
            ]);

            return redirect()->route('role.index')->with('success', 'Successfully created a role.');
        }
        return redirect()->route('role.create')->with('error', 'Something Went to Wrong.');
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
        $role = Role::where('id', $id)->first();
        $accessesForEditing = Access::where('role_id', $id)->with('menu', 'role')->orderBy('menu_id', 'ASC')->get();

        return view('admin.role.edit', compact('accessesForEditing', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::where('id', $id)->first();
        $role->name = $request->role_name;
        $role->updated_by = auth()->user()->id;
        $update = $role->save();
        if ($update) {
            foreach ($request->menuAndAccessLevel as $mna) {
                $key = key($mna);
                Access::where([
                    ['role_id', '=', $id],
                    ['menu_id', '=', $key],
                ])->update([
                    'status' => $mna[$key]
                ]);
            }

            Log::create([
                'user_id' => auth()->user()->id,
                'module' =>'Role',
                'description' => auth()->user()->name . " updated a role's detail named '" . $role->name . "'"
            ]);

            return redirect()->route('role.index')->with('success', 'Successfully updated role.');
        }
        return redirect()->route('role.edit',$id)->with('error', 'Something Went Wrong.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->roles->where('id', $id)->first();
        $delete = $this->roles->where('id', $id)->delete();
        if($delete){

            Log::create([
                'user_id' => auth()->user()->id,
                'module'=>'Role',
                'description' => auth()->user()->name . " deleted a role named '" . $role->name . "'"
            ]);
            return response()->json(['status'=>1,'message' => 'Role deleted successfully.'],200);
        }
        return response()->json(['status'=>0,'message' => 'Something went to wrong.'],500);
    }
}
