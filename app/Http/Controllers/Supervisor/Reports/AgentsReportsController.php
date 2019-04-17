<?php

namespace App\Http\Controllers\Supervisor\Reports;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\LeadAssignment;
use Carbon\Carbon;

class AgentsReportsController extends Controller
{
    public function index()
    {
        $default = auth()->user()->campaignsSupervisor->first();

        $campaign = Campaign::find(request()->get('campaign_id', $default->id ?? null));

        if (!$campaign) {
            abort(404);
        }

        $start = request()->get('start_date')
            ? Carbon::createFromFormat('d/m/Y', request()->get('start_date'))->startOfDay()
            : Carbon::now()->startOfDay();
        $end = request()->get('end_date')
            ? Carbon::createFromFormat('d/m/Y', request()->get('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();

        $agents = $campaign->users
            ->keyBy('full_name')
            ->map(function ($user) use ($start, $end, $campaign) {
                return [
                    'total'       => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('completed_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->count(),
                    'validated'   => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('confirmed_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->count(),
                    'invalidated' => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('rejected_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->count(),
                    'qualified'   => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('qualified_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->count(),
                    'dealt'       => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('dealt_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->count(),
                    'gp'          => LeadAssignment::where('campaign_id', $campaign->id)
                                                   ->whereBetween('dealt_at', [$start, $end])
                                                   ->where('assigned_id', $user->id)
                                                   ->whereHas('lead', function ($query) {
                                                       $query->where('tenant_id', auth()->user()->id);
                                                   })
                                                   ->sum('gp'),
                ];
            })
            ->sortByDesc('total');

        return view('reports.agents', [
            'agents'   => $agents,
            'start'    => $start,
            'end'      => $end,
            'campaign' => $campaign,
        ]);
    }
}
