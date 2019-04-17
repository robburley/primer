<?php

namespace App\Models\Traits;

use Carbon\Carbon;

trait ActiveGetAndSet
{
    public function setActiveAttribute($value)
    {
        return $this->attributes['deactivated_at'] = !$value
            ? Carbon::now()
            : null;
    }

    public function getActiveAttribute()
    {
        return $this->deactivated_at
            ? 0
            : 1;
    }
}
