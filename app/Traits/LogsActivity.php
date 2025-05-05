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

                    $skipTimestampAttributes = ['created_at', 'updated_at'];

                    $properties = [];
                    if ($event === 'created') {
                        dd($event);
                    } elseif ($event === 'updated') {
                        $oldAttributes = $model->getOriginalAttributes();
                        $changedAttributes = $model->getChangedAttributes();

                        // $changesData = [];
                        foreach ($changedAttributes as $attributeName => $newValue) {
                            if (in_array($attributeName, $skipTimestampAttributes)) {
                                continue;
                            }
                            
                            $properties[$attributeName] = [
                                'before' => $oldAttributes[$attributeName],
                                'after' => $newValue
                            ];
                        }
                        // dd($changesData);
                        // $properties = 

                        // dd([$old, $new]);
                    } elseif ($event === 'deleted') {
                        dd($event);
                    }
    
                    $activity = new ActivityLog([
                        'log_name' => class_basename($model),
                        'user_id' => Auth::id(), // Set the actor (e.g. currently logged in user)
                        'action' => $event,
                        'description' => $description,
                        'properties' => json_encode($properties)
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
