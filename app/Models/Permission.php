<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /** @use HasFactory<\Database\Factories\PermissionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    /**
     * Get the roles that have this permission
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Get the resources this permission applies to.
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'permission_resources');
    }

    /**
     * Assigne this permission to a resource
     */
    public function assignToResource($resource)
    {
        if (is_string($resource)) {
            $resource = Resource::whereName($resource)->firstOrFail();
        }

        $this->resources()->syncWithoutDetaching([$resource->id]);
    }
}
