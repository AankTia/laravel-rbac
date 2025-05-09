<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function permissionRoleModule()
    {
        return $this->hasMany(PermissionRoleModule::class);
    }

    // /**
    //  * Get roles that have access to this module.
    //  */
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'permission_role_module')
    //                 ->withPivot('permission_id')
    //                 ->using(PermissionRoleModule::class);
    // }

    /**
     * Get permissions for this module.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'module_permission');
    }

    // /**
    //  * Get Role permissions for this module.
    //  */
    // public function rolePermissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'permission_role_module')
    //                 ->withPivot('role_id')
    //                 ->using(PermissionRoleModule::class);
    // }

    public function assignPermission($permissionId)
    {
        ModulePermission::create([
            'module_id' => $this->id,
            'permission_id' => $permissionId
        ]);
    }

    public static function idsBySlug()
    {
        return (new self)
            ->all()
            ->pluck('id', 'slug')
            ->toArray();
    }

    public function getPermissionSlugs()
    {
        return $this->permissions()->pluck('slug')->toArray();
    }
}
