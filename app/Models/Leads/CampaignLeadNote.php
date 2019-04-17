<?php

namespace App\Models\Leads;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class CampaignLeadNote extends Model
{
    protected $fillable = [
        'note',
        'user_id',
        'lead_assignment_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leadAssignment()
    {
        return $this->belongsTo(LeadAssignment::class, 'lead_assignment_id');
    }
}
