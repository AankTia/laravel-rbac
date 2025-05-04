<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    // If you want timestamps on your pivot table
    public $timestamps = true;

    protected $fillable = ['user_id', 'role_id'];
    
    /**
     * Get the user for this entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the role for this entry.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
