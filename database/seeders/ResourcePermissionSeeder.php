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
        $userResource = Resource::where('name', 'users')->first();
        $roleResource = Resource::where('name', 'roles')->first();
        $reportResource = Resource::where('name', 'reports')->first();
        $dashboardResource = Resource::where('name', 'dashboard')->first();

        $createPermission = Permission::where('name', 'create')->first();
        $createPermission->resources()->attach($userResource);
        $createPermission->resources()->attach($roleResource);
        $createPermission->resources()->attach($reportResource);

        $readPermission = Permission::where('name', 'read')->first();
        $readPermission->resources()->attach($userResource);
        $readPermission->resources()->attach($roleResource);
        $readPermission->resources()->attach($reportResource);
        $readPermission->resources()->attach($dashboardResource);

        $updatePermission = Permission::where('name', 'update')->first();
        $updatePermission->resources()->attach($userResource);
        $updatePermission->resources()->attach($roleResource);
        $updatePermission->resources()->attach($reportResource);

        $deletePermission = Permission::where('name', 'delete')->first();
        $deletePermission->resources()->attach($userResource);
        $deletePermission->resources()->attach($roleResource);
        $deletePermission->resources()->attach($reportResource);
    }
}
