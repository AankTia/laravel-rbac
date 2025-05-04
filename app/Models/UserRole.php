<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use function Psy\debug;

class UserRole extends Model
{
    protected $table = 'user_roles';

    // If you want timestamps on your pivot table
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_by_id',
        'assigned_at'
    ];

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

    /**
     * Get the user for this entry.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class);
    }

    public function getAssignedByName()
    {
        if ($this->assignedBy) {
            return $this->assignedBy->name;
        } else {
            return null;
        }
    }

    public function getFormatedAssignedAt($format = 'd M Y, H:i')
    {
        if ($this->assigned_at instanceof Carbon) {
            return $this->assigned_at->format($format);
        } else {
            return Carbon::parse($this->assigned_at)->format($format);
        }
    }
}
