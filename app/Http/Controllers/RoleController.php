<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $viewData = [
            'title' => "Create New Role",
            // 'subtitle' => $role->name
        ];

        return view('roles.create')
            ->with('viewData', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $roleData = $request->validated();

        $isAllowToBeAssigne = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['allow_to_be_assigne'] = $isAllowToBeAssigne;
        $roleData['created_by_id'] = Auth::user()->id;

        $role = Role::create($roleData);

        return redirect()->route('roles.show', ['role' => $role])
            ->with('success', 'Role created successfully.');
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
        $viewData = [
            'title' => "Edit Role"
        ];

        return view('roles.edit', compact('role'))
            ->with('viewData', $viewData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $roleData = $request->validated();

        $roleData['allow_to_be_assigne'] = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['last_updated_by_id'] = Auth::user()->id;

        $role->update($roleData);

        return redirect()->route('roles.show', ['role' => $role])
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $name = $role->name;
        $role->delete(); // This is a soft delete
        return redirect()->route('roles.index')->with('success', 'Role: ' . $name . ' deleted successfully.');
    }
}
