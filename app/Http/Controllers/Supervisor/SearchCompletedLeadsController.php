<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use App\Models\Leads\LeadAssignment;
use Illuminate\Http\Request;

class SearchCompletedLeadsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search_term'   => 'required',
            'search_reason' => 'required',
        ]);

        $campaigns = auth()->user()->campaignsSupervisor;

        $leads = $campaigns
            ->map(function ($campaign) {
                return LeadAssignment::where('campaign_id', $campaign->id)
                                     ->whereNotNull('sent_at')
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
            })
            ->flatten()
            ->sortBy('completed_at');

        return view('supervisor.search.completed.index', [
            'leads'        => $leads,
            'accessReason' => request()->get('search_reason'),
        ]);
    }

    public function store(Request $request, Campaign $campaign, Lead $lead)
    {
        $leadAssignment = $lead->campaignData($campaign);

        $leadAssignment->accessLog()->create([
            'user_id' => auth()->user()->id,
            'action'  => $request->get('access_reason'),
        ]);

        return view('campaigns.edit', [
            'campaign'       => $campaign,
            'lead'           => $lead,
            'leadAssignment' => $lead->campaignData($campaign),
        ]);
    }
}
