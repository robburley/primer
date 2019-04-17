<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Leads\LeadAssignment;
use App\Models\Users\User;

class InvalidLeadsController extends Controller
{
    public function index()
    {
        $user = request()->get('user_id') && auth()->user()->campaignsSupervisor->count() > 0
            ? request()->get('user_id')
            : auth()->user()->id;

        $campaigns = auth()->user()->campaignsSupervisor->count() > 0
            ? auth()->user()->campaignsSupervisor
            : auth()->user()->campaigns;

        $user = User::findTenantUser($user)->first();

        !$user && abort(403);

        $leads = LeadAssignment::invalid()
                               ->whereIn('campaign_id', $campaigns->pluck('id'))
                               ->where('assigned_id', $user->id)
                               ->get();

        return view('agent.invalid.index', [
            'leads' => $leads,
        ]);
    }
}
