<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('checkInstaller');
        $this->middleware('auth');
        $this->middleware('basicView');
        $this->middleware('dueCount');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['page_title'] = 'Manage Permission';
        $data['permissions'] = DB::table('permissions')->get();
        return view('permissions.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['page_title'] = 'Create Permission';
        $data['roles'] = Role::all();
        return view('permissions.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'name' => 'required|unique:permissions,name',
            'role' => 'required'
        ]);
        $permission = Permission::create(['name' => Str::slug($request->input('name'))]);
        $permission->syncRoles($request->input('role'));

        return redirect()->route('permissions.index')
            ->withToastSuccess('Permission Create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['page_title'] = 'Show Permission';
        $data['permission'] = Permission::findById($id);
        $data['users'] = User::permission($data['permission']->name)->get();
        return view('permissions.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['page_title'] = 'Edit Permission';
        $data['permission'] = Permission::findById($id);
        $data['roles'] = Role::all();
        $data['rolePermissions'] = DB::table("role_has_permissions")->where("role_has_permissions.permission_id",$id)
            ->pluck('role_has_permissions.role_id','role_has_permissions.role_id')
            ->all();
        return view('permissions.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,'.$id
        ]);
        $permission = Permission::findById($id);
        $permission->update(['name'=>Str::slug($request->input('name'))]);
        $permission->syncRoles($request->input('role'));
        return redirect()->back()
            ->withToastSuccess('Permission Update Successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $rolePermissions = DB::table("role_has_permissions")->where("permission_id",$id)
            ->pluck('role_id')
            ->toArray();
        foreach ($rolePermissions as $role){
            $r = Role::findById($role);
            $r->revokePermissionTo($permission);
        }
        $permission->delete();

        return redirect()->back()
            ->withToastSuccess('Permission Deleted Successfully');
    }
}
