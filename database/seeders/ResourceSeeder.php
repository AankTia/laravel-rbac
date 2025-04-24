<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            [
                'name' => 'users',
                'resource_type' => 'model',
                'description' => 'User management',
            ],
            [
                'name' => 'roles',
                'resource_type' => 'model',
                'description' => 'Role management',
            ],
            [
                'name' => 'reports',
                'resource_type' => 'page',
                'description' => 'System reports',
            ],
            [
                'name' => 'dashboard',
                'resource_type' => 'page',
                'description' => 'Dashboard',
            ]
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
}
