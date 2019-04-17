<?php

namespace App\Models\Leads;

use App\Models\Campaigns\Campaign;
use App\Models\Campaigns\CampaignInvalidReason;
use App\Models\Model;
use App\Models\Tenant\Tenant;
use App\Models\Traits\ActiveGetAndSet;
use App\Models\Traits\CommonScopes;
use App\Models\Users\User;
use Carbon\Carbon;

class Lead extends Model
{
    use CommonScopes, ActiveGetAndSet;

    protected $fillable = [
        'data',
        'tenant_id',
        'deactivated_at',
        'active',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class)
                    ->withPivot([
                        'id',
                        'callback',
                        'assigned_id',
                        'created_at',
                        'updated_at',
                        'completed_at',
                        'confirmed_at',
                        'confirmed_by',
                        'sent_at',
                        'rejected_at',
                    ]);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'campaign_lead',
            'lead_id',
            'assigned_id'
        )
                    ->withPivot([
                        'callback',
                        'campaign_id',
                    ]);
    }

    public function validator()
    {
        return $this->belongsToMany(
            User::class,
            'campaign_lead',
            'lead_id',
            'confirmed_by'
        )
                    ->withPivot([
                        'callback',
                        'campaign_id',
                    ]);
    }

    public function leadAssignment()
    {
        return $this->hasMany(LeadAssignment::class);
    }

    public function campaignData($campaign)
    {
        return $this->leadAssignment
            ->where('campaign_id', $campaign->id)
            ->first();
    }

    public function updateData($data)
    {
        $data = collect($data)
            ->map(function ($data, $key) {
                return [
                    'key'  => $key,
                    'data' => $data,
                ];
            })
            ->keyBy('key')
            ->map(function ($item) {
                return $item['data'];
            });

        $this->update([
            'data' => collect(json_decode($this->data))->merge($data),
        ]);
    }

    public function getInvalidReasons($campaign)
    {
        return $this->campaignData($campaign)->invalidReasons;
    }

    public function attachToCampaign($campaign, $callbackDateTime)
    {
        $this->campaigns()->attach($campaign->id, [
            'assigned_id' => auth()->user()->id,
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
            'callback'    => Carbon::createFromFormat(
                'd/m/Y h:iA',
                $callbackDateTime
            ),
        ]);
    }

    public function submit($campaign)
    {
        $this->campaigns()
             ->updateExistingPivot($campaign->id, [
                 'completed_at'   => null,
                 'confirmed_at'   => null,
                 'rejected_at' => null,
                 'confirmed_by'   => null,
                 'sent_at'        => null,
                 'updated_at'     => Carbon::now(),
                 'callback'       => Carbon::createFromFormat(
                     'd/m/Y h:iA',
                     request()->get('callback_date') . ' ' . request()->get('callback_time')
                 ),
             ]);
    }

    public function clearInvalidReasons($campaign)
    {
        $this->getInvalidReasons($campaign)
             ->each(function ($reason) {
                 $reason->delete();
             });
    }

    public function invalidate($campaign)
    {
        $this->campaigns()
             ->updateExistingPivot($campaign->id, [
                 'rejected_at'  => Carbon::now(),
                 'confirmed_by'    => auth()->user()->id,
                 'invalid_comment' => request()->get('invalid_comment'),
             ]);

        foreach (request()->get('invalid_reasons') as $invalidReason) {
            CampaignInvalidReason::create([
                'campaign_pivot_id' => $this->campaignData($campaign)->id,
                'invalid_reason_id' => $invalidReason,
            ]);
        }
    }

    public function isComplete($campaign)
    {
        return $this->campaignData($campaign)->completed_at;
    }

    public function isInvalid($campaign)
    {
        return $this->campaignData($campaign)->rejected_at;
    }

    public function isValid($campaign)
    {
        return $this->campaignData($campaign)->confirmed_at;
    }

    public function isSent($campaign)
    {
        return $this->campaignData($campaign)->sent_at;
    }

    public function isCallback($campaign)
    {
        if ($callback = $this->campaignData($campaign)->callback) {
            return $callback->lessThanOrEqualTo(Carbon::now());
        }

        return false;
    }
}
