<?php

namespace App\Http\Controllers\Api\Leads;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LeadCallbackController extends Controller
{
    public function store(Request $request, Campaign $campaign, Lead $lead)
    {
        $leadAssignment = $lead->campaignData($campaign);

        if ($request->get('hash') !== $leadAssignment->hash) {
            abort(403);
        }

        $data = new Collection();

        if ($request->has('rejected_at')) {
            $data->put('rejected_at', Carbon::CreateFromFormat('d/m/Y H:i', $request->get('rejected_at')));
        }

        if ($request->has('qualified_at')) {
            $data->put('qualified_at', Carbon::CreateFromFormat('d/m/Y H:i', $request->get('qualified_at')));
        }

        if ($request->has('invalid_comment')) {
            $data->put('invalid_comment', $request->get('invalid_comment'));
        }

        if ($request->has('sent_at')) {
            $data->put('sent_at', $request->get('sent_at'));
        }

        if ($request->has('confirmed_at')) {
            $data->put('confirmed_at', $request->get('confirmed_at'));
        }

        if ($request->has('dealt_at')) {
            $data->put('dealt_at', $request->get('dealt_at'));
        }

        if ($request->has('gp')) {
            $data->put('gp', $request->get('gp'));
        }

        $lead->campaigns()
             ->updateExistingPivot(
                 $campaign->id,
                 $data->toArray()
             );
    }
}
