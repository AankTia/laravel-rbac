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
            ['name' => 'Users', 'slug' => 'users', 'description' => 'User management module'],
            ['name' => 'Products', 'slug' => 'products', 'description' => 'Product management module'],
            ['name' => 'Orders', 'slug' => 'orders', 'description' => 'Order management module'],
            ['name' => 'Reports', 'slug' => 'reports', 'description' => 'Reporting module'],
            ['name' => 'Settings', 'slug' => 'settings', 'description' => 'System settings module'],
        ];

        foreach ($modules as $module) {
            Module::create($module);
        }
    }
}
