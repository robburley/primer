<?php

namespace App\Models\Leads;

use App\Models\Model;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use Illuminate\Support\Facades\Validator;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CustomField extends Model
{
    use HasSlug, CommonScopes, ActiveGetAndSet;

    protected $fillable = [
        'name',
        'deactivated_at',
        'active',
        'name',
        'type',
        'placeholder',
        'default',
        'options',
        'bespoke_form_field_id',
        'custom_field_group_id',
    ];

    protected $with = [
        'bespokeFormField',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug')
                          ->allowDuplicateSlugs();
    }

    public function customFieldGroup()
    {
        return $this->belongsTo(CustomFieldGroup::class);
    }

    public function bespokeFormField()
    {
        return $this->belongsTo(BespokeFormField::class, 'bespoke_form_field_id');
    }

    public function rules()
    {
        return $this->belongsToMany(CustomFieldValidation::class)
                    ->withPivot('argument');
    }

    public function getValue($value)
    {
        if (!$value) {
            $value = $this->default;
        }

        return $this->bespokeFormField->getClass()
                                      ->formatData($value);
    }

    public function processValue($value)
    {
        if (!$value) {
            $value = $this->default;
        }

        return $this->bespokeFormField->getClass()
                                      ->processData($value);
    }

    public function getRules($field)
    {
        $rules = $this->rules->map(function ($rule) use ($field) {
            return $rule->render($field);
        })->flatten();

        $predefinedRules = collect($this->bespokeFormField->getClass()->getValidationRules());

        return $rules->count() > 0
            ? $rules->merge($predefinedRules)->toArray()
            : $predefinedRules->toArray();
    }

    public function validateData($value, $field)
    {
        return Validator::make(["$this->name" => $value], [
            "$this->name" => $this->getRules($field),
        ]);
    }

    public function render($value = null, $readonly = false)
    {
        return $this->bespokeFormField->render($this, $value, $readonly);
    }

    public function formatOptions($data)
    {
        return collect($data)->keyBy(function ($item) {
            return $item;
        });
    }
}
