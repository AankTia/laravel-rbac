<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissions = [
            'admin' => ['read', 'create', 'update', 'delete'],
            'manager' => ['read', 'create', 'update'],
            'user' => ['read'],
        ];


        foreach ($rolePermissions as $roleName => $permissionsName) {
            $role = Role::firstWhere('name', $roleName);
            if ($role) {
                $permissionIds = Permission::whereIn('name', $permissionsName)->pluck('id')->toArray();
                $role->permissions()->attach($permissionIds);
            }
        }
    }
}
