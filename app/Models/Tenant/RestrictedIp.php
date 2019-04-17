<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class RestrictedIp extends Model
{
    protected $fillable = [
        'address',
        'blacklisted',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
