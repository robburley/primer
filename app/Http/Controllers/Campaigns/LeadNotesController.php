<?php

namespace App\Http\Controllers\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use Illuminate\Http\Request;

class LeadNotesController extends Controller
{
    public function store(Request $request, Campaign $campaign, Lead $lead)
    {
        $request->validate([
            'note' => 'required',
        ]);

        $lead->campaignData($campaign)
             ->notes()
             ->create([
                 'note'    => $request->note,
                 'user_id' => auth()->user()->id,
             ]);

        flash('Note Added!')->success();

        return redirect()->back();
    }
}
