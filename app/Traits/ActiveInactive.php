<?php

namespace App\Traits;

trait ActiveInactive
{
    public function activate($attribute = 'is_active')
    {
        return $this->update([ $attribute => 1 ]);
    }

    public function deactivate($attribute = 'is_active') {
        return $this->update([ $attribute => 0 ]);
    }
}
