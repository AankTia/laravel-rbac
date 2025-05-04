<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            [
                'user' => [
                    'name' => 'Super Admin User',
                    'email' => 'superadmin@example.com',
                    'password' => Hash::make('password'),
                ],
                'role_id' => Role::where('slug', 'superadmin')->first()->id
            ],
            [
                'user' => [
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password'),
                ],
                'role_id' => Role::where('slug', 'admin')->first()->id
            ],
            [
                'user' => [
                    'name' => 'User',
                    'email' => 'user@example.com',
                    'password' => Hash::make('password'),
                ],
                'role_id' => Role::where('slug', 'viewer')->first()->id
            ],
        ];

        foreach ($usersData as $userData) {
            $user = User::create($userData['user']);
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $userData['role_id']
            ]);
        }
    }
}
