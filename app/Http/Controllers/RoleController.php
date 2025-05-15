<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRoleModule;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.permission:role,read')->only('index', 'show');
        $this->middleware('check.permission:role,create')->only('create', 'store');
        $this->middleware('check.permission:role,update')->only('edit', 'update');
        $this->middleware('check.permission:role,delete')->only('destroy');
        $this->middleware('check.permission:role,update-role-permissions')->only('editPermissions', 'updatePermissions');
        // $this->middleware('check.permission:role,read-activity-log')->only('editPermissions', 'activityLogs');
        $this->middleware('check.permission:role,delete-user')->only('deleteUser');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attributeLabels = Role::getAttributesLabel();

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

        return view('roles.index', compact('roles', 'attributeLabels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attributeLabels = Role::getAttributesLabel();

        return view('roles.create', compact('attributeLabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $roleData = $request->all();
        $isAllowToBeAssigne = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['allow_to_be_assigne'] = $isAllowToBeAssigne ? 1 : 0;

        $role = new Role();
        $validated = $role->validate('create', $roleData);

        DB::beginTransaction();
        try {
            $role->fill($validated)->save();

            $role->createStoredDataLog([
                'user_description' => 'Created a new Role : ' . $role->name,
            ]);

            DB::commit();

            return redirect()->route('roles.show', ['role' => $role])
                ->with('success', 'Role created successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('roles.index')
                ->with('error', 'Failed to create a new Role. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $attributeLabels = Role::getAttributesLabel();
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

        return view('roles.show', compact(
            'role',
            'attributeLabels',
            'modulePermissions',
            'lastActivity'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $attributeLabels = Role::getAttributesLabel();
        return view('roles.edit', compact('role', 'attributeLabels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $roleData = $request->all();
        $isAllowToBeAssigne = (isset($roleData['allow_to_be_assigne']) && $roleData['allow_to_be_assigne'] == 'on');
        $roleData['allow_to_be_assigne'] = $isAllowToBeAssigne ? 1 : 0;

        $validated = $role->validate('update', $roleData);

        DB::beginTransaction();
        try {
            $role->update($validated);
            $role->createUpdatedDataLog([
                'user_description' => 'Updated ' . $role->name . ' Role.'
            ]);

            DB::commit();

            return redirect()
                ->route('roles.show', $role)
                ->with('success', 'Role updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('roles.show', $role)
                ->with('error', 'Failed to update Role. ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $name = $role->name;
            $role->delete(); // This is a soft delete
            $role->createDeletedDataLog([
                'user_description' => 'Deleted ' . $role->name . ' Role.'
            ]);

            DB::commit();

            return redirect()
                ->route('roles.index')
                ->with('success', 'Role: ' . $name . ' deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('roles.show', $role)
                ->with('error', 'Failed to delete Role. ' . $e->getMessage());
        }
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

        return view('roles.edit_permissions', compact('role', 'moduleNamebySlug', 'modulePermissions'));
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
                        dd($oldPermissions);
                        $logProperties['attributes'][$moduleSlug] = [
                            'label' => $moduleNameBySlug[$moduleSlug],
                            'old_value' => implode(', ', $oldPermissions),
                            'new_value' => implode(', ', $newPermissions)
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
                        $logProperties['attributes'][$moduleSlug] = [
                            'label' => $moduleNameBySlug[$moduleSlug],
                            'old_value' => implode(', ', $oldPermissions),
                            'new_value' => implode(', ', $newPermissions)
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
                        dd();
                        $logProperties['attributes'][$moduleSlug] = [
                            'label' => $moduleNameBySlug[$moduleSlug],
                            'old_value' => implode(', ', $oldPermissions),
                            'new_value' => implode(', ', $newPermissions)
                        ];
                    }
                }
            }
        }

        $moduleIdBySlug = Module::pluck('id', 'slug')->toArray();
        $permissionIdBySlug = Permission::pluck('id', 'slug')->toArray();

        DB::beginTransaction();
        try {

            $role->clearPermissions();

            foreach ($requestPermissions as $requestModule => $reqPermissions) {
                $moduleId = $moduleIdBySlug[$requestModule];
                foreach ($reqPermissions as $reqPermission) {
                    $permissionId = $permissionIdBySlug[$reqPermission];
                    $role->assignPermission($moduleId, $permissionId);
                }
            }

            $logActivityAttributes = array_merge(
                $role->generateUpdateLogActivityAttributes(),
                [
                    'user_description' => 'Update Role permissions for : ' . $role->name,
                    'subject_description' => 'Update Role permissions',
                    'subject_properties' => $logProperties,
                ]
            );

            $role->createUpdateRolePermissionHistoryLog($logActivityAttributes);

            DB::commit();

            return redirect()
                ->route('roles.show', $role)
                ->with('success', 'Role Permissions updated successfully.');
        } catch (Exception $e) {
            $errFullPath = $e->getFile();
            $errRelativePath = str_replace(base_path() . '/', '', $errFullPath);

            DB::rollBack();

            return redirect()
                ->route('roles.show', $role)
                ->with('error', 'Failed to update permissions. ' . $e->getMessage() . '(' . $errRelativePath . ':' . $e->getLine() . ')');
        }
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
            ->with('attributeLabels', Role::getAttributesLabel())
            ->with('orderBy', $orderBy);
    }

    /**
     * Update the specified user from role.
     */
    public function deleteUser(Role $role, User $user)
    {
        DB::beginTransaction();
        try {
            $user->unsetRole();

            $logActivityAttributes = array_merge(
                $role->generateUpdateLogActivityAttributes(),
                [
                    'user_description' =>  "Unset {$role->name} Role for {$user->name}",
                    'subject_description' => "Unset User : {$user->name}",
                ]
            );

            $role->createUnsetUserRoleHistoryLog($logActivityAttributes);

            DB::commit();

            return redirect()
                ->route('roles.show', $role)
                ->with('success', ' Successfully unset User : ' . $user->name);
        } catch (Exception $e) {
            $errFullPath = $e->getFile();
            $errRelativePath = str_replace(base_path() . '/', '', $errFullPath);

            DB::rollBack();

            return redirect()
                ->route('roles.show', $role)
                ->with('error', 'Failed to delete unset User . ' . $user->name . '.' . $e->getMessage() . '(' . $errRelativePath . ':' . $e->getLine() . ')');
        }
    }
}
