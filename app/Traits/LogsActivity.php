<?php

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

                // Set the actor (e.g. currently logged in user)
                if ($user = Auth::user()) {
                    $activity->actor()->associate($user);
                }

                $activity->save();
            });
        }
    }

    protected static function getActivityDescription(string $event, $model): string
    {
        return $event . " " . class_basename($model);
    }
}
