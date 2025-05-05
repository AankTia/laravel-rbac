# Laravel - Creating an Activity Log System

I've prepared a complete custom solution for tracking model changes in Laravel, including migrations, models, traits, and example usage. Here's a breakdown of the components:

## 1. Migration File

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable();
            $table->string('description');

            // Subject: the model that was changed
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->index(['subject_type', 'subject_id']);

            // Causer: the user who made the change
            $table->string('causer_type')->nullable();
            $table->unsignedBigInteger('causer_id')->nullable();
            $table->index(['causer_type', 'causer_id']);

            // Properties: holds the old and new attributes
            $table->json('properties')->nullable();

            // Event type: created, updated, deleted, etc.
            $table->string('event')->nullable();

            // Batch UUID for grouping related activities
            $table->uuid('batch_uuid')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}
```

The migration file creates a structured `activity_logs` table that captures:

- Subject information (which model was changed)
- Causer information (which user made the change)
- Properties (the actual changes - old and new values)
- Event type (created, updated, deleted)
- Other helpful metadata

This schema is designed to be flexible, allowing you to log any kind of model changes with detailed information.

## 2. ActivityLog Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'properties' => AsCollection::class,
    ];

    /**
     * Get the subject model of the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the causer (user) model of the activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include activities with a specific log name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $logName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLog($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }

    /**
     * Scope a query to only include activities for a specific subject.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $subject
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query
            ->where('subject_type', get_class($subject))
            ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope a query to only include activities by a specific causer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $causer
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCausedBy($query, Model $causer)
    {
        return $query
            ->where('causer_type', get_class($causer))
            ->where('causer_id', $causer->getKey());
    }

    /**
     * Get the changes attribute.
     *
     * @return array
     */
    public function getChangesAttribute(): array
    {
        if (!$this->properties) {
            return [];
        }

        $changes = [];

        if ($this->properties->has('attributes')) {
            $changes['new'] = $this->properties->get('attributes');
        }

        if ($this->properties->has('old')) {
            $changes['old'] = $this->properties->get('old');
        }

        return $changes;
    }
}
```

The ActivityLog model provides a clean interface for working with your activity logs, including:

- Properly casting the JSON properties column
- Relationships to both subject (the changed model) and causer (the user)
- Helpful query scopes for filtering logs
- A method to easily access changes

## 3. Loggable Trait

```php
<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Loggable
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
    protected static $logExceptAttributes = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The log name to use for the activity log.
     *
     * @var string|null
     */
    protected static $logName = null;

    /**
     * Register the model events to be logged.
     */
    public static function bootLoggable()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Get all activity logs for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Log an activity for this model.
     *
     * @param string $event
     * @return \App\Models\ActivityLog
     */
    public function logActivity(string $event)
    {
        $logName = static::$logName ?? strtolower(class_basename($this));
        $properties = [];

        if ($event === 'created') {
            $properties['attributes'] = $this->getActivityAttributes();
        } elseif ($event === 'updated') {
            $dirty = $this->getDirty();
            
            // Only log if there are actual changes to be logged
            if (empty($this->getChangedActivityAttributes($dirty))) {
                return null;
            }
            
            $properties['attributes'] = $this->getChangedActivityAttributes($dirty);
            $properties['old'] = $this->getOriginalActivityAttributes($dirty);
        } elseif ($event === 'deleted') {
            $properties['attributes'] = $this->getActivityAttributes();
        }

        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $this->getActivityDescription($event),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
            'event' => $event,
        ]);
    }

    /**
     * Get the description for the activity.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityDescription(string $event): string
    {
        return ucfirst($event) . ' ' . strtolower(class_basename($this));
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

    /**
     * Get the changed attributes for activity logging.
     *
     * @param array $dirty
     * @return array
     */
    protected function getChangedActivityAttributes(array $dirty): array
    {
        $attributes = $dirty;
        
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

    /**
     * Get the original attributes for changed attributes.
     *
     * @param array $dirty
     * @return array
     */
    protected function getOriginalActivityAttributes(array $dirty): array
    {
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
        
        return $original;
    }

    /**
     * Specify the attributes that should be logged.
     *
     * @param array $attributes
     * @return $this
     */
    public function logOnly(array $attributes)
    {
        static::$logAttributes = $attributes;
        
        return $this;
    }

    /**
     * Specify the attributes that should be excluded from logging.
     *
     * @param array $attributes
     * @return $this
     */
    public function logExcept(array $attributes)
    {
        static::$logExceptAttributes = array_merge(static::$logExceptAttributes, $attributes);
        
        return $this;
    }

    /**
     * Set the log name for activity logs.
     *
     * @param string $logName
     * @return $this
     */
    public function setLogName(string $logName)
    {
        static::$logName = $logName;
        
        return $this;
    }
}
```

This is where the magic happens! The Loggable trait can be added to any model that you want to track changes for. It:

- Automatically logs create, update, and delete events
- Allows you to specify which attributes to log (or exclude from logging)
- Tracks old and new values for updated models
- Associates logs with the authenticated user
- Provides a clean interface for accessing a model's activity history

## 4. Example Usage

```php
<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Loggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'status'
    ];
    
    /**
     * The attributes to be logged when changed.
     * If empty, all fillable attributes will be logged.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name', 'price', 'status'
    ];
    
    /**
     * The log name to use for this model's activities.
     *
     * @var string
     */
    protected static $logName = 'product';
}

// Example usage in a Controller:

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        
        $product = Product::create($validated);
        
        // Activity log is automatically created through the Loggable trait
        
        return response()->json($product, 201);
    }
    
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'status' => 'sometimes|in:active,inactive',
        ]);
        
        $product->update($validated);
        
        // Activity log is automatically created through the Loggable trait
        
        return response()->json($product);
    }
    
    public function activityLogs(Product $product)
    {
        $activities = $product->activities()
            ->with('causer')
            ->latest()
            ->paginate(15);
            
        return response()->json($activities);
    }
}
```
I've included example code showing how to:

- Add the Loggable trait to a model
- Configure which attributes to track
- Automatically log changes in your controllers
- Retrieve activity logs for a model

## How to Use This System

1. Create the migration file and run it:

```bash
php artisan migrate
```

2. Place the ActivityLog model in `app/Models/`

3. Place the Loggable trait in `app/Traits/`

4. Add the trait to any model you want to track:

```php
use App\Traits\Loggable;

class YourModel extends Model
{
    use Loggable;

    // Optionally specify which attributes to log
    protected static $logAttributes = ['name', 'status', 'price'];
}
```

5. Your model changes will now be automatically logged!

## Accessing the Logs

You can access logs in several ways:

```php
// Get all logs for a specific model
$logs = $model->activities()->get();

// Get logs for a specific user
$userLogs = ActivityLog::causedBy($user)->get();

// Get logs for a specific action
$creationLogs = ActivityLog::where('event', 'created')->get();

// Get detailed changes
foreach ($logs as $log) {
    echo "Changed by: " . optional($log->causer)->name;
    echo "Action: " . $log->event;

    if ($log->event === 'updated') {
        $changes = $log->changes;
        $oldValues = $changes['old'] ?? [];
        $newValues = $changes['new'] ?? [];

        foreach ($newValues as $field => $value) {
            $oldValue = $oldValues[$field] ?? 'null';
            echo "{$field}: {$oldValue} → {$value}";
        }
    }
}
```

This system gives you complete control over tracking model changes in your Laravel application, without relying on external packages.

---

I'll extend the activity logging solution to include specialized events for activating and deactivating models. This is a common requirement for models with status fields.

# Enhanced Activity Logging for Activation/Deactivation

## Logable Trait for model

```php
<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Loggable
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
    protected static $logExceptAttributes = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The log name to use for the activity log.
     *
     * @var string|null
     */
    protected static $logName = null;

    /**
     * Register the model events to be logged.
     */
    public static function bootLoggable()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            // Check if a status change occurred that indicates activation/deactivation
            if ($model->isDirty('status') || $model->isDirty('active') || $model->isDirty('is_active')) {
                $statusField = $model->isDirty('status') ? 'status' : 
                              ($model->isDirty('active') ? 'active' : 'is_active');
                
                $newValue = $model->getAttribute($statusField);
                $oldValue = $model->getOriginal($statusField);
                
                // Determine if this is activation or deactivation
                if ($model->isActivationChange($oldValue, $newValue)) {
                    if ($model->isActivationValue($newValue)) {
                        $model->logActivity('activated');
                    } else {
                        $model->logActivity('deactivated');
                    }
                }
            }
            
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Get all activity logs for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Log an activity for this model.
     *
     * @param string $event
     * @return \App\Models\ActivityLog|null
     */
    public function logActivity(string $event)
    {
        $logName = static::$logName ?? strtolower(class_basename($this));
        $properties = [];

        if ($event === 'created') {
            $properties['attributes'] = $this->getActivityAttributes();
        } elseif ($event === 'updated') {
            $dirty = $this->getDirty();
            
            // Only log if there are actual changes to be logged
            if (empty($this->getChangedActivityAttributes($dirty))) {
                return null;
            }
            
            $properties['attributes'] = $this->getChangedActivityAttributes($dirty);
            $properties['old'] = $this->getOriginalActivityAttributes($dirty);
        } elseif ($event === 'deleted') {
            $properties['attributes'] = $this->getActivityAttributes();
        } elseif ($event === 'activated' || $event === 'deactivated') {
            // For activation/deactivation, log status field and any other relevant fields
            $statusField = $this->getStatusField();
            
            if ($statusField) {
                $properties['attributes'] = [
                    $statusField => $this->getAttribute($statusField)
                ];
                $properties['old'] = [
                    $statusField => $this->getOriginal($statusField)
                ];
            }
        }

        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $this->getActivityDescription($event),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
            'event' => $event,
        ]);
    }
    
    /**
     * Log a custom activity with additional properties.
     *
     * @param string $event
     * @param array $additionalProperties
     * @return \App\Models\ActivityLog
     */
    public function logCustomActivity(string $event, array $additionalProperties = [])
    {
        $logName = static::$logName ?? strtolower(class_basename($this));
        
        // Get standard properties
        $properties = [];
        
        if ($event === 'activated' || $event === 'deactivated') {
            $statusField = $this->getStatusField();
            
            if ($statusField) {
                $properties['attributes'] = [
                    $statusField => $this->getAttribute($statusField)
                ];
            }
        }
        
        // Merge with additional properties
        $properties = array_merge($properties, $additionalProperties);

        return ActivityLog::create([
            'log_name' => $logName,
            'description' => $this->getActivityDescription($event),
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
            'event' => $event,
        ]);
    }
    
    /**
     * Get the status field name for this model.
     *
     * @return string|null
     */
    protected function getStatusField()
    {
        $possibleFields = ['status', 'active', 'is_active'];
        
        foreach ($possibleFields as $field) {
            if (array_key_exists($field, $this->attributes)) {
                return $field;
            }
        }
        
        return null;
    }

    /**
     * Get the description for the activity.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityDescription(string $event): string
    {
        return ucfirst($event) . ' ' . strtolower(class_basename($this));
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

    /**
     * Get the changed attributes for activity logging.
     *
     * @param array $dirty
     * @return array
     */
    protected function getChangedActivityAttributes(array $dirty): array
    {
        $attributes = $dirty;
        
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

    /**
     * Get the original attributes for changed attributes.
     *
     * @param array $dirty
     * @return array
     */
    protected function getOriginalActivityAttributes(array $dirty): array
    {
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
        
        return $original;
    }

    /**
     * Specify the attributes that should be logged.
     *
     * @param array $attributes
     * @return $this
     */
    public function logOnly(array $attributes)
    {
        static::$logAttributes = $attributes;
        
        return $this;
    }

    /**
     * Specify the attributes that should be excluded from logging.
     *
     * @param array $attributes
     * @return $this
     */
    public function logExcept(array $attributes)
    {
        static::$logExceptAttributes = array_merge(static::$logExceptAttributes, $attributes);
        
        return $this;
    }

    /**
     * Set the log name for activity logs.
     *
     * @param string $logName
     * @return $this
     */
    public function setLogName(string $logName)
    {
        static::$logName = $logName;
        
        return $this;
    }
    
    /**
     * Determine if the change represents an activation/deactivation.
     *
     * @param mixed $oldValue
     * @param mixed $newValue
     * @return bool
     */
    protected function isActivationChange($oldValue, $newValue)
    {
        // Handle boolean values (true/false, 1/0)
        if (is_bool($oldValue) || is_bool($newValue) || 
            in_array($oldValue, [0, 1], true) || in_array($newValue, [0, 1], true)) {
            return true;
        }
        
        // Handle string status values
        $activationStatusValues = [
            'active', 'inactive',
            'enabled', 'disabled',
            'published', 'unpublished',
            'on', 'off'
        ];
        
        return in_array(strtolower($oldValue), $activationStatusValues, true) && 
               in_array(strtolower($newValue), $activationStatusValues, true);
    }
    
    /**
     * Determine if the value represents an "active" state.
     *
     * @param mixed $value
     * @return bool
     */
    protected function isActivationValue($value)
    {
        // Handle boolean values
        if (is_bool($value)) {
            return $value === true;
        }
        
        // Handle integer values
        if (in_array($value, [0, 1], true)) {
            return $value === 1;
        }
        
        // Handle string status values
        $activeValues = ['active', 'enabled', 'published', 'on', 'yes', 'true', '1'];
        
        return in_array(strtolower($value), $activeValues, true);
    }
}
```

### Example Ussage with Activation/Deactivation

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Activate a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function activate(Product $product)
    {
        // This will trigger the 'activated' activity log
        $product->status = 'active';
        $product->save();
        
        return response()->json([
            'message' => 'Product has been activated successfully',
            'data' => $product
        ]);
    }
    
    /**
     * Deactivate a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Product $product)
    {
        // This will trigger the 'deactivated' activity log
        $product->status = 'inactive';
        $product->save();
        
        return response()->json([
            'message' => 'Product has been deactivated successfully',
            'data' => $product
        ]);
    }
    
    /**
     * Bulk activate multiple products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkActivate(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);
        
        $products = Product::whereIn('id', $validated['product_ids'])->get();
        
        foreach ($products as $product) {
            $product->status = 'active';
            $product->save(); // Each save will create an 'activated' activity log
        }
        
        return response()->json([
            'message' => count($products) . ' products have been activated successfully'
        ]);
    }
    
    /**
     * Get activity logs for a product with a specific event.
     *
     * @param  \App\Models\Product  $product
     * @param  string  $event
     * @return \Illuminate\Http\Response
     */
    public function getEventLogs(Product $product, $event)
    {
        $events = [
            'all', 'created', 'updated', 'deleted', 'activated', 'deactivated'
        ];
        
        if (!in_array($event, $events)) {
            return response()->json([
                'message' => 'Invalid event type'
            ], 400);
        }
        
        $query = $product->activities();
        
        if ($event !== 'all') {
            $query->where('event', $event);
        }
        
        $logs = $query->with('causer')->latest()->paginate(15);
        
        return response()->json($logs);
    }
}
```

### Explicit Status Changes methods

```php
<?php

namespace App\Models;

use App\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Loggable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price', 'stock', 'status'
    ];
    
    /**
     * The attributes to be logged when changed.
     * If empty, all fillable attributes will be logged.
     *
     * @var array
     */
    protected static $logAttributes = [
        'name', 'price', 'status'
    ];
    
    /**
     * The log name to use for this model's activities.
     *
     * @var string
     */
    protected static $logName = 'product';
    
    /**
     * Activate the product and log the action explicitly.
     * 
     * @param string|null $reason Optional reason for activation
     * @return bool
     */
    public function activate(?string $reason = null)
    {
        // Store the current status before change
        $previousStatus = $this->status;
        
        // Update the status
        $this->status = 'active';
        $saved = $this->save();
        
        // If you want to log with additional custom properties
        if ($saved && !empty($reason)) {
            // Create an explicit activity log with the reason
            $this->logCustomActivity('activated', [
                'reason' => $reason,
                'previous_status' => $previousStatus
            ]);
        }
        
        return $saved;
    }
    
    /**
     * Deactivate the product and log the action explicitly.
     * 
     * @param string|null $reason Optional reason for deactivation
     * @return bool
     */
    public function deactivate(?string $reason = null)
    {
        // Store the current status before change
        $previousStatus = $this->status;
        
        // Update the status
        $this->status = 'inactive';
        $saved = $this->save();
        
        // If you want to log with additional custom properties
        if ($saved && !empty($reason)) {
            // Create an explicit activity log with the reason
            $this->logCustomActivity('deactivated', [
                'reason' => $reason,
                'previous_status' => $previousStatus
            ]);
        }
        
        return $saved;
    }
}
```

I've extended the activity logging system to specifically track activation and deactivation events. This provides more granular control over monitoring status changes in your models. Here's what I've added:

## 1. Automatic Detection of Status Changes

The updated `Loggable` trait now:

- Automatically detects when a model's status changes (`status`, `active`, or `is_active` fields)
- Identifies whether the change is an activation or deactivation
- Creates a specific activity log entry with either `activated` or `deactivated` event type
- Tracks both the old and new status values

## 2. Smart Status Detection

The system intelligently handles various status field formats:

- Boolean values (`true`/`false`)
- Integer values (`1`/`0`)
- String status values like:
  - `active`/`inactive`
  - `enabled`/`disabled`
  - `published`/`unpublished`
  - `on`/`off`

## 3. Explicit Activation/Deactivation Methods

For more control, I've added explicit methods to the example `Product` model:

```php
// Activate a product with an optional reason
$product->activate('Restocked and ready for sale');

// Deactivate a product with an optional reason
$product->deactivate('Out of stock');
```

These methods:
- Change the status
- Log the action explicitly
- Allow adding custom metadata (like reasons for the status change)

## 4. Custom Properties Support

The new `logCustomActivity` method allows adding any custom properties to your activity logs:

```php
$this->logCustomActivity('activated', [
    'reason' => 'Product is now in season',
    'approved_by' => $adminId,
    'previous_status' => $oldStatus
]);
```

## 5. Controller Example

I've provided a controller example showing different ways to handle activation/deactivation:

- Single item activation/deactivation
- Bulk activation of multiple items
- Retrieving logs for specific event types

## How to Use This Enhanced System

### Simple Use Case

```php
// In your controller
public function toggleStatus(Product $product)
{
    if ($product->status === 'active') {
        $product->status = 'inactive';
    } else {
        $product->status = 'active';
    }
    
    $product->save();
    
    // The Loggable trait will automatically create an 'activated' or 'deactivated' log
    
    return response()->json($product);
}
```

### Advanced Use Case with Custom Data

```php
// In your controller
public function deactivateWithReason(Product $product, Request $request)
{
    $validated = $request->validate([
        'reason' => 'required|string|max:255'
    ]);
    
    // Use the explicit method
    $product->deactivate($validated['reason']);
    
    return response()->json([
        'message' => 'Product deactivated',
        'data' => $product
    ]);
}
```

### Querying Activity Logs

```php
// Get all activation events
$activations = ActivityLog::where('event', 'activated')
    ->with('subject', 'causer')
    ->get();

// Get all deactivation events for a specific model
$deactivations = $product->activities()
    ->where('event', 'deactivated')
    ->get();

// Get activation/deactivation history with reasons
$statusChanges = ActivityLog::whereIn('event', ['activated', 'deactivated'])
    ->where('subject_type', Product::class)
    ->where('subject_id', $productId)
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($statusChanges as $change) {
    $reason = $change->properties['reason'] ?? 'No reason provided';
    echo "{$change->event} on {$change->created_at} by {$change->causer->name}: {$reason}";
}
```

This enhanced system provides a comprehensive way to track not just general model changes but also important state transitions like activation and deactivation, with detailed context about when, why, and by whom these changes were made.
