<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Carbon\Carbon;
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
        'created_by_id'
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
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            dd('updated');
            // // Check if a status change occurred that indicates activation/deactivation
            // if ($model->isDirty('status') || $model->isDirty('active') || $model->isDirty('is_active')) {
            //     $statusField = $model->isDirty('status') ? 'status' : 
            //                   ($model->isDirty('active') ? 'active' : 'is_active');

            //     $newValue = $model->getAttribute($statusField);
            //     $oldValue = $model->getOriginal($statusField);

            //     // Determine if this is activation or deactivation
            //     if ($model->isActivationChange($oldValue, $newValue)) {
            //         if ($model->isActivationValue($newValue)) {
            //             $model->logActivity('activated');
            //         } else {
            //             $model->logActivity('deactivated');
            //         }
            //     }
            // }

            // $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            dd('deleted');
            // $model->logActivity('deleted');
        });


        // if (Auth::id()) {
        //     foreach (['created', 'updated', 'deleted'] as $event) {
        //         static::$event(function ($model) use ($event) {
        //             $description = static::getActivityDescription($event, $model);

        //             $skipTimestampAttributes = ['created_at', 'updated_at', 'deleted_at', 'created_by_id', 'last_updated_by_id'];

        //             $properties = [];
        //             if ($event === 'created') {
        //                 foreach ($model->originalAttributes as $attributeName => $value) {
        //                     if (in_array($attributeName, $skipTimestampAttributes)) {
        //                         continue;
        //                     }

        //                     $properties[$attributeName] = [
        //                         'old_value' => null,
        //                         'new_value' => $value
        //                     ];
        //                 }
        //             } elseif ($event === 'updated') {
        //                 $oldAttributes = $model->getOriginalAttributes();
        //                 $changedAttributes = $model->getChangedAttributes();

        //                 foreach ($changedAttributes as $attributeName => $newValue) {
        //                     if (in_array($attributeName, $skipTimestampAttributes)) {
        //                         continue;
        //                     }

        //                     $properties[$attributeName] = [
        //                         'old_value' => $oldAttributes[$attributeName],
        //                         'new_value' => $newValue
        //                     ];
        //                 }
        //             }

        //             $activity = new ActivityLog([
        //                 'log_name' => class_basename($model),
        //                 'user_id' => Auth::id(), // Set the actor (e.g. currently logged in user)
        //                 'action' => $event,
        //                 'description' => $description,
        //                 'properties' => ($properties ? $properties : null)
        //             ]);

        //             // Set the subject (the model that was changed)
        //             $activity->subject()->associate($model);

        //             $activity->save();
        //         });
        //     }
        // }
    }

    // /**
    //  * Get all activity logs for this model.
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\MorphMany
    //  */
    // public function activities(): MorphMany
    // {
    //     return $this->morphMany(ActivityLog::class, 'subject');
    // }

    /**
     * Log an activity for this model.
     *
     * @param string $event
     * @return \App\Models\ActivityLog|null
     */
    public function logActivity(string $event)
    {
        if (!Auth::id()) {
            return null;
        }

        $logName = static::$logName ?? class_basename($this);
        $properties = [];

        if ($event === 'created') {
            $properties['attributes'] = $this->getActivityAttributes();
        }

        // if ($event === 'created') {
        //     $properties['attributes'] = $this->getActivityAttributes();
        // } elseif ($event === 'updated') {
        //     $dirty = $this->getDirty();

        //     // Only log if there are actual changes to be logged
        //     if (empty($this->getChangedActivityAttributes($dirty))) {
        //         return null;
        //     }

        //     $properties['attributes'] = $this->getChangedActivityAttributes($dirty);
        //     $properties['old'] = $this->getOriginalActivityAttributes($dirty);
        // } elseif ($event === 'deleted') {
        //     $properties['attributes'] = $this->getActivityAttributes();
        // } elseif ($event === 'activated' || $event === 'deactivated') {
        //     // For activation/deactivation, log status field and any other relevant fields
        //     $statusField = $this->getStatusField();

        //     if ($statusField) {
        //         $properties['attributes'] = [
        //             $statusField => $this->getAttribute($statusField)
        //         ];
        //         $properties['old'] = [
        //             $statusField => $this->getOriginal($statusField)
        //         ];
        //     }
        // }

        // return ActivityLog::create([
        //     'log_name' => $logName,
        //     'description' => $this->getActivityDescription($event),
        //     'subject_type' => get_class($this),
        //     'subject_id' => $this->getKey(),
        //     'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
        //     'causer_id' => Auth::id(),
        //     'properties' => $properties,
        //     'event' => $event,
        // ]);

        $description = str_replace('-', ' ', Str::title($event));

        $activity = new ActivityLog([
            'log_name' => $logName,
            'action' => $event,
            'description' => $description,
            'user_id' => Auth::id(),
            'properties' => $properties
        ]);
        $activity->subject()->associate($this);
        return $activity->save();
    }

    /**
     * Log a custom activity with additional properties.
     *
     * @param string $event
     * @param array $properties
     * @return \App\Models\ActivityLog
     */
    public function customLogActivity(string $event, array $properties = [])
    {
        $updatedRole = $this->update([
            'last_updated_by_id' => Auth::id(),
            'updated_at' => Carbon::now()
        ]);

        if ($updatedRole) {
            $logName = static::$logName ?? class_basename($this);
            $description = str_replace('-', ' ', Str::title($event));

            $activity = new ActivityLog([
                'log_name' => $logName,
                'action' => $event,
                'description' => $description,
                'user_id' => Auth::id(),
                'properties' => $properties
            ]);
            $activity->subject()->associate($this);
            return $activity->save();
        } else {
            return false;
        }
    }

    /**
     * Get the attributes to be logged.
     *
     * @return array
     */
    protected function getActivityAttributes(): array
    {
        $attributes = $this->getAttributes();
        
        // If specific attributes are set to be logged
        if (!empty(static::$logAttributes)) {
            $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
        }
        
        // Remove excluded attributes
        if (!empty(static::$logExceptAttributes)) {
            $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
        }
        
        return $attributes;
    }

    protected static function getActivityDescription(string $event, $model): string
    {
        if (method_exists($model, 'getCustomActivityDescription')) {
            return $model->getCustomActivityDescription($event);
        }

        return ucfirst($event) . " " . class_basename($model);
    }
}
