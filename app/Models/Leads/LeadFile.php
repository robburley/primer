<?php

namespace App\Models\Leads;

use App\Models\Model;
use App\Models\Tenant\Tenant;

class LeadFile extends Model
{
    protected $fillable = [
        'tenant_id',
        'location',
        'name',
        'hash',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function generateLink()
    {
        return sprintf(
            'https://%s.getprimer.com/files/%s/%s/%s',
            $this->tenant->domain,
            $this->tenant->id,
            $this->id,
            $this->hash
        );
    }

    public function downloadPath()
    {
        return 'app/tenants/' . $this->tenant->id . $this->location . '/' . $this->name;
    }
}
