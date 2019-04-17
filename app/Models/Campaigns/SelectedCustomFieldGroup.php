<?php

namespace App\Models\Campaigns;

use App\Models\Leads\CustomFieldGroup;
use Illuminate\Database\Eloquent\Model;

class SelectedCustomFieldGroup extends Model
{
    protected $fillable = [
        'campaign_id',
        'custom_field_group_id',
        'order',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function customFieldGroup()
    {
        return $this->belongsTo(CustomFieldGroup::class);
    }

    public function selectedCustomFields()
    {
        return $this->hasMany(SelectedCustomField::class)
                    ->orderBy('order');
    }

    public function selectedCustomFieldsToShowToAgent()
    {
        return $this->hasMany(SelectedCustomField::class)
                    ->where('show_to_lead_generator', 1)
                    ->orderBy('order');
    }
}
