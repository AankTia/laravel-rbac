<?php

namespace App;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait TimestampAndUserTrackingTrait
 * 
 * This trait provides formatted accessors for created_at, updated_at timestamps
 * and relationship methods for created_by, updated_by user fields.
 * 
 * Requirements:
 * - Your model should use Laravel timestamps
 * - Your database should have created_by and updated_by columns (both unsigned big integers)
 */
trait TimestampAndUserTrackingTrait
{
    /**
     * Boot the trait
     */
    public static function bootTimestampAndUserTrackingTrait()
    {
        // Automatically set created_by on model creation
        static::creating(function ($model) {
            if (!$model->isDirty('created_by_id') && Auth::check()) {
                $model->created_by_id = Auth::id();
            }
        });

        // Automatically set updated_by on model update
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->last_updated_by_id = Auth::id();
            }
        });
    }

    /**
     * Get the user who created this record
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the user who last updated this record
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastUpdater()
    {
        return $this->belongsTo(User::class, 'last_updated_by_id');
    }

    /**
     * Get the creator's name
     * 
     * @return string|null
     */
    public function creatorName()
    {
        return $this->creator ? $this->creator->name : null;
    }

    /**
     * Get the updater's name
     * 
     * @return string|null
     */
    public function lastUpdaterName()
    {
        return $this->lastUpdater ? $this->lastUpdater->name : null;
    }

    /**
     * Get the formatted created_at timestamp (e.g., 03 May 2025, 00:47)
     * 
     * @param string $format The date format (default: 'd M Y, H:i')
     * @return string
     */
    public function createdAt($format = 'd M Y, H:i')
    {
        return $this->created_at instanceof Carbon
            ? $this->created_at->format($format)
            : null;
    }

    /**
     * Get the formatted updated_at timestamp (e.g., 03 May 2025, 00:47)
     * 
     * @param string $format The date format (default: 'd M Y, H:i')
     * @return string
     */
    public function lastUpdate($format = 'd M Y, H:i')
    {
        if ($this->created_at == $this->updated_at) {
            return null;
        } else {
            return $this->updated_at instanceof Carbon
                ? $this->updated_at->format($format)
                : null;
        }
    }

    // /**
    //  * Get the human-readable created_at timestamp (e.g., "2 hours ago")
    //  * 
    //  * @return string
    //  */
    // public function getHumanCreatedAtAttribute()
    // {
    //     return $this->created_at instanceof Carbon
    //         ? $this->created_at->diffForHumans()
    //         : null;
    // }

    // /**
    //  * Get the human-readable updated_at timestamp (e.g., "5 minutes ago")
    //  * 
    //  * @return string
    //  */
    // public function getHumanUpdatedAtAttribute()
    // {
    //     return $this->updated_at instanceof Carbon
    //         ? $this->updated_at->diffForHumans()
    //         : null;
    // }

    // /**
    //  * Get creation information in a formatted string
    //  * 
    //  * @return string
    //  */
    // public function getCreationInfoAttribute()
    // {
    //     if (!$this->created_at) {
    //         return 'Not created yet';
    //     }

    //     $time = $this->human_created_at;
    //     $user = $this->creator_name ?? 'Unknown user';

    //     return "Created $time by $user";
    // }

    // /**
    //  * Get update information in a formatted string
    //  * 
    //  * @return string
    //  */
    // public function getUpdateInfoAttribute()
    // {
    //     if (!$this->updated_at) {
    //         return 'Not updated yet';
    //     }

    //     $time = $this->human_updated_at;
    //     $user = $this->updater_name ?? 'Unknown user';

    //     return "Updated $time by $user";
    // }
}
