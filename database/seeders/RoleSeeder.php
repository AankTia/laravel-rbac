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
                'name' => 'Super Admin', 
                'slug' => 'superadmin', 
                'allow_to_be_assigne' => false,
                'description' => 'Full system access with all permissions'
            ],
            [
                'name' => 'Admin', 
                'slug' => 'admin', 
                'allow_to_be_assigne' => true,
                'description' => 'Administrative access with most permissions'
            ],
            [
                'name' => 'User', 
                'slug' => 'user', 
                'allow_to_be_assigne' => true,
                'description' => 'Basic access with minimal permissions'
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
