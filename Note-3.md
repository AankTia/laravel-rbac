I'll expand the permission system to include specific roles: superadmin, admin, manager, and staff. I'll create a new seeder that implements these roles with appropriate permissions.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Module;
use App\Models\PermissionUserModule;
use App\Models\Role; // We'll add a role model
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'superadmin', 'description' => 'Full system access with all permissions'],
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrative access with most permissions'],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Management access with limited permissions'],
            ['name' => 'Staff', 'slug' => 'staff', 'description' => 'Basic staff access with minimal permissions'],
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        // Create Users
        $users = [
            [
                'name' => 'Super Admin User',
                'email' => 'superadmin@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'superadmin')->first()->id
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'admin')->first()->id
            ],
            [
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'manager')->first()->id
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::where('slug', 'staff')->first()->id
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Create Permissions
        $permissions = [
            ['name' => 'Create', 'slug' => 'create', 'description' => 'Permission to create resources'],
            ['name' => 'Read', 'slug' => 'read', 'description' => 'Permission to view resources'],
            ['name' => 'Update', 'slug' => 'update', 'description' => 'Permission to update resources'],
            ['name' => 'Delete', 'slug' => 'delete', 'description' => 'Permission to delete resources'],
            ['name' => 'Approve', 'slug' => 'approve', 'description' => 'Permission to approve resources'],
            ['name' => 'Export', 'slug' => 'export', 'description' => 'Permission to export resources'],
            ['name' => 'Import', 'slug' => 'import', 'description' => 'Permission to import resources'],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'description' => 'Permission to manage users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Permission to manage roles'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'description' => 'Permission to manage system settings'],
        ];

        foreach ($permissions as $permissionData) {
            Permission::create($permissionData);
        }

        // Create Modules
        $modules = [
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'description' => 'Main dashboard module'],
            ['name' => 'Users', 'slug' => 'users', 'description' => 'User management module'],
            ['name' => 'Roles', 'slug' => 'roles', 'description' => 'Role management module'],
            ['name' => 'Products', 'slug' => 'products', 'description' => 'Product management module'],
            ['name' => 'Orders', 'slug' => 'orders', 'description' => 'Order management module'],
            ['name' => 'Invoices', 'slug' => 'invoices', 'description' => 'Invoice management module'],
            ['name' => 'Reports', 'slug' => 'reports', 'description' => 'Reporting module'],
            ['name' => 'Settings', 'slug' => 'settings', 'description' => 'System settings module'],
            ['name' => 'Audit Logs', 'slug' => 'audit-logs', 'description' => 'System audit logs module'],
        ];

        foreach ($modules as $moduleData) {
            Module::create($moduleData);
        }

        // Get users
        $superadmin = User::where('email', 'superadmin@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $manager = User::where('email', 'manager@example.com')->first();
        $staff = User::where('email', 'staff@example.com')->first();

        // Get all permissions and modules for easy reference
        $createPermission = Permission::where('slug', 'create')->first();
        $readPermission = Permission::where('slug', 'read')->first();
        $updatePermission = Permission::where('slug', 'update')->first();
        $deletePermission = Permission::where('slug', 'delete')->first();
        $approvePermission = Permission::where('slug', 'approve')->first();
        $exportPermission = Permission::where('slug', 'export')->first();
        $importPermission = Permission::where('slug', 'import')->first();
        $manageUsersPermission = Permission::where('slug', 'manage-users')->first();
        $manageRolesPermission = Permission::where('slug', 'manage-roles')->first();
        $manageSettingsPermission = Permission::where('slug', 'manage-settings')->first();

        // Get all modules
        $allModules = Module::all();
        $dashboardModule = Module::where('slug', 'dashboard')->first();
        $usersModule = Module::where('slug', 'users')->first();
        $rolesModule = Module::where('slug', 'roles')->first();
        $productsModule = Module::where('slug', 'products')->first();
        $ordersModule = Module::where('slug', 'orders')->first();
        $invoicesModule = Module::where('slug', 'invoices')->first();
        $reportsModule = Module::where('slug', 'reports')->first();
        $settingsModule = Module::where('slug', 'settings')->first();
        $auditLogsModule = Module::where('slug', 'audit-logs')->first();

        // Get all permissions
        $allPermissions = Permission::all();
        
        // 1. SUPERADMIN PERMISSIONS
        // Superadmin has all permissions for all modules
        foreach ($allModules as $module) {
            foreach ($allPermissions as $permission) {
                $this->assignPermission($superadmin->id, $permission->id, $module->id);
            }
        }
        
        // 2. ADMIN PERMISSIONS
        // Admin has all basic CRUD permissions for all modules
        $adminModules = $allModules->except([$auditLogsModule->id]);
        foreach ($adminModules as $module) {
            $this->assignPermission($admin->id, $createPermission->id, $module->id);
            $this->assignPermission($admin->id, $readPermission->id, $module->id);
            $this->assignPermission($admin->id, $updatePermission->id, $module->id);
            $this->assignPermission($admin->id, $deletePermission->id, $module->id);
            $this->assignPermission($admin->id, $approvePermission->id, $module->id);
            $this->assignPermission($admin->id, $exportPermission->id, $module->id);
            $this->assignPermission($admin->id, $importPermission->id, $module->id);
        }
        
        // Admin can manage users but not roles
        $this->assignPermission($admin->id, $manageUsersPermission->id, $usersModule->id);
        
        // Admin can access but not modify settings
        $this->assignPermission($admin->id, $readPermission->id, $settingsModule->id);
        
        // Admin can access audit logs but cannot modify them
        $this->assignPermission($admin->id, $readPermission->id, $auditLogsModule->id);
        
        // 3. MANAGER PERMISSIONS
        // Manager has access to dashboard
        $this->assignPermission($manager->id, $readPermission->id, $dashboardModule->id);
        
        // Manager has CRUD for products, orders, and invoices
        foreach ([$productsModule, $ordersModule, $invoicesModule] as $module) {
            $this->assignPermission($manager->id, $createPermission->id, $module->id);
            $this->assignPermission($manager->id, $readPermission->id, $module->id);
            $this->assignPermission($manager->id, $updatePermission->id, $module->id);
            $this->assignPermission($manager->id, $deletePermission->id, $module->id);
            $this->assignPermission($manager->id, $approvePermission->id, $module->id);
        }
        
        // Manager can read and export reports
        $this->assignPermission($manager->id, $readPermission->id, $reportsModule->id);
        $this->assignPermission($manager->id, $exportPermission->id, $reportsModule->id);
        
        // Manager can read user information but not modify
        $this->assignPermission($manager->id, $readPermission->id, $usersModule->id);
        
        // 4. STAFF PERMISSIONS
        // Staff has access to dashboard
        $this->assignPermission($staff->id, $readPermission->id, $dashboardModule->id);
        
        // Staff has read access to products
        $this->assignPermission($staff->id, $readPermission->id, $productsModule->id);
        
        // Staff has create and read access to orders
        $this->assignPermission($staff->id, $createPermission->id, $ordersModule->id);
        $this->assignPermission($staff->id, $readPermission->id, $ordersModule->id);
        $this->assignPermission($staff->id, $updatePermission->id, $ordersModule->id);
        
        // Staff has read access to invoices
        $this->assignPermission($staff->id, $readPermission->id, $invoicesModule->id);
        
        // Staff has read access to basic reports
        $this->assignPermission($staff->id, $readPermission->id, $reportsModule->id);

        $this->command->info('Role and Permission system seeded successfully!');
    }

    /**
     * Assign a permission to a user for a specific module
     *
     * @param int $userId
     * @param int $permissionId
     * @param int $moduleId
     * @return void
     */
    private function assignPermission($userId, $permissionId, $moduleId)
    {
        PermissionUserModule::create([
            'user_id' => $userId,
            'permission_id' => $permissionId,
            'module_id' => $moduleId,
        ]);
    }
}
```

Let's also create a migration and model for the Role table:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Add role_id to users table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        
        Schema::dropIfExists('roles');
    }
}
```

And let's create the Role model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get the users that belong to this role.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```

Update the User model to include the role relationship:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get user permissions for modules.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user_module')
                    ->withPivot('module_id')
                    ->using(PermissionUserModule::class);
    }

    /**
     * Get user modules with permissions.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'permission_user_module')
                    ->withPivot('permission_id')
                    ->using(PermissionUserModule::class);
    }

    /**
     * Get all user module permissions.
     */
    public function modulePermissions()
    {
        return $this->hasMany(PermissionUserModule::class);
    }

    /**
     * Check if user has a specific permission for a module.
     *
     * @param string $permissionSlug
     * @param string $moduleSlug
     * @return bool
     */
    public function hasPermission($permissionSlug, $moduleSlug)
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        $module = Module::where('slug', $moduleSlug)->first();
        
        if (!$permission || !$module) {
            return false;
        }
        
        return $this->modulePermissions()
            ->where('permission_id', $permission->id)
            ->where('module_id', $module->id)
            ->exists();
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole($roleSlug)
    {
        return $this->role && $this->role->slug === $roleSlug;
    }

    /**
     * Check if user is super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is manager.
     *
     * @return bool
     */
    public function isManager()
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user is staff.
     *
     * @return bool
     */
    public function isStaff()
    {
        return $this->hasRole('staff');
    }
}
```

Here's a breakdown of the permissions by role:

### 1. Superadmin
- Has **all permissions** for **all modules**
- Exclusive access to sensitive areas like audit logs and system settings

### 2. Admin
- Has **full CRUD access** to all modules except audit logs
- Can manage users but not roles
- Can view but not modify system settings
- Can view but not modify audit logs

### 3. Manager
- Has **full CRUD access** to products, orders, and invoices
- Can read and export reports
- Can view user information but not modify
- Has dashboard access

### 4. Staff
- Has **read-only access** to products
- Has create, read, and update access to orders
- Has read-only access to invoices
- Has read-only access to basic reports
- Has dashboard access

To check permissions in your controllers or views, you can use the helper methods like:

```php
// Check if user has specific permission for a module
if ($user->hasPermission('create', 'orders')) {
    // User can create orders
}

// Check user role
if ($user->isAdmin()) {
    // User is an admin
}

// Check for specific role
if ($user->hasRole('manager')) {
    // User has manager role
}
```

This approach combines role-based access control with more granular permission-based access control at the module level.