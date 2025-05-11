<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * The array of attributes to be logged when changed.
     *
     * If empty, all attributes will be logged.
     *
     * @var array
     */
    protected static $logAttributes = [];

    /**
     * The array of attributes to be excluded from logging.
     *
     * @var array
     */
    protected static $logExceptAttributes = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by_id',
        'last_updated_by_id',
        'password',
        'remember_token'
    ];

    /**
     * The log name to use for the activity log.
     *
     * @var string|null
     */
    protected static $logName = null;

    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            // dd();
            // $model->logActivity('created');
        });

        static::updated(function ($model) {
            // dd();
            // $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            // dd();
            // $model->logActivity('deleted');
        });
    }

    /**
     * Get all activity logs for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function histories(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public function createLogActivity($action, $params)
    {
        $params['action'] = $action;
        $newActivityLogAttributes = $this->generateNewActivityLogAttributes($params);

        $newActivity = new ActivityLog($newActivityLogAttributes);
        if (!in_array($newActivityLogAttributes['action'], ['login', 'logout'])) {
            $newActivity->subject()->associate($this);
        }
        $newActivity->save();
    }

    function generateNewActivityLogAttributes($params)
    {
        $newActivityLogAttributes = [
            'log_name' => null,
            'action' => null,
            'user_id' => null,
            'user_description' => null,
            'user_properties' => [],
            'subject_description' => null,
            'subject_properties' => []
        ];

        if (isset($params['log_name']) && $params['log_name'] !== null) {
            $newActivityLogAttributes['log_name'] = $params['log_name'];
        } else {
            $newActivityLogAttributes['log_name'] = static::$logName ?? class_basename($this);
        }

        if (isset($params['action']) && $params['action'] !== null) {
            $newActivityLogAttributes['action'] = $params['action'];
        } else {
            dd();
            // $newActivityLogAttributes['action'] = ???;
        }

        if (isset($params['user_id']) && $params['user_id'] !== null) {
            $newActivityLogAttributes['user_id'] = $params['user_id'];
        } else {
            dd();
            // $newActivityLogAttributes['user_id'] = ???;
        }

        if (isset($params['user_description']) && $params['user_description'] !== null) {
            $newActivityLogAttributes['user_description'] = $params['user_description'];
        } else {
            dd();
            // $newActivityLogAttributes['user_description'] = ???;
        }

        if (isset($params['subject_description']) && $params['subject_description'] !== null) {
            dd();
            // $newActivityLogAttributes['subject_description'] = $params['subject_description'];
        }

        if (isset($params['subject_properties']) && $params['subject_properties'] !== null) {
            dd();
            // $newActivityLogAttributes['subject_properties'] = $params['subject_properties'];
        }

        if (isset($params['user_description']) && $params['user_description'] !== null) {
            $newActivityLogAttributes['user_description'] = $params['user_description'];
        }

        if (isset($params['user_properties']) && $params['user_properties'] !== null) {
            if (is_array($params['user_properties'])) {
                $userPropertiesKeys = array_keys($params['user_properties']);
                if (in_array('ip_address', $userPropertiesKeys) && in_array('user_agent', $userPropertiesKeys)) {
                    $newActivityLogAttributes['user_properties'] = $params['user_properties'];
                } else {
                    dd($params['user_properties']);
                }
            } else {
                dd($params['user_properties']);
            }
        } else {
            dd($params['user_properties']);
        }

        return $newActivityLogAttributes;
    }

    // /**
    //  * Log an activity for this model.
    //  *
    //  * @param string $event
    //  * @return \App\Models\ActivityLog|null
    //  */
    // public function logActivity(string $event)
    // {
    //     dd();
    //     // if (!Auth::id()) {
    //     //     return null;
    //     // }

    //     // $logName = static::$logName ?? class_basename($this);
    //     // $properties = [];

    //     // if ($event === 'created') {
    //     //     $properties['attributes'] = $this->getActivityAttributes();
    //     // } elseif ($event === 'updated') {
    //     //     $dirty = $this->getDirty();
    //     //     $changedAttributes = $this->getChangedActivityAttributes($dirty);

    //     //     // Only log if there are actual changes to be logged
    //     //     if (empty($changedAttributes)) {
    //     //         return null;
    //     //     }

    //     //     if (in_array('is_active', array_keys($changedAttributes)) && count($changedAttributes) === 1) {
    //     //         if ($changedAttributes['is_active'] === 1 || $changedAttributes['is_active'] === true) {
    //     //             $event = 'activated';
    //     //         } else {
    //     //             $event = 'deactivated';
    //     //         }
    //     //     } 

    //     //     $originalAttributes = $this->getOriginalActivityAttributes($dirty);

    //     //     $properties['attributes'] = [];
    //     //     foreach ($changedAttributes as $name => $value) {
    //     //         $properties['attributes'][$name] = [
    //     //             'old' => $originalAttributes[$name],
    //     //             'new' => $value
    //     //         ];
    //     //     }
    //     // } elseif ($event === 'deleted') {
    //     //     $properties['attributes'] = $this->getActivityAttributes();
    //     // }


    //     // $description = $logName . " " . str_replace('-', ' ', Str::title($event));

    //     // $activity = new ActivityLog([
    //     //     'log_name' => $logName,
    //     //     'action' => $event,
    //     //     'description' => $description,
    //     //     'user_id' => Auth::id(),
    //     //     'properties' => $properties
    //     // ]);
    //     // $activity->subject()->associate($this);
    //     // return $activity->save();
    // }

    // /**
    //  * Log a custom activity with additional properties.
    //  *
    //  * @param string $event
    //  * @param array $properties
    //  * @return \App\Models\ActivityLog
    //  */
    // public function customLogActivity(string $logName)
    // {
    //     // $updatedRole = $this->update([
    //     //     'last_updated_by_id' => Auth::id(),
    //     //     'updated_at' => Carbon::now()
    //     // ]);

    //     // if ($updatedRole) {
    //     //     $logName = static::$logName ?? class_basename($this);
    //     //     if (trim($description) == '') {
    //     //         $description = str_replace('-', ' ', Str::title($event));
    //     //     }

    //     //     $activity = new ActivityLog([
    //     //         'log_name' => $logName,
    //     //         'action' => $event,
    //     //         'description' => $description,
    //     //         'user_id' => Auth::id(),
    //     //         'properties' => $properties
    //     //     ]);
    //     //     $activity->subject()->associate($this);
    //     //     return $activity->save();
    //     // } else {
    //     //     return false;
    //     // }
    // }

    // /**
    //  * Get the attributes to be logged.
    //  *
    //  * @return array
    //  */
    // protected function getActivityAttributes(): array
    // {
    //     dd();
    //     // $attributes = $this->getAttributes();

    //     // // If specific attributes are set to be logged
    //     // if (!empty(static::$logAttributes)) {
    //     //     $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
    //     // }

    //     // // Remove excluded attributes
    //     // if (!empty(static::$logExceptAttributes)) {
    //     //     $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
    //     // }

    //     // return $attributes;
    // }

    // /**
    //  * Get the original attributes for changed attributes.
    //  *
    //  * @param array $dirty
    //  * @return array
    //  */
    // protected function getOriginalActivityAttributes(array $dirty): array
    // {
    //     dd();
    //     // $original = [];

    //     // foreach (array_keys($dirty) as $key) {
    //     //     if (array_key_exists($key, $this->getOriginal())) {
    //     //         $original[$key] = $this->getOriginal($key);
    //     //     }
    //     // }

    //     // // If specific attributes are set to be logged
    //     // if (!empty(static::$logAttributes)) {
    //     //     $original = array_intersect_key($original, array_flip(static::$logAttributes));
    //     // }

    //     // // Remove excluded attributes
    //     // if (!empty(static::$logExceptAttributes)) {
    //     //     $original = array_diff_key($original, array_flip(static::$logExceptAttributes));
    //     // }

    //     // return $original;
    // }

    // /**
    //  * Get the changed attributes for activity logging.
    //  *
    //  * @param array $dirty
    //  * @return array
    //  */
    // protected function getChangedActivityAttributes(array $dirty): array
    // {
    //     dd();
    //     // $attributes = $dirty;

    //     // // If specific attributes are set to be logged
    //     // if (!empty(static::$logAttributes)) {
    //     //     $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
    //     // }

    //     // // Remove excluded attributes
    //     // if (!empty(static::$logExceptAttributes)) {
    //     //     $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
    //     // }

    //     // return $attributes;
    // }

    // protected static function getActivityDescription(string $event, $model): string
    // {
    //     dd();
    //     // if (method_exists($model, 'getCustomActivityDescription')) {
    //     //     return $model->getCustomActivityDescription($event);
    //     // }

    //     // return ucfirst($event) . " " . class_basename($model);
    // }
}
