<?php

namespace App\Models\Traits;

trait CommonScopes
{
    public function scopeActive($query)
    {
        return $query->whereNull($this->table . '.deactivated_at');
    }

    public function createdBetween($query, $dates)
    {
        return $query->whereBetween($this->table . '.created_at', $dates);
    }
}
