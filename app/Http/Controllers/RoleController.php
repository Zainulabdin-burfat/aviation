<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::get();

        $permissions = Permission::all();
        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            list($module, $action) = explode('.', $permission->name);
            $groupedPermissions[$module][$permission->id] = $action;
        }

        return view('roles.index', compact('roles', 'groupedPermissions'));
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
    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $roleName = strtolower($request->input('role_name'));
            $permissions = $request->input('permissions', []);

            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->permissions()->sync($permissions);

            DB::commit();

            return back()->with(['message' => 'Record added successfully!', 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());

            return back()->with(['message' => 'Record not added. Please try again later.', 'status' => false]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $groupedPermissions = [];

        foreach ($permissions as $permission) {
            list($module, $action) = explode('.', $permission->name);
            $groupedPermissions[$module][$permission->id] = $action;
        }
        return view('content.role.edit-role', compact('role', 'groupedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        try {
            DB::beginTransaction();

            // $roleName = strtolower($request->input('role_name'));
            $permissions = $request->input('permissions', []);

            // $role->update(['name' => $roleName]);
            $role->permissions()->sync($permissions);

            DB::commit();

            return back()->with(['status' => 'success', 'message' => 'Role updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());

            return back()->with(['status' => 'error', 'message' => 'Role not updated. Please try again later.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
