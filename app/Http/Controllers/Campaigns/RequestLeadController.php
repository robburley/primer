<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\LeadAssignment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RequestLeadController extends Controller
{
    public function store(Campaign $campaign)
    {
        $lead = LeadAssignment::where('campaign_id', $campaign->id)
                              ->where('callback', '<=', Carbon::now())
                              ->where(function ($query) {
                                  $query->whereNull('completed_at')
                                        ->orWhereNotNull('rejected_at');
                              })
                              ->where('assigned_id', auth()->user()->id)
                              ->orderBy('callback', 'asc')
                              ->with(['lead'])
                              ->first();

        $lead = $lead
            ? $lead->lead
            : $this->getLead($campaign);

        return $lead
            ? redirect()->route('campaigns.leads.edit', [$campaign, $lead])
            : view('campaigns.empty', [
                'campaign' => $campaign,
            ]);
    }

    public function getLead($campaign)
    {
        try {
            $lead = $campaign->requestLead($campaign->lead_order);

            if (!$lead) {
                throw new ModelNotFoundException();
            }

            $lead->campaigns()
                 ->attach($campaign->id, [
                     'assigned_id' => auth()->user()->id,
                     'created_at'  => Carbon::now(),
                     'updated_at'  => Carbon::now(),
                     'callback'    => Carbon::now()->addHours(4),
                     'hash'        => str_random(64),
                 ]);

            return $lead;
        } catch (\Exception $e) {
            return null;
        }
    }
}
