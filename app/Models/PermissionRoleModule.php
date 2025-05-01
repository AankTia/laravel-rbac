<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRoleModule extends Model
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
