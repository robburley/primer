<?php

namespace App\Helpers\Campaigns\Endpoints;

use App\Http\Resources\LeadResource;

class Endpoint
{
    public $lead;
    public $campaign;
    public $resource;
    public $tenant;

    public function __construct($lead, $campaign)
    {
        $this->lead = $lead;

        $this->campaign = $campaign;

        $this->tenant = $campaign->tenant;

        $this->resource = (new LeadResource($this->lead))->toArray(request());
    }

    public function process()
    {
        return $this->resource;
    }
}
