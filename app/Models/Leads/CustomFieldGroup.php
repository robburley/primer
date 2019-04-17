<?php

namespace App\Models\Leads;

use App\Models\Campaigns\SelectedCustomFieldGroup;
use App\Models\Model;
use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CustomFieldGroup extends Model
{
    use HasSlug, CommonScopes, ActiveGetAndSet;

    protected $fillable = [
        'name',
        'deactivated_at',
        'active',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customFields()
    {
        return $this->hasMany(CustomField::class)
                    ->active();
    }

    public function selectedCustomFieldGroups()
    {
        return $this->hasMany(SelectedCustomFieldGroup::class);
    }
}
