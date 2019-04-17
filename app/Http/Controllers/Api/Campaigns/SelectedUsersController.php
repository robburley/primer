<?php

namespace App\Http\Controllers\Api\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Users\User;

class SelectedUsersController extends Controller
{
    public function store(Campaign $campaign)
    {
        $campaign->users()->attach(request()->get('user')['value']);

        return $campaign->users;
    }

    public function update(Campaign $campaign, User $user)
    {
        $current = request()->get('user');

        $campaign->users()->syncWithoutDetaching([
            $user->id => [
                'supervisor'      => $current['pivot']['supervisor'] ?? $user->pivot->supervisor,
                'create_new_lead' => $current['pivot']['create_new_lead'] ?? $user->pivot->create_new_lead,
                'update_lead'     => $current['pivot']['update_lead'] ?? $user->pivot->update_lead,
                'confirm_lead'    => $current['pivot']['confirm_lead'] ?? $user->pivot->confirm_lead,
            ],
        ]);

        return $campaign->users;
    }

    public function destroy(Campaign $campaign, User $user)
    {
        $campaign->users()->detach($user->id);

        return $campaign->users;
    }
}
