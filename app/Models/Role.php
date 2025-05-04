<?php

namespace App\Models;

use App\TimestampAndUserTrackingTrait;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory, SoftDeletes, TimestampAndUserTrackingTrait, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'allow_to_be_assigne',
        'created_by_id',
        'last_updated_by_id'
    ];

    protected static $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'description' => 'required|string|max:255',
        'allow_to_be_assigne' => 'nullable',
    ];

    protected static function boot()
    {
        parent::boot();

        // Before create
        static::creating(function ($model) {
            if ($model->slug == null || trim($model->slug) == '') {
                $model->slug = Str::slug($model->name);
            }
        });

        // // Before update
        // static::updating(function ($model) {
        //     $model->validate($model->getAttributes(), $model->id);
        // });
    }

    public function validate($action, $data)
    {
        $rules = static::$rules;
        $isUpdate = $action == 'update';

        // If updating, ignore unique constraint for current user
        if ($isUpdate && isset($this->id)) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->id;
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Get the users that belong to this role.
     */
    public function roleUsers()
    {
        return $this->hasMany(UserRole::class);
    }

    /**
     * Get permissions for this role with their modules.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role_module')
            ->withPivot('module_id')
            ->using(PermissionRoleModule::class);
    }

    /**
     * Get modules for this role with their permissions.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'permission_role_module')
            ->withPivot('permission_id')
            ->using(PermissionRoleModule::class);
    }

    /**
     * Get all role module permissions.
     */
    public function modulePermissions()
    {
        return $this->hasMany(PermissionRoleModule::class);
    }

    /**
     * Check if role has a specific permission for a module.
     *
     * @param string $permissionSlug
     * @param string $moduleSlug
     * @return bool
     */
    public function hasPermission($permissionSlug, $moduleSlug)
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        $module = Module::where('slug', $moduleSlug)->first();

        if (!$permission || !$module) {
            return false;
        }

        return $this->modulePermissions()
            ->where('permission_id', $permission->id)
            ->where('module_id', $module->id)
            ->exists();
    }

    public function assignPermission($moduleId, $permissionId)
    {
        PermissionRoleModule::create([
            'role_id' => $this->id,
            'permission_id' => $permissionId,
            'module_id' => $moduleId,
        ]);
    }

    public function getTotalUsers() {
        if ($this->roleUsers) {
            return $this->roleUsers->count();
        } else {
            return 0;
        }
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }
}
