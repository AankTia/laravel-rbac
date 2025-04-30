<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'allow_to_be_assigne'];

    protected static function boot()
    {
        parent::boot();

        // Before create
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        // // Before update
        // static::updating(function ($model) {
        //     $model->validate($model->getAttributes(), $model->id);
        // });
    }

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
