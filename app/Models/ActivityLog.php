<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'log_name',
        'user_id',
        'action',
        'description',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionIcon() // need move to helper
    {
        $result = '';

        switch ($this->action) {
            case 'created':
                $result = createIcon();
                break;
            case 'updated':
                $result = updateIcon();
                break;
            case 'role-permission-updated';
                $result = lockIcon();
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

    public function getActionBackgroundColor() // need move to helper
    {

        $result = '';

        switch ($this->action) {
            case 'created':
                $result = 'bg-primary';
                break;
            case 'updated':
                $result = 'bg-warning';
                break;
            case 'role-permission-updated';
                $result = 'bg-warning';
                break;
            case 'deleted':
                $result = 'bg-danger';
                break;
            case 'activated':
                $result = 'bg-success';
                break;
            case 'deactivated':
                $result = 'bg-danger';
                break;
            default:
                $result = '';
        }

        return $result;
    }

    public function getActionTextColor() // unused
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

    public function isCreated()
    {
        return $this->action == 'created';
    }

    public function isUpdated()
    {
        return $this->action == 'updated';
    }

    public function isRoleLog()
    {
        return $this->log_name === 'Role';
    }
}
