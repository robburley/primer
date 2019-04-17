<?php

namespace App\Http\Controllers\Campaigns;

use App\Events\LeadCompleted;
use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfirmationLeadsController extends Controller
{
    public function edit(Campaign $campaign, Lead $lead)
    {
        return view('campaigns.confirmation.edit', [
            'campaign'       => $campaign,
            'lead'           => $lead,
            'leadAssignment' => $lead->campaignData($campaign),
        ]);
    }

    public function update(Campaign $campaign, Lead $lead)
    {
        $validator = Validator::make(
            $campaign->formatData(),
            $campaign->getValidationRules(),
            [],
            $campaign->selectedFieldNames()
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $lead->updateData($campaign->processData());

        if (request()->has('primer_confirm_button')) {
            $lead->campaigns()
                 ->updateExistingPivot($campaign->id, [
                     'confirmed_at' => Carbon::now(),
                 ]);

            $leadAssignment = $lead->campaignData($campaign);

            $leadAssignment->notes()
                           ->create([
                               'note'    => 'Record confirmed',
                               'user_id' => auth()->user()->id,
                           ]);

            event(new LeadCompleted($campaign, $lead));

            flash('Lead Confirmed!')->success();
        } else {
            $lead->campaigns()
                 ->updateExistingPivot($campaign->id, [
                     'callback' => Carbon::createFromFormat(
                         'd/m/Y h:iA',
                         request()->get('callback_date') . ' ' . request()->get('callback_time')
                     ),
                 ]);

            flash('Lead Saved!')->success();
        }

        return redirect()->route('dashboard');
    }

    public function destroy(Request $request, Campaign $campaign, Lead $lead)
    {
        $request->validate([
            'invalid_comment' => 'required',
        ]);

        $leadAssignment = $lead->campaignData($campaign);

        $leadAssignment->notes()
                       ->create([
                           'note'    => 'Record rejected and sent back to agent',
                           'user_id' => auth()->user()->id,
                       ]);

        $lead->invalidate($campaign);

        flash('Lead Rejected!')->warning();

        return redirect()->route('supervisor.confirmation.index', $campaign);
    }
}
