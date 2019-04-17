<?php

namespace App\Models\Leads;

use App\Models\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class CustomFieldValidation extends Model
{
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'rule',
        'has_args',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    public function fields()
    {
        return $this->belongsToMany(CustomField::class);
    }

    public function bespokeFormField()
    {
        return $this->belongsToMany(BespokeFormField::class);
    }

    public function render($field)
    {
        if ($this->has_class) {
            $class = "App\Rules\\$this->rule";

            return new $class($field);
        }

        return collect(explode('|', $this->rule))
            ->map(function ($current) {
                return str_contains($current, ':')
                    ? $current . $this->pivot->argument
                    : $current;
            });
    }
}
