<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;

class InvalidLeadsController extends Controller
{
    public function index(Campaign $campaign)
    {
        $leads = $campaign->leads()
                          ->wherePivot('completed_at', '<>', null)
                          ->wherePivot('rejected_at', '<>', null)
                          ->wherePivot('sent_at', null)
                          ->orderBy('campaign_lead.completed_at', 'desc')
                          ->paginate(25);

        return view('campaigns.invalid.index', [
            'campaign' => $campaign,
            'leads'    => $leads,
        ]);
    }
}
