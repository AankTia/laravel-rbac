<?php

namespace App\Traits;

trait TracksChanges
{
    protected $isNewRecord = false;
    protected $originalAttributes = [];
    protected $changedAttributes = [];
    
    public static function bootTracksChanges()
    {
        // Before create
        static::creating(function ($model) {
            $model->isNewRecord = true;
            $model->changedAttributes = $model->getAttributes();
        });

        // Before update
        static::updating(function ($model) {
            $model->originalAttributes = $model->getOriginal();
        });

        // After update
        static::updated(function ($model) {
            $model->changedAttributes = $model->getChanges();
        });
        
        // static::updating(function ($model) {
        //     $model->changedAttributes = $model->getDirty();
        // });
    }

    public function getOriginalAttributes()
    {
        return $this->originalAttributes;
    }
    
    public function getChangedAttributes()
    {
        return $this->changedAttributes;
    }
    
    public function isNewRecord()
    {
        return $this->isNewRecord;
    }
}