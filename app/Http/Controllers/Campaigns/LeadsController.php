<?php

namespace App\Http\Controllers\Campaigns;

use App\Events\LeadSaved;
use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    public function create(Campaign $campaign)
    {
        return view('campaigns.create', [
            'campaign' => $campaign->load([
                'selectedCustomFieldGroups.customFieldGroup',
                'selectedCustomFieldGroups.selectedCustomFieldsToShowToAgent.customField.bespokeFormField',
            ]),
        ]);
    }

    public function store(Campaign $campaign)
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

        $lead = auth()->user()->tenant->leads()
                                      ->create([
                                          'data' => json_encode($campaign->processData()),
                                      ]);

        $dateTime = request()->get('callback_date') . ' ' . request()->get('callback_time');

        $lead->attachToCampaign($campaign, $dateTime);

        event(new LeadSaved($campaign, $lead));

        flash('Lead Created!')->success();

        return redirect()->route('campaigns.leads.edit', [$campaign, $lead]);
    }

    public function edit(Campaign $campaign, Lead $lead)
    {
        $leadAssignment = $lead->campaignData($campaign);

        $leadAssignment->accessLog()
                       ->create([
                           'user_id' => auth()->user()->id,
                           'action'  => 'Accessed to edit',
                       ]);

        return view('campaigns.edit', [
            'campaign'       => $campaign->load([
                'selectedCustomFieldGroups.customFieldGroup',
                'selectedCustomFieldGroups.selectedCustomFieldsToShowToAgent.customField.bespokeFormField',
            ]),
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

        $lead->submit($campaign);

        $lead->clearInvalidReasons($campaign);

        event(new LeadSaved($campaign, $lead));

        return redirect(route('dashboard'));
    }
}
