<?php

namespace App\Models\Leads;

use App\Models\Campaigns\Campaign;
use App\Models\Traits\ActiveGetAndSet;
use Illuminate\Database\Eloquent\Model;

class InvalidReason extends Model
{
    use ActiveGetAndSet;

    protected $fillable = [
        'title',
        'description',
        'active',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
