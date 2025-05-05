<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        if (Auth::id()) {
            foreach (['created', 'updated', 'deleted'] as $event) {
                static::$event(function ($model) use ($event) {
                    $description = static::getActivityDescription($event, $model);

                    $skipTimestampAttributes = ['created_at', 'updated_at', 'deleted_at', 'created_by_id', 'last_updated_by_id'];

                    $properties = null;
                    if ($event === 'created') {
                        foreach ($model->originalAttributes as $attributeName => $value) {
                            if (in_array($attributeName, $skipTimestampAttributes)) {
                                continue;
                            }
                            
                            $properties[$attributeName] = $value;
                        }
                    } elseif ($event === 'updated') {
                        $oldAttributes = $model->getOriginalAttributes();
                        $changedAttributes = $model->getChangedAttributes();
                        
                        foreach ($changedAttributes as $attributeName => $newValue) {
                            if (in_array($attributeName, $skipTimestampAttributes)) {
                                continue;
                            }
                            
                            $properties[$attributeName] = [
                                'before' => $oldAttributes[$attributeName],
                                'after' => $newValue
                            ];
                        }
                    }
    
                    $activity = new ActivityLog([
                        'log_name' => class_basename($model),
                        'user_id' => Auth::id(), // Set the actor (e.g. currently logged in user)
                        'action' => $event,
                        'description' => $description,
                        'properties' => ($properties ? $properties : null)
                    ]);
    
                    // Set the subject (the model that was changed)
                    $activity->subject()->associate($model);
    
                    $activity->save();
                });
            }
        }
    }

    protected static function getActivityDescription(string $event, $model): string
    {
        if (method_exists($model, 'getCustomActivityDescription')) {
            return $model->getCustomActivityDescription($event);
        }

        return ucfirst($event) . " " . class_basename($model);
    }
}
