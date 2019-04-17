<?php

namespace App\Models\Campaigns;

use App\Models\Leads\CustomField;
use App\Models\Model;

class Scope extends Model
{
    protected $fillable = [
        'campaign_id',
        'custom_field_id',
        'operator',
        'value',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
