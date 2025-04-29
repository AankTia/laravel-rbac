I'll redesign the system to map modules and permissions to roles instead of users. Then users will only have a relationship with roles. This approach is more common in role-based access control systems and easier to manage.

Here's the updated schema and seeder:

## 1. Database Schema

First, let's update our database schema:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionSystemTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create modules table
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create pivot table for role-permission-module
        Schema::create('permission_role_module', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('module_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            
            // Ensure unique combination
            $table->unique(['role_id', 'permission_id', 'module_id']);
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
        
        Schema::dropIfExists('permission_role_module');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
}
```

## 2. Models

Now let's update our models to reflect this structure:

### Role Model:

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

    /**
     * Get permissions for this role with their modules.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role_module')
                    ->withPivot('module_id')
                    ->using(PermissionRoleModule::class);
    }

    /**
     * Get modules for this role with their permissions.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'permission_role_module')
                    ->withPivot('permission_id')
                    ->using(PermissionRoleModule::class);
    }

    /**
     * Get all role module permissions.
     */
    public function modulePermissions()
    {
        return $this->hasMany(PermissionRoleModule::class);
    }

    /**
     * Check if role has a specific permission for a module.
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
}
```

### Permission Model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get roles that have this permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role_module')
                    ->withPivot('module_id')
                    ->using(PermissionRoleModule::class);
    }

    /**
     * Get modules that have this permission.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'permission_role_module')
                    ->withPivot('role_id')
                    ->using(PermissionRoleModule::class);
    }
}
```

### Module Model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get roles that have access to this module.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role_module')
                    ->withPivot('permission_id')
                    ->using(PermissionRoleModule::class);
    }

    /**
     * Get permissions for this module.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role_module')
                    ->withPivot('role_id')
                    ->using(PermissionRoleModule::class);
    }
}
```

### Pivot Model:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRoleModule extends Pivot
{
    protected $table = 'permission_role_module';
    
    // If you want timestamps on your pivot table
    public $timestamps = true;
    
    protected $fillable = ['role_id', 'permission_id', 'module_id'];

    /**
     * Get the role that owns this permission.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Get the permission for this entry.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
    
    /**
     * Get the module for this entry.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
```

### User Model:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

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
     * Check if user has a specific permission for a module.
     *
     * @param string $permissionSlug
     * @param string $moduleSlug
     * @return bool
     */
    public function hasPermission($permissionSlug, $moduleSlug)
    {
        return $this->role && $this->role->hasPermission($permissionSlug, $moduleSlug);
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

## 3. Seeder

Now let's create a seeder for this updated structure:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use App\Models\Module;
use App\Models\Role;
use App\Models\PermissionRoleModule;
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

        // Get roles
        $superadminRole = Role::where('slug', 'superadmin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $staffRole = Role::where('slug', 'staff')->first();

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
        
        // 1. SUPERADMIN ROLE PERMISSIONS
        // Superadmin has all permissions for all modules
        foreach ($allModules as $module) {
            foreach ($allPermissions as $permission) {
                $this->assignPermission($superadminRole->id, $permission->id, $module->id);
            }
        }
        
        // 2. ADMIN ROLE PERMISSIONS
        // Admin has all basic CRUD permissions for all modules
        $adminModules = $allModules->except([$auditLogsModule->id]);
        foreach ($adminModules as $module) {
            $this->assignPermission($adminRole->id, $createPermission->id, $module->id);
            $this->assignPermission($adminRole->id, $readPermission->id, $module->id);
            $this->assignPermission($adminRole->id, $updatePermission->id, $module->id);
            $this->assignPermission($adminRole->id, $deletePermission->id, $module->id);
            $this->assignPermission($adminRole->id, $approvePermission->id, $module->id);
            $this->assignPermission($adminRole->id, $exportPermission->id, $module->id);
            $this->assignPermission($adminRole->id, $importPermission->id, $module->id);
        }
        
        // Admin can manage users but not roles
        $this->assignPermission($adminRole->id, $manageUsersPermission->id, $usersModule->id);
        
        // Admin can access but not modify settings
        $this->assignPermission($adminRole->id, $readPermission->id, $settingsModule->id);
        
        // Admin can access audit logs but cannot modify them
        $this->assignPermission($adminRole->id, $readPermission->id, $auditLogsModule->id);
        
        // 3. MANAGER ROLE PERMISSIONS
        // Manager has access to dashboard
        $this->assignPermission($managerRole->id, $readPermission->id, $dashboardModule->id);
        
        // Manager has CRUD for products, orders, and invoices
        foreach ([$productsModule, $ordersModule, $invoicesModule] as $module) {
            $this->assignPermission($managerRole->id, $createPermission->id, $module->id);
            $this->assignPermission($managerRole->id, $readPermission->id, $module->id);
            $this->assignPermission($managerRole->id, $updatePermission->id, $module->id);
            $this->assignPermission($managerRole->id, $deletePermission->id, $module->id);
            $this->assignPermission($managerRole->id, $approvePermission->id, $module->id);
        }
        
        // Manager can read and export reports
        $this->assignPermission($managerRole->id, $readPermission->id, $reportsModule->id);
        $this->assignPermission($managerRole->id, $exportPermission->id, $reportsModule->id);
        
        // Manager can read user information but not modify
        $this->assignPermission($managerRole->id, $readPermission->id, $usersModule->id);
        
        // 4. STAFF ROLE PERMISSIONS
        // Staff has access to dashboard
        $this->assignPermission($staffRole->id, $readPermission->id, $dashboardModule->id);
        
        // Staff has read access to products
        $this->assignPermission($staffRole->id, $readPermission->id, $productsModule->id);
        
        // Staff has create and read access to orders
        $this->assignPermission($staffRole->id, $createPermission->id, $ordersModule->id);
        $this->assignPermission($staffRole->id, $readPermission->id, $ordersModule->id);
        $this->assignPermission($staffRole->id, $updatePermission->id, $ordersModule->id);
        
        // Staff has read access to invoices
        $this->assignPermission($staffRole->id, $readPermission->id, $invoicesModule->id);
        
        // Staff has read access to basic reports
        $this->assignPermission($staffRole->id, $readPermission->id, $reportsModule->id);

        $this->command->info('Role and Permission system seeded successfully!');
    }

    /**
     * Assign a permission to a role for a specific module
     *
     * @param int $roleId
     * @param int $permissionId
     * @param int $moduleId
     * @return void
     */
    private function assignPermission($roleId, $permissionId, $moduleId)
    {
        PermissionRoleModule::create([
            'role_id' => $roleId,
            'permission_id' => $permissionId,
            'module_id' => $moduleId,
        ]);
    }
}
```

## 4. Using the System

Here's how you can use this system in your application:

### Middleware to Check Permissions

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @param  string  $module
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $module)
    {
        if (!$request->user() || !$request->user()->hasPermission($permission, $module)) {
            return redirect('/')->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
```

Register this middleware in your `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... other middlewares
    'permission' => \App\Http\Middleware\CheckPermission::class,
];
```

### Using the Middleware in Routes

```php
// Only users with create permission for orders module can access this route
Route::get('/orders/create', 'OrderController@create')->middleware('permission:create,orders');

// Only users with read permission for reports module can access this route
Route::get('/reports', 'ReportController@index')->middleware('permission:read,reports');
```

### Using in Controllers

```php
public function update(Request $request, $id)
{
    $user = Auth::user();
    
    if (!$user->hasPermission('update', 'products')) {
        return redirect()->back()->with('error', 'You do not have permission to update products.');
    }
    
    // Continue with the update
}
```

### Using in Blade Templates

```php
@if(auth()->user()->hasPermission('create', 'orders'))
    <a href="{{ route('orders.create') }}" class="btn btn-primary">Create Order</a>
@endif

@if(auth()->user()->hasPermission('manage-users', 'users'))
    <a href="{{ route('users.index') }}" class="btn btn-primary">Manage Users</a>
@endif
```

## 5. Role Management

Here's a simple controller for role management:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Module;
use App\Models\PermissionRoleModule;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        $modules = Module::all();
        return view('roles.create', compact('permissions', 'modules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*.permission_id' => 'required|exists:permissions,id',
            'permissions.*.module_id' => 'required|exists:modules,id',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Assign permissions to the role
        foreach ($request->permissions as $permission) {
            PermissionRoleModule::create([
                'role_id' => $role->id,
                'permission_id' => $permission['permission_id'],
                'module_id' => $permission['module_id'],
            ]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $modulePermissions = PermissionRoleModule::where('role_id', $role->id)
            ->with(['permission', 'module'])
            ->get();
            
        return view('roles.show', compact('role', 'modulePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $modules = Module::all();
        $rolePermissions = PermissionRoleModule::where('role_id', $role->id)
            ->get()
            ->map(function ($item) {
                return [
                    'permission_id' => $item->permission_id,
                    'module_id' => $item->module_id,
                ];
            })
            ->toArray();
            
        return view('roles.edit', compact('role', 'permissions', 'modules', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'required|array',
            'permissions.*.permission_id' => 'required|exists:permissions,id',
            'permissions.*.module_id' => 'required|exists:modules,id',
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        // Remove all existing permissions
        PermissionRoleModule::where('role_id', $role->id)->delete();

        // Assign new permissions to the role
        foreach ($request->permissions as $permission) {
            PermissionRoleModule::create([
                'role_id' => $role->id,
                'permission_id' => $permission['permission_id'],
                'module_id' => $permission['module_id'],
            ]);
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Role cannot be deleted because it has users assigned.');
        }

        // Delete all permissions for this role
        PermissionRoleModule::where('role_id', $role->id)->delete();
        
        // Delete the role
        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
```

## Summary

This approach provides several advantages:

1. **Simplified User Management**: Users are only associated with roles, not individual permissions.
2. **Better Role-Based Access Control**: Permissions are managed at the role level.
3. **Module-Level Granularity**: Permissions are specific to modules.
4. **Easy Permission Checking**: Using the `hasPermission` method.
5. **Scalable**: Easy to add new roles, permissions, and modules.

The system has four predefined roles:
- **Super Admin**: Full access to all modules and permissions
- **Admin**: Almost full access except for audit logs management
- **Manager**: Access to operational modules like products, orders, and invoices
- **Staff**: Limited access to basic functionality

This structure is more maintainable and follows standard role-based access control patterns.