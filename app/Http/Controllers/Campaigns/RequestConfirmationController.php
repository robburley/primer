<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\LeadAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestConfirmationController extends Controller
{
    public function store(Campaign $campaign)
    {
        $lead = LeadAssignment::where('campaign_id', $campaign->id)
                              ->where(function ($qry) {
                                  $qry->where('callback', '<=', Carbon::now())
                                      ->orWhereNull('callback');
                              })
                              ->whereNull('confirmed_at')
                              ->whereNull('rejected_at')
                              ->whereNotNull('completed_at')
                              ->where('confirmed_by', auth()->user()->id)
                              ->orderBy('callback', 'asc')
                              ->with(['lead'])
                              ->first();

        $lead = $lead
            ? $lead->lead
            : $this->getLead($campaign);

        return $lead
            ? redirect()->route('campaigns.leads.confirmation.edit', [$campaign, $lead])
            : view('campaigns.empty', [
                'campaign' => $campaign,
            ]);
    }

    public function getLead($campaign)
    {
        $query = $campaign->leads()
                          ->wherePivot('confirmed_at', null)
                          ->wherePivot('rejected_at', null)
                          ->wherePivot('completed_at', '!=', null)
                          ->wherePivot('confirmed_by', null)
                          ->with([
                              'campaigns',
                              'users',
                          ]);

        try {
            $lead = $campaign->requestLead($campaign->lead_order, true, $query);

            if (!$lead) {
                throw new ModelNotFoundException();
            }

            $lead->campaigns()
                 ->updateExistingPivot($campaign->id, [
                     'confirmed_by' => auth()->user()->id,
                 ]);

            return $lead;
        } catch (\Exception $e) {
            return null;
        }
    }
}
