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
    protected static $logActivityAttributes = [];

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
        'email_verified_at',
        'slug'
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
        // });

        static::updating(function ($model) {
            static::$logActivityAttributes = $model->generateUpdateLogActivityAttributes();
        });

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

    public function getClassBaseName()
    {
        return class_basename($this);
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

    public function generateUpdateLogActivityAttributes()
    {
        $classBaseName = $this->getClassBaseName();
        $message = $this->getChangeDataMessage();

        return [
            'log_name' => $classBaseName,
            'action' => 'update',
            'user_id' => Auth::id(),
            'user_properties' => $this->generateUserProperties(),
            'user_description' => $message,
            'subject_description' => $message,
            'subject_properties' => $this->getChangedSubjectProperties()
        ];
    }

    public function createUpdatedDataLog($params = [])
    {
        if (!empty($params)) {
            if (array_key_exists('user_description', $params)) {
                static::$logActivityAttributes['user_description'] = $params['user_description'] . ' ' . static::$logActivityAttributes['user_description'];
            }
        }

        return $this->createLog('update', static::$logActivityAttributes);
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

    public function getChangeDataMessage()
    {
        $changedAttributeLabels = [];
        $changedLogAttributes = $this->getChangedLogAttributes();
        foreach ($changedLogAttributes as $attribute => $data) {
            $changedAttributeLabels[] = $data['label'];
        }

        if (!empty($changedAttributeLabels)) {
            return 'Changed ' . implode(', ', $changedAttributeLabels) . '.';
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

    static function getAttributesLabel()
    {
        return static::$attributeLabels;
    }
}
