<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $viewData = [
            'title' => "Role - Laravel RBAC",
            'subtitle' => "Role"
        ];

        $roles = Role::all();

        return view('roles.index')
            ->with('viewData', $viewData)
            ->with('roles', $roles);


        // $roles = Role::all();
        // return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email|unique:roles',
        // ]);

        // Role::create($request->all());

        // return redirect()->route('roles.index')
        //                  ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        // return view('roles.show', compact('Role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // return view('roles.edit', compact('Role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email|unique:roles,email,' . $Role->id,
        // ]);

        // $Role->update($request->all());

        // return redirect()->route('roles.index')
        //                  ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // $Role->delete();

        // return redirect()->route('roles.index')
        //                  ->with('success', 'Role deleted successfully.');
    }
}
