<?php

namespace App\Models\Leads;

use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use App\Models\Model;

class InvalidLead extends Model
{
    use CommonScopes, ActiveGetAndSet;

    protected $fillable = [
        'name',
        'data',
        'validation_errors',
        'deactivated_at',
        'active',
        'file_upload_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }
}
