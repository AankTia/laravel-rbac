# User Activity Logs

Logging **user activity** in Laravel is a great way to track actions like logins, updates, deletes, or anything critical to your app’s flow.

Here’s a clean and scalable way to implement user activity logging:

---

## **Option 1: Custom User Activity Logger**

### 1. **Create a helper/service for activity logs**

```bash
php artisan make:model ActivityLog -m
```

**Migration (`database/migrations/xxxx_xx_xx_create_activity_logs_table.php`):**

```php
Schema::create('activity_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('action');
    $table->text('description')->nullable();
    $table->string('ip_address')->nullable();
    $table->timestamps();
});
```

**Model (`app/Models/ActivityLog.php`):**

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

---

### 2. **Create a helper function**

You could put this in a trait or helper class:

```php
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

function log_activity($action, $description = null)
{
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => $action,
        'description' => $description,
        'ip_address' => Request::ip(),
    ]);
}
```

Then use it like:

```php
log_activity('user_login', 'User logged in successfully');
log_activity('deleted_post', "Deleted post ID: $postId");
```

---

## **Option 2: Event Listener (More Decoupled)**

You could fire a custom event like `UserLoggedIn`, and have a listener log that to the DB. Better for cleaner code and reusability.

---

## **Option 3: Log File Instead of DB**

If you prefer writing to a **log file** instead:

```php
Log::channel('custom')->info('User logged in', [
    'user_id' => Auth::id(),
    'ip' => request()->ip()
]);
```

Define a `custom` channel in `config/logging.php` to write to something like `storage/logs/user_activity.log`.

---

Want me to help you wire up the DB-based logger or the file-based one in your current Laravel project?


Great use case — having an **Activity Log** that tracks actions across multiple **modules** (like `User`, `Order`, `Product`, etc.) is a perfect scenario for **polymorphic relationships** in Laravel.

---

## 🛠️ Goal

You want an `ActivityLog` model that can store logs for different models like `User`, `Product`, etc., with details such as:

* Who did it (`causer`)
* What was done (`action`)
* On what module/model (`subject`)

This setup will use **two polymorphic relationships**:

1. `subject()` — the model the action was performed on.
2. `causer()` — the model that performed the action (usually a User or Admin).

---

## 📦 Step-by-Step Implementation

---

### 🔸 Migration: `activity_logs` Table

```bash
php artisan make:migration create_activity_logs_table
```

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTable extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->text('description')->nullable();

            // Polymorphic relation to the affected model
            $table->morphs('subject');

            // Polymorphic relation to the actor/causer (like a user/admin)
            $table->nullableMorphs('causer');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
}
```

---

### 🔸 Model: `ActivityLog`

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'action',
        'description',
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }
}
```

---

### 🔸 Example Tracked Models

Let’s say you want to track actions on a `Product` and a `User`.

#### In `Product` model:

```php
public function activityLogs()
{
    return $this->morphMany(ActivityLog::class, 'subject');
}
```

#### In `User` model (for both subject and causer):

```php
// As the subject of an action
public function activityLogs()
{
    return $this->morphMany(ActivityLog::class, 'subject');
}

// As the one who did the action
public function causedActivities()
{
    return $this->morphMany(ActivityLog::class, 'causer');
}
```

---

### 🔸 Usage Examples

```php
// Logging a product update by a user
ActivityLog::create([
    'action' => 'updated',
    'description' => 'Updated product price',
])->subject()->associate($product)
  ->causer()->associate($user)
  ->save();
```

---

### 🔎 Query Examples

* Get all logs for a product:

```php
$product->activityLogs;
```

* Get all logs done **by a user**:

```php
$user->causedActivities;
```

---

Would you like me to help you create a trait to auto-log activities in models, or build an event listener-based approach to automate this?


Perfect — creating a **trait** to auto-log activities is a clean and reusable way to implement activity logging across your Laravel models.

We’ll build a trait that:

* Hooks into Eloquent model events (`created`, `updated`, `deleted`)
* Automatically logs the event
* Associates both the **subject** (model being acted on) and the **causer** (usually the currently authenticated user)

---

## 🔁 Step-by-Step: Auto Activity Logging Trait

---

### 📄 Step 1: Create the Trait

Create a file in `app/Traits/LogsActivity.php`:

```php
namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $description = static::getActivityDescription($event, $model);

                $activity = new ActivityLog([
                    'action' => $event,
                    'description' => $description,
                ]);

                // Set the subject (the model that was changed)
                $activity->subject()->associate($model);

                // Set the causer (e.g. currently logged in user)
                if ($user = Auth::user()) {
                    $activity->causer()->associate($user);
                }

                $activity->save();
            });
        }
    }

    protected static function getActivityDescription(string $event, $model): string
    {
        return class_basename($model) . " was {$event}";
    }
}
```

---

### 🧱 Step 2: Use the Trait in Your Models

Add the trait to any model you want to track:

```php
use App\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    // your other logic...
}
```

This will now **automatically log** `created`, `updated`, and `deleted` events on `Product`.

---

### ✅ Resulting Log

A new record in `activity_logs` like:

```json
{
  "action": "updated",
  "description": "Product was updated",
  "subject_id": 1,
  "subject_type": "App\\Models\\Product",
  "causer_id": 2,
  "causer_type": "App\\Models\\User"
}
```

---

### 🧩 Optional Enhancements

You can make the trait even better:

#### 1. Allow custom activity messages:

```php
protected static function getActivityDescription(string $event, $model): string
{
    if (method_exists($model, 'getCustomActivityDescription')) {
        return $model->getCustomActivityDescription($event);
    }

    return class_basename($model) . " was {$event}";
}
```

Then in your model:

```php
public function getCustomActivityDescription($event)
{
    return "Product '{$this->name}' was {$event}";
}
```

#### 2. Only log certain actions:

You can add a property to control which events to log:

```php
protected static function bootLogsActivity()
{
    $events = property_exists(static::class, 'logEvents') ? static::$logEvents : ['created', 'updated', 'deleted'];

    foreach ($events as $event) {
        static::$event(function ($model) use ($event) {
            // ... same logic
        });
    }
}
```

And in the model:

```php
protected static $logEvents = ['updated'];
```

---

Would you like this turned into a full Laravel package or service provider? Or integrated with a job queue for async logging?
