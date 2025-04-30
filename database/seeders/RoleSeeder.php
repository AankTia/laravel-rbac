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
            ['name' => 'Super Admin', 'slug' => 'superadmin', 'description' => 'Full system access with all permissions'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrative access with most permissions'],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Management access with limited permissions'],
            ['name' => 'Staff', 'slug' => 'staff', 'description' => 'Basic staff access with minimal permissions'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
