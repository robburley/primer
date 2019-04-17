<?php

namespace App\Models\Leads;

use App\Models\Model;
use App\Models\Tenant\Tenant;

class FileUpload extends Model
{
    protected $import;

    protected $casts = [
        'error_at'          => 'timestamp',
        'analysed_at'       => 'timestamp',
        'fields_mapped_at'  => 'timestamp',
        'import_started_at' => 'timestamp',
    ];

    protected $fillable = [
        'name',
        'location',
        'total',
        'processed_leads',
        'invalid_leads',
        'valid_leads',
        'import_started_at',
        'imported_leads',
        'error_at',
        'error_text',
        'analysed_at',
        'fields_mapped_at',
        'status',
        'running',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function headings()
    {
        return $this->hasMany(FileUploadHeading::class);
    }

    public function invalidLeads()
    {
        return $this->hasMany(InvalidLead::class);
    }

    public function temporaryLeads()
    {
        return $this->hasMany(TemporaryLead::class);
    }

    public function storeFile($file)
    {
        $location = 'tenants/' . auth()->user()->tenant->id . '/file-uploads/';

        $fileName = $this->id . '-' . $file->getClientOriginalName();

        $file->storeAs($location, $fileName);

        return $location . $fileName;
    }
}
