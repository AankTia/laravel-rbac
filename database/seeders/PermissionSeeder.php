<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModulePermission;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createPermissions();
        $permissionsIdBySlug = Permission::pluck('id', 'slug');

        $this->assignModulePermissions($permissionsIdBySlug);
        $this->assignPermissionRoleModule($permissionsIdBySlug);
    }

    private function createPermissions()
    {
        $this->command->info('   Creating Permissions!');

        $permissions = [
            ['name' => 'Read', 'slug' => 'read', 'description' => 'Permission to view resources'],
            ['name' => 'Create', 'slug' => 'create', 'description' => 'Permission to create resources'],
            ['name' => 'Update', 'slug' => 'update', 'description' => 'Permission to update resources'],
            ['name' => 'Delete', 'slug' => 'delete', 'description' => 'Permission to delete resources'],
            ['name' => 'Activate', 'slug' => 'activate', 'description' => 'Permission to activate resources'],
            ['name' => 'Deactivate', 'slug' => 'deactivate', 'description' => 'Permission to deactivate resources'],
            ['name' => 'Update Role Permission', 'slug' => 'update-role-permissions', 'description' => 'Permission to update Role Permissions resources'],
            ['name' => 'Read Activity Logs', 'slug' => 'read-activity-log', 'description' => 'Permission to read Activity Logs resources'],
            ['name' => 'Delete User', 'slug' => 'delete-user', 'description' => 'Permission to delete user'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('   Permissions seeded successfully!');
        $this->command->info('   ---');
    }

    private function assignModulePermissions($permissionsIdBySlug)
    {
        $this->command->info('   Mapping Module Permissions!');

        $modulePermissions = [
            'dashboard' => ['read'],
            'user' => ['read', 'create', 'update', 'delete', 'activate', 'deactivate', 'read-activity-log'],
            'role' => ['read', 'create', 'update', 'delete', 'update-role-permissions', 'read-activity-log', 'delete-user']
        ];

        foreach ($modulePermissions as $modulSlug => $permissionSlugs) {
            $module = Module::where('slug', $modulSlug)->first();
            foreach ($permissionSlugs as $permissionSlug) {
                $module->assignPermission($permissionsIdBySlug[$permissionSlug]);
            }
        }

        $this->command->info('   Module Permissions mapped successfully!');
        $this->command->info('   ---');
    }

    private function assignPermissionRoleModule($permissionsIdBySlug)
    {
        $this->command->info('   Mapping Role Module Permissions!');

        // Superadmin has all permissions for all modules
        $superadminRole = Role::where('slug', 'superadmin')->first();
        $allModulePermission = ModulePermission::all();
        foreach ($allModulePermission as $modulePermission) {
            $superadminRole->assignPermission($modulePermission->module_id, $modulePermission->permission_id);
        }

        $rolePermissionData = [
            'admin' => [
                'dashboard' => ['read'],
                'user' => ['read', 'create', 'update', 'activate', 'deactivate', 'read-activity-log'],
                'role' => ['read', 'update-role-permissions', 'read-activity-log']
            ],
            'viewer' => [
                'dashboard' => ['read'],
                'user' => ['read']
            ]
        ];

        $modulesIdBySlug = Module::pluck('id', 'slug');
        foreach ($rolePermissionData as $roleSlug => $modulePermissions) {
            $role = Role::where('slug', $roleSlug)->first();

            foreach ($modulePermissions as $moduleSlug => $permissionSlugs) {
                $moduleId = $modulesIdBySlug[$moduleSlug];
                
                foreach ($permissionSlugs as $permissionSlug) {
                    $role->assignPermission($moduleId, $permissionsIdBySlug[$permissionSlug]);
                }
            }
        }

        $this->command->info('   Role Module Permissions mapped successfully!');
        $this->command->info('   ---');
    }
}
