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
            [
                'name' => 'create',
                'display_name' => 'Create',
                'description' => 'Create new items',
            ],
            [
                'name' => 'read',
                'display_name' => 'Read',
                'description' => 'Read items',
            ],
            [
                'name' => 'update',
                'display_name' => 'Update',
                'description' => 'Update existing items',
            ],
            [
                'name' => 'delete',
                'display_name' => 'Delete',
                'description' => 'Delete items',
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
