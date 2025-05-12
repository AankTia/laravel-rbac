<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
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

    protected static $attributeLabels = [];

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
        'remember_token',
        'email_verified_at'
    ];

    /**
     * The log name to use for the activity log.
     *
     * @var string|null
     */
    protected static $logName = null;

    public static function bootLogsActivity()
    {
        // static::created(function ($model) {
        //     if (Auth::user()) {
        //         $klass = get_class($model);
        //         $activityAttributes = $model->getActivityAttributes();

        //         $subjectProperties = [
        //             'attributes' => []
        //         ];

        //         foreach ($activityAttributes as $attribute => $value) {
        //             $subjectProperties['attributes'][$attribute] = [
        //                 'label' => $model->getAttributeLabelFor($klass, $attribute),
        //                 'value' => $value
        //             ];
        //         }

        //         $model->createLogActivity('create', [
        //             'user_description' => 'Created new ' . class_basename($model),
        //             'subject_description' => 'Created new ' . class_basename($model),
        //             'subject_properties' => $subjectProperties
        //         ]);
        //     }
        // });

        // static::updated(function ($model) {
        //     $dirty = $model->getDirty();
        //     $changedAttributes = $model->getChangedActivityAttributes($dirty);

        //     // Only log if there are actual changes to be logged
        //     if (empty($changedAttributes)) {
        //         return null;
        //     }

        //     if (in_array('is_active', array_keys($changedAttributes)) && count($changedAttributes) === 1) {
        //         // if ($changedAttributes['is_active'] === 1 || $changedAttributes['is_active'] === true) {
        //         //     dd();
        //         //     // $event = 'activated';
        //         // } else {
        //         //     dd();
        //         //     // $event = 'deactivated';
        //         // }
        //     } else {
        //         $klass = get_class($model);

        //         $subjectProperties = [
        //             'attributes' => []
        //         ];

        //         $originalAttributes = $model->getOriginalActivityAttributes($dirty);
        //         foreach ($changedAttributes as $attribute => $value) {
        //             $subjectProperties['attributes'][$attribute] = [
        //                 'label' => $model->getAttributeLabelFor($klass, $attribute),
        //                 'new_value' => $value,
        //                 'old_value' => $originalAttributes[$attribute]
        //             ];
        //         }

        //         $model->createLogActivity('update', [
        //             'user_description' => 'Updated ' . class_basename($model),
        //             'subject_description' => 'Updated ' . class_basename($model),
        //             'subject_properties' => $subjectProperties
        //         ]);
        //     }
        // });

        // static::deleted(function ($model) {
        //     // dd();
        // });
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

    function getLatestHistory()
    {
        return $this->histories()->latest()->first();
    }

    public function createLoginLog()
    {
        $params = [
            'log_name' => 'Auth',
            'user_description' => 'Loged in'
        ];
        return $this->createLog('login', $params);
    }

    public function createLogoutLog()
    {
        $params = [
            'log_name' => 'Auth',
            'user_description' => 'Loged out'
        ];
        return $this->createLog('logout', $params);
    }

    public function createStoredDataLog($params = [])
    {
        return $this->createLog('create', $params);
    }

    public function createUpdatedDataLog($params = [])
    {
        return $this->createLog('update', $params);
    }

    public function createDeletedDataLog($params = [])
    {
        return $this->createLog('delete', $params);
    }

    public function createActivateDataLog($params = [])
    {
        return $this->createLog('activate', $params);
    }

    public function createDeactivateDataLog($params = [])
    {
        return $this->createLog('deactivate', $params);
    }

    public function createLog($action, $params = [])
    {
        $activityLogData = $params;
        $activityLogData['action'] = $action;

        $classBaseName = class_basename($this);
        $defaultDecsription = ucwords($action) . ' ' . $classBaseName;

        if (!array_key_exists('log_name', $params)) {
            $activityLogData['log_name'] = $classBaseName;
        }

        if (!array_key_exists('user_id', $params)) {
            $activityLogData['user_id'] = Auth::id();
        }

        if (!array_key_exists('user_description', $params)) {
            $activityLogData['user_description'] = $defaultDecsription;
        }

        if (!array_key_exists('subject_description', $params)) {
            $activityLogData['subject_description'] = $defaultDecsription;
        }

        if (!array_key_exists('subject_properties', $params)) {
            $activityLogData['subject_properties'] = $this->getOriginalSubjectProperties();
        }

        $activityLogData['user_properties'] = $this->generateUserProperties($params['user_properties'] ?? []);

        $newActivity = new ActivityLog($activityLogData);
        if (!in_array($action, ['login', 'logout'])) {
            $newActivity->subject()->associate($this);
        }

        return $newActivity->save();
    }

    public function generateUserProperties($properties = [])
    {
        $userProperties = [
            'ip_address' => Request::ip() ?? '',
            'user_agent' => Request::userAgent() ?? ''
        ];

        if (!empty($properties)) {
            if (in_array('ip_address', $properties) && in_array('user_agent', $properties)) {
                $userProperties = $properties;
            }
        }

        return $userProperties;
    }

    public function getOriginalSubjectProperties()
    {
        return [
            'attributes' => $this->getOriginalLogAttributes()
        ];
    }

    /**
     * Get the original attributes for changed attributes to log.
     *
     * @param array $dirty
     * @return array
     */
    public function getOriginalLogAttributes(array $dirty = []): array
    {
        $result = [];

        if (empty($dirty)) {
            $dirty = $this->getOriginal();
        }

        $original = [];
        foreach (array_keys($dirty) as $key) {
            if (array_key_exists($key, $this->getOriginal())) {
                $original[$key] = $this->getOriginal($key);
            }
        }

        // If specific attributes are set to be logged
        if (!empty(static::$logAttributes)) {
            $original = array_intersect_key($original, array_flip(static::$logAttributes));
        }

        // Remove excluded attributes
        if (!empty(static::$logExceptAttributes)) {
            $original = array_diff_key($original, array_flip(static::$logExceptAttributes));
        }

        if (!empty($original)) {
            foreach ($original as $attribute => $value) {
                $result[$attribute] = [
                    'label' => $this->getAttributeLabel($attribute),
                    'value' => $value
                ];
            }
        }

        return $result;
    }

    public function getChangedSubjectProperties()
    {
        return [
            'attributes' => $this->getChangedLogAttributes()
        ];
    }

    public function getChangeDataDescription()
    {
        $changedAttributeLabels = [];
        $changedLogAttributes = $this->getChangedLogAttributes();
        foreach ($changedLogAttributes as $attribute => $data) {
            $changedAttributeLabels[] = $data['label'];
        }

        if (!empty($changedAttributeLabels)) {
            return 'Change ' . implode(', ', $changedAttributeLabels) . '.';
        } else {
            return null;
        }
    }

    public function getChangedLogAttributes()
    {
        $result = [];

        if (empty($dirty)) {
            $dirty = $this->getDirty();
        }

        $attributes = $dirty;

        // If specific attributes are set to be logged
        if (!empty(static::$logAttributes)) {
            $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
        }

        // Remove excluded attributes
        if (!empty(static::$logExceptAttributes)) {
            $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
        }

        if (!empty($attributes)) {
            $originalAttributes = $this->getOriginal();
            foreach ($attributes as $attribute => $value) {
                $result[$attribute] = [
                    'label' => $this->getAttributeLabel($attribute),
                    'new_value' => $value,
                    'old_value' => $originalAttributes[$attribute],
                ];
            }
        }

        return $result;
    }

    function getAttributeLabel($attribute)
    {
        $klass = get_class($this);
        if (!property_exists($klass, 'attributeLabels')) {
            return $attribute;
        }

        if (array_key_exists($attribute, static::$attributeLabels)) {
            return static::$attributeLabels[$attribute];
        } else {
            return $attribute;
        }
    }


    //======

    public function createLogActivity($action, $params = [])
    {
        $params['action'] = $action;
        $newActivityLogAttributes = $this->generateNewActivityLogAttributes($params);

        $newActivity = new ActivityLog($newActivityLogAttributes);
        if (!in_array($action, ['login', 'logout'])) {
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
            $newActivityLogAttributes['user_id'] = Auth::id();
        }

        if (isset($params['user_description']) && $params['user_description'] !== null) {
            $newActivityLogAttributes['user_description'] = $params['user_description'];
        } else {
            dd();
            // $newActivityLogAttributes['user_description'] = ???;
        }

        if (isset($params['subject_description']) && $params['subject_description'] !== null) {
            $newActivityLogAttributes['subject_description'] = $params['subject_description'];
        }

        if (isset($params['subject_properties']) && $params['subject_properties'] !== null) {
            $newActivityLogAttributes['subject_properties'] = $params['subject_properties'];
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
                    $newActivityLogAttributes['user_properties'] = [
                        'ip_address' => Request::ip() ?? '',
                        'user_agent' => Request::userAgent() ?? ''
                    ];
                }
            } else {
                $newActivityLogAttributes['user_properties'] = [
                    'ip_address' => Request::ip() ?? '',
                    'user_agent' => Request::userAgent() ?? ''
                ];
            }
        } else {
            $newActivityLogAttributes['user_properties'] = [
                'ip_address' => Request::ip() ?? '',
                'user_agent' => Request::userAgent() ?? ''
            ];
        }

        return $newActivityLogAttributes;
    }

    function getAttributeLabelFor($klass, $attribute)
    {
        if (property_exists($klass, 'attributeLabels')) {
            if (array_key_exists($attribute, $klass::$attributeLabels)) {
                return $klass::$attributeLabels[$attribute];
            } else {
                return $attribute;
            }
        } else {
            return $attribute;
        }
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
    //     $attributes = $this->getAttributes();

    //     // If specific attributes are set to be logged
    //     if (!empty(static::$logAttributes)) {
    //         $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
    //     }

    //     // Remove excluded attributes
    //     if (!empty(static::$logExceptAttributes)) {
    //         $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
    //     }

    //     return $attributes;
    // }

    // /**
    //  * Get the original attributes for changed attributes.
    //  *
    //  * @param array $dirty
    //  * @return array
    //  */
    // public function getOriginalActivityAttributes(array $dirty = []): array
    // {
    //     $result = [];

    //     if (empty($dirty)) {
    //         $dirty = $this->getOriginal();
    //     }

    //     $original = [];
    //     foreach (array_keys($dirty) as $key) {
    //         if (array_key_exists($key, $this->getOriginal())) {
    //             $original[$key] = $this->getOriginal($key);
    //         }
    //     }

    //     // If specific attributes are set to be logged
    //     if (!empty(static::$logAttributes)) {
    //         $original = array_intersect_key($original, array_flip(static::$logAttributes));
    //     }

    //     // Remove excluded attributes
    //     if (!empty(static::$logExceptAttributes)) {
    //         $original = array_diff_key($original, array_flip(static::$logExceptAttributes));
    //     }

    //     if (!empty($original)) {
    //         foreach ($original as $attribute => $value) {
    //             $result[$attribute] = [
    //                 'label' => $this->getAttributeLabel($attribute),
    //                 'value' => $value
    //             ];
    //         }
    //     }

    //     return $result;
    // }

    // /**
    //  * Get the changed attributes for activity logging.
    //  *
    //  * @param array $dirty
    //  * @return array
    //  */
    // protected function getChangedActivityAttributes(array $dirty = []): array
    // {
    //     $attributes = $dirty;

    //     // If specific attributes are set to be logged
    //     if (!empty(static::$logAttributes)) {
    //         $attributes = array_intersect_key($attributes, array_flip(static::$logAttributes));
    //     }

    //     // Remove excluded attributes
    //     if (!empty(static::$logExceptAttributes)) {
    //         $attributes = array_diff_key($attributes, array_flip(static::$logExceptAttributes));
    //     }

    //     return $attributes;
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
