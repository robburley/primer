<?php

namespace App\Models\Leads;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'lead_id',
        'action',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leadAssignment()
    {
        return $this->belongsTo(LeadAssignment::class, 'lead_id');
    }
}
