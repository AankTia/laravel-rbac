<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        // 'ip_address',
        // 'user_agent',
        // module ==> polymorphic
        // old_value
        // new_value
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionIcon()
    {
        $result = '';

        switch ($this->action) {
            case 'created':
                $result = createIcon();
                break;
            case 'updated':
                $result = updateIcon();
                break;
            case 'deleted':
                $result = deleteIcon();
                break;
            case 'activated':
                $result = activateIcon();
                break;
            case 'deactivated':
                $result = deactivateIcon();
                break;
            default:
                $result = '';
        }

        return $result;
    }

    public function getActionTextColor()
    {
        $result = '';

        switch ($this->action) {
            case 'created':
                $result = 'text-primary';
                break;
            case 'updated':
                $result = 'text-warning';
                break;
            case 'deleted':
                $result = 'text-danger';
                break;
            case 'activated':
                $result = 'text-success';
                break;
            case 'deactivated':
                $result = 'text-danger';
                break;
            default:
                $result = '';
        }

        return $result;
    }
}
