<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Create', 'slug' => 'create', 'description' => 'Permission to create resources'],
            ['name' => 'Read', 'slug' => 'read', 'description' => 'Permission to view resources'],
            ['name' => 'Update', 'slug' => 'update', 'description' => 'Permission to update resources'],
            ['name' => 'Delete', 'slug' => 'delete', 'description' => 'Permission to delete resources'],
            // ['name' => 'Approve', 'slug' => 'approve', 'description' => 'Permission to approve resources'],
            // ['name' => 'Export', 'slug' => 'export', 'description' => 'Permission to export resources'],
            // ['name' => 'Import', 'slug' => 'import', 'description' => 'Permission to import resources'],
            // ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Permission to manage users'],
            // ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Permission to manage roles'],
            // ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'description' => 'Permission to manage system settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
