<?php

namespace App\Helpers;

use App\Models\Leads\InvalidReason;
use App\Models\Users\Role;

class FormPopulator
{
    public static function roles()
    {
        return Role::active()->get()->pluck('name', 'id');
    }

    public static function yesNo()
    {
        return [
            1 => 'Yes',
            0 => 'No',
        ];
    }

    public static function campaigns()
    {
        return auth()->user()->tenant->campaigns()
                                     ->active()
                                     ->get();
    }

    public static function campaignEndpoints()
    {
        return [
            'None',
            'API',
            'Email',
        ];
    }

    public static function activeUsers()
    {
        return auth()->user()->tenant->users()
                                     ->whereNull('deactivated_at')
                                     ->get();
    }

    public static function leadOrderingTypes()
    {
        return [
            'first'  => 'Newest To Oldest',
            'last'   => 'Oldest To Newest',
            'random' => 'Random',
        ];
    }

    public static function invalidReasons($campaign)
    {
        return InvalidReason::where('campaign_id', $campaign->id)
                            ->whereNull('invalid_reasons.deactivated_at')
                            ->get();
    }

    public static function selectedCustomFields($campaign)
    {
        if ($campaign->selectedCustomFields->count() > 0) {
            return ['' => 'Please select'] +
                $campaign->selectedCustomFields->pluck(
                    'customField.name',
                    'customField.id'
                )->toArray();
        }

        return [
            '' => 'Please select custom fields first',
        ];
    }

    public static function campaignUsers()
    {
        return auth()->user()->campaignsSupervisor->pluck('users')->flatten()->unique()->pluck('full_name', 'id');
    }
}
