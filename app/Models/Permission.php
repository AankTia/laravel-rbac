<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

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
