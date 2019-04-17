<?php

namespace App\Models\Campaigns;

use App\Helpers\Scopes\ScopeQuery;
use App\Models\Leads\CustomField;
use App\Models\Leads\InvalidReason;
use App\Models\Leads\Lead;
use App\Models\Model;
use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use App\Models\Users\User;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Campaign extends Model
{
    use HasSlug, CommonScopes, ActiveGetAndSet;

    protected $fillable = [
        'name',
        'deactivated_at',
        'active',
        'endpoint_type',
        'endpoint_location',
        'lead_order',
        'validate_leads',
        'primary_name_field_id',
        'primary_telephone_field_id',
        'primary_email_field_id',
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

    public function selectedCustomFieldGroups()
    {
        return $this->hasMany(SelectedCustomFieldGroup::class)
                    ->orderBy('order');
    }

    public function selectedCustomFields()
    {
        return $this->hasManyThrough(SelectedCustomField::class, SelectedCustomFieldGroup::class)
                    ->with([
                        'customField',
                    ]);
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class)
                    ->withPivot([
                        'callback',
                        'assigned_id',
                        'completed_at',
                        'confirmed_at',
                        'confirmed_by',
                        'sent_at',
                    ]);
    }

    public function scopes()
    {
        return $this->hasMany(Scope::class);
    }

    public function invalidReasons()
    {
        return $this->hasMany(InvalidReason::class);
    }

    public function activeInvalidReasons()
    {
        return $this->hasMany(InvalidReason::class)
                    ->whereNull('invalid_reasons.deactivated_at');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot([
                        'supervisor',
                        'create_new_lead',
                        'update_lead',
                        'confirm_lead',
                    ]);
    }

    public function currentUser()
    {
        return $this->users()
                    ->where('users.id', auth()->user()->id)
                    ->first();
    }

    public function primaryNameField()
    {
        return $this->belongsTo(CustomField::class, 'primary_name_field_id');
    }

    public function primaryTelephoneField()
    {
        return $this->belongsTo(CustomField::class, 'primary_telephone_field_id');
    }

    public function primaryEmailField()
    {
        return $this->belongsTo(CustomField::class, 'primary_email_field_id');
    }

    public function getTotalLeadsAttribute()
    {
        try {
            return $this->getLeads(false)
                        ->count();
        } catch (\Exception $e) {
            return 'There was an error, please check your scopes';
        }
    }

    public function requestLead($order = 'first', $includeAssigned = false, $query = null)
    {
        $leads = $this->getLeads($includeAssigned, $query);

        return $leads->count() > 0
            ? $leads->{$order}()
            : null;
    }

    public function getLeads($includeAssigned = true, $query = null)
    {
        return (new ScopeQuery($this->scopes->load(['customField.bespokeFormField']), $this, $includeAssigned, $query))
            ->handle();
    }

    public function getValidationRules()
    {
        return $this->selectedCustomFields
            ->keyBy('customField.slug')
            ->map(function ($selectedCustomField) {
                return $selectedCustomField->getValidationRules();
            })
            ->toArray();
    }

    public function selectedFieldNames()
    {
        return $this->selectedCustomFields
            ->keyBy('customField.slug')
            ->map(function ($item) {
                return $item->getName();
            })
            ->toArray();
    }

    public function formatData()
    {
        $fields = $this->selectedCustomFields->pluck('customField');

        return collect(request()->except(['_token']))
            ->map(function ($value, $key) use ($fields) {
                $currentField = $fields->firstWhere('slug', $key);

                return $currentField
                    ? $currentField->getValue($value)
                    : $value;
            })
            ->toArray();
    }

    public function processData()
    {
        $fields = $this->selectedCustomFields->pluck('customField');

        return collect(request()->except(['_token', 'callback_date', 'callback_time']))
            ->map(function ($value, $key) use ($fields) {
                $currentField = $fields->firstWhere('slug', $key);

                return $currentField
                    ? $currentField->processValue($value)
                    : $value;
            })
            ->toArray();
    }
}
