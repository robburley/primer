<?php

namespace App\Helpers\Campaigns;

use App\Models\Campaigns\Campaign;
use App\Models\Leads\LeadAssignment;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CallbackSearch
{
    protected $user;
    protected $campaigns;

    public function __construct($user, Collection $campaigns)
    {
        $user = User::findTenantUser($user)->first();

        !$user && abort(403);

        $this->user = $user;

        $this->campaigns = Campaign::whereIn('id', $campaigns)
                                   ->get();
    }

    public function handle()
    {
        return [
            $this->search(),
            $this->countTomorrow(),
        ];
    }

    public function search()
    {
        return $this->campaigns
            ->map(function ($campaign) {
                $leads = LeadAssignment::where('campaign_id', $campaign->id)
                                       ->where(function ($query) use ($campaign) {
                                           if ($campaign->currentUser() && $campaign->currentUser()->pivot->confirm_lead) {
                                               return $query->whereNull('sent_at');
                                           }

                                           return $query->whereNull('completed_at')
                                                        ->orWhereNotNull('rejected_at');
                                       })
                                       ->when(!request()->has('search_term'), function ($query) {
                                           $query->where('callback', '<=', Carbon::now()->endOfDay());
                                       })
                                       ->where(function ($query) use ($campaign) {
                                           if ($campaign->currentUser() && $campaign->currentUser()->pivot->confirm_lead) {
                                               return $query->where('assigned_id', $this->user->id)
                                                            ->where('confirmed_by', $this->user->id);
                                           }

                                           return $query->where('assigned_id', $this->user->id);
                                       })
                                       ->where(function ($query) use ($campaign) {
                                           return $query->whereHas(
                                               'lead',
                                               function ($qry) use ($campaign) {
                                                   if ($campaign->primaryNameField) {
                                                       $value = '%' . strtolower(request()->get('search_term')) . '%';

                                                       $fieldFormatted = 'data->\'$."' . $campaign->primaryNameField->slug . '"\'';

                                                       $qry->whereRaw("LOWER($fieldFormatted) LIKE ?", $value);
                                                   }
                                               }
                                           )->orWhereHas(
                                               'lead',
                                               function ($qry) use ($campaign) {
                                                   if ($campaign->primaryTelephoneField) {
                                                       $value = '%' . strtolower(request()->get('search_term')) . '%';

                                                       $fieldFormatted = 'data->\'$."' . $campaign->primaryTelephoneField->slug . '"\'';

                                                       $qry->whereRaw("LOWER($fieldFormatted) LIKE ?", $value);
                                                   }
                                               }
                                           );
                                       })
                                       ->get();

                return $leads;
            })
            ->flatten()
            ->sortBy('callback');
    }

    public function countTomorrow()
    {
        return $this->campaigns
            ->map(function ($campaign) {
                $leads = LeadAssignment::where('campaign_id', $campaign->id)
                                       ->whereBetween('callback', [
                                           Carbon::tomorrow()->startOfDay(),
                                           Carbon::tomorrow()->endOfDay(),
                                       ])
                                       ->where(function ($query) use ($campaign) {
                                           if ($campaign->currentUser() && $campaign->currentUser()->confirm_lead) {
                                               return $query->whereNull('sent_at');
                                           }

                                           return $query->whereNull('completed_at')
                                                        ->orWhereNotNull('rejected_at');
                                       })
                                       ->where(function ($query) use ($campaign) {
                                           if ($campaign->currentUser() && $campaign->currentUser()->confirm_lead) {
                                               return $query->where('assigned_id', $this->user->id)
                                                            ->where('confirmed_by', $this->user->id);
                                           }

                                           return $query->where('assigned_id', $this->user->id);
                                       })
                                       ->count();

                return $leads;
            })
            ->flatten()
            ->sum();
    }
}
