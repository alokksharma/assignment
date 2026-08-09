<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
    {
        $title = 'Roles';

        $roles = Role::with('permissions')->whereNotIN('name', ['SuperAdmin', 'Tenant'])->get();

        return view('masters.roles.index', compact('title', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'create role';
        $permissions = Permission::get();
        return view('masters.roles.create',compact('title', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'role' => 'required|min:3|max:255',
            'display_name' => 'nullable|min:3|max:255',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->role, 'display_name' => $request->display_name]);
        $role->syncPermissions($request->permission);
        return redirect()->route('roles.index') ->with('success', 'role create successfully.');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $title = 'edit role';
        $permissions = Permission::get();
        return view('masters.roles.edit',compact(
            'title','role','permissions'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request,[
            'role' => 'required|min:3|max:255',
            'permission' => 'required',
            'display_name' => 'nullable|min:3|max:255',
        ]);
        $role->update([
            'name' => $request->role,
            'display_name' => $request->display_name
        ]);
        $role->syncPermissions($request->permission);
        return redirect()->route('roles.index')->with('success', 'role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return Role::findOrFail($request->id)->delete();
    }
}
