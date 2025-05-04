<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    protected $table = 'module_permission';

    // If you want timestamps on your pivot table
    public $timestamps = true;

    protected $fillable = ['module_id', 'permission_id'];
    
    /**
     * Get the module for this entry.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    /**
     * Get the permission for this entry.
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
