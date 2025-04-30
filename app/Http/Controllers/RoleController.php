<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [
            'title' => "Roles",
            'subtitle' => "Manage all roles in the system"
        ];

        // $roles = Role::all();
        $query = Role::query();
        if ($request->has('search_keyword')) {
            $keyword = $request->search_keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            });
        }
        $roles = $query->orderBy('id', 'asc')
            ->paginate(10);

        $roles->appends($request->all());

        return view('roles.index')
            ->with('viewData', $viewData)
            ->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
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
        $viewData = [
            'title' => "Role Details",
            'subtitle' => $role->name
        ];

        $modules = Module::all();

        return view('roles.show', compact('role'))
            ->with('viewData', $viewData)
            ->with('modules', $modules);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // return view('roles.edit', compact('Role'));
        return view('roles.edit');
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
