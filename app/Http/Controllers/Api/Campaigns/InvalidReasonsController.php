<?php

namespace App\Http\Controllers\Api\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;
use App\Models\Leads\InvalidReason;
use Illuminate\Http\Request;

class InvalidReasonsController extends Controller
{
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title'       => 'required|unique:invalid_reasons',
            'description' => 'required',
        ]);

        $campaign->invalidReasons()
                 ->create($request->only([
                     'title',
                     'description',
                 ]));

        return $campaign->activeInvalidReasons;
    }

    public function destroy(Campaign $campaign, InvalidReason $invalidReason)
    {
        $invalidReason->update(['active' => 0]);

        return $campaign->activeInvalidReasons;
    }
}
