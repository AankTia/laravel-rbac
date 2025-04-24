<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator with full system access',
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Manager with limited administrative access',
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Regular user with basic access',
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
