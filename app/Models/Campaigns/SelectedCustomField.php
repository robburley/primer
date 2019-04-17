<?php

namespace App\Models\Campaigns;

use App\Models\Leads\CustomField;
use Illuminate\Database\Eloquent\Model;

class SelectedCustomField extends Model
{
    protected $fillable = [
        'selected_custom_field_group_id',
        'custom_field_id',
        'show_to_lead_generator',
        'show_to_customers',
        'required_for_completion',
        'show_to_confirmer',
        'order',
    ];

    public function selectedCustomFieldGroup()
    {
        return $this->belongsTo(SelectedCustomFieldGroup::class);
    }

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function getName()
    {
        ucwords(str_replace('-', ' ', $this->customField->name));
    }

    public function getValidationRules()
    {
        return $this->customField->getRules($this->customField->bespokeFormField);
    }
}
