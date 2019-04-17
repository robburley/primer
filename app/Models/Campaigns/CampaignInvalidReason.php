<?php

namespace App\Models\Campaigns;

use App\Models\Leads\InvalidReason;
use Illuminate\Database\Eloquent\Model;

class CampaignInvalidReason extends Model
{
    protected $fillable = [
        'campaign_pivot_id',
        'invalid_reason_id',
    ];

    public function invalidReason()
    {
        return $this->belongsTo(InvalidReason::class);
    }
}
