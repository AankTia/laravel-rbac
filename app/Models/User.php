<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\TimestampAndUserTrackingTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, TimestampAndUserTrackingTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'created_by_id',
        'last_updated_by_id'
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

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
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
        return $this->role && $this->role->hasPermission($permissionSlug, $moduleSlug);
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $roleSlug
     * @return bool
     */
    public function hasRole($roleSlug)
    {
        return $this->role && $this->role->slug === $roleSlug;
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

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

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
