<?php

namespace App\Models\Leads;

use App\Models\Campaigns\Campaign;
use App\Models\Campaigns\CampaignInvalidReason;
use App\Models\Model;
use App\Models\Users\User;

class LeadAssignment extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'callback',
        'completed_at',
        'confirmed_at',
        'rejected_at',
        'sent_at',
        'qualified_at',
    ];

    protected $table = 'campaign_lead';

    protected $fillable = [
        'campaign_id',
        'lead_id',
        'assigned_id',
        'callback',
        'completed_at',
        'confirmed_at',
        'confirmed_by',
        'rejected_at',
        'sent_at',
        'invalid_comment',
        'qualified_at',
        'dealt_at',
        'gp',
    ];

    protected $with = [
        'campaign',
        'lead',
        'assigned',
        'validator',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assigned()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function invalidReasons()
    {
        return $this->hasMany(CampaignInvalidReason::class, 'campaign_pivot_id')
                    ->with(['invalidReason']);
    }

    public function notes()
    {
        return $this->hasMany(CampaignLeadNote::class, 'lead_assignment_id')
                    ->orderBy('created_at', 'desc');
    }

    public function accessLog()
    {
        return $this->hasMany(AccessLog::class, 'lead_id')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeInvalid($query)
    {
        $query
            ->where('completed_at', '<>', null)
            ->where('rejected_at', '<>', null)
            ->where('sent_at', null)
            ->orderBy('completed_at', 'asc');
    }
}
