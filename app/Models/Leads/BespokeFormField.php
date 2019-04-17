<?php

namespace App\Models\Leads;

use App\Helpers\PredefinedFields\PredefinedField;
use App\Models\Model;

class BespokeFormField extends Model
{
    protected $fillable = [
        'name',
        'class',
        'has_placeholder',
        'has_default',
        'has_rules',
    ];

    public function getClass()
    {
        if (!$this->class) {
            return (new PredefinedField($this));
        }

        $class = 'App\Helpers\PredefinedFields\\' . $this->class;

        return new $class($this);
    }

    public function rules()
    {
        return $this->belongsToMany(CustomFieldValidation::class);
    }

    public function render(CustomField $customField, $value = null, $readonly = false)
    {
        return $this->getClass()->render($customField, $value, $readonly);
    }
}
