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
                'slug' => 'create',
                'name' => 'Create',
                'description' => 'Create new items',
            ],
            [
                'slug' => 'read',
                'name' => 'Read',
                'description' => 'Read items',
            ],
            [
                'slug' => 'update',
                'name' => 'Update',
                'description' => 'Update existing items',
            ],
            [
                'slug' => 'delete',
                'name' => 'Delete',
                'description' => 'Delete items',
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
