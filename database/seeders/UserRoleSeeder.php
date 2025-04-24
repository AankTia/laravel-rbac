<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = [
            'admin@example.com' => 'admin',
            'manager@example.com' => 'manager',
            'user@example.com' => 'user'
        ];

        foreach ($userRole as $userEmail => $roleName) {
            $user = User::firstWhere('email', $userEmail);
            $role = Role::firstWhere('name', $roleName);

            if ($user && $role) {
                $user->roles()->attach($role);
            }
        }
    }
}
