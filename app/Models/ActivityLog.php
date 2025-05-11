<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    const UPDATED_AT = null; // disable updated_at

    /**
     * log_name => create, update, delete, activate, deactivate
     */

    protected $fillable = [
        'log_name',
        'action',
        'user_id',
        'user_description',
        'user_properties',
        'subject_description',
        'subject_properties'
    ];

    protected $casts = [
        'user_properties' => 'array',
        'subject_properties' => 'array'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function getActionIcon() // need move to helper
    // {
    //     $result = '';

    //     switch ($this->action) {
    //         case 'created':
    //             $result = getIcon('create');
    //             break;
    //         case 'updated':
    //             $result = getIcon('edit');
    //             break;
    //         case 'role-permission-updated';
    //             $result = getIcon('lock');
    //             break;
    //         case 'deleted':
    //             $result = getIcon('delete');
    //             break;
    //         case 'activated':
    //             $result = getIcon('activate');
    //             break;
    //         case 'deactivated':
    //             $result = getIcon('deactivate');
    //             break;

    //         case 'delete-user':
    //             $result = getIcon('delete-user');
    //             break;
    //         default:
    //             $result = '';
    //     }

    //     return $result;
    // }

    // public function getActionBackgroundColor() // need move to helper
    // {

    //     $result = '';

    //     switch ($this->action) {
    //         case 'created':
    //             $result = 'bg-primary';
    //             break;
    //         case 'updated':
    //             $result = 'bg-warning';
    //             break;
    //         case 'role-permission-updated';
    //             $result = 'bg-warning';
    //             break;
    //         case 'deleted':
    //             $result = 'bg-danger';
    //             break;
    //         case 'activated':
    //             $result = 'bg-success';
    //             break;
    //         case 'deactivated':
    //             $result = 'bg-danger';
    //             break;
    //         case 'delete-user':
    //             $result = 'bg-danger';
    //             break;
    //         default:
    //             $result = '';
    //     }

    //     return $result;
    // }

    // public function getActionTextColor() // unused
    // {
    //     $result = '';

    //     switch ($this->action) {
    //         case 'created':
    //             $result = 'text-primary';
    //             break;
    //         case 'updated':
    //             $result = 'text-warning';
    //             break;
    //         case 'deleted':
    //             $result = 'text-danger';
    //             break;
    //         case 'activated':
    //             $result = 'text-success';
    //             break;
    //         case 'deactivated':
    //             $result = 'text-danger';
    //             break;
    //         default:
    //             $result = '';
    //     }

    //     return $result;
    // }

    // public function isCreated()
    // {
    //     return $this->action == 'created';
    // }

    // public function isUpdated()
    // {
    //     return $this->action == 'updated';
    // }

    // public function isRoleLog()
    // {
    //     return $this->log_name === 'Role';
    // }
}
