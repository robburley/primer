<?php

namespace App\Helpers\Campaigns\Endpoints;

use GuzzleHttp\Client;

class JsonApi extends Endpoint
{
    public function process()
    {
        $leadAssignment = $this->lead->campaignData($this->campaign);

        $client = new Client();

        $client->post($this->campaign->endpoint_location, [
            'form_params' => $this->resource + [
                    'callback_url' => 'https://' . $this->tenant->domain . '.getprimer.com/api/campaigns/' . $this->campaign->id . '/leads/' . $this->lead->id . '/callback?hash=' . $leadAssignment->hash,
                ],
        ]);
    }
}
