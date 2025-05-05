<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRoleModule;
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
    public function store(Request $request)
    {
        $roleData = $request->all();
        $allowToBeAssigne = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['allow_to_be_assigne'] = $allowToBeAssigne ? 1 : 0;

        $role = new Role();
        $validated = $role->validate('create', $roleData);

        $role->fill($validated)->save();
        $this->logActivity($request, 'create');

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

        $roleModulePermissions = [];
        foreach ($role->modulePermissions as $roleModulePermission) {
            $moduleSlug = $roleModulePermission->module->slug;
            $permissionSlug = $roleModulePermission->permission->slug;
            if (isset($roleModulePermissions[$moduleSlug])) {
                $roleModulePermissions[$moduleSlug][] = $permissionSlug;
            } else {
                $roleModulePermissions[$moduleSlug] = [$permissionSlug];
            }
        }

        $modulePermissions = [];
        $modules = Module::all();
        foreach ($modules as $module) {
            $permissionsData = [
                'read' => false,
                'create' => false,
                'update' => false,
                'delete' => false,
                'others' => []
            ];

            if (isset($roleModulePermissions[$module->slug])) {
                $roleModulePermission = $roleModulePermissions[$module->slug];

                $modulePermissionSlugs = [];
                $otherPermissions = [];
                foreach ($module->permissions as $permission) {
                    $modulePermissionSlugs[] = $permission->slug;
                    if (!in_array($permission->slug, ['read', 'create', 'update', 'delete'])) {
                        if (in_array($permission->slug, $roleModulePermission)) {
                            $otherPermissions[] = $permission->name;
                        }
                    }
                }

                $permissionsData['read'] = (in_array('read', $modulePermissionSlugs) && in_array('read', $roleModulePermission));
                $permissionsData['create'] = (in_array('create', $modulePermissionSlugs) && in_array('create', $roleModulePermission));
                $permissionsData['update'] = (in_array('update', $modulePermissionSlugs) && in_array('update', $roleModulePermission));
                $permissionsData['delete'] = (in_array('delete', $modulePermissionSlugs) && in_array('delete', $roleModulePermission));
                $permissionsData['others'] = $otherPermissions;
            }

            $modulePermissions[$module->name] = $permissionsData;
        }

        $crudPermissionData = Permission::whereIn('slug', ['create', 'read', 'update', 'delete'])
            ->pluck('id', 'slug')
            ->toArray();

        $activityLogs = $role->activityLogs()
            ->latest()
            ->limit(3)
            ->get();

        return view('roles.show', compact('role'))
            ->with('viewData', $viewData)
            ->with('modulePermissions', $modulePermissions)
            ->with('activityLogs', $activityLogs);
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
    public function update(Request $request, Role $role)
    {
        $roleData = $request->all();
        $allowToBeAssigne = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['allow_to_be_assigne'] = $allowToBeAssigne ? 1 : 0;

        $validated = $role->validate('update', $roleData);

        $role->update($validated);

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

    /**
     * Show the form for editing the specified permissions.
     */
    public function editPermissions(Role $role)
    {
        $viewData = [
            'title' => "Edit Role Permission"
        ];
        $modules = Module::all();

        return view('roles.edit_permissions', compact('role'))
            ->with('viewData', $viewData)
            ->with('modules', $modules);
    }

    /**
     * Update the specified permissions in storage.
     */
    public function updatePermissions(Request $request, Role $role)
    {
        PermissionRoleModule::where('role_id', $role->id)->delete();

        if (isset($request->modules)) {
            $newRolePermissionsData = [];
            foreach ($request->modules as $moduleSlug => $permissionsSlug) {
                $module = Module::where('slug', $moduleSlug)->first();
                if ($module) {
                    $permissionsId = Permission::whereIn('slug', $permissionsSlug)->pluck('id')->toArray();
                    foreach ($permissionsId as $permissionId) {
                        $newRolePermissionsData[] = [
                            'role_id' => $role->id,
                            'module_id' => $module->id,
                            'permission_id' => $permissionId
                        ];
                    }
                }
            }

            if (count($newRolePermissionsData) > 0) {
                PermissionRoleModule::insert($newRolePermissionsData);
            }
        }

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role Permissions updated successfully.');
    }

    public function activityLogs(Role $role)
    {
        $viewData = [
            'title' => "Role Activity Logs"
        ];

        $activityLogs = $role->activityLogs()
            ->latest()
            ->get();
        // ->limit(10)

        return view('roles.activity_logs', compact('role'))
            ->with('viewData', $viewData)
            ->with('activityLogs', $activityLogs);
    }
}
