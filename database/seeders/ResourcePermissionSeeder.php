<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourcePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resourcePermission = [
            'users' => ['read', 'create', 'update', 'delete'],
            'roles' => ['read', 'create', 'update', 'delete'],
            'reports' => ['read', 'create', 'update', 'delete'],
            'dashboard' => ['read'],
        ];

        foreach ($resourcePermission as $resourceName => $permissionsName) {
            $resource = Resource::firstWhere('name', $resourceName);
            if ($resource) {
                $permissions = Permission::whereIn('name', $permissionsName)->get();

                foreach ($permissions as $permission) {
                    $permission->resources()->attach($resource);
                }
            }
        }
    }
}
