<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

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
    public function RolePermissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role_module')
                    ->withPivot('role_id')
                    ->using(PermissionRoleModule::class);
    }

    public function assignPermission($permissionId) {
        ModulePermission::create([
            'module_id' => $this->id,
            'permission_id' => $permissionId
        ]);
    }
}
