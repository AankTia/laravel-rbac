<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        // module ==> polymorphic
        // old_value
        // new_value
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
