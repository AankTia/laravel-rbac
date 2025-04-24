<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    /** @use HasFactory<\Database\Factories\ResourceFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'resource_type',
        'description'
    ];

    /** Get the permissions for this resource. */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_resources');
    }
}
