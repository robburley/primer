<?php

namespace App\Http\Controllers\Agent;

use App\Helpers\Campaigns\CallbackSearch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbacksController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search_term' => 'nullable|min:3',
        ]);

        $user = request()->get('user_id') && auth()->user()->campaignsSupervisor->count() > 0
            ? request()->get('user_id')
            : auth()->user()->id;

        $campaigns = auth()->user()->campaignsSupervisor->count() > 0
            ? auth()->user()->campaignsSupervisor
            : auth()->user()->campaigns;

        list($leads, $tomorrowsCallbacks) = (new CallbackSearch($user, $campaigns))->handle();

        return view('agent.callbacks.index', [
            'leads'              => $leads,
            'tomorrowsCallbacks' => $tomorrowsCallbacks,
        ]);
    }
}
