<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\TimestampAndUserTrackingTrait;
use App\Traits\LogsActivity;
use App\Traits\TracksChanges;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TimestampAndUserTrackingTrait, LogsActivity, TracksChanges;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // 'role_id',
        'is_active',
        'created_by_id',
        'last_updated_by_id'
    ];

    protected static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        // 'role_id' => 'required|exists:roles,id',
        'is_active' => 'required|boolean'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function validate($action, $data)
    {
        $rules = static::$rules;
        $isUpdate = $action == 'update';

        // If updating, ignore unique constraint for current user
        if ($isUpdate && isset($this->id)) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->id;
            $rules['password'] = 'nullable|min:8'; // Optional on update
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public function userRole()
    {
        return $this->hasOne(UserRole::class);
    }

    public function unsetRole() {
        return $this->userRole()->delete();
    }

    public function getRoleId()
    {
        return $this->userRole ? $this->userRole->role->id : null;
    }

    public function getRoleName()
    {
        return $this->userRole ? $this->userRole->role->name : null;
    }

    public function initialName()
    {
        return implode('', array_map(fn($word) => $word[0], explode(' ', $this->name)));
    }

    /**
     * Check if user has a specific permission for a module.
     *
     * @param string $permissionSlug
     * @param string $moduleSlug
     * @return bool
     */
    public function hasPermission($permissionSlug, $moduleSlug)
    {
        if ($this->userRole) {
            return $this->userRole->role && $this->userRole->role->hasPermission($permissionSlug, $moduleSlug);
        } else {
            return false;
        }
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole($roleSlug)
    {
        if ($this->userRole) {
            return $this->userRole->role && $this->userRole->role->slug === $roleSlug;
        } else {
            return false;
        }
    }

    /**
     * Check if user is super admin.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public function actitityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function getCustomActivityDescription($event)
    {
        return ucfirst($event) . " Role: {$this->name}";
    }

    // /**
    //  * Check if user is admin.
    //  *
    //  * @return bool
    //  */
    // public function isAdmin()
    // {
    //     return $this->hasRole('admin');
    // }

    // public function createdBy()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function lastUpdatedBy()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function lastUpdate() {
    //     if ($this->created_at == $this->updated_at) {
    //         return null;
    //     } else {
    //         return $this->updated_at->format('d M Y, h:i A');
    //     }
    // }

    // /**
    //  * Check if user is manager.
    //  *
    //  * @return bool
    //  */
    // public function isManager()
    // {
    //     return $this->hasRole('manager');
    // }

    // /**
    //  * Check if user is staff.
    //  *
    //  * @return bool
    //  */
    // public function isStaff()
    // {
    //     return $this->hasRole('staff');
    // }
}
