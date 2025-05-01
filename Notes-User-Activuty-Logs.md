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