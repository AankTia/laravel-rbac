<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'description' => 'Main dashboard module'],
            ['name' => 'User', 'slug' => 'user', 'description' => 'User management module'],
            ['name' => 'Role', 'slug' => 'role', 'description' => 'Role management module'],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
