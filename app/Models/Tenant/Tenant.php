<?php

namespace App\Models\Tenant;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\CustomField;
use App\Models\Leads\CustomFieldGroup;
use App\Models\Leads\FileUpload;
use App\Models\Leads\Lead;
use App\Models\Model;
use App\Models\Users\User;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model
{
    use HasSlug;

    protected $fillable = [
        'restriction_type',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('domain');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function customFieldGroups()
    {
        return $this->hasMany(CustomFieldGroup::class)
                    ->active();
    }

    public function customFields()
    {
        return $this->hasManyThrough(CustomField::class, CustomFieldGroup::class)
                    ->whereNull('custom_fields.deactivated_at');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    public function ipRestrictions()
    {
        return $this->hasMany(RestrictedIp::class);
    }

    public function blacklistedIps()
    {
        return $this->hasMany(RestrictedIp::class)
                    ->where('blacklisted', 1);
    }

    public function whitelistedIps()
    {
        return $this->hasMany(RestrictedIp::class)
                    ->where('blacklisted', 0);
    }
}
