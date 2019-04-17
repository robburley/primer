<?php

namespace App\Models\Leads;

use App\Models\Model;
use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;

class TemporaryLead extends Model
{
    use CommonScopes, ActiveGetAndSet;

    protected $casts = [
        'deactivated_at' => 'timestamp',
        'imported_at'    => 'timestamp',
    ];

    protected $fillable = [
        'data',
        'deactivated_at',
        'imported_at',
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
