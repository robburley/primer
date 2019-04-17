<?php

namespace App\Models\Leads;

use App\Models\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class FileUploadHeading extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'custom_field_id',
        'default_field_id',
        'array_key',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug')
                          ->allowDuplicateSlugs();
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }
}
