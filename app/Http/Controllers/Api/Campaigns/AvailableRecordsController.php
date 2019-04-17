<?php

namespace App\Http\Controllers\Api\Campaigns;

use App\Http\Controllers\Controller;
use App\Models\Campaigns\Campaign;

class AvailableRecordsController extends Controller
{
    public function show(Campaign $campaign)
    {
        return $campaign->total_leads;
    }
}
