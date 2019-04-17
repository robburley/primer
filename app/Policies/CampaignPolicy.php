<?php

namespace App\Policies;

use App\Models\Campaigns\Campaign;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Campaign $campaign)
    {
        return $user->tenant_id == $campaign->tenant_id;
    }
}
