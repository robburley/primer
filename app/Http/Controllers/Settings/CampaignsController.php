<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaigns\CampaignRequest;
use App\Models\Campaigns\Campaign;

class CampaignsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.campaigns.index', [
            'campaigns' => auth()->user()->tenant->campaigns()
                                                 ->orderBy('name', 'asc')
                                                 ->paginate(25),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.campaigns.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CampaignRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampaignRequest $request)
    {
        $campaign = auth()->user()->tenant->campaigns()
                                          ->create(
                                              $request->only([
                                                  'name',
                                                  'active',
                                                  'endpoint_type',
                                                  'endpoint_location',
                                                  'lead_order',
                                                  'validate_leads',
                                              ])
                                          );

        flash('Campaign Created!')->success();

        return redirect()->route('settings.campaigns.edit', $campaign);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Campaign $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        return view('settings.campaigns.edit', [
            'campaign'          => $campaign->load(['scopes', 'users']),
            'customFieldGroups' => auth()->user()->tenant->customFieldGroups()
                                                         ->with([
                                                             'customFields.rules',
                                                             'customFields.bespokeFormField.rules',
                                                         ])
                                                         ->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CampaignRequest $request
     * @param Campaign        $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(CampaignRequest $request, Campaign $campaign)
    {
        $campaign->update(
            $request->only([
                'name',
                'active',
                'endpoint_type',
                'endpoint_location',
                'lead_order',
                'validate_leads',
                'primary_name_field_id',
                'primary_telephone_field_id',
                'primary_email_field_id',
            ])
        );

        flash('Campaign Updated!')->success();

        return redirect()->route('settings.campaigns.index');
    }
}
