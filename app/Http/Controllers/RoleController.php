<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRoleModule;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:role,read')->only('index', 'show');
        $this->middleware('check.permission:role,create')->only('create', 'store');
        $this->middleware('check.permission:role,update')->only('edit', 'update');
        $this->middleware('check.permission:role,delete')->only('destroy');
        $this->middleware('check.permission:role,update-role-permissions')->only('editPermissions', 'updatePermissions');
        $this->middleware('check.permission:role,read-activity-log')->only('editPermissions', 'activityLogs');
        $this->middleware('check.permission:role,delete-user')->only('deleteUser');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Roles';
        $attributeLabels = Role::$attributeLabels;

        $query = Role::query();
        if ($request->has('search_keyword')) {
            $keyword = $request->search_keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%");
            });
        }
        $roles = $query->orderBy('id', 'asc')
            ->paginate(10)
            ->appends($request->all());

        return view('roles.index', compact('roles', 'title', 'attributeLabels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create')
            ->with('title', 'Create New Role')
            ->with('attributeLabels', Role::$attributeLabels);
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

        $lastActivity = $role->activityLogs()
            ->latest()
            ->limit(1)
            ->first();

        return view('roles.show', compact('role'))
            ->with('title', $role->name . ' Details')
            ->with('attributeLabels', Role::$attributeLabels)
            ->with('modulePermissions', $modulePermissions)
            ->with('lastActivity', $lastActivity);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'))
            ->with('title', 'Edit Role')
            ->with('attributeLabels', Role::$attributeLabels);
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
        $moduleNamebySlug = [];
        $modules = Module::all();

        foreach ($modules as $module) {
            $moduleNamebySlug[$module->slug] = $module->name;
            $modulePermissionSLugs = $module->getPermissionSlugs();

            $permissionsData = [
                'read' => null,
                'create' => null,
                'update' => null,
                'delete' => null,
                'others' => []
            ];

            foreach (['read', 'create', 'update', 'delete'] as $action) {
                if (in_array($action, $modulePermissionSLugs)) {
                    if (isset($roleModulePermissions[$module->slug])) {
                        $permissionsData[$action] = in_array($action, $roleModulePermissions[$module->slug]) ? 'checked' : 'unchecked';
                    } else {
                        $permissionsData[$action] = 'unchecked';
                    }
                }
            }

            foreach ($module->permissions as $permission) {
                $modulePermissionSlugs[] = $permission->slug;
                if (!in_array($permission->slug, ['read', 'create', 'update', 'delete'])) {
                    if (isset($roleModulePermissions[$module->slug])) {
                        $isChecked = in_array($permission->slug, $roleModulePermissions[$module->slug]);
                    } else {
                        $isChecked = false;
                    }
                    $permissionsData['others'][$permission->slug] = [
                        'label' => $permission->name,
                        'checked' => $isChecked
                    ];
                }
            }

            $modulePermissions[$module->slug] = $permissionsData;
        }

        return view('roles.edit_permissions', compact('role'))
            ->with('title', 'Edit ' . $role->name . ' Permissions')
            ->with('moduleNamebySlug', $moduleNamebySlug)
            ->with('modulePermissions', $modulePermissions);
    }

    /**
     * Update the specified permissions in storage.
     */
    public function updatePermissions(Request $request, Role $role)
    {
        $requestPermissions = $request->modules ?? [];

        $currentPermissionData = [];
        $currentPermissions = PermissionRoleModule::with('module', 'permission')
            ->where('role_id', $role->id)
            ->get();

        foreach ($currentPermissions as $currentPermission) {
            if (isset($currentPermissionData[$currentPermission->module->slug])) {
                $currentPermissionData[$currentPermission->module->slug][] = $currentPermission->permission->slug;
            } else {
                $currentPermissionData[$currentPermission->module->slug] = [$currentPermission->permission->slug];
            }
        }

        $moduleNameBySlug = Module::pluck('name', 'slug')->toArray();

        $logProperties = [
            'attributes' => []
        ];
        if (!empty($requestPermissions)) {
            if (empty($currentPermissionData)) {
                $requestModules = array_keys($requestPermissions);
                $changesModules = $requestModules;

                foreach ($changesModules as $moduleSlug) {
                    $newPermissions = isset($requestPermissions[$moduleSlug]) ? $requestPermissions[$moduleSlug] : [];
                    $oldPermissions = [];

                    if ($newPermissions != $oldPermissions) {
                        $modulName = $moduleNameBySlug[$moduleSlug];
                        $logProperties['attributes'][$modulName] = [
                            'old' => $oldPermissions,
                            'new' => $newPermissions
                        ];
                    }
                }
            } else {
                $requestModules = array_keys($requestPermissions);
                $currentModules = array_keys($currentPermissionData);
                $changesModules = $requestModules + $currentModules;

                foreach ($changesModules as $moduleSlug) {
                    $newPermissions = isset($requestPermissions[$moduleSlug]) ? $requestPermissions[$moduleSlug] : [];
                    $oldPermissions = isset($currentPermissionData[$moduleSlug]) ? $currentPermissionData[$moduleSlug] : [];

                    if ($newPermissions != $oldPermissions) {
                        $modulName = $moduleNameBySlug[$moduleSlug];
                        $logProperties['attributes'][$modulName] = [
                            'old' => $oldPermissions,
                            'new' => $newPermissions
                        ];
                    }
                }
            }
        } else {
            if (!empty($currentPermissionData)) {
                $currentModules = array_keys($currentPermissionData);
                $changesModules = $currentModules;

                foreach ($changesModules as $moduleSlug) {
                    $newPermissions = [];
                    $oldPermissions = isset($currentPermissionData[$moduleSlug]) ? $currentPermissionData[$moduleSlug] : [];

                    if ($newPermissions != $oldPermissions) {
                        $modulName = $moduleNameBySlug[$moduleSlug];
                        $logProperties['attributes'][$modulName] = [
                            'old' => $oldPermissions,
                            'new' => $newPermissions
                        ];
                    }
                }
            }
        }

        $moduleIdBySlug = Module::pluck('id', 'slug')->toArray();
        $permissionIdBySlug = Permission::pluck('id', 'slug')->toArray();

        $role->clearPermissions();

        foreach ($requestPermissions as $requestModule => $reqPermissions) {
            $moduleId = $moduleIdBySlug[$requestModule];
            foreach ($reqPermissions as $reqPermission) {
                $permissionId = $permissionIdBySlug[$reqPermission];
                $role->assignPermission($moduleId, $permissionId);
            }
        }

        $role->customLogActivity('role-permission-updated', 'Updated Role Permissions', $logProperties);

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role Permissions updated successfully.');
    }

    public function activityLogs(Request $request, Role $role)
    {
        $orderBy = 'desc';
        if ($request->has('sort_by')) {
            if ($request->sort_by == 'asc') {
                $orderBy = 'asc';
            }
        }

        $activityLogs = $role->activityLogs()
            ->orderBy('created_at', $orderBy)
            ->paginate(5);

        $activityLogs->appends($request->all());

        return view('roles.activity_logs', compact('role'))
            ->with('title', $role->name . ' Activity Histories')
            ->with('activityLogs', $activityLogs)
            ->with('attributeLabels', Role::$attributeLabels)
            ->with('orderBy', $orderBy);
    }

    /**
     * Update the specified user from role.
     */
    public function deleteUser(Role $role, User $user)
    {
        $message = 'Unset ' . $role->name . ' Role from ' . $user->name;

        $unsetRole = $user->unsetRole();
        if ($unsetRole) {
            $role->customLogActivity('delete-user', $message);
            $user->customLogActivity('delete-user', $message);

            return redirect()
                ->route('roles.show', $role)
                ->with('success', ' Successfully ' . $message);
        } else {
            return redirect()
                ->route('roles.show', $role)
                ->with('error', ' Failed to ' . $message);
        }
    }
}
