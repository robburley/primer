<?php

namespace App\Policies;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use App\Models\Users\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Lead $lead, Campaign $campaign)
    {
        $userTenant = $user->tenant_id == $campaign->tenant_id;

        $leadCampaign = $lead->campaigns->contains($campaign);

        $campaignUser = $lead->leadAssignment()
                             ->where('campaign_id', $campaign->id)
                             ->where('assigned_id', $user->id)
                             ->count() > 0;

        $campaignSupervisor = $campaign->users()
                                       ->wherePivot('supervisor', 1)
                                       ->get()
                                       ->contains($user);

        return $userTenant && $leadCampaign && ($campaignUser || $campaignSupervisor);
    }
}
