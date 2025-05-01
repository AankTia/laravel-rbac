<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionRoleModule;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $superadminRole = Role::where('slug', 'superadmin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $staffRole = Role::where('slug', 'staff')->first();

        // Get all permissions and modules for easy reference
        $createPermission = Permission::where('slug', 'create')->first();
        $readPermission = Permission::where('slug', 'read')->first();
        $updatePermission = Permission::where('slug', 'update')->first();
        $deletePermission = Permission::where('slug', 'delete')->first();
        $approvePermission = Permission::where('slug', 'approve')->first();
        $exportPermission = Permission::where('slug', 'export')->first();
        $importPermission = Permission::where('slug', 'import')->first();
        $manageUsersPermission = Permission::where('slug', 'manage-users')->first();
        $manageRolesPermission = Permission::where('slug', 'manage-roles')->first();
        $manageSettingsPermission = Permission::where('slug', 'manage-settings')->first();

        // Get all modules
        $allModules = Module::all();
        $dashboardModule = Module::where('slug', 'dashboard')->first();
        $usersModule = Module::where('slug', 'users')->first();
        $rolesModule = Module::where('slug', 'roles')->first();
        $productsModule = Module::where('slug', 'products')->first();
        $ordersModule = Module::where('slug', 'orders')->first();
        $invoicesModule = Module::where('slug', 'invoices')->first();
        $reportsModule = Module::where('slug', 'reports')->first();
        $settingsModule = Module::where('slug', 'settings')->first();
        $auditLogsModule = Module::where('slug', 'audit-logs')->first();

        // Get all permissions
        $allPermissions = Permission::all();
        
        // 1. SUPERADMIN ROLE PERMISSIONS
        // Superadmin has all permissions for all modules
        foreach ($allModules as $module) {
            foreach ($allPermissions as $permission) {
                $this->assignPermission($superadminRole->id, $permission->id, $module->id);
            }
        }
        
        // // 2. ADMIN ROLE PERMISSIONS
        // // Admin has all basic CRUD permissions for all modules
        // $adminModules = $allModules->except([$auditLogsModule->id]);
        // foreach ($adminModules as $module) {
        //     $this->assignPermission($adminRole->id, $createPermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $readPermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $updatePermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $deletePermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $approvePermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $exportPermission->id, $module->id);
        //     $this->assignPermission($adminRole->id, $importPermission->id, $module->id);
        // }
        
        // // Admin can manage users but not roles
        // $this->assignPermission($adminRole->id, $manageUsersPermission->id, $usersModule->id);
        
        // // Admin can access but not modify settings
        // $this->assignPermission($adminRole->id, $readPermission->id, $settingsModule->id);
        
        // // Admin can access audit logs but cannot modify them
        // $this->assignPermission($adminRole->id, $readPermission->id, $auditLogsModule->id);
        
        // // 3. MANAGER ROLE PERMISSIONS
        // // Manager has access to dashboard
        // $this->assignPermission($managerRole->id, $readPermission->id, $dashboardModule->id);
        
        // // Manager has CRUD for products, orders, and invoices
        // foreach ([$productsModule, $ordersModule, $invoicesModule] as $module) {
        //     $this->assignPermission($managerRole->id, $createPermission->id, $module->id);
        //     $this->assignPermission($managerRole->id, $readPermission->id, $module->id);
        //     $this->assignPermission($managerRole->id, $updatePermission->id, $module->id);
        //     $this->assignPermission($managerRole->id, $deletePermission->id, $module->id);
        //     $this->assignPermission($managerRole->id, $approvePermission->id, $module->id);
        // }
        
        // // Manager can read and export reports
        // $this->assignPermission($managerRole->id, $readPermission->id, $reportsModule->id);
        // $this->assignPermission($managerRole->id, $exportPermission->id, $reportsModule->id);
        
        // // Manager can read user information but not modify
        // $this->assignPermission($managerRole->id, $readPermission->id, $usersModule->id);
        
        // // 4. STAFF ROLE PERMISSIONS
        // // Staff has access to dashboard
        // $this->assignPermission($staffRole->id, $readPermission->id, $dashboardModule->id);
        
        // // Staff has read access to products
        // $this->assignPermission($staffRole->id, $readPermission->id, $productsModule->id);
        
        // // Staff has create and read access to orders
        // $this->assignPermission($staffRole->id, $createPermission->id, $ordersModule->id);
        // $this->assignPermission($staffRole->id, $readPermission->id, $ordersModule->id);
        // $this->assignPermission($staffRole->id, $updatePermission->id, $ordersModule->id);
        
        // // Staff has read access to invoices
        // $this->assignPermission($staffRole->id, $readPermission->id, $invoicesModule->id);
        
        // // Staff has read access to basic reports
        // $this->assignPermission($staffRole->id, $readPermission->id, $reportsModule->id);

        $this->command->info('Role and Permission system seeded successfully!');
    }

    /**
     * Assign a permission to a role for a specific module
     *
     * @param int $roleId
     * @param int $permissionId
     * @param int $moduleId
     * @return void
     */
    private function assignPermission($roleId, $permissionId, $moduleId)
    {
        PermissionRoleModule::create([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'module_id' => $moduleId,
        ]);
    }
}
